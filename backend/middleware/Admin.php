<?php
require_once __DIR__ . '/auth.php';

function requireAdmin() {
    requireLogin();
    if ($_SESSION['role'] !== 'admin') {
        header('Location: /frontend/pages/student/dashboard.php');
        exit();
    }
}
?>