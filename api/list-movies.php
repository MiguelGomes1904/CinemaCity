<?php
// list-movies.php
// API endpoint to list all active movies

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
    $genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    // Base query
    $sql = "SELECT id, title, description, duration, genre, director, cast, 
            release_date, poster_url, trailer_url, rating, age_rating, 
            language, subtitle, is_active 
            FROM movies WHERE is_active = TRUE";
    
    $params = [];
    $types = '';
    
    // Add genre filter
    if (!empty($genre)) {
        $sql .= " AND genre = ?";
        $params[] = $genre;
        $types .= 's';
    }
    
    // Add search filter
    if (!empty($search)) {
        $sql .= " AND (title LIKE ? OR description LIKE ?)";
        $searchParam = "%{$search}%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $types .= 'ss';
    }
    
    $sql .= " ORDER BY release_date DESC, title ASC";
    
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

    $movies = [];
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }

    // Return JSON response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'count' => count($movies),
        'movies' => $movies
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
