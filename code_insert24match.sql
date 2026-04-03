-- 1. Đảm bảo bảng trống trước khi nạp (tùy chọn)
-- DELETE FROM tbl_trandau;
-- ALTER TABLE tbl_trandau AUTO_INCREMENT = 1;

-- 2. Chèn 24 trận đấu vòng bảng tiêu biểu World Cup 2026
INSERT INTO tbl_trandau (bang_dau, doi_nha, doi_khach, thoi_gian, san_van_dong, trang_thai) VALUES
-- Bảng A (Trận khai mạc)
('A', 'Mexico', 'USA', '2026-06-11 20:00:00', 'Estadio Azteca (Mexico City)', 'sap_dien_ra'),
('A', 'Canada', 'Nigeria', '2026-06-12 18:00:00', 'BMO Field (Toronto)', 'sap_dien_ra'),

-- Bảng B
('B', 'Argentina', 'South Korea', '2026-06-13 15:00:00', 'MetLife Stadium (New York)', 'sap_dien_ra'),
('B', 'England', 'Ecuador', '2026-06-13 21:00:00', 'Lincoln Financial Field (Philadelphia)', 'sap_dien_ra'),

-- Bảng C
('C', 'Brazil', 'Japan', '2026-06-14 17:00:00', 'SoFi Stadium (Los Angeles)', 'sap_dien_ra'),
('C', 'France', 'Morocco', '2026-06-14 20:00:00', 'Levi Stadium (San Francisco)', 'sap_dien_ra'),

-- Bảng D
('D', 'Spain', 'Australia', '2026-06-15 16:00:00', 'Hard Rock Stadium (Miami)', 'sap_dien_ra'),
('D', 'Germany', 'Saudi Arabia', '2026-06-15 19:00:00', 'Mercedes-Benz Stadium (Atlanta)', 'sap_dien_ra'),

-- Bảng E
('E', 'Portugal', 'Netherlands', '2026-06-16 14:00:00', 'NRG Stadium (Houston)', 'sap_dien_ra'),
('E', 'Vietnam', 'Italy', '2026-06-16 18:00:00', 'Lumen Field (Seattle)', 'sap_dien_ra'), -- Thêm VN cho máu bro nhé!

-- Bảng F
('F', 'Belgium', 'Uruguay', '2026-06-17 13:00:00', 'Arrowhead Stadium (Kansas City)', 'sap_dien_ra'),
('F', 'Croatia', 'Senegal', '2026-06-17 17:00:00', 'Gillette Stadium (Boston)', 'sap_dien_ra'),

-- Lượt trận thứ 2 tiêu biểu
('A', 'Mexico', 'Nigeria', '2026-06-18 20:00:00', 'Estadio Guadalajara', 'sap_dien_ra'),
('A', 'USA', 'Canada', '2026-06-19 21:00:00', 'BC Place (Vancouver)', 'sap_dien_ra'),
('B', 'Argentina', 'England', '2026-06-20 18:00:00', 'AT&T Stadium (Dallas)', 'sap_dien_ra'),
('C', 'Brazil', 'France', '2026-06-21 20:00:00', 'SoFi Stadium (Los Angeles)', 'sap_dien_ra'),
('D', 'Spain', 'Germany', '2026-06-22 19:00:00', 'MetLife Stadium (New York)', 'sap_dien_ra'),
('E', 'Vietnam', 'Portugal', '2026-06-23 15:00:00', 'Hard Rock Stadium (Miami)', 'sap_dien_ra'),

-- Lượt trận cuối vòng bảng
('A', 'Mexico', 'Canada', '2026-06-24 18:00:00', 'Estadio Azteca (Mexico City)', 'sap_dien_ra'),
('A', 'USA', 'Nigeria', '2026-06-24 18:00:00', 'SoFi Stadium (Los Angeles)', 'sap_dien_ra'),
('B', 'Argentina', 'Ecuador', '2026-06-25 21:00:00', 'MetLife Stadium (New York)', 'sap_dien_ra'),
('B', 'England', 'South Korea', '2026-06-25 21:00:00', 'Lincoln Financial Field (Philadelphia)', 'sap_dien_ra'),
('C', 'Brazil', 'Morocco', '2026-06-26 17:00:00', 'NRG Stadium (Houston)', 'sap_dien_ra'),
('C', 'France', 'Japan', '2026-06-26 17:00:00', 'Levi Stadium (San Francisco)', 'sap_dien_ra');