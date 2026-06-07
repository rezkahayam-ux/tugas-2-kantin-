<?php
require_once __DIR__ . '/../config/auth_check.php';
require_once __DIR__ . '/../config/database.php';

$pageTitle  = 'Tambah Menu';
$activeMenu = 'tambah';
require_once __DIR__ . '/../config/layout_start.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card">
            <div class="card-header">
                <span class="fw-bold">Form Tambah Menu Baru</span>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="/manajemen_menu_kantin/menu/store.php">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="nama_menu" class="form-control"
                                   placeholder="cth: Nasi Goreng Spesial" required
                                   value="<?= htmlspecialchars($_SESSION['form_old']['nama_menu'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_menu" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach (['Makanan','Minuman','Snack'] as $kat): ?>
                                <option value="<?= $kat ?>"
                                    <?= (($_SESSION['form_old']['kategori_menu'] ?? '')===$kat)?'selected':'' ?>>
                                    <?= $kat ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status_menu" class="form-select" required>
                                <option value="Tersedia" <?= (($_SESSION['form_old']['status_menu'] ?? 'Tersedia')==='Tersedia')?'selected':'' ?>>Tersedia</option>
                                <option value="Habis"    <?= (($_SESSION['form_old']['status_menu'] ?? '')==='Habis')?'selected':''    ?>>Habis</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control"
                                   placeholder="cth: 12000" min="0" required
                                   value="<?= htmlspecialchars($_SESSION['form_old']['harga'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control"
                                   placeholder="cth: 20" min="0" required
                                   value="<?= htmlspecialchars($_SESSION['form_old']['stok'] ?? '') ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tanggal Input <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_input" class="form-control" required
                                   value="<?= htmlspecialchars($_SESSION['form_old']['tanggal_input'] ?? date('Y-m-d')) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"
                                      placeholder="Deskripsi singkat menu (opsional)"><?= htmlspecialchars($_SESSION['form_old']['deskripsi'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <?php
                    $err = $_SESSION['form_error'] ?? '';
                    unset($_SESSION['form_old'], $_SESSION['form_error']);
                    if ($err): ?>
                        <div class="alert alert-danger mt-3 mb-0">
                            <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($err) ?>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Menu
                        </button>
                        <a href="/manajemen_menu_kantin/menu/index.php" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../config/layout_end.php'; ?>
