<?php
session_start();
require 'db_connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['bu_user_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
$current_user = $_SESSION['student_id'];

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'No ID provided']);
    exit;
}

try {
    // Delete with Authorization Check (AND submitted_by = ?)
    $stmt = $pdo->prepare("DELETE FROM pending_students WHERE id = ? AND submitted_by = ?");
    $stmt->execute([$id, $current_user]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Record not found or unauthorized']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>