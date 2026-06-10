<?php
require_once __DIR__ . '/auth.php';

function requireStudent() {
    requireLogin();
    if ($_SESSION['role'] !== 'student') {
        header('Location: /frontend/pages/admin/dashboard.php');
        exit();
    }
}
?>