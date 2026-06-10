<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../middleware/admin.php';

requireAdmin();

$id       = $_POST['id'] ?? '';
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$house    = $_POST['house'] ?? '';
$role     = $_POST['role'] ?? 'student';

if (empty($id) || empty($username) || empty($email) || empty($house)) {
    echo json_encode([
        'success' => false,
        'message' => 'Semua field wajib diisi'
    ]);
    exit();
}

$user = new User();

if ($user->update($id, $username, $email, $house, $role)) {
    echo json_encode([
        'success' => true,
        'message' => 'Data student berhasil diperbarui'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal memperbarui data student'
    ]);
}
?>