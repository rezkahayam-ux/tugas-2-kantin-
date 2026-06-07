<?php
// auth_check.php — include di setiap halaman yang butuh login
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /manajemen_menu_kantin/auth/login.php?msg=login_required');
    exit;
}
