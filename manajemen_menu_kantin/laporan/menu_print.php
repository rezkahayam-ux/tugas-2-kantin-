<?php
require_once __DIR__ . '/../config/auth_check.php';
require_once __DIR__ . '/../config/database.php';

$uid = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM menu WHERE user_id = ? ORDER BY kategori_menu, nama_menu");
$stmt->execute([$uid]);
$menus = $stmt->fetchAll();

// Statistik untuk laporan
$totalMenu      = count($menus);
$totalTersedia  = count(array_filter($menus, fn($m) => $m['status_menu'] === 'Tersedia'));
$totalHabis     = count(array_filter($menus, fn($m) => $m['status_menu'] === 'Habis'));
$totalNilai     = array_sum(array_map(fn($m) => $m['harga'] * $m['stok'], $menus));

function formatRp($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }

$pageTitle  = 'Cetak Laporan';
$activeMenu = 'laporan';
require_once __DIR__ . '/../config/layout_start.php';
?>

<!-- Toolbar (tidak tercetak) -->
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <div>
        <h5 class="mb-0 fw-bold">Preview Laporan</h5>
        <small class="text-muted">Klik "Cetak Laporan" untuk mencetak atau simpan sebagai PDF</small>
    </div>
    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer me-1"></i> Cetak Laporan
    </button>
</div>

<!-- AREA LAPORAN -->
<div class="card" id="laporanArea">
    <div class="card-body p-4">

        <!-- Header Laporan -->
        <div class="text-center mb-4">
            <div style="font-size:2rem">🍱</div>
            <h4 class="mb-0 fw-bold">LAPORAN MENU KANTIN SEKOLAH</h4>
            <h5 class="mb-0"><?= htmlspecialchars($_SESSION['user_name']) ?></h5>
            <p class="text-muted mb-0" style="font-size:.85rem">
                Tanggal Cetak: <?= date('d F Y, H:i') ?> WIB
            </p>
            <hr>
        </div>

        <!-- Ringkasan -->
        <div class="row g-2 mb-4">
            <div class="col-3 text-center">
                <div style="font-size:1.5rem;font-weight:800;color:#f97316"><?= $totalMenu ?></div>
                <div style="font-size:.78rem;color:#64748b">Total Menu</div>
            </div>
            <div class="col-3 text-center">
                <div style="font-size:1.5rem;font-weight:800;color:#16a34a"><?= $totalTersedia ?></div>
                <div style="font-size:.78rem;color:#64748b">Tersedia</div>
            </div>
            <div class="col-3 text-center">
                <div style="font-size:1.5rem;font-weight:800;color:#dc2626"><?= $totalHabis ?></div>
                <div style="font-size:.78rem;color:#64748b">Habis</div>
            </div>
            <div class="col-3 text-center">
                <div style="font-size:1.1rem;font-weight:800;color:#2563eb"><?= formatRp($totalNilai) ?></div>
                <div style="font-size:.78rem;color:#64748b">Nilai Inventori</div>
            </div>
        </div>

        <!-- Tabel Menu -->
        <?php if (empty($menus)): ?>
            <p class="text-center text-muted">Tidak ada menu untuk ditampilkan.</p>
        <?php else: ?>
            <table class="table table-bordered" style="font-size:.84rem">
                <thead style="background:#f8fafc">
                    <tr>
                        <th style="width:35px">#</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Nilai</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menus as $i => $m): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><strong><?= htmlspecialchars($m['nama_menu']) ?></strong></td>
                        <td><?= htmlspecialchars($m['kategori_menu']) ?></td>
                        <td><?= formatRp($m['harga']) ?></td>
                        <td><?= $m['stok'] ?></td>
                        <td><?= htmlspecialchars($m['status_menu']) ?></td>
                        <td><?= formatRp($m['harga'] * $m['stok']) ?></td>
                        <td><?= date('d/m/Y', strtotime($m['tanggal_input'])) ?></td>
                        <td style="font-size:.78rem;color:#64748b"><?= htmlspecialchars($m['deskripsi'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background:#fff7ed">
                        <th colspan="6" class="text-end">Total Nilai Inventori:</th>
                        <th colspan="3" style="color:#f97316"><?= formatRp($totalNilai) ?></th>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>

        <!-- Footer Laporan -->
        <div class="mt-4 d-flex justify-content-between" style="font-size:.82rem">
            <div>
                <p class="mb-0 text-muted">Dicetak oleh: <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></p>
                <p class="mb-0 text-muted">Email: <?= htmlspecialchars($_SESSION['user_email']) ?></p>
            </div>
            <div class="text-end">
                <p class="mb-0 text-muted">Sistem Manajemen Menu</p>
                <p class="mb-0 text-muted">Kantin Sekolah</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../config/layout_end.php'; ?>
