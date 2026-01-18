<?php
// create-product-order.php - regista compra de artigos de bar

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

    $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
    $customerName = trim($data['customer_name'] ?? '');
    $customerEmail = trim($data['customer_email'] ?? '');
    $customerPhone = trim($data['customer_phone'] ?? '');
    $paymentMethod = trim($data['payment_method'] ?? '');
    $pickupLocation = trim($data['pickup_location'] ?? '');

    if (empty($items) || $customerName === '' || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL) || $paymentMethod === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Dados inválidos. Confirme artigos, contacto e pagamento.']);
        exit;
    }

    $cleanItems = [];
    foreach ($items as $item) {
        $pid = isset($item['product_id']) ? (int)$item['product_id'] : 0;
        $qty = isset($item['quantity']) ? (int)$item['quantity'] : 0;
        if ($pid > 0 && $qty > 0) {
            $cleanItems[$pid] = ($cleanItems[$pid] ?? 0) + $qty; // agrega por produto
        }
    }

    if (empty($cleanItems)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nenhum artigo válido enviado.']);
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
        $userRes = $findUser->get_result();
        if ($row = $userRes->fetch_assoc()) {
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

    // Buscar produtos e validar stock
    $placeholders = implode(',', array_fill(0, count($cleanItems), '?'));
    $types = str_repeat('i', count($cleanItems));
    $stmt = $conn->prepare('SELECT id, name, price, stock, is_available FROM products WHERE id IN (' . $placeholders . ') FOR UPDATE');
    if (!$stmt) {
        throw new Exception('Failed to prepare product lookup: ' . $conn->error);
    }

    $stmt->bind_param($types, ...array_keys($cleanItems));
    $stmt->execute();
    $res = $stmt->get_result();

    $products = [];
    while ($row = $res->fetch_assoc()) {
        $products[$row['id']] = $row;
    }
    $stmt->close();

    foreach ($cleanItems as $pid => $qty) {
        if (!isset($products[$pid])) {
            $conn->rollback();
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => "Produto $pid não encontrado."]);
            exit;
        }
        $p = $products[$pid];
        if (!$p['is_available']) {
            $conn->rollback();
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Produto {$p['name']} indisponível."]);
            exit;
        }
        if (isset($p['stock']) && $p['stock'] !== null && (int)$p['stock'] < $qty) {
            $conn->rollback();
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Stock insuficiente para {$p['name']}."]);
            exit;
        }
    }

    // Criar encomenda
    $total = 0.0;
    foreach ($cleanItems as $pid => $qty) {
        $total += ((float)$products[$pid]['price']) * $qty;
    }

    $orderStmt = $conn->prepare('INSERT INTO orders (user_id, ticket_id, total_amount, payment_method, payment_status) VALUES (?, NULL, ?, ?, "completed")');
    if (!$orderStmt) {
        throw new Exception('Failed to prepare order insert: ' . $conn->error);
    }
    $orderStmt->bind_param('ids', $userId, $total, $paymentMethod);
    if (!$orderStmt->execute()) {
        throw new Exception('Failed to insert order: ' . $orderStmt->error);
    }
    $orderId = $orderStmt->insert_id;
    $orderStmt->close();

    // Inserir itens
    $itemStmt = $conn->prepare('INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)');
    if (!$itemStmt) {
        throw new Exception('Failed to prepare order_items insert: ' . $conn->error);
    }

    foreach ($cleanItems as $pid => $qty) {
        $price = (float)$products[$pid]['price'];
        $sub = $price * $qty;
        $itemStmt->bind_param('iiidd', $orderId, $pid, $qty, $price, $sub);
        if (!$itemStmt->execute()) {
            throw new Exception('Failed to insert order item: ' . $itemStmt->error);
        }

        // Atualizar stock se existir stock controlado
        if (isset($products[$pid]['stock'])) {
            $updateStock = $conn->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
            if ($updateStock) {
                $updateStock->bind_param('ii', $qty, $pid);
                $updateStock->execute();
                $updateStock->close();
            }
        }
    }
    $itemStmt->close();

    $conn->commit();

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Compra registada com sucesso.',
        'order_id' => $orderId,
        'total' => $total,
        'items' => array_map(function($pid, $qty) use ($products) {
            return [
                'product_id' => $pid,
                'name' => $products[$pid]['name'],
                'quantity' => $qty,
                'unit_price' => (float)$products[$pid]['price']
            ];
        }, array_keys($cleanItems), array_values($cleanItems)),
        'pickup_location' => $pickupLocation
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
