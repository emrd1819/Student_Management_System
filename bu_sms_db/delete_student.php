<?php
session_start();
require 'db_connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['bu_admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'No ID provided']);
    exit;
}

try {
    // The frontend JS sends the studentId (string) or the index.
    // The updated JS in admin_dashboard sends `studentId` in the body.
    $stmt = $pdo->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->execute([$id]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>