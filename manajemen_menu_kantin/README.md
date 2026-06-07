# Sistem Manajemen Menu Kantin Sekolah
> LKS Kabupaten Subang — Bidang Web Teknologi

## Cara Instalasi

### 1. Siapkan Server
- Pastikan **XAMPP** atau **Laragon** sudah terinstal dan berjalan
- PHP 7.4+ dan MySQL/MariaDB harus aktif

### 2. Letakkan Project
Salin folder `manajemen_menu_kantin` ke:
- **XAMPP**: `C:\xampp\htdocs\`
- **Laragon**: `C:\laragon\www\`

### 3. Buat Database
1. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`)
2. Klik **Import**
3. Pilih file `kantin_sekolah.sql` dari dalam folder project
4. Klik **Go / Kirim**

> Database `kantin_sekolah` beserta tabel dan data testing akan otomatis terbuat.

### 4. (Opsional) Sesuaikan Koneksi
Buka `config/database.php` dan sesuaikan jika perlu:
```php
$host = 'localhost';
$db   = 'kantin_sekolah';
$user = 'root';   // username MySQL kamu
$pass = '';       // password MySQL kamu
```

### 5. Jalankan Aplikasi
Buka browser dan akses:
```
http://localhost/manajemen_menu_kantin/
```

---

## Akun Login Testing

| Nama          | Email              | Password    |
|---------------|--------------------|-------------|
| Warung Bu Siti | siti@kantin.com   | password123 |
| Kantin Pak Budi| budi@kantin.com   | password123 |

---

## Struktur Folder

```
manajemen_menu_kantin/
├── config/
│   ├── database.php        ← Koneksi database
│   ├── auth_check.php      ← Guard login session
│   ├── layout_start.php    ← Template sidebar/topbar (buka)
│   └── layout_end.php      ← Template footer (tutup)
├── auth/
│   ├── login.php           ← Halaman login
│   ├── register.php        ← Halaman register
│   └── logout.php          ← Proses logout
├── menu/
│   ├── index.php           ← Daftar menu (+ filter)
│   ├── create.php          ← Form tambah menu
│   ├── store.php           ← Proses simpan menu baru
│   ├── edit.php            ← Form edit menu
│   ├── update.php          ← Proses update menu
│   └── delete.php          ← Proses hapus menu
├── laporan/
│   └── menu_print.php      ← Laporan + window.print()
├── assets/
│   └── css/
│       └── style.css       ← Custom stylesheet
├── dashboard.php           ← Halaman dashboard
├── index.php               ← Redirect ke login
└── kantin_sekolah.sql      ← File database
```

---

## Fitur yang Tersedia

| # | Fitur | Status |
|---|-------|--------|
| 1 | Desain database (users + menu + relasi) | ✅ |
| 2 | Register akun baru (hash password) | ✅ |
| 3 | Login & Logout (session) | ✅ |
| 4 | Proteksi halaman (auth_check.php) | ✅ |
| 5 | Dashboard (statistik + 5 menu terbaru) | ✅ |
| 6 | CRUD menu lengkap | ✅ |
| 7 | Filter data berdasarkan user login | ✅ |
| 8 | Keamanan akses (user_id pada WHERE) | ✅ |
| 9 | Filter & pencarian menu | ✅ |
| 10 | Laporan + window.print() | ✅ |
| 11 | Tampilan responsif (Bootstrap 5) | ✅ |
| 12 | Konfirmasi hapus (modal) | ✅ |

---

## Teknologi yang Digunakan
- **Backend**: PHP Native (no framework)
- **Database**: MySQL / MariaDB
- **Frontend**: Bootstrap 5.3, Bootstrap Icons
- **Font**: Plus Jakarta Sans, Nunito (Google Fonts)
- **Laporan**: window.print() (CSS print media)

---

## Keamanan Data
- Setiap query tampil menggunakan `WHERE user_id = $_SESSION['user_id']`
- Edit, update, hapus menggunakan `WHERE id = ? AND user_id = ?`
- Password disimpan dengan `password_hash()` (bcrypt)
- Semua input di-escape dengan `htmlspecialchars()`
- Query menggunakan PDO Prepared Statements (anti SQL injection)
