<?php
// list-products.php
// API endpoint to list all available products

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
    // Check for category filter
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';
    
    // Base query
    $sql = "SELECT id, name, description, category, price, image_url, 
            is_available, stock 
            FROM products WHERE is_available = TRUE";
    
    // Add category filter if provided
    if (!empty($category)) {
        $stmt = $conn->prepare($sql . " AND category = ? ORDER BY category, name");
        if (!$stmt) {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql .= " ORDER BY category, name";
        $result = $conn->query($sql);
    }
    
    if ($result === false) {
        throw new Exception('Database query failed: ' . $conn->error);
    }

    $products = [];
    $productsByCategory = [];
    
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
        
        // Group by category
        if (!isset($productsByCategory[$row['category']])) {
            $productsByCategory[$row['category']] = [];
        }
        $productsByCategory[$row['category']][] = $row;
    }

    // Return JSON response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'count' => count($products),
        'products' => $products,
        'productsByCategory' => $productsByCategory
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
