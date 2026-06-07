<?php
require_once __DIR__ . '/../config/auth_check.php';
require_once __DIR__ . '/../config/database.php';

$uid = $_SESSION['user_id'];

// Filter
$kategori = $_GET['kategori'] ?? '';
$status   = $_GET['status']   ?? '';
$cari     = $_GET['cari']     ?? '';

$sql    = "SELECT * FROM menu WHERE user_id = ?";
$params = [$uid];

if ($kategori) { $sql .= " AND kategori_menu = ?"; $params[] = $kategori; }
if ($status)   { $sql .= " AND status_menu = ?";   $params[] = $status; }
if ($cari)     { $sql .= " AND nama_menu LIKE ?";   $params[] = "%$cari%"; }

$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$menus = $stmt->fetchAll();

function formatRp($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
function kategoriClass($k) {
    return match($k) {
        'Makanan' => 'badge-makanan',
        'Minuman' => 'badge-minuman',
        default   => 'badge-snack',
    };
}

$success = $_SESSION['flash_success'] ?? '';
$error   = $_SESSION['flash_error']   ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$pageTitle  = 'Daftar Menu';
$activeMenu = 'menu';
require_once __DIR__ . '/../config/layout_start.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
        <span class="fw-bold">Menu Kantin — <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        <a href="/manajemen_menu_kantin/menu/create.php" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Menu
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="p-3 border-bottom bg-light" style="border-radius:0">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <input type="text" name="cari" class="form-control form-control-sm"
                       placeholder="🔍 Cari nama menu..." value="<?= htmlspecialchars($cari) ?>">
            </div>
            <div class="col-6 col-md-3">
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    <option value="Makanan" <?= $kategori==='Makanan'?'selected':'' ?>>Makanan</option>
                    <option value="Minuman" <?= $kategori==='Minuman'?'selected':'' ?>>Minuman</option>
                    <option value="Snack"   <?= $kategori==='Snack'?'selected':''   ?>>Snack</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="Tersedia" <?= $status==='Tersedia'?'selected':'' ?>>Tersedia</option>
                    <option value="Habis"    <?= $status==='Habis'?'selected':''    ?>>Habis</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                <a href="/manajemen_menu_kantin/menu/index.php" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <?php if (empty($menus)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                <p class="mb-0">Tidak ada menu ditemukan.</p>
                <?php if (!$kategori && !$status && !$cari): ?>
                    <a href="/manajemen_menu_kantin/menu/create.php" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Menu Pertama
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menus as $i => $m): ?>
                    <tr>
                        <td class="text-muted"><?= $i+1 ?></td>
                        <td>
                            <div class="fw-600" style="font-weight:600"><?= htmlspecialchars($m['nama_menu']) ?></div>
                            <?php if ($m['deskripsi']): ?>
                                <div class="text-muted" style="font-size:.76rem;max-width:200px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis">
                                    <?= htmlspecialchars($m['deskripsi']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><span class="<?= kategoriClass($m['kategori_menu']) ?>"><?= htmlspecialchars($m['kategori_menu']) ?></span></td>
                        <td style="font-weight:600"><?= formatRp($m['harga']) ?></td>
                        <td><?= $m['stok'] ?></td>
                        <td><span class="<?= $m['status_menu']==='Tersedia'?'badge-tersedia':'badge-habis' ?>"><?= htmlspecialchars($m['status_menu']) ?></span></td>
                        <td class="text-muted" style="font-size:.82rem"><?= date('d/m/Y', strtotime($m['tanggal_input'])) ?></td>
                        <td>
                            <a href="/manajemen_menu_kantin/menu/edit.php?id=<?= $m['id'] ?>"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-hapus"
                                    data-id="<?= $m['id'] ?>"
                                    data-nama="<?= htmlspecialchars($m['nama_menu']) ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="p-3 text-muted" style="font-size:.82rem">
            Menampilkan <?= count($menus) ?> menu
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius:var(--radius)">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Hapus Menu</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size:.88rem">Yakin ingin menghapus menu <strong id="namaMenuHapus"></strong>? Tindakan ini tidak bisa dibatalkan.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="/manajemen_menu_kantin/menu/delete.php" id="formHapus">
                    <input type="hidden" name="id" id="inputIdHapus">
                    <button type="submit" class="btn btn-sm btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('namaMenuHapus').textContent = btn.dataset.nama;
        document.getElementById('inputIdHapus').value = btn.dataset.id;
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    });
});
</script>

<?php require_once __DIR__ . '/../config/layout_end.php'; ?>
