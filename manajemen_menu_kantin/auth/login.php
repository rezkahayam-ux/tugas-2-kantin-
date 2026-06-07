<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /manajemen_menu_kantin/dashboard.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$error = '';

// Pesan dari redirect
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'login_required') $error = 'Silakan login terlebih dahulu.';
    if ($_GET['msg'] === 'logged_out')    $error = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Email dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: /manajemen_menu_kantin/dashboard.php');
            exit;
        } else {
            $error = 'Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Kantin Sekolah</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/manajemen_menu_kantin/assets/css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">🍱</div>
        <h2 class="auth-title">Selamat Datang!</h2>
        <p class="auth-sub">Masuk untuk mengelola menu kantinmu</p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out'): ?>
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>Kamu berhasil keluar.</div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0"
                           placeholder="email@contoh.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 ps-0"
                           placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
            </button>
        </form>

        <hr class="my-3">
        <p class="text-center mb-0" style="font-size:.82rem;color:#64748b">
            <strong>Akun Testing:</strong><br>
            siti@kantin.com / password123<br>
            budi@kantin.com / password123
        </p>
        <p class="text-center mt-3 mb-0" style="font-size:.88rem">
            Belum punya akun?
            <a href="/manajemen_menu_kantin/auth/register.php" class="fw-bold" style="color:var(--primary)">Daftar sekarang</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
