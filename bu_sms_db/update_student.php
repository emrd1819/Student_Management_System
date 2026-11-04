<?php
session_start();
require 'db_connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['bu_admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    // Note: Frontend sends 'editStudentId' as a read-only field identifying the record by Student ID
    // Ideally use primary key ID, but using student_id is sufficient if unique.
    
    $stmt = $pdo->prepare("UPDATE students SET first_name=?, middle_name=?, last_name=?, date_of_birth=?, gender=?, email=?, phone=?, address=?, program=?, year_level=? WHERE student_id=?");
    
    $result = $stmt->execute([
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
        $_POST['studentId'] // Where clause
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>