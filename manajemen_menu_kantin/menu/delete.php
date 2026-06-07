<?php
require_once __DIR__ . '/../config/auth_check.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /manajemen_menu_kantin/menu/index.php');
    exit;
}

$uid = $_SESSION['user_id'];
$id  = (int)($_POST['id'] ?? 0);

if (!$id) {
    $_SESSION['flash_error'] = 'ID tidak valid.';
    header('Location: /manajemen_menu_kantin/menu/index.php');
    exit;
}

// Ambil nama menu untuk flash message (sekaligus validasi akses)
$stmt = $pdo->prepare("SELECT nama_menu FROM menu WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $uid]);
$menu = $stmt->fetch();

if (!$menu) {
    $_SESSION['flash_error'] = 'Data tidak ditemukan atau Anda tidak memiliki akses.';
    header('Location: /manajemen_menu_kantin/menu/index.php');
    exit;
}

// Hapus — kondisi WHERE id DAN user_id untuk keamanan ganda
$del = $pdo->prepare("DELETE FROM menu WHERE id = ? AND user_id = ?");
$del->execute([$id, $uid]);

$_SESSION['flash_success'] = "Menu \"{$menu['nama_menu']}\" berhasil dihapus.";
header('Location: /manajemen_menu_kantin/menu/index.php');
exit;
