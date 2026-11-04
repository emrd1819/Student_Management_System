<?php
session_start();
require 'db_connect.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$email = $_POST['email'] ?? '';

// --- STRICT BACKEND DOMAIN CHECK ---
if (!empty($email) && !preg_match('/@bicol-u\.edu\.ph$/i', $email)) {
    echo json_encode(['success' => false, 'message' => 'Registration Denied: Only @bicol-u.edu.ph emails are allowed.']);
    exit;
}

if ($action === 'signup_admin') {
    $userCode = $_POST['auth_code'] ?? '';
    
    // 1. Fetch the correct code from the database
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'admin_auth_code'");
        $stmt->execute();
        $row = $stmt->fetch();
        // Default fallback if DB is empty
        $dbCode = $row ? $row['setting_value'] : 'BU-ADMIN-2024'; 
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'System Error: Could not verify code']);
        exit;
    }

    // 2. Compare User Input vs Database Code
    if ($userCode !== $dbCode) {
        echo json_encode(['success' => false, 'message' => 'Invalid Admin Authorization Code']);
        exit;
    }

    // 3. Register
    $fullName = $_POST['full_name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO admins (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$fullName, $email, $password]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'Email already registered']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }

} elseif ($action === 'signup_student') {
    $fullName = trim($_POST['full_name']);
    $studentId = $_POST['student_id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $parts = explode(' ', $fullName);
    $lastName = array_pop($parts);
    $firstName = implode(' ', $parts);
    if (empty($firstName)) { $firstName = $lastName; $lastName = ''; }

    try {
        $stmt = $pdo->prepare("INSERT INTO students (student_id, first_name, last_name, email, password, program, year_level) VALUES (?, ?, ?, ?, ?, 'Unassigned', '1')");
        $stmt->execute([$studentId, $firstName, $lastName, $email, $password]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'Student ID or Email already exists']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>