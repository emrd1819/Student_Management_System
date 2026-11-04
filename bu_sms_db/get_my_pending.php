<?php
session_start();
require 'db_connect.php';
header('Content-Type: application/json');

$current_user = $_SESSION['student_id'] ?? 'Guest';

try {
    // Select ALL fields so we can populate the Edit Modal
    $stmt = $pdo->prepare("SELECT 
        id, 
        student_id AS studentId, 
        first_name AS firstName, 
        middle_name AS middleName, 
        last_name AS lastName, 
        date_of_birth AS dateOfBirth, 
        gender, 
        email, 
        phone, 
        address, 
        program, 
        year_level AS yearLevel, 
        status, 
        submitted_date AS submittedDate 
        FROM pending_students 
        WHERE submitted_by = ? 
        ORDER BY submitted_date DESC");
        
    $stmt->execute([$current_user]);
    $pending = $stmt->fetchAll();
    echo json_encode($pending);
} catch (Exception $e) {
    echo json_encode([]);
}
?>