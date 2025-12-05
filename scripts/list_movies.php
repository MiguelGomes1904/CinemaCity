<?php
// Lists movies in the database for debugging
require_once __DIR__ . '/../api/db.inc';

if (!isset($conn) || !($conn instanceof mysqli)) {
    die("No DB connection. Check api/db.inc\n");
}

$res = $conn->query('SELECT id, title, poster FROM movies ORDER BY id');
if (!$res) {
    die('Query error: ' . $conn->error);
}

echo "<h2>Movies table</h2>\n";
echo "<table border=1 cellpadding=6>\n<tr><th>id</th><th>title</th><th>poster</th></tr>\n";
while ($r = $res->fetch_assoc()) {
    echo '<tr><td>' . htmlspecialchars($r['id']) . '</td><td>' . htmlspecialchars($r['title']) . '</td><td>' . htmlspecialchars($r['poster']) . '</td></tr>\n';
}
echo "</table>\n";

?>