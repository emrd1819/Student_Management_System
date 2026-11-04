<?php
require 'db_connect.php';
echo "<h2>Database Status Check</h2>";

// Check Main Students
$stmt = $pdo->query("SELECT * FROM students");
$students = $stmt->fetchAll();
echo "<h3>Main Students Table (" . count($students) . ")</h3>";
echo "<pre>";
print_r($students);
echo "</pre>";

// Check Pending
$stmt = $pdo->query("SELECT * FROM pending_students");
$pending = $stmt->fetchAll();
echo "<h3>Pending Students Table (" . count($pending) . ")</h3>";
echo "<pre>";
print_r($pending);
echo "</pre>";
?>