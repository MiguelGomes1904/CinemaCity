<?php
// Creates the `cinema_city` database if it doesn't exist.
// Update credentials if needed.

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'cinema_city';

$conn = new mysqli($host, $username, $password);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS `" . $conn->real_escape_string($dbname) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Database `{$dbname}` created or already exists.\n";
} else {
    die('Error creating database: ' . $conn->error);
}

echo "Done. Now run scripts/setup_movies.php to create tables and seed sample data.\n";

$conn->close();

?>
