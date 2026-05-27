-- ================================================
-- Database: pengelolaan_data_banking
-- Sistem Informasi Pengelolaan Data Nasabah Bank BTN
-- ================================================

CREATE DATABASE IF NOT EXISTS pengelolaan_data_banking 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pengelolaan_data_banking;

-- ================================================
-- roles
-- ================================================
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO roles (name, description) VALUES
('admin', 'Superadmin - mengelola seluruh sistem & user'),
('cs', 'Customer Service - input & update data nasabah'),
('backoffice', 'Back Office - verifikasi data, kelola rekening & kredit'),
('manager', 'Kepala Cabang / Manager - monitoring & laporan'),
('auditor', 'Auditor Internal - audit trail & laporan kepatuhan');

-- ================================================
-- users
-- ================================================
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nip VARCHAR(20) NULL,
    jabatan VARCHAR(100) NULL,
    no_telepon VARCHAR(20) NULL,
    foto VARCHAR(255) NULL,
    status ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ================================================
-- user_roles
-- ================================================
CREATE TABLE user_roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_role (user_id, role_id)
) ENGINE=InnoDB;

-- ================================================
-- nasabah
-- ================================================
CREATE TABLE nasabah (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    no_nasabah VARCHAR(20) NOT NULL UNIQUE,
    nik VARCHAR(16) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(255) NOT NULL,
    tempat_lahir VARCHAR(100) NULL,
    tanggal_lahir DATE NOT NULL,
    jenis_kelamin ENUM('L','P') NOT NULL,
    agama VARCHAR(20) NULL,
    status_perkawinan ENUM('lajang','menikah','cerai') DEFAULT 'lajang',
    pekerjaan VARCHAR(100) NULL,
    penghasilan_bulanan DECIMAL(15,2) NULL,
    no_telepon VARCHAR(20) NOT NULL,
    email VARCHAR(255) NULL,
    alamat TEXT NOT NULL,
    rt_rw VARCHAR(10) NULL,
    kelurahan VARCHAR(100) NULL,
    kecamatan VARCHAR(100) NULL,
    kota_kabupaten VARCHAR(100) NOT NULL,
    provinsi VARCHAR(100) NOT NULL,
    kode_pos VARCHAR(10) NULL,
    no_ktp_path VARCHAR(500) NULL,
    status ENUM('aktif','nonaktif','blacklist') NOT NULL DEFAULT 'aktif',
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_nik (nik),
    INDEX idx_nama (nama_lengkap),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ================================================
-- rekening
-- ================================================
CREATE TABLE rekening (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    no_rekening VARCHAR(20) NOT NULL UNIQUE,
    nasabah_id BIGINT UNSIGNED NOT NULL,
    jenis_rekening ENUM('tabungan','giro','deposito','batara') NOT NULL DEFAULT 'tabungan',
    nama_produk VARCHAR(100) NULL,
    saldo DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    saldo_minimum DECIMAL(15,2) DEFAULT 50000.00,
    mata_uang VARCHAR(5) NOT NULL DEFAULT 'IDR',
    tanggal_buka DATE NOT NULL,
    tanggal_tutup DATE NULL,
    status ENUM('aktif','beku','tutup') NOT NULL DEFAULT 'aktif',
    keterangan TEXT NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (nasabah_id) REFERENCES nasabah(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_nasabah (nasabah_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ================================================
-- transaksi
-- ================================================
CREATE TABLE transaksi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    no_transaksi VARCHAR(30) NOT NULL UNIQUE,
    rekening_id BIGINT UNSIGNED NOT NULL,
    rekening_tujuan_id BIGINT UNSIGNED NULL,
    jenis_transaksi ENUM('setoran','penarikan','transfer_masuk','transfer_keluar') NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL,
    saldo_sebelum DECIMAL(15,2) NOT NULL,
    saldo_sesudah DECIMAL(15,2) NOT NULL,
    keterangan VARCHAR(255) NULL,
    tanggal_transaksi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    teller_id BIGINT UNSIGNED NOT NULL,
    status ENUM('sukses','batal','pending') NOT NULL DEFAULT 'sukses',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rekening_id) REFERENCES rekening(id) ON DELETE CASCADE,
    FOREIGN KEY (rekening_tujuan_id) REFERENCES rekening(id) ON DELETE SET NULL,
    FOREIGN KEY (teller_id) REFERENCES users(id),
    INDEX idx_rekening (rekening_id),
    INDEX idx_tanggal (tanggal_transaksi),
    INDEX idx_jenis (jenis_transaksi)
) ENGINE=InnoDB;

-- ================================================
-- kredit
-- ================================================
CREATE TABLE kredit (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    no_kredit VARCHAR(25) NOT NULL UNIQUE,
    nasabah_id BIGINT UNSIGNED NOT NULL,
    jenis_kredit VARCHAR(100) NOT NULL,
    plafon DECIMAL(15,2) NOT NULL,
    outstanding DECIMAL(15,2) NOT NULL,
    suku_bunga DECIMAL(5,2) NOT NULL,
    tenor_bulan INT NOT NULL,
    angsuran_per_bulan DECIMAL(15,2) NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_jatuh_tempo DATE NOT NULL,
    tujuan_kredit TEXT NULL,
    jaminan VARCHAR(255) NULL,
    kolektibilitas ENUM('lancar','perhatian_khusus','kurang_lancar','diragukan','macet') NOT NULL DEFAULT 'lancar',
    status ENUM('aktif','lunas','hapus_buku','macet') NOT NULL DEFAULT 'aktif',
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (nasabah_id) REFERENCES nasabah(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_nasabah (nasabah_id),
    INDEX idx_status (status),
    INDEX idx_kolektibilitas (kolektibilitas)
) ENGINE=InnoDB;

-- ================================================
-- angsuran
-- ================================================
CREATE TABLE angsuran (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kredit_id BIGINT UNSIGNED NOT NULL,
    periode_ke INT NOT NULL,
    tanggal_jatuh_tempo DATE NOT NULL,
    tanggal_bayar DATE NULL,
    pokok DECIMAL(15,2) NOT NULL,
    bunga DECIMAL(15,2) NOT NULL,
    total_angsuran DECIMAL(15,2) NOT NULL,
    denda DECIMAL(15,2) DEFAULT 0.00,
    status ENUM('belum_bayar','lunas','terlambat') NOT NULL DEFAULT 'belum_bayar',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kredit_id) REFERENCES kredit(id) ON DELETE CASCADE,
    INDEX idx_kredit (kredit_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ================================================
-- activity_logs
-- ================================================
CREATE TABLE activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    model_type VARCHAR(100) NULL,
    model_id BIGINT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- ================================================
-- SEED DATA
-- ================================================

-- Admin user (password: admin123)
INSERT INTO users (nama, username, email, password, nip, jabatan, status) VALUES
('Administrator', 'admin', 'admin@btn-pekanbaru.co.id', '$2y$12$nXjrrGOuzyUv5rDNVgkPiu4RXLM5oqKJENwHDqZcMzKU1rrVyGUVS', '198501012010011001', 'Administrator Sistem', 'aktif'),
('Budi Santoso', 'cs_budi', 'budi@btn-pekanbaru.co.id', '$2y$12$nXjrrGOuzyUv5rDNVgkPiu4RXLM5oqKJENwHDqZcMzKU1rrVyGUVS', '199002152015011002', 'Customer Service', 'aktif'),
('Siti Rahayu', 'bo_siti', 'siti@btn-pekanbaru.co.id', '$2y$12$nXjrrGOuzyUv5rDNVgkPiu4RXLM5oqKJENwHDqZcMzKU1rrVyGUVS', '198803202012012003', 'Back Office', 'aktif'),
('Ir. Ahmad Fauzi', 'mgr_ahmad', 'ahmad@btn-pekanbaru.co.id', '$2y$12$nXjrrGOuzyUv5rDNVgkPiu4RXLM5oqKJENwHDqZcMzKU1rrVyGUVS', '197506102000011004', 'Kepala Cabang', 'aktif'),
('Dewi Lestari', 'aud_dewi', 'dewi@btn-pekanbaru.co.id', '$2y$12$nXjrrGOuzyUv5rDNVgkPiu4RXLM5oqKJENwHDqZcMzKU1rrVyGUVS', '199105252018012005', 'Auditor Internal', 'aktif');

-- Assign roles
INSERT INTO user_roles (user_id, role_id) VALUES
(1, 1), -- admin
(2, 2), -- cs
(3, 3), -- backoffice
(4, 4), -- manager
(5, 5); -- auditor

-- Sample nasabah
INSERT INTO nasabah (no_nasabah, nik, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, status_perkawinan, pekerjaan, penghasilan_bulanan, no_telepon, email, alamat, kota_kabupaten, provinsi, status, created_by) VALUES
('NAS-2025-0001', '1471012345670001', 'Rudi Hartono', 'Pekanbaru', '1985-03-15', 'L', 'Islam', 'menikah', 'Wiraswasta', 15000000.00, '08127654321', 'rudi@gmail.com', 'Jl. Sudirman No. 45', 'Pekanbaru', 'Riau', 'aktif', 1),
('NAS-2025-0002', '1471012345670002', 'Maria Susanti', 'Padang', '1990-07-22', 'P', 'Kristen', 'lajang', 'PNS', 8500000.00, '08521234567', 'maria@gmail.com', 'Jl. Diponegoro No. 12', 'Pekanbaru', 'Riau', 'aktif', 1),
('NAS-2025-0003', '1471012345670003', 'Ahmad Wijaya', 'Dumai', '1978-11-30', 'L', 'Islam', 'menikah', 'Dokter', 25000000.00, '08131234567', 'ahmad.w@gmail.com', 'Jl. Harapan Raya No. 88', 'Pekanbaru', 'Riau', 'aktif', 2),
('NAS-2025-0004', '1471012345670004', 'Fitri Handayani', 'Pekanbaru', '1995-01-10', 'P', 'Islam', 'menikah', 'Guru', 6000000.00, '08561234567', 'fitri.h@gmail.com', 'Jl. Soekarno Hatta No. 25', 'Pekanbaru', 'Riau', 'aktif', 2),
('NAS-2025-0005', '1471012345670005', 'Joko Prasetyo', 'Bengkalis', '1982-06-18', 'L', 'Islam', 'menikah', 'Pengusaha', 50000000.00, '08117654321', 'joko.p@gmail.com', 'Jl. Riau No. 100', 'Pekanbaru', 'Riau', 'aktif', 1);

-- Sample rekening
INSERT INTO rekening (no_rekening, nasabah_id, jenis_rekening, nama_produk, saldo, tanggal_buka, status, created_by) VALUES
('00123-01-50-000001', 1, 'tabungan', 'BTN Batara', 25000000.00, '2023-01-15', 'aktif', 1),
('00123-01-50-000002', 2, 'tabungan', 'BTN Prima', 12000000.00, '2023-03-20', 'aktif', 1),
('00123-01-50-000003', 3, 'giro', 'Giro BTN', 150000000.00, '2022-08-10', 'aktif', 1),
('00123-01-50-000004', 4, 'tabungan', 'BTN Batara', 5000000.00, '2024-01-05', 'aktif', 2),
('00123-01-50-000005', 5, 'deposito', 'Deposito BTN', 500000000.00, '2023-06-01', 'aktif', 1),
('00123-01-50-000006', 1, 'giro', 'Giro BTN', 75000000.00, '2024-02-10', 'aktif', 1);

-- Sample transaksi
INSERT INTO transaksi (no_transaksi, rekening_id, jenis_transaksi, jumlah, saldo_sebelum, saldo_sesudah, keterangan, tanggal_transaksi, teller_id, status) VALUES
('TRX-20250101-0001', 1, 'setoran', 5000000.00, 20000000.00, 25000000.00, 'Setoran tunai', '2025-01-15 09:30:00', 2, 'sukses'),
('TRX-20250101-0002', 2, 'penarikan', 2000000.00, 14000000.00, 12000000.00, 'Penarikan tunai', '2025-01-16 10:15:00', 2, 'sukses'),
('TRX-20250101-0003', 3, 'setoran', 50000000.00, 100000000.00, 150000000.00, 'Setoran bisnis', '2025-01-17 14:00:00', 2, 'sukses'),
('TRX-20250101-0004', 1, 'transfer_keluar', 1000000.00, 25000000.00, 24000000.00, 'Transfer ke rekening Maria', '2025-01-18 11:00:00', 2, 'sukses');

-- Sample kredit
INSERT INTO kredit (no_kredit, nasabah_id, jenis_kredit, plafon, outstanding, suku_bunga, tenor_bulan, angsuran_per_bulan, tanggal_mulai, tanggal_jatuh_tempo, tujuan_kredit, jaminan, kolektibilitas, status, created_by) VALUES
('KPR-2024-0001', 1, 'KPR', 500000000.00, 450000000.00, 7.50, 240, 4025000.00, '2024-01-15', '2044-01-15', 'Pembelian rumah tinggal', 'SHM No. 1234 - Rumah Jl. Sudirman', 'lancar', 'aktif', 3),
('KKB-2024-0001', 3, 'KKB', 200000000.00, 150000000.00, 8.00, 60, 4055000.00, '2024-03-01', '2029-03-01', 'Pembelian kendaraan roda 4', 'BPKB Toyota Avanza 2024', 'lancar', 'aktif', 3),
('KTA-2024-0001', 5, 'KTA', 100000000.00, 80000000.00, 10.00, 36, 3226000.00, '2024-06-01', '2027-06-01', 'Modal usaha', NULL, 'lancar', 'aktif', 3);
