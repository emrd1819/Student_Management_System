<?php
session_start();
require 'db_connect.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$email = $_POST['email'] ?? '';

// --- STRICT BACKEND DOMAIN CHECK ---
if (!empty($email) && !preg_match('/@bicol-u\.edu\.ph$/i', $email)) {
    echo json_encode(['success' => false, 'message' => 'Login Denied: Only @bicol-u.edu.ph emails are allowed.']);
    exit;
}

if ($action === 'login_admin') {
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['bu_admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }

} elseif ($action === 'login_student') {
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->execute([$email]);
    $student = $stmt->fetch();

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['bu_user_logged_in'] = true;
        $_SESSION['student_db_id'] = $student['id'];
        $_SESSION['student_id'] = $student['student_id'];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>