<?php
$host     = 'localhost';
$db       = 'kantin_sekolah';
$user     = 'root';
$pass     = '';
$charset  = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('<div style="font-family:sans-serif;padding:2rem;color:red;">
        <h2>Koneksi Database Gagal</h2>
        <p>' . htmlspecialchars($e->getMessage()) . '</p>
        <p>Pastikan XAMPP/Laragon berjalan dan database <strong>kantin_sekolah</strong> sudah dibuat.</p>
    </div>');
}
