-- =============================================
-- DATABASE: kantin_sekolah
-- Sistem Manajemen Menu Kantin Sekolah
-- =============================================

CREATE DATABASE IF NOT EXISTS kantin_sekolah
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE kantin_sekolah;

-- ---------------------------------------------
-- Tabel users
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------
-- Tabel menu
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS menu (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    nama_menu     VARCHAR(100) NOT NULL,
    kategori_menu VARCHAR(50)  NOT NULL,
    harga         INT NOT NULL,
    stok          INT NOT NULL,
    status_menu   VARCHAR(50)  NOT NULL,
    deskripsi     TEXT,
    tanggal_input DATE NOT NULL,
    created_at    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------
-- Data akun testing
-- Password: password123  (hashed dengan password_hash)
-- ---------------------------------------------
INSERT INTO users (name, email, password) VALUES
('Warung Bu Siti',  'siti@kantin.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Kantin Pak Budi', 'budi@kantin.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Data menu contoh untuk Bu Siti (user_id=1)
INSERT INTO menu (user_id, nama_menu, kategori_menu, harga, stok, status_menu, deskripsi, tanggal_input) VALUES
(1, 'Nasi Goreng Spesial', 'Makanan', 12000, 20, 'Tersedia', 'Nasi goreng dengan telur, ayam, dan sayuran segar', CURDATE()),
(1, 'Mie Ayam', 'Makanan', 10000, 15, 'Tersedia', 'Mie dengan topping ayam dan bakso', CURDATE()),
(1, 'Es Teh Manis', 'Minuman', 3000, 50, 'Tersedia', 'Teh manis dingin yang menyegarkan', CURDATE()),
(1, 'Risol Mayo', 'Snack', 4000, 30, 'Tersedia', 'Risol isi sayuran dengan saus mayo', CURDATE()),
(1, 'Jus Jeruk', 'Minuman', 7000, 0, 'Habis', 'Jus jeruk segar tanpa pengawet', CURDATE());

-- Data menu contoh untuk Pak Budi (user_id=2)
INSERT INTO menu (user_id, nama_menu, kategori_menu, harga, stok, status_menu, deskripsi, tanggal_input) VALUES
(2, 'Ayam Geprek', 'Makanan', 15000, 25, 'Tersedia', 'Ayam goreng geprek dengan sambal level', CURDATE()),
(2, 'Bakso Kuah', 'Makanan', 12000, 20, 'Tersedia', 'Bakso sapi dengan kuah bening gurih', CURDATE()),
(2, 'Air Mineral', 'Minuman', 2000, 100, 'Tersedia', 'Air mineral botol 600ml', CURDATE()),
(2, 'Onde-onde', 'Snack', 2000, 40, 'Tersedia', 'Onde-onde isi kacang hijau', CURDATE());
