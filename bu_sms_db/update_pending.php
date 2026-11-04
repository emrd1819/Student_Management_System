<?php
session_start();
require 'db_connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['bu_user_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $current_user = $_SESSION['student_id'];

    // Update with Authorization Check (AND submitted_by = ?)
    $stmt = $pdo->prepare("UPDATE pending_students SET 
        student_id = ?, first_name = ?, middle_name = ?, last_name = ?, 
        date_of_birth = ?, gender = ?, email = ?, phone = ?, address = ?, 
        program = ?, year_level = ? 
        WHERE id = ? AND submitted_by = ?");
    
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
        $_POST['dbId'],
        $current_user // User ID Check
    ]);

    // Check if any row was actually updated
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        // If no rows updated, either no changes made OR user unauthorized
        echo json_encode(['success' => true]); 
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>