-- 1. TẠO DATABASE
CREATE DATABASE IF NOT EXISTS banve_bongda 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE banve_bongda;

-- 2. XÓA BẢNG CŨ (Theo thứ tự an toàn chống lỗi khóa ngoại)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS tbl_donhang;
DROP TABLE IF EXISTS tbl_thanhtoan;
DROP TABLE IF EXISTS tbl_ve;
DROP TABLE IF EXISTS tbl_hangve;
DROP TABLE IF EXISTS tbl_trandau;
DROP TABLE IF EXISTS tbl_doibong;
DROP TABLE IF EXISTS tbl_giaidau;
DROP TABLE IF EXISTS tbl_nguoidung;
SET FOREIGN_KEY_CHECKS = 1;

-- =========================
-- 3. BẢNG NGƯỜI DÙNG
-- =========================
CREATE TABLE tbl_nguoidung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_dang_nhap VARCHAR(50) NOT NULL UNIQUE,
    mat_khau VARCHAR(255) NOT NULL,
    ho_ten VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    sdt VARCHAR(15) UNIQUE,
    so_du DECIMAL(15,2) DEFAULT 0 CHECK (so_du >= 0),
    vai_tro ENUM('admin', 'khach_hang') DEFAULT 'khach_hang',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================
-- 4. BẢNG GIẢI ĐẤU
-- =========================
CREATE TABLE tbl_giaidau (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_giai VARCHAR(100) NOT NULL,
    quoc_gia VARCHAR(100),
    logo VARCHAR(255)
) ENGINE=InnoDB;

-- =========================
-- 5. BẢNG ĐỘI BÓNG (Đã thêm id_giaidau)
-- =========================
CREATE TABLE tbl_doibong (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_giaidau INT,
    ten_doi VARCHAR(100) NOT NULL,
    quoc_gia VARCHAR(100),
    logo VARCHAR(255),
    FOREIGN KEY (id_giaidau) REFERENCES tbl_giaidau(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =========================
-- 6. BẢNG TRẬN ĐẤU
-- =========================
CREATE TABLE tbl_trandau (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_giaidau INT NOT NULL,
    id_doi_nha INT NOT NULL,
    id_doi_khach INT NOT NULL,
    thoi_gian DATETIME NOT NULL,
    san_van_dong VARCHAR(150),
    trang_thai ENUM('sap_dien_ra', 'dang_da', 'da_ket_thuc') DEFAULT 'sap_dien_ra',
    FOREIGN KEY (id_giaidau) REFERENCES tbl_giaidau(id) ON DELETE CASCADE,
    FOREIGN KEY (id_doi_nha) REFERENCES tbl_doibong(id),
    FOREIGN KEY (id_doi_khach) REFERENCES tbl_doibong(id)
) ENGINE=InnoDB;

-- =========================
-- 7. BẢNG HẠNG VÉ
-- =========================
CREATE TABLE tbl_hangve (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_hang VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- =========================
-- 8. BẢNG VÉ
-- =========================
CREATE TABLE tbl_ve (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_trandau INT NOT NULL,
    id_hangve INT NOT NULL,
    gia_tien DECIMAL(15,2) NOT NULL CHECK (gia_tien >= 0),
    so_luong_con INT NOT NULL CHECK (so_luong_con >= 0),
    FOREIGN KEY (id_trandau) REFERENCES tbl_trandau(id) ON DELETE CASCADE,
    FOREIGN KEY (id_hangve) REFERENCES tbl_hangve(id)
) ENGINE=InnoDB;

-- =========================
-- 9. BẢNG THANH TOÁN
-- =========================
CREATE TABLE tbl_thanhtoan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_nguoidung INT NOT NULL,
    so_tien DECIMAL(15,2) NOT NULL CHECK (so_tien > 0),
    trang_thai ENUM('thanh_cong', 'that_bai') DEFAULT 'thanh_cong',
    FOREIGN KEY (id_nguoidung) REFERENCES tbl_nguoidung(id)
) ENGINE=InnoDB;

-- =========================
-- 10. BẢNG ĐƠN HÀNG
-- =========================
CREATE TABLE tbl_donhang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_nguoidung INT,
    id_ve INT,
    so_luong INT,
    tong_tien DECIMAL(15,2),
    ten_trandau VARCHAR(255),
    ten_hangve VARCHAR(50),
    ngay_dat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_nguoidung) REFERENCES tbl_nguoidung(id),
    FOREIGN KEY (id_ve) REFERENCES tbl_ve(id)
) ENGINE=InnoDB;