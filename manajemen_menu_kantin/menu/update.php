<?php
require_once __DIR__ . '/../config/auth_check.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /manajemen_menu_kantin/menu/index.php');
    exit;
}

$uid           = $_SESSION['user_id'];
$id            = (int)($_POST['id']            ?? 0);
$nama_menu     = trim($_POST['nama_menu']     ?? '');
$kategori_menu = trim($_POST['kategori_menu'] ?? '');
$harga         = (int)($_POST['harga']        ?? 0);
$stok          = (int)($_POST['stok']         ?? 0);
$status_menu   = trim($_POST['status_menu']   ?? '');
$deskripsi     = trim($_POST['deskripsi']     ?? '');
$tanggal_input = trim($_POST['tanggal_input'] ?? '');

// Validasi
if (!$id || !$nama_menu || !$kategori_menu || !$status_menu || !$tanggal_input) {
    $_SESSION['form_error'] = 'Kolom wajib tidak boleh kosong.';
    $_SESSION['form_old']   = $_POST;
    header("Location: /manajemen_menu_kantin/menu/edit.php?id=$id");
    exit;
}

// Keamanan: update hanya jika id DAN user_id cocok
$stmt = $pdo->prepare("
    UPDATE menu
    SET nama_menu=?, kategori_menu=?, harga=?, stok=?, status_menu=?, deskripsi=?, tanggal_input=?
    WHERE id=? AND user_id=?
");
$stmt->execute([$nama_menu, $kategori_menu, $harga, $stok, $status_menu, $deskripsi, $tanggal_input, $id, $uid]);

if ($stmt->rowCount() === 0) {
    // Cek apakah data ada tapi akses ditolak atau data tidak berubah
    $check = $pdo->prepare("SELECT id FROM menu WHERE id = ? AND user_id = ?");
    $check->execute([$id, $uid]);
    if (!$check->fetch()) {
        $_SESSION['flash_error'] = 'Data tidak ditemukan atau Anda tidak memiliki akses.';
        header('Location: /manajemen_menu_kantin/menu/index.php');
        exit;
    }
}

$_SESSION['flash_success'] = "Menu \"$nama_menu\" berhasil diperbarui!";
header('Location: /manajemen_menu_kantin/menu/index.php');
exit;
