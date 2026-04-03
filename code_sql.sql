-- 1. Tạo database
CREATE DATABASE IF NOT EXISTS banve_worldcup 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE banve_worldcup;

-- 2. Xóa bảng (đúng thứ tự tránh lỗi FK)
DROP TABLE IF EXISTS tbl_donhang;
DROP TABLE IF EXISTS tbl_thanhtoan;
DROP TABLE IF EXISTS tbl_ve;
DROP TABLE IF EXISTS tbl_trandau;
DROP TABLE IF EXISTS tbl_hangve;
DROP TABLE IF EXISTS tbl_nguoidung;

-- =========================
-- 3. Bảng người dùng
-- =========================
CREATE TABLE tbl_nguoidung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_dang_nhap VARCHAR(50) NOT NULL UNIQUE,
    mat_khau VARCHAR(255) NOT NULL,
    ho_ten VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    sdt VARCHAR(15) UNIQUE,
    ten_ngan_hang VARCHAR(100),
    ma_the VARCHAR(20),
    so_du DECIMAL(15,2) DEFAULT 0.00 CHECK (so_du >= 0),
    vai_tro ENUM('admin', 'khach_hang') DEFAULT 'khach_hang',
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================
-- 4. Bảng trận đấu
-- =========================
CREATE TABLE tbl_trandau (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bang_dau CHAR(1) NOT NULL,
    doi_nha VARCHAR(100) NOT NULL,
    doi_khach VARCHAR(100) NOT NULL,
    thoi_gian DATETIME NOT NULL,
    san_van_dong VARCHAR(150),
    trang_thai ENUM('sap_dien_ra', 'dang_da', 'da_ket_thuc') DEFAULT 'sap_dien_ra',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================
-- 5. Bảng hạng vé (linh hoạt hơn ENUM)
-- =========================
CREATE TABLE tbl_hangve (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_hang VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- =========================
-- 6. Bảng vé
-- =========================
CREATE TABLE tbl_ve (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_trandau INT NOT NULL,
    id_hangve INT NOT NULL,
    gia_tien DECIMAL(15,2) NOT NULL CHECK (gia_tien >= 0),
    so_luong_tong INT NOT NULL CHECK (so_luong_tong >= 0),
    so_luong_con INT NOT NULL CHECK (so_luong_con >= 0),
    trang_thai ENUM('con_ve', 'het_ve', 'ngung_ban') DEFAULT 'con_ve',

    FOREIGN KEY (id_trandau) REFERENCES tbl_trandau(id) ON DELETE CASCADE,
    FOREIGN KEY (id_hangve) REFERENCES tbl_hangve(id) ON DELETE CASCADE,

    INDEX idx_trandau (id_trandau)
) ENGINE=InnoDB;

-- =========================
-- 7. Bảng thanh toán (lịch sử nạp tiền)
-- =========================
CREATE TABLE tbl_thanhtoan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_nguoidung INT NOT NULL,
    so_tien DECIMAL(15,2) NOT NULL CHECK (so_tien > 0),
    phuong_thuc VARCHAR(50),
    trang_thai ENUM('thanh_cong', 'that_bai') DEFAULT 'thanh_cong',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_nguoidung) REFERENCES tbl_nguoidung(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- 8. Bảng đơn hàng (có snapshot)
-- =========================
CREATE TABLE tbl_donhang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_nguoidung INT NOT NULL,
    id_ve INT NOT NULL,

    so_luong_mua INT NOT NULL CHECK (so_luong_mua > 0),
    tong_tien DECIMAL(15,2) NOT NULL,

    -- snapshot dữ liệu
    ten_trandau VARCHAR(255),
    ten_hangve VARCHAR(50),
    gia_ve DECIMAL(15,2),

    ngay_dat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    trang_thai ENUM('thanh_cong', 'da_huy') DEFAULT 'thanh_cong',

    FOREIGN KEY (id_nguoidung) REFERENCES tbl_nguoidung(id) ON DELETE CASCADE,
    FOREIGN KEY (id_ve) REFERENCES tbl_ve(id) ON DELETE CASCADE,

    INDEX idx_user (id_nguoidung)
) ENGINE=InnoDB;

-- =========================
-- 9. DỮ LIỆU MẪU
-- =========================

-- Admin
INSERT INTO tbl_nguoidung (ten_dang_nhap, mat_khau, ho_ten, vai_tro) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản trị viên', 'admin');

-- Hạng vé
INSERT INTO tbl_hangve (ten_hang) VALUES 
('Category 1'),
('Category 2'),
('Category 3'),
('VIP');
