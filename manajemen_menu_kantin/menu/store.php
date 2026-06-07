<?php
require_once __DIR__ . '/../config/auth_check.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /manajemen_menu_kantin/menu/create.php');
    exit;
}

$uid           = $_SESSION['user_id'];
$nama_menu     = trim($_POST['nama_menu']     ?? '');
$kategori_menu = trim($_POST['kategori_menu'] ?? '');
$harga         = (int)($_POST['harga']        ?? 0);
$stok          = (int)($_POST['stok']         ?? 0);
$status_menu   = trim($_POST['status_menu']   ?? '');
$deskripsi     = trim($_POST['deskripsi']     ?? '');
$tanggal_input = trim($_POST['tanggal_input'] ?? '');

// Validasi
$valid_kategori = ['Makanan','Minuman','Snack'];
$valid_status   = ['Tersedia','Habis'];

if (!$nama_menu || !$kategori_menu || !$status_menu || !$tanggal_input) {
    $_SESSION['form_error'] = 'Kolom wajib tidak boleh kosong.';
    $_SESSION['form_old']   = $_POST;
    header('Location: /manajemen_menu_kantin/menu/create.php');
    exit;
}

if (!in_array($kategori_menu, $valid_kategori) || !in_array($status_menu, $valid_status)) {
    $_SESSION['form_error'] = 'Nilai kategori atau status tidak valid.';
    $_SESSION['form_old']   = $_POST;
    header('Location: /manajemen_menu_kantin/menu/create.php');
    exit;
}

if ($harga < 0 || $stok < 0) {
    $_SESSION['form_error'] = 'Harga dan stok tidak boleh negatif.';
    $_SESSION['form_old']   = $_POST;
    header('Location: /manajemen_menu_kantin/menu/create.php');
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO menu (user_id, nama_menu, kategori_menu, harga, stok, status_menu, deskripsi, tanggal_input)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$uid, $nama_menu, $kategori_menu, $harga, $stok, $status_menu, $deskripsi, $tanggal_input]);

$_SESSION['flash_success'] = "Menu \"$nama_menu\" berhasil ditambahkan!";
header('Location: /manajemen_menu_kantin/menu/index.php');
exit;
