<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../models/User.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm_password'] ?? '';
$house    = $_POST['house'] ?? '';

// Validasi
$allowedHouses = ['Gryffindor', 'Slytherin', 'Ravenclaw', 'Hufflepuff'];

if (empty($username) || empty($email) || empty($password) || empty($house)) {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Format email tidak valid']);
    exit();
}
if ($password !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'Password tidak cocok']);
    exit();
}
if (!in_array($house, $allowedHouses)) {
    echo json_encode(['success' => false, 'message' => 'House tidak valid']);
    exit();
}

$user = new User();

if ($user->exists($username, $email)) {
    echo json_encode(['success' => false, 'message' => 'Username atau email sudah digunakan']);
    exit();
}

if ($user->create($username, $email, $password, $house)) {
    echo json_encode(['success' => true, 'message' => 'Registrasi berhasil! Silakan login.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal mendaftar, coba lagi']);
}
?>