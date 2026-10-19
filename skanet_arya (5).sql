-- =====================================================================
-- Database Sistem Penilaian Sekolah (skanet_arya)
-- Cara pakai: phpMyAdmin > tab Import > pilih file ini > Go.
-- Nama database harus "skanet_arya" (sesuai koneksi.php).
-- =====================================================================

CREATE DATABASE IF NOT EXISTS skanet_arya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE skanet_arya;

-- Akun login (password disimpan apa adanya karena login.php membandingkan teks biasa)
CREATE TABLE IF NOT EXISTS users (
    id       INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(50)  NOT NULL,
    password VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_users_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS siswa (
    nis    VARCHAR(20)  NOT NULL,
    nama   VARCHAR(100) NOT NULL,
    alamat TEXT         NOT NULL,
    PRIMARY KEY (nis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS guru (
    nip    VARCHAR(25)  NOT NULL,
    nama   VARCHAR(100) NOT NULL,
    alamat TEXT         NOT NULL,
    PRIMARY KEY (nip)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kolom "mapel" = nama mata pelajaran, "guru" = nama guru pengampu (teks bebas)
CREATE TABLE IF NOT EXISTS mapel (
    id_mapel INT UNSIGNED NOT NULL AUTO_INCREMENT,
    mapel    VARCHAR(100) NOT NULL,
    guru     VARCHAR(100) NOT NULL,
    PRIMARY KEY (id_mapel)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS nilai (
    id_nilai INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nis      VARCHAR(20)  NOT NULL,
    nip      VARCHAR(25)  NOT NULL,
    id_mapel INT UNSIGNED NOT NULL,
    nilai    DECIMAL(5,2) NOT NULL,
    PRIMARY KEY (id_nilai),
    KEY idx_nilai_nis (nis),
    KEY idx_nilai_nip (nip),
    KEY idx_nilai_mapel (id_mapel),
    CONSTRAINT fk_nilai_siswa FOREIGN KEY (nis)      REFERENCES siswa(nis)      ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_nilai_guru  FOREIGN KEY (nip)      REFERENCES guru(nip)       ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_nilai_mapel FOREIGN KEY (id_mapel) REFERENCES mapel(id_mapel) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Akun awal: admin / admin123 (ganti lewat phpMyAdmin kalau perlu)
INSERT INTO users (username, password) VALUES ('admin', 'admin123');
