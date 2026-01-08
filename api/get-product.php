<?php
// get-product.php
// API endpoint to get details of a specific product

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
    // Get product ID from query parameter
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($productId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Valid product ID is required.']);
        exit;
    }

    // Get product details
    $stmt = $conn->prepare("
        SELECT id, name, description, category, price, image_url, 
               is_available, stock, created_at 
        FROM products 
        WHERE id = ?
    ");
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
        $stmt->close();
        $conn->close();
        exit;
    }

    $product = $result->fetch_assoc();
    $stmt->close();

    // Return JSON response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'product' => $product
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
