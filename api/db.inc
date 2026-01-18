<?php
// Cinema City Database Connection
// Prefer environment variables; fallback to local XAMPP defaults
$host = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$database = getenv('DB_NAME') ?: 'cinemacity';

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Set charset to UTF-8 for proper Portuguese language support
$conn->set_charset("utf8mb4");
?>
