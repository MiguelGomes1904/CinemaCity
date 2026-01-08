<?php
// get-ticket.php
// API endpoint to get details of a specific ticket

// Include the database connection
require_once __DIR__ . '/db.inc';

// Set response header to JSON
header('Content-Type: application/json');

// Enable CORS if needed
header('Access-Control-Allow-Origin: *');

// Ensure the request method is GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed. GET required.']);
    exit;
}

try {
    // Get ticket ID from query parameter
    $ticketId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $qrCode = isset($_GET['qr_code']) ? trim($_GET['qr_code']) : '';

    if ($ticketId <= 0 && empty($qrCode)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Ticket ID or QR code is required.']);
        exit;
    }

    // Prepare query based on available parameter
    if (!empty($qrCode)) {
        $stmt = $conn->prepare("
            SELECT 
                t.id, t.seat_number, t.ticket_type, t.price, t.purchase_date, t.status, t.qr_code,
                m.title as movie_title, m.poster_url, m.duration, m.genre, m.age_rating,
                s.session_date, s.session_time,
                sc.screen_name, sc.screen_type,
                c.name as cinema_name, c.address, c.city, c.phone,
                u.name as user_name, u.email as user_email
            FROM tickets t
            JOIN sessions s ON t.session_id = s.id
            JOIN movies m ON s.movie_id = m.id
            JOIN screens sc ON s.screen_id = sc.id
            JOIN cinemas c ON sc.cinema_id = c.id
            JOIN users u ON t.user_id = u.id
            WHERE t.qr_code = ?
        ");
        $stmt->bind_param("s", $qrCode);
    } else {
        $stmt = $conn->prepare("
            SELECT 
                t.id, t.seat_number, t.ticket_type, t.price, t.purchase_date, t.status, t.qr_code,
                m.title as movie_title, m.poster_url, m.duration, m.genre, m.age_rating,
                s.session_date, s.session_time,
                sc.screen_name, sc.screen_type,
                c.name as cinema_name, c.address, c.city, c.phone,
                u.name as user_name, u.email as user_email
            FROM tickets t
            JOIN sessions s ON t.session_id = s.id
            JOIN movies m ON s.movie_id = m.id
            JOIN screens sc ON s.screen_id = sc.id
            JOIN cinemas c ON sc.cinema_id = c.id
            JOIN users u ON t.user_id = u.id
            WHERE t.id = ?
        ");
        $stmt->bind_param("i", $ticketId);
    }
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Ticket not found.']);
        $stmt->close();
        $conn->close();
        exit;
    }

    $ticket = $result->fetch_assoc();
    $stmt->close();

    // Return JSON response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'ticket' => $ticket
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
