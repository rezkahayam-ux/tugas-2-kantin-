<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /manajemen_menu_kantin/dashboard.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!$name || !$email || !$password || !$confirm) {
        $error = 'Semua kolom wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $confirm) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        // Cek duplikat email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email sudah terdaftar, gunakan email lain.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash]);
            $success = 'Akun berhasil dibuat! Silakan login.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Kantin Sekolah</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/manajemen_menu_kantin/assets/css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">🍱</div>
        <h2 class="auth-title">Daftar Akun Penjual</h2>
        <p class="auth-sub">Buat akun baru untuk mulai mengelola menu kantinmu</p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Nama Warung / Penjual</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-shop text-muted"></i></span>
                    <input type="text" name="name" class="form-control border-start-0 ps-0"
                           placeholder="cth: Warung Bu Siti"
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0"
                           placeholder="email@contoh.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 ps-0"
                           placeholder="Minimal 6 karakter" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                    <input type="password" name="confirm_password" class="form-control border-start-0 ps-0"
                           placeholder="Ulangi password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="bi bi-person-plus me-1"></i> Buat Akun
            </button>
        </form>

        <p class="text-center mt-3 mb-0" style="font-size:.88rem">
            Sudah punya akun?
            <a href="/manajemen_menu_kantin/auth/login.php" class="fw-bold" style="color:var(--primary)">Masuk di sini</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
