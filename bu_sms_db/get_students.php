<?php
// Disable error reporting for the output to prevent invalid JSON
error_reporting(0);
ini_set('display_errors', 0);

require 'db_connect.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT student_id AS studentId, first_name AS firstName, middle_name AS middleName, last_name AS lastName, date_of_birth AS dateOfBirth, gender, email, phone, address, program, year_level AS yearLevel FROM students ORDER BY last_name ASC");
    $students = $stmt->fetchAll();
    
    // If empty, return empty array
    if (!$students) {
        echo json_encode([]);
    } else {
        echo json_encode($students);
    }
} catch (Exception $e) {
    // Return empty array on error
    echo json_encode([]);
}
?>