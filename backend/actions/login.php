<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../models/User.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$identifier = trim($_POST['identifier'] ?? ''); // username atau email
$password   = $_POST['password'] ?? '';

if (empty($identifier) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username/email dan password wajib diisi']);
    exit();
}

$user = new User();
$data = $user->findByUsernameOrEmail($identifier);

if (!$data || !password_verify($password, $data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Username/email atau password salah']);
    exit();
}

// Set session
$_SESSION['user_id']  = $data['id'];
$_SESSION['username'] = $data['username'];
$_SESSION['role']     = $data['role'];
$_SESSION['house']    = $data['house'];

// Redirect berdasarkan role
$redirect = $data['role'] === 'admin' 
    ? '/frontend/pages/admin/dashboard.php' 
    : '/frontend/pages/student/dashboard.php';

echo json_encode(['success' => true, 'redirect' => $redirect]);
?>