<?php
session_start();
require 'db_connect.php';
header('Content-Type: application/json');

// Security Check
if (!isset($_SESSION['bu_admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$studentId = $data['studentId'] ?? null;

if (!$studentId) {
    echo json_encode(['success' => false, 'message' => 'No ID provided']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Get Data from Pending Table
    $stmt = $pdo->prepare("SELECT * FROM pending_students WHERE student_id = ?");
    $stmt->execute([$studentId]);
    $student = $stmt->fetch();

    if ($student) {
        // 2. Insert into Main Students Table
        // Use IGNORE to prevent crashing if ID already exists
        $insert = $pdo->prepare("INSERT IGNORE INTO students (student_id, first_name, middle_name, last_name, date_of_birth, gender, email, phone, address, program, year_level) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $insert->execute([
            $student['student_id'],
            $student['first_name'],
            $student['middle_name'],
            $student['last_name'],
            $student['date_of_birth'],
            $student['gender'],
            $student['email'],
            $student['phone'],
            $student['address'],
            $student['program'],
            $student['year_level']
        ]);

        // 3. Delete from Pending Table
        $delete = $pdo->prepare("DELETE FROM pending_students WHERE student_id = ?");
        $delete->execute([$studentId]);

        $pdo->commit();
        echo json_encode(['success' => true]);
    } else {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Record not found']);
    }
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>