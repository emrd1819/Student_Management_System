<?php
error_reporting(0);
ini_set('display_errors', 0);

session_start();
require 'db_connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['bu_admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// --- STRICT BACKEND DOMAIN CHECK ---
$email = $_POST['email'] ?? '';
if (!empty($email) && !preg_match('/@bicol-u\.edu\.ph$/i', $email)) {
    echo json_encode(['success' => false, 'message' => 'Error: Only @bicol-u.edu.ph emails are allowed.']);
    exit;
}

try {
    $defaultPassword = password_hash('123456', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO students 
        (student_id, first_name, middle_name, last_name, date_of_birth, gender, email, phone, address, program, year_level, password) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $result = $stmt->execute([
        $_POST['studentId'],
        $_POST['firstName'],
        $_POST['middleName'] ?? '',
        $_POST['lastName'],
        $_POST['dateOfBirth'],
        $_POST['gender'],
        $_POST['email'], // Validated email
        $_POST['phone'] ?? '',
        $_POST['address'] ?? '',
        $_POST['program'],
        $_POST['yearLevel'],
        $defaultPassword 
    ]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'message' => 'Error: Student ID or Email already exists.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
    }
}
?>