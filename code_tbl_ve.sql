-- Mở bán vé Category 3 (id_hangve = 3) - Giá 2.500.000đ - SL: 2000 vé/trận
INSERT INTO tbl_ve (id_trandau, id_hangve, gia_tien, so_luong_tong, so_luong_con, trang_thai)
SELECT id, 3, 2500000, 2000, 2000, 'con_ve' FROM tbl_trandau;

-- Mở bán vé Category 2 (id_hangve = 2) - Giá 5.000.000đ - SL: 1000 vé/trận
INSERT INTO tbl_ve (id_trandau, id_hangve, gia_tien, so_luong_tong, so_luong_con, trang_thai)
SELECT id, 2, 5000000, 1000, 1000, 'con_ve' FROM tbl_trandau;

-- Mở bán vé Category 1 (id_hangve = 1) - Giá 8.500.000đ - SL: 500 vé/trận
INSERT INTO tbl_ve (id_trandau, id_hangve, gia_tien, so_luong_tong, so_luong_con, trang_thai)
SELECT id, 1, 8500000, 500, 500, 'con_ve' FROM tbl_trandau;

-- Mở bán vé VIP (id_hangve = 4) - Giá 18.000.000đ - SL: 100 vé/trận
INSERT INTO tbl_ve (id_trandau, id_hangve, gia_tien, so_luong_tong, so_luong_con, trang_thai)
SELECT id, 4, 18000000, 100, 100, 'con_ve' FROM tbl_trandau;