<?php
// layout.php - reusable sidebar/topbar component
// Include AFTER session_start() and after setting $pageTitle & $activeMenu

function getInitial($name) {
    $parts = explode(' ', trim($name));
    return strtoupper(substr($parts[0], 0, 1));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Kantin Sekolah') ?> — Kantin Sekolah</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/manajemen_menu_kantin/assets/css/style.css">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon">🍱</span>
            <span>Kantin<br>Sekolah</span>
        </div>

        <div class="sidebar-user">
            <div class="d-flex align-items-center">
                <div class="avatar"><?= getInitial($_SESSION['user_name']) ?></div>
                <div>
                    <div class="user-name"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
                    <div class="user-role">Penjual Kantin</div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>
            <a href="/manajemen_menu_kantin/dashboard.php" class="<?= ($activeMenu==='dashboard')?'active':'' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="/manajemen_menu_kantin/menu/index.php" class="<?= ($activeMenu==='menu')?'active':'' ?>">
                <i class="bi bi-grid-3x3-gap"></i> Daftar Menu
            </a>
            <a href="/manajemen_menu_kantin/menu/create.php" class="<?= ($activeMenu==='tambah')?'active':'' ?>">
                <i class="bi bi-plus-circle"></i> Tambah Menu
            </a>
            <div class="nav-label">Laporan</div>
            <a href="/manajemen_menu_kantin/laporan/menu_print.php" class="<?= ($activeMenu==='laporan')?'active':'' ?>">
                <i class="bi bi-printer"></i> Cetak Laporan
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="/manajemen_menu_kantin/auth/logout.php">
                <i class="bi bi-box-arrow-left"></i> Keluar
            </a>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main-content">
        <!-- TOPBAR -->
        <div class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="btn-hamburger" id="hamburgerBtn"><i class="bi bi-list"></i></button>
                <h1 class="topbar-title"><?= htmlspecialchars($pageTitle ?? '') ?></h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted d-none d-md-inline" style="font-size:.82rem">
                    <?= date('d M Y') ?>
                </span>
                <a href="/manajemen_menu_kantin/auth/logout.php" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-left me-1"></i>Keluar
                </a>
            </div>
        </div>

        <div class="page-body">
