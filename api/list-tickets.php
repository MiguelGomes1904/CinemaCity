<?php
// list-tickets.php
// API endpoint to list user's tickets

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
    // Get user ID from query parameter
    $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

    if ($userId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Valid user ID is required.']);
        exit;
    }

    // Get user's tickets with movie and session details
    $stmt = $conn->prepare("
        SELECT 
            t.id, t.seat_number, t.ticket_type, t.price, t.purchase_date, t.status, t.qr_code,
            m.title as movie_title, m.poster_url, m.duration, m.genre, m.age_rating,
            s.session_date, s.session_time,
            sc.screen_name, sc.screen_type,
            c.name as cinema_name, c.address, c.city
        FROM tickets t
        JOIN sessions s ON t.session_id = s.id
        JOIN movies m ON s.movie_id = m.id
        JOIN screens sc ON s.screen_id = sc.id
        JOIN cinemas c ON sc.cinema_id = c.id
        WHERE t.user_id = ?
        ORDER BY s.session_date DESC, s.session_time DESC
    ");
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $tickets = [];
    $upcomingTickets = [];
    $pastTickets = [];
    
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
        
        // Separate upcoming and past tickets
        $sessionDateTime = $row['session_date'] . ' ' . $row['session_time'];
        if (strtotime($sessionDateTime) >= time() && $row['status'] === 'active') {
            $upcomingTickets[] = $row;
        } else {
            $pastTickets[] = $row;
        }
    }
    
    $stmt->close();

    // Return JSON response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'count' => count($tickets),
        'tickets' => $tickets,
        'upcomingTickets' => $upcomingTickets,
        'pastTickets' => $pastTickets
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
