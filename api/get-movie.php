<?php
// get-movie.php
// API endpoint to get details of a specific movie

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
    // Get movie ID from query parameter
    $movieId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($movieId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Valid movie ID is required.']);
        exit;
    }

    // Get movie details
    $stmt = $conn->prepare("
        SELECT id, title, description, duration, genre, director, cast, 
               release_date, poster_url, trailer_url, rating, age_rating, 
               language, subtitle, is_active, created_at 
        FROM movies 
        WHERE id = ?
    ");
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Movie not found.']);
        $stmt->close();
        $conn->close();
        exit;
    }

    $movie = $result->fetch_assoc();
    $stmt->close();

    // Get sessions for this movie
    $sessionsStmt = $conn->prepare("
        SELECT s.id, s.session_date, s.session_time, s.price, s.available_seats,
               sc.screen_name, sc.screen_type, c.name as cinema_name, c.city
        FROM sessions s
        JOIN screens sc ON s.screen_id = sc.id
        JOIN cinemas c ON sc.cinema_id = c.id
        WHERE s.movie_id = ? AND s.is_active = TRUE AND s.session_date >= CURDATE()
        ORDER BY s.session_date ASC, s.session_time ASC
    ");
    
    if ($sessionsStmt) {
        $sessionsStmt->bind_param("i", $movieId);
        $sessionsStmt->execute();
        $sessionsResult = $sessionsStmt->get_result();
        
        $sessions = [];
        while ($sessionRow = $sessionsResult->fetch_assoc()) {
            $sessions[] = $sessionRow;
        }
        $sessionsStmt->close();
        $movie['sessions'] = $sessions;
    }

    // Get reviews for this movie
    $reviewsStmt = $conn->prepare("
        SELECT r.id, r.rating, r.comment, r.review_date, u.name as user_name
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        WHERE r.movie_id = ?
        ORDER BY r.review_date DESC
        LIMIT 10
    ");
    
    if ($reviewsStmt) {
        $reviewsStmt->bind_param("i", $movieId);
        $reviewsStmt->execute();
        $reviewsResult = $reviewsStmt->get_result();
        
        $reviews = [];
        while ($reviewRow = $reviewsResult->fetch_assoc()) {
            $reviews[] = $reviewRow;
        }
        $reviewsStmt->close();
        $movie['reviews'] = $reviews;
    }

    // Return JSON response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'movie' => $movie
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
