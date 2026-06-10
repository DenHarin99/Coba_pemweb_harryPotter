<?php
// Pastikan session sudah start sebelum require ini
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /frontend/pages/login.php');
        exit();
    }
}
?>