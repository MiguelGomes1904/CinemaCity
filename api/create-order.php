<?php
// create-order.php - cria uma compra e bilhetes para uma sessão

require_once __DIR__ . '/db.inc';
session_start();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed. POST required.']);
    exit;
}

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON body.']);
        exit;
    }

    $sessionId = isset($data['session_id']) ? (int)$data['session_id'] : 0;
    $seatsInput = isset($data['seats']) ? $data['seats'] : '';
    $customerName = trim($data['customer_name'] ?? '');
    $customerEmail = trim($data['customer_email'] ?? '');
    $customerPhone = trim($data['customer_phone'] ?? '');
    $paymentMethod = trim($data['payment_method'] ?? '');
    $frontTotal = isset($data['total_price']) ? (float)$data['total_price'] : 0.0;

    $seatsArray = is_array($seatsInput)
        ? $seatsInput
        : explode(',', (string)$seatsInput);
    $seats = array_values(array_unique(array_filter(array_map('trim', $seatsArray))));

    if ($sessionId <= 0 || empty($seats) || $customerName === '' || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Dados inválidos. Confirme sessão, assentos e contacto.']);
        exit;
    }

    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

    if ($userId <= 0) {
        $findUser = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        if (!$findUser) {
            throw new Exception('Failed to prepare user lookup: ' . $conn->error);
        }
        $findUser->bind_param('s', $customerEmail);
        $findUser->execute();
        $userResult = $findUser->get_result();
        if ($row = $userResult->fetch_assoc()) {
            $userId = (int)$row['id'];
        }
        $findUser->close();
    }

    if ($userId <= 0) {
        $pwd = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
        $insertUser = $conn->prepare('INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)');
        if (!$insertUser) {
            throw new Exception('Failed to prepare user insert: ' . $conn->error);
        }
        $insertUser->bind_param('ssss', $customerName, $customerEmail, $pwd, $customerPhone);
        if (!$insertUser->execute()) {
            throw new Exception('Failed to create user: ' . $insertUser->error);
        }
        $userId = $insertUser->insert_id;
        $insertUser->close();
    }

    $conn->begin_transaction();

    $sessionStmt = $conn->prepare('
        SELECT s.id, s.movie_id, s.session_date, s.session_time, s.price, s.available_seats,
               m.title AS movie_title, c.name AS cinema_name
        FROM sessions s
        JOIN movies m ON s.movie_id = m.id
        JOIN screens sc ON s.screen_id = sc.id
        JOIN cinemas c ON sc.cinema_id = c.id
        WHERE s.id = ? AND s.is_active = TRUE
        FOR UPDATE
    ');

    if (!$sessionStmt) {
        throw new Exception('Failed to prepare session lookup: ' . $conn->error);
    }

    $sessionStmt->bind_param('i', $sessionId);
    $sessionStmt->execute();
    $sessionRes = $sessionStmt->get_result();
    if ($sessionRes->num_rows === 0) {
        $conn->rollback();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Sessão não encontrada ou inativa.']);
        exit;
    }
    $session = $sessionRes->fetch_assoc();
    $sessionStmt->close();

    if ((int)$session['available_seats'] < count($seats)) {
        $conn->rollback();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Não há lugares suficientes disponíveis.']);
        exit;
    }

    $tickets = [];
    $seatCheckStmt = $conn->prepare('SELECT COUNT(*) AS c FROM tickets WHERE session_id = ? AND seat_number = ? AND status = "active"');
    $ticketInsertStmt = $conn->prepare('INSERT INTO tickets (user_id, session_id, seat_number, ticket_type, price, qr_code, status) VALUES (?, ?, ?, "Standard", ?, ?, "active")');

    if (!$seatCheckStmt || !$ticketInsertStmt) {
        throw new Exception('Failed to prepare ticket statements: ' . $conn->error);
    }

    $totalCalculated = 0.0;
    foreach ($seats as $seat) {
        $seatCheckStmt->bind_param('is', $sessionId, $seat);
        $seatCheckStmt->execute();
        $seatRes = $seatCheckStmt->get_result();
        $countRow = $seatRes->fetch_assoc();
        if ($countRow['c'] > 0) {
            $conn->rollback();
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "O lugar $seat já está ocupado. Escolha outro."]);
            exit;
        }

        $price = (float)$session['price'];
        $qr = uniqid('TICKET-', true);
        $ticketInsertStmt->bind_param('iisss', $userId, $sessionId, $seat, $price, $qr);
        if (!$ticketInsertStmt->execute()) {
            throw new Exception('Failed to criar bilhete: ' . $ticketInsertStmt->error);
        }
        $ticketId = $ticketInsertStmt->insert_id;
        $tickets[] = [
            'id' => $ticketId,
            'seat' => $seat,
            'price' => $price,
            'qr_code' => $qr
        ];
        $totalCalculated += $price;
    }

    $seatCheckStmt->close();
    $ticketInsertStmt->close();

    $updateSeats = $conn->prepare('UPDATE sessions SET available_seats = available_seats - ? WHERE id = ?');
    if (!$updateSeats) {
        throw new Exception('Failed to prepare seat update: ' . $conn->error);
    }
    $seatsCount = count($seats);
    $updateSeats->bind_param('ii', $seatsCount, $sessionId);
    $updateSeats->execute();
    $updateSeats->close();

    $orderTotal = $frontTotal > 0 ? $frontTotal : $totalCalculated;
    $ticketIdRef = $tickets[0]['id'] ?? null;
    $orderStmt = $conn->prepare('INSERT INTO orders (user_id, ticket_id, total_amount, payment_method, payment_status) VALUES (?, ?, ?, ?, "completed")');
    if (!$orderStmt) {
        throw new Exception('Failed to prepare order insert: ' . $conn->error);
    }
    $orderStmt->bind_param('iids', $userId, $ticketIdRef, $orderTotal, $paymentMethod);
    if (!$orderStmt->execute()) {
        throw new Exception('Failed to criar encomenda: ' . $orderStmt->error);
    }
    $orderId = $orderStmt->insert_id;
    $orderStmt->close();

    $conn->commit();

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Compra registada com sucesso.',
        'order_id' => $orderId,
        'tickets' => $tickets,
        'session' => [
            'id' => $session['id'],
            'date' => $session['session_date'],
            'time' => $session['session_time'],
            'movie_title' => $session['movie_title'],
            'cinema_name' => $session['cinema_name']
        ],
        'total' => $orderTotal
    ]);

} catch (Exception $e) {
    if ($conn->errno) {
        $conn->rollback();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}

$conn->close();
?>
