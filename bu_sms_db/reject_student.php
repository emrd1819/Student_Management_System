<?php
session_start();
require 'db_connect.php';
header('Content-Type: application/json');

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
    $stmt = $pdo->prepare("DELETE FROM pending_students WHERE student_id = ?");
    $stmt->execute([$studentId]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>