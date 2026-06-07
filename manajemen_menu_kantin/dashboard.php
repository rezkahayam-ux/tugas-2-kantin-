<?php
require_once __DIR__ . '/config/auth_check.php';
require_once __DIR__ . '/config/database.php';

$uid = $_SESSION['user_id'];

// Statistik
$total = $pdo->prepare("SELECT COUNT(*) FROM menu WHERE user_id = ?");
$total->execute([$uid]);
$totalMenu = $total->fetchColumn();

$tersedia = $pdo->prepare("SELECT COUNT(*) FROM menu WHERE user_id = ? AND status_menu = 'Tersedia'");
$tersedia->execute([$uid]);
$totalTersedia = $tersedia->fetchColumn();

$habis = $pdo->prepare("SELECT COUNT(*) FROM menu WHERE user_id = ? AND status_menu = 'Habis'");
$habis->execute([$uid]);
$totalHabis = $habis->fetchColumn();

$nilaiTotal = $pdo->prepare("SELECT COALESCE(SUM(harga * stok),0) FROM menu WHERE user_id = ?");
$nilaiTotal->execute([$uid]);
$nilaiInventori = $nilaiTotal->fetchColumn();

// 5 menu terbaru
$stmt = $pdo->prepare("SELECT * FROM menu WHERE user_id = ? ORDER BY id DESC LIMIT 5");
$stmt->execute([$uid]);
$recentMenu = $stmt->fetchAll();

function formatRp($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }

$pageTitle  = 'Dashboard';
$activeMenu = 'dashboard';
require_once __DIR__ . '/config/layout_start.php';
?>

<!-- Greeting -->
<div class="mb-4">
    <h2 class="mb-1">Halo, <?= htmlspecialchars($_SESSION['user_name']) ?>! 👋</h2>
    <p class="text-muted mb-0">Berikut ringkasan menu kantinmu hari ini.</p>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-orange">
            <div class="stat-icon"><i class="bi bi-grid-3x3-gap"></i></div>
            <div class="stat-num"><?= $totalMenu ?></div>
            <div class="stat-label">Total Menu</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-num"><?= $totalTersedia ?></div>
            <div class="stat-label">Menu Tersedia</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-red">
            <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
            <div class="stat-num"><?= $totalHabis ?></div>
            <div class="stat-label">Menu Habis</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-num" style="font-size:1.2rem"><?= formatRp($nilaiInventori) ?></div>
            <div class="stat-label">Nilai Inventori</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Recent Menu -->
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">Menu Terbaru</span>
                <a href="/manajemen_menu_kantin/menu/index.php" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
            </div>
            <div class="card-body p-3">
                <?php if (empty($recentMenu)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        Belum ada menu. <a href="/manajemen_menu_kantin/menu/create.php">Tambah sekarang</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($recentMenu as $m): ?>
                    <div class="recent-item">
                        <div class="recent-icon"
                             style="background:<?= $m['kategori_menu']==='Makanan'?'#fef9c3':($m['kategori_menu']==='Minuman'?'#dbeafe':'#f3e8ff') ?>">
                            <?= $m['kategori_menu']==='Makanan'?'🍛':($m['kategori_menu']==='Minuman'?'🥤':'🍿') ?>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-600" style="font-size:.88rem;font-weight:600"><?= htmlspecialchars($m['nama_menu']) ?></div>
                            <div class="text-muted" style="font-size:.78rem"><?= htmlspecialchars($m['kategori_menu']) ?> · Stok: <?= $m['stok'] ?></div>
                        </div>
                        <div class="text-end">
                            <div style="font-size:.88rem;font-weight:700;color:var(--primary)"><?= formatRp($m['harga']) ?></div>
                            <span class="<?= $m['status_menu']==='Tersedia'?'badge-tersedia':'badge-habis' ?>">
                                <?= htmlspecialchars($m['status_menu']) ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header"><span class="fw-bold">Aksi Cepat</span></div>
            <div class="card-body p-3">
                <a href="/manajemen_menu_kantin/menu/create.php" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Menu Baru
                </a>
                <a href="/manajemen_menu_kantin/menu/index.php" class="btn btn-outline-secondary w-100 mb-2">
                    <i class="bi bi-list-ul me-1"></i> Kelola Semua Menu
                </a>
                <a href="/manajemen_menu_kantin/laporan/menu_print.php" class="btn btn-outline-success w-100">
                    <i class="bi bi-printer me-1"></i> Cetak Laporan
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span class="fw-bold">Informasi Akun</span></div>
            <div class="card-body p-3" style="font-size:.88rem">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Nama</span>
                    <span class="fw-600"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Email</span>
                    <span class="fw-600"><?= htmlspecialchars($_SESSION['user_email']) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/config/layout_end.php'; ?>
