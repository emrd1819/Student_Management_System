<?php
require 'db_connect.php';
header('Content-Type: application/json');

try {
    // Select all pending students
    $stmt = $pdo->query("SELECT id, student_id AS studentId, first_name AS firstName, middle_name AS middleName, last_name AS lastName, date_of_birth AS dateOfBirth, gender, email, phone, address, program, year_level AS yearLevel, submitted_date AS submittedDate, status FROM pending_students ORDER BY submitted_date DESC");
    $pending = $stmt->fetchAll();
    echo json_encode($pending);
} catch (Exception $e) {
    echo json_encode([]);
}
?>