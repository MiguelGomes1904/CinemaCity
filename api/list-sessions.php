<?php
// list-sessions.php
// API endpoint to list all active sessions

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
    // Check for filters
    $movieId = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;
    $cinemaId = isset($_GET['cinema_id']) ? intval($_GET['cinema_id']) : 0;
    $date = isset($_GET['date']) ? trim($_GET['date']) : '';
    
    // Base query
    $sql = "SELECT 
                s.id, s.session_date, s.session_time, s.price, s.available_seats,
                s.movie_id, m.title as movie_title, m.poster_url, m.duration, m.age_rating,
                sc.id as screen_id, sc.screen_name, sc.screen_type,
                c.id as cinema_id, c.name as cinema_name, c.city, c.address
            FROM sessions s
            JOIN movies m ON s.movie_id = m.id
            JOIN screens sc ON s.screen_id = sc.id
            JOIN cinemas c ON sc.cinema_id = c.id
            WHERE s.is_active = TRUE AND s.session_date >= CURDATE()";
    
    $params = [];
    $types = '';
    
    // Add movie filter
    if ($movieId > 0) {
        $sql .= " AND s.movie_id = ?";
        $params[] = $movieId;
        $types .= 'i';
    }
    
    // Add cinema filter
    if ($cinemaId > 0) {
        $sql .= " AND c.id = ?";
        $params[] = $cinemaId;
        $types .= 'i';
    }
    
    // Add date filter
    if (!empty($date)) {
        $sql .= " AND s.session_date = ?";
        $params[] = $date;
        $types .= 's';
    }
    
    $sql .= " ORDER BY s.session_date ASC, s.session_time ASC, c.name ASC";
    
    // Prepare and execute
    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }
    
    if ($result === false) {
        throw new Exception('Database query failed: ' . $conn->error);
    }

    $sessions = [];
    while ($row = $result->fetch_assoc()) {
        $sessions[] = $row;
    }

    // Return JSON response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'count' => count($sessions),
        'sessions' => $sessions
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
