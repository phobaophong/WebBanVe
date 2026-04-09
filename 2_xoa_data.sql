-- 1. Tắt kiểm tra khóa ngoại
SET FOREIGN_KEY_CHECKS = 0;

-- 2. Dùng DELETE để xóa sạch dữ liệu (lệnh này không bị chặn)
DELETE FROM tbl_donhang;
DELETE FROM tbl_thanhtoan;
DELETE FROM tbl_ve;
DELETE FROM tbl_trandau;
DELETE FROM tbl_doibong;
DELETE FROM tbl_giaidau;
DELETE FROM tbl_hangve;
DELETE FROM tbl_nguoidung;

-- 3. Ép hệ thống đếm lại ID (AUTO_INCREMENT) từ số 1
ALTER TABLE tbl_donhang AUTO_INCREMENT = 1;
ALTER TABLE tbl_thanhtoan AUTO_INCREMENT = 1;
ALTER TABLE tbl_ve AUTO_INCREMENT = 1;
ALTER TABLE tbl_trandau AUTO_INCREMENT = 1;
ALTER TABLE tbl_doibong AUTO_INCREMENT = 1;
ALTER TABLE tbl_giaidau AUTO_INCREMENT = 1;
ALTER TABLE tbl_hangve AUTO_INCREMENT = 1;
ALTER TABLE tbl_nguoidung AUTO_INCREMENT = 1;

-- 4. Bật lại khóa ngoại để bảo vệ Database
SET FOREIGN_KEY_CHECKS = 1;