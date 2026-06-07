<?php
require_once __DIR__ . '/../config/auth_check.php';
require_once __DIR__ . '/../config/database.php';

$uid = $_SESSION['user_id'];
$id  = (int)($_GET['id'] ?? 0);

// Keamanan: cari data berdasarkan id DAN user_id login
$stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $uid]);
$menu = $stmt->fetch();

if (!$menu) {
    $_SESSION['flash_error'] = 'Data tidak ditemukan atau Anda tidak memiliki akses.';
    header('Location: /manajemen_menu_kantin/menu/index.php');
    exit;
}

// Pakai nilai lama dari session jika ada (setelah validasi gagal)
$old = $_SESSION['form_old'] ?? $menu;
unset($_SESSION['form_old'], $_SESSION['form_error']);

$pageTitle  = 'Edit Menu';
$activeMenu = 'menu';
require_once __DIR__ . '/../config/layout_start.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">Edit Menu: <?= htmlspecialchars($menu['nama_menu']) ?></span>
                <a href="/manajemen_menu_kantin/menu/index.php" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="/manajemen_menu_kantin/menu/update.php">
                    <input type="hidden" name="id" value="<?= $menu['id'] ?>">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="nama_menu" class="form-control" required
                                   value="<?= htmlspecialchars($old['nama_menu']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_menu" class="form-select" required>
                                <?php foreach (['Makanan','Minuman','Snack'] as $kat): ?>
                                <option value="<?= $kat ?>" <?= ($old['kategori_menu']===$kat)?'selected':'' ?>><?= $kat ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status_menu" class="form-select" required>
                                <option value="Tersedia" <?= ($old['status_menu']==='Tersedia')?'selected':'' ?>>Tersedia</option>
                                <option value="Habis"    <?= ($old['status_menu']==='Habis')?'selected':''    ?>>Habis</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control" min="0" required
                                   value="<?= htmlspecialchars($old['harga']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control" min="0" required
                                   value="<?= htmlspecialchars($old['stok']) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tanggal Input <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_input" class="form-control" required
                                   value="<?= htmlspecialchars($old['tanggal_input']) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($old['deskripsi'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                        <a href="/manajemen_menu_kantin/menu/index.php" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../config/layout_end.php'; ?>
