<?php
// create-ticket.php
// API endpoint to create/purchase a ticket

// Include the database connection
require_once __DIR__ . '/db.inc';

// Start session to check user authentication
session_start();

// Set response header to JSON
header('Content-Type: application/json');

// Enable CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed. POST required.']);
    exit;
}

try {
    // Get JSON input
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    // Validate input
    $userId = isset($data['user_id']) ? intval($data['user_id']) : 0;
    $sessionId = isset($data['session_id']) ? intval($data['session_id']) : 0;
    $seatNumber = isset($data['seat_number']) ? trim($data['seat_number']) : '';
    $ticketType = isset($data['ticket_type']) ? trim($data['ticket_type']) : 'Standard';

    if ($userId <= 0 || $sessionId <= 0 || empty($seatNumber)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'User ID, session ID, and seat number are required.']);
        exit;
    }

    // Start transaction
    $conn->begin_transaction();

    // Check if session exists and has available seats
    $sessionStmt = $conn->prepare("
        SELECT s.id, s.price, s.available_seats, m.title, s.session_date, s.session_time
        FROM sessions s
        JOIN movies m ON s.movie_id = m.id
        WHERE s.id = ? AND s.is_active = TRUE
    ");
    
    if (!$sessionStmt) {
        throw new Exception('Failed to prepare session statement: ' . $conn->error);
    }
    
    $sessionStmt->bind_param("i", $sessionId);
    $sessionStmt->execute();
    $sessionResult = $sessionStmt->get_result();

    if ($sessionResult->num_rows === 0) {
        $conn->rollback();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Session not found or not active.']);
        exit;
    }

    $session = $sessionResult->fetch_assoc();
    $sessionStmt->close();

    if ($session['available_seats'] <= 0) {
        $conn->rollback();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No available seats for this session.']);
        exit;
    }

    // Check if seat is already taken
    $seatCheckStmt = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM tickets 
        WHERE session_id = ? AND seat_number = ? AND status = 'active'
    ");
    
    if (!$seatCheckStmt) {
        throw new Exception('Failed to prepare seat check statement: ' . $conn->error);
    }
    
    $seatCheckStmt->bind_param("is", $sessionId, $seatNumber);
    $seatCheckStmt->execute();
    $seatCheckResult = $seatCheckStmt->get_result();
    $seatCheck = $seatCheckResult->fetch_assoc();
    $seatCheckStmt->close();

    if ($seatCheck['count'] > 0) {
        $conn->rollback();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Seat already taken. Please choose another seat.']);
        exit;
    }

    // Generate QR code (simple unique identifier)
    $qrCode = uniqid('TICKET-', true);

    // Calculate price based on ticket type
    $price = $session['price'];
    if ($ticketType === 'Student') {
        $price *= 0.8; // 20% discount
    } elseif ($ticketType === 'Senior') {
        $price *= 0.7; // 30% discount
    }

    // Insert ticket
    $ticketStmt = $conn->prepare("
        INSERT INTO tickets (user_id, session_id, seat_number, ticket_type, price, qr_code, status)
        VALUES (?, ?, ?, ?, ?, ?, 'active')
    ");
    
    if (!$ticketStmt) {
        throw new Exception('Failed to prepare ticket statement: ' . $conn->error);
    }
    
    $ticketStmt->bind_param("iissds", $userId, $sessionId, $seatNumber, $ticketType, $price, $qrCode);
    
    if (!$ticketStmt->execute()) {
        throw new Exception('Failed to create ticket: ' . $ticketStmt->error);
    }
    
    $ticketId = $conn->insert_id;
    $ticketStmt->close();

    // Update available seats
    $updateSeatsStmt = $conn->prepare("
        UPDATE sessions 
        SET available_seats = available_seats - 1 
        WHERE id = ?
    ");
    
    if (!$updateSeatsStmt) {
        throw new Exception('Failed to prepare update statement: ' . $conn->error);
    }
    
    $updateSeatsStmt->bind_param("i", $sessionId);
    $updateSeatsStmt->execute();
    $updateSeatsStmt->close();

    // Commit transaction
    $conn->commit();

    // Return success response
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Ticket purchased successfully.',
        'ticket' => [
            'id' => $ticketId,
            'movie_title' => $session['title'],
            'session_date' => $session['session_date'],
            'session_time' => $session['session_time'],
            'seat_number' => $seatNumber,
            'ticket_type' => $ticketType,
            'price' => $price,
            'qr_code' => $qrCode
        ]
    ]);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
