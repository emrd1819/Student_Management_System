<?php
session_start();
require 'db_connect.php';
header('Content-Type: application/json');

$submitted_by = $_SESSION['student_id'] ?? 'Guest';

try {
    $stmt = $pdo->prepare("INSERT INTO pending_students (student_id, first_name, middle_name, last_name, date_of_birth, gender, email, phone, address, program, year_level, submitted_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $result = $stmt->execute([
        $_POST['studentId'],
        $_POST['firstName'],
        $_POST['middleName'] ?? '',
        $_POST['lastName'],
        $_POST['dateOfBirth'],
        $_POST['gender'],
        $_POST['email'],
        $_POST['phone'] ?? '',
        $_POST['address'] ?? '',
        $_POST['program'],
        $_POST['yearLevel'],
        $submitted_by
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>