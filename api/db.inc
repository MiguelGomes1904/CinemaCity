<?php
// Cinema City Database Connection
// Database connection details for Cinema City project
$host = 'localhost';
$username = 'root';
$password = ''; // Update with your MySQL password if needed
$database = 'cinemacity'; // Cinema City database

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Set charset to UTF-8 for proper Portuguese language support
$conn->set_charset("utf8mb4");
?>
