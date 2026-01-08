<?php
// get-session.php - Get single session details

include 'db.inc';

header('Content-Type: application/json; charset=utf-8');

$sessionId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($sessionId <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Session ID is required'
    ]);
    exit;
}

try {
    $query = "
        SELECT 
            s.id,
            s.movie_id,
            s.screen_id,
            s.session_date,
            s.session_time,
            s.price,
            s.available_seats,
            c.id as cinema_id,
            c.name as cinema_name,
            c.address as cinema_address,
            sc.screen_number,
            sc.screen_name,
            sc.total_seats
        FROM sessions s
        JOIN screens sc ON s.screen_id = sc.id
        JOIN cinemas c ON sc.cinema_id = c.id
        WHERE s.id = ?
    ";
    
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param('i', $sessionId);
    
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Session not found'
        ]);
        exit;
    }
    
    $session = $result->fetch_assoc();
    
    echo json_encode([
        'success' => true,
        'session' => $session
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
