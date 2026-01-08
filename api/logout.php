<?php
// logout.php
// API endpoint for user logout

session_start();

// Set response header to JSON
header('Content-Type: application/json');

// Destroy session
session_unset();
session_destroy();

http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'Logout successful.'
]);
?>
