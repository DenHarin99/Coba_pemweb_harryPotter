<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../middleware/admin.php';

requireAdmin();

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$house    = $_POST['house'] ?? '';
$role     = $_POST['role'] ?? 'student';

if (empty($username) || empty($email) || empty($password) || empty($house)) {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
    exit();
}

$user = new User();

if ($user->exists($username, $email)) {
    echo json_encode(['success' => false, 'message' => 'Username atau email sudah ada']);
    exit();
}

if ($user->create($username, $email, $password, $house, $role)) {
    echo json_encode(['success' => true, 'message' => 'Student berhasil ditambahkan']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan student']);
}
?>