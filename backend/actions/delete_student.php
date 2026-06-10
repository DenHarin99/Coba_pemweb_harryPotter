<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../middleware/admin.php';

requireAdmin();

$id = $_POST['id'] ?? '';

if (empty($id)) {
    echo json_encode([
        'success' => false,
        'message' => 'ID student tidak ditemukan'
    ]);
    exit();
}

$user = new User();

if ($user->delete($id)) {
    echo json_encode([
        'success' => true,
        'message' => 'Student berhasil dihapus'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menghapus student'
    ]);
}
?>