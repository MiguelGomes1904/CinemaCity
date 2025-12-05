<?php
// Quick DB check script: lists tables in the configured database
require_once __DIR__ . '/../api/db.inc';

if (!isset($conn) || !($conn instanceof mysqli)) {
    die("No DB connection. Check api/db.inc\n");
}

echo "Connected to database: " . htmlspecialchars($conn->database) . "\n<br>\n";

$res = $conn->query("SHOW TABLES");
if (!$res) {
    die("Error running SHOW TABLES: " . htmlspecialchars($conn->error));
}

echo "Tables in database:<br>\n<ul>\n";
while ($row = $res->fetch_array()) {
    echo "<li>" . htmlspecialchars($row[0]) . "</li>\n";
}
echo "</ul>\n";

?>
