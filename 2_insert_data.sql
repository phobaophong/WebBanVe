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

INSERT INTO tbl_giaidau (ten_giai) VALUES ('Ngoại hạng Anh (EPL)'); -- Có ID = 1
INSERT INTO tbl_giaidau (ten_giai) VALUES ('Serie A (Ý)');        -- ID = 2
INSERT INTO tbl_giaidau (ten_giai) VALUES ('Bundesliga (Đức)');   -- ID = 3
INSERT INTO tbl_giaidau (ten_giai) VALUES ('La Liga (Tây Ban Nha)'); -- ID = 4

-- CHÈN HẠNG VÉ
INSERT INTO tbl_hangve (id, ten_hang) VALUES 
(1, 'Khán đài A'), 
(2, 'Khán đài B'), 
(3, 'Khán đài C'), 
(4, 'VIP (Phòng kính)');
INSERT INTO tbl_doibong (id_giaidau, ten_doi, quoc_gia) VALUES 
-- Ngoại hạng Anh (ID Giải: 1)
(1, 'Arsenal', 'Anh'),                   -- ID 1
(1, 'Aston Villa', 'Anh'),               -- ID 2
(1, 'Bournemouth', 'Anh'),               -- ID 3
(1, 'Brentford', 'Anh'),                 -- ID 4
(1, 'Brighton', 'Anh'),                  -- ID 5
(1, 'Burnley', 'Anh'),                   -- ID 6
(1, 'Chelsea', 'Anh'),                   -- ID 7
(1, 'Crystal Palace', 'Anh'),            -- ID 8
(1, 'Everton', 'Anh'),                   -- ID 9
(1, 'Fulham', 'Anh'),                    -- ID 10
(1, 'Leeds United', 'Anh'),              -- ID 11
(1, 'Liverpool', 'Anh'),                 -- ID 12
(1, 'Manchester City', 'Anh'),           -- ID 13
(1, 'Manchester United', 'Anh'),         -- ID 14
(1, 'Newcastle', 'Anh'),                 -- ID 15
(1, 'Nottingham Forest', 'Anh'),         -- ID 16
(1, 'Sunderland', 'Anh'),                -- ID 17
(1, 'Tottenham', 'Anh'),                 -- ID 18
(1, 'West Ham', 'Anh'),                  -- ID 19
(1, 'Wolverhampton Wanderers', 'Anh'),   -- ID 20

-- Serie A (ID Giải: 2)
(2, 'AC Milan', 'Ý'),                  -- ID 21
(2, 'AS Roma', 'Ý'),                   -- ID 22
(2, 'Atalanta', 'Ý'),                  -- ID 23
(2, 'Bologna', 'Ý'),                   -- ID 24
(2, 'Cagliari', 'Ý'),                  -- ID 25
(2, 'Como', 'Ý'),                      -- ID 26
(2, 'Cremonese', 'Ý'),                 -- ID 27
(2, 'Fiorentina', 'Ý'),                -- ID 28
(2, 'Genoa', 'Ý'),                     -- ID 29
(2, 'Hellas Verona', 'Ý'),             -- ID 30
(2, 'Inter Milan', 'Ý'),               -- ID 31
(2, 'Juventus', 'Ý'),                  -- ID 32
(2, 'Lazio', 'Ý'),                     -- ID 33
(2, 'Lecce', 'Ý'),                     -- ID 34
(2, 'Napoli', 'Ý'),                    -- ID 35
(2, 'Parma', 'Ý'),                     -- ID 36
(2, 'Pisa', 'Ý'),                      -- ID 37
(2, 'Sassuolo', 'Ý'),                  -- ID 38
(2, 'Torino', 'Ý'),                    -- ID 39
(2, 'Udinese', 'Ý'),                   -- ID 40

-- Bundesliga (ID Giải: 3)
(3, 'FC Koln', 'Đức'),                 -- ID 41
(3, 'Bayer Leverkusen', 'Đức'),        -- ID 42
(3, 'Bayern Munich', 'Đức'),           -- ID 43
(3, 'Borussia Dortmund', 'Đức'),       -- ID 44
(3, 'Borussia Monchengladbach', 'Đức'),-- ID 45
(3, 'Eintracht Frankfurt', 'Đức'),     -- ID 46
(3, 'FC Augsburg', 'Đức'),             -- ID 47
(3, 'FC Heidenheim', 'Đức'),           -- ID 48
(3, 'St. Pauli', 'Đức'),               -- ID 49
(3, 'Hamburger SV', 'Đức'),            -- ID 50
(3, 'Mainz 05', 'Đức'),                -- ID 51
(3, 'RB Leipzig', 'Đức'),              -- ID 52
(3, 'SC Freiburg', 'Đức'),             -- ID 53
(3, 'TSG Hoffenheim', 'Đức'),          -- ID 54
(3, 'Union Berlin', 'Đức'),            -- ID 55
(3, 'VfB Stuttgart', 'Đức'),           -- ID 56
(3, 'VfL Wolfsburg', 'Đức'),           -- ID 57
(3, 'Werder Bremen', 'Đức'),           -- ID 58

-- La Liga (ID Giải: 4)
(4, 'Alavés', 'Tây Ban Nha'),          -- ID 59
(4, 'Athletic Bilbao', 'Tây Ban Nha'), -- ID 60
(4, 'Atlético Madrid', 'Tây Ban Nha'), -- ID 61
(4, 'Barcelona', 'Tây Ban Nha'),       -- ID 62
(4, 'Celta Vigo', 'Tây Ban Nha'),      -- ID 63
(4, 'Elche', 'Tây Ban Nha'),           -- ID 64 
(4, 'Espanyol', 'Tây Ban Nha'),        -- ID 65
(4, 'Getafe', 'Tây Ban Nha'),          -- ID 66
(4, 'Girona', 'Tây Ban Nha'),          -- ID 67
(4, 'Levante', 'Tây Ban Nha'),         -- ID 68 
(4, 'Mallorca', 'Tây Ban Nha'),        -- ID 69
(4, 'Osasuna', 'Tây Ban Nha'),         -- ID 70
(4, 'Rayo Vallecano', 'Tây Ban Nha'),  -- ID 71
(4, 'Real Betis', 'Tây Ban Nha'),      -- ID 72
(4, 'Real Madrid', 'Tây Ban Nha'),     -- ID 73
(4, 'Real Oviedo', 'Tây Ban Nha'),     -- ID 74 
(4, 'Real Sociedad', 'Tây Ban Nha'),   -- ID 75
(4, 'Sevilla', 'Tây Ban Nha'),         -- ID 76
(4, 'Valencia', 'Tây Ban Nha'),        -- ID 77
(4, 'Villarreal', 'Tây Ban Nha');      -- ID 78
-- chen 50 tran dau ngoai hang anh
INSERT INTO tbl_trandau (id_giaidau, id_doi_nha, id_doi_khach, thoi_gian, san_van_dong, trang_thai) VALUES
-- VÒNG 32
(1, 19, 20, '2026-04-11 02:00:00', 'SVĐ London', 'sap_dien_ra'),
(1, 1, 3, '2026-04-11 18:30:00', 'SVĐ Emirates', 'sap_dien_ra'),
(1, 4, 9, '2026-04-11 21:00:00', 'SVĐ Gtech Community', 'sap_dien_ra'),
(1, 6, 5, '2026-04-11 21:00:00', 'SVĐ Turf Moor', 'sap_dien_ra'),
(1, 12, 10, '2026-04-11 23:30:00', 'SVĐ Anfield', 'sap_dien_ra'),
(1, 8, 15, '2026-04-12 20:00:00', 'SVĐ Selhurst Park', 'sap_dien_ra'),
(1, 17, 18, '2026-04-12 20:00:00', 'SVĐ Ánh Sáng', 'sap_dien_ra'),
(1, 16, 2, '2026-04-12 20:00:00', 'SVĐ City Ground', 'sap_dien_ra'),
(1, 7, 13, '2026-04-12 22:30:00', 'SVĐ Stamford Bridge', 'sap_dien_ra'),
(1, 14, 11, '2026-04-14 02:00:00', 'SVĐ Old Trafford', 'sap_dien_ra'),

-- VÒNG 33
(1, 4, 10, '2026-04-18 18:30:00', 'SVĐ Gtech Community', 'sap_dien_ra'),
(1, 11, 20, '2026-04-18 21:00:00', 'SVĐ Elland Road', 'sap_dien_ra'),
(1, 15, 3, '2026-04-18 21:00:00', 'SVĐ St James Park', 'sap_dien_ra'),
(1, 18, 5, '2026-04-18 23:30:00', 'SVĐ Tottenham Hotspur', 'sap_dien_ra'),
(1, 7, 14, '2026-04-19 02:00:00', 'SVĐ Stamford Bridge', 'sap_dien_ra'),
(1, 16, 6, '2026-04-19 20:00:00', 'SVĐ City Ground', 'sap_dien_ra'),
(1, 2, 17, '2026-04-19 20:00:00', 'SVĐ Villa Park', 'sap_dien_ra'),
(1, 9, 12, '2026-04-19 20:00:00', 'SVĐ Goodison Park', 'sap_dien_ra'),
(1, 13, 1, '2026-04-19 22:30:00', 'SVĐ Etihad', 'sap_dien_ra'),
(1, 8, 19, '2026-04-21 02:00:00', 'SVĐ Selhurst Park', 'sap_dien_ra'),

-- VÒNG 34
(1, 5, 7, '2026-04-22 02:00:00', 'SVĐ Amex', 'sap_dien_ra'),
(1, 6, 13, '2026-04-23 02:00:00', 'SVĐ Turf Moor', 'sap_dien_ra'),
(1, 3, 11, '2026-04-23 02:00:00', 'SVĐ Vitality', 'sap_dien_ra'),
(1, 17, 16, '2026-04-25 02:00:00', 'SVĐ Ánh Sáng', 'sap_dien_ra'),
(1, 10, 2, '2026-04-25 18:30:00', 'SVĐ Craven Cottage', 'sap_dien_ra'),
(1, 19, 9, '2026-04-25 21:00:00', 'SVĐ London', 'sap_dien_ra'),
(1, 20, 18, '2026-04-25 21:00:00', 'SVĐ Molineux', 'sap_dien_ra'),
(1, 12, 8, '2026-04-25 21:00:00', 'SVĐ Anfield', 'sap_dien_ra'),
(1, 1, 15, '2026-04-25 23:30:00', 'SVĐ Emirates', 'sap_dien_ra'),
(1, 14, 4, '2026-04-28 02:00:00', 'SVĐ Old Trafford', 'sap_dien_ra'),

-- VÒNG 35
(1, 11, 6, '2026-05-02 02:00:00', 'SVĐ Elland Road', 'sap_dien_ra'),
(1, 2, 18, '2026-05-02 18:30:00', 'SVĐ Villa Park', 'sap_dien_ra'),
(1, 4, 19, '2026-05-02 21:00:00', 'SVĐ Gtech Community', 'sap_dien_ra'),
(1, 20, 17, '2026-05-02 21:00:00', 'SVĐ Molineux', 'sap_dien_ra'),
(1, 15, 5, '2026-05-02 21:00:00', 'SVĐ St James Park', 'sap_dien_ra'),
(1, 3, 8, '2026-05-02 21:00:00', 'SVĐ Vitality', 'sap_dien_ra'),
(1, 1, 10, '2026-05-02 23:30:00', 'SVĐ Emirates', 'sap_dien_ra'),
(1, 14, 12, '2026-05-03 21:30:00', 'SVĐ Old Trafford', 'sap_dien_ra'),
(1, 7, 16, '2026-05-04 21:00:00', 'SVĐ Stamford Bridge', 'sap_dien_ra'),
(1, 9, 13, '2026-05-05 02:00:00', 'SVĐ Goodison Park', 'sap_dien_ra'),

-- VÒNG 36
(1, 12, 7, '2026-05-09 18:30:00', 'SVĐ Anfield', 'sap_dien_ra'),
(1, 10, 3, '2026-05-09 21:00:00', 'SVĐ Craven Cottage', 'sap_dien_ra'),
(1, 17, 14, '2026-05-09 21:00:00', 'SVĐ Ánh Sáng', 'sap_dien_ra'),
(1, 5, 20, '2026-05-09 21:00:00', 'SVĐ Amex', 'sap_dien_ra'),
(1, 8, 9, '2026-05-09 21:00:00', 'SVĐ Selhurst Park', 'sap_dien_ra'),
(1, 6, 2, '2026-05-09 21:00:00', 'SVĐ Turf Moor', 'sap_dien_ra'),
(1, 13, 4, '2026-05-09 23:30:00', 'SVĐ Etihad', 'sap_dien_ra'),
(1, 16, 15, '2026-05-10 20:00:00', 'SVĐ City Ground', 'sap_dien_ra'),
(1, 19, 1, '2026-05-10 22:30:00', 'SVĐ London', 'sap_dien_ra'),
(1, 18, 11, '2026-05-12 02:00:00', 'SVĐ Tottenham Hotspur', 'sap_dien_ra');

-- CHÈN LỊCH THI ĐẤU SERIE A (VÒNG 32, 33, 34)
INSERT INTO tbl_trandau (id_giaidau, id_doi_nha, id_doi_khach, thoi_gian, san_van_dong, trang_thai) VALUES
-- VÒNG 32
(2, 22, 37, '2026-04-11 00:00:00', 'SVĐ Olimpico', 'da_ket_thuc'),          -- Roma vs Pisa
(2, 39, 30, '2026-04-11 00:00:00', 'SVĐ Olimpico Grande', 'da_ket_thuc'),   -- Torino vs Verona
(2, 25, 27, '2026-04-11 00:00:00', 'SVĐ Unipol Domus', 'da_ket_thuc'),      -- Cagliari vs Cremonese
(2, 21, 40, '2026-04-11 23:00:00', 'SVĐ San Siro', 'sap_dien_ra'),          -- Milan vs Udinese
(2, 23, 32, '2026-04-12 01:45:00', 'SVĐ Gewiss', 'sap_dien_ra'),            -- Atalanta vs Juventus
(2, 29, 38, '2026-04-12 17:30:00', 'SVĐ Luigi Ferraris', 'sap_dien_ra'),    -- Genoa vs Sassuolo
(2, 36, 35, '2026-04-12 20:00:00', 'SVĐ Ennio Tardini', 'sap_dien_ra'),     -- Parma vs Napoli
(2, 24, 34, '2026-04-12 23:00:00', 'SVĐ Renato Dall Ara', 'sap_dien_ra'),   -- Bologna vs Lecce
(2, 26, 31, '2026-04-13 01:45:00', 'SVĐ Como', 'sap_dien_ra'),              -- Calcio Como vs Inter
(2, 28, 33, '2026-04-14 01:45:00', 'SVĐ Artemio Franchi', 'sap_dien_ra'),   -- Fiorentina vs Lazio

-- VÒNG 33
(2, 38, 26, '2026-04-17 23:30:00', 'SVĐ Mapei', 'sap_dien_ra'),             -- Sassuolo vs Calcio Como
(2, 31, 25, '2026-04-18 01:45:00', 'SVĐ Giuseppe Meazza', 'sap_dien_ra'),   -- Inter vs Cagliari
(2, 40, 36, '2026-04-18 20:00:00', 'SVĐ Friuli', 'sap_dien_ra'),            -- Udinese vs Parma
(2, 35, 33, '2026-04-18 23:00:00', 'SVĐ Diego Maradona', 'sap_dien_ra'),    -- Napoli vs Lazio
(2, 22, 23, '2026-04-19 01:45:00', 'SVĐ Olimpico', 'sap_dien_ra'),          -- Roma vs Atalanta
(2, 27, 39, '2026-04-19 17:30:00', 'SVĐ Giovanni Zini', 'sap_dien_ra'),     -- Cremonese vs Torino
(2, 30, 21, '2026-04-19 20:00:00', 'SVĐ Marcantonio Bentegodi', 'sap_dien_ra'), -- Verona vs Milan
(2, 37, 29, '2026-04-19 23:00:00', 'SVĐ Arena Garibaldi', 'sap_dien_ra'),   -- Pisa vs Genoa
(2, 32, 24, '2026-04-20 01:45:00', 'SVĐ Allianz', 'sap_dien_ra'),           -- Juventus vs Bologna
(2, 34, 28, '2026-04-21 01:45:00', 'SVĐ Via del Mare', 'sap_dien_ra'),      -- Lecce vs Fiorentina

-- VÒNG 34
(2, 35, 27, '2026-04-25 01:45:00', 'SVĐ Diego Maradona', 'sap_dien_ra'),    -- Napoli vs Cremonese
(2, 36, 37, '2026-04-25 20:00:00', 'SVĐ Ennio Tardini', 'sap_dien_ra'),     -- Parma vs Pisa
(2, 24, 22, '2026-04-25 23:00:00', 'SVĐ Renato Dall Ara', 'sap_dien_ra'),   -- Bologna vs Roma
(2, 30, 34, '2026-04-26 01:45:00', 'SVĐ Marcantonio Bentegodi', 'sap_dien_ra'), -- Verona vs Lecce
(2, 28, 38, '2026-04-26 17:30:00', 'SVĐ Artemio Franchi', 'sap_dien_ra'),   -- Fiorentina vs Sassuolo
(2, 29, 26, '2026-04-26 20:00:00', 'SVĐ Luigi Ferraris', 'sap_dien_ra'),    -- Genoa vs Calcio Como
(2, 39, 31, '2026-04-26 23:00:00', 'SVĐ Olimpico Grande', 'sap_dien_ra'),   -- Torino vs Inter
(2, 21, 32, '2026-04-27 01:45:00', 'SVĐ San Siro', 'sap_dien_ra'),          -- Milan vs Juventus
(2, 25, 23, '2026-04-27 23:30:00', 'SVĐ Unipol Domus', 'sap_dien_ra'),      -- Cagliari vs Atalanta
(2, 33, 40, '2026-04-28 01:45:00', 'SVĐ Olimpico', 'sap_dien_ra');          -- Lazio vs Udinese
-- CHÈN LỊCH THI ĐẤU BUNDESLIGA (VÒNG 30 ĐẾN 34)
-- Giải đấu ID = 3
INSERT INTO tbl_trandau (id_giaidau, id_doi_nha, id_doi_khach, thoi_gian, san_van_dong, trang_thai) VALUES

-- VÒNG 30
(3, 49, 41, '2026-04-18 01:30:00', 'SVĐ Millerntor', 'sap_dien_ra'),            -- St. Pauli vs Koln
(3, 42, 47, '2026-04-18 20:30:00', 'SVĐ BayArena', 'sap_dien_ra'),              -- Leverkusen vs Augsburg
(3, 54, 44, '2026-04-18 20:30:00', 'SVĐ PreZero Arena', 'sap_dien_ra'),         -- Hoffenheim vs Dortmund
(3, 58, 50, '2026-04-18 20:30:00', 'SVĐ Weserstadion', 'sap_dien_ra'),          -- Werder Bremen vs Hamburger
(3, 55, 57, '2026-04-18 20:30:00', 'SVĐ An der Alten Försterei', 'sap_dien_ra'),-- Union Berlin vs Wolfsburg
(3, 46, 52, '2026-04-18 23:30:00', 'SVĐ Deutsche Bank Park', 'sap_dien_ra'),    -- Frankfurt vs Leipzig
(3, 53, 48, '2026-04-19 20:30:00', 'SVĐ Europa-Park', 'sap_dien_ra'),           -- Freiburg vs Heidenheim
(3, 43, 56, '2026-04-19 22:30:00', 'SVĐ Allianz Arena', 'sap_dien_ra'),         -- Bayern vs Stuttgart
(3, 45, 51, '2026-04-20 00:30:00', 'SVĐ Borussia-Park', 'sap_dien_ra'),         -- Gladbach vs Mainz

-- VÒNG 31
(3, 52, 55, '2026-04-25 01:30:00', 'SVĐ Red Bull Arena', 'sap_dien_ra'),        -- Leipzig vs Union Berlin
(3, 57, 45, '2026-04-25 20:30:00', 'SVĐ Volkswagen Arena', 'sap_dien_ra'),      -- Wolfsburg vs Gladbach
(3, 51, 43, '2026-04-25 20:30:00', 'SVĐ Mewa Arena', 'sap_dien_ra'),            -- Mainz vs Bayern
(3, 48, 49, '2026-04-25 20:30:00', 'SVĐ Voith-Arena', 'sap_dien_ra'),           -- Heidenheim vs St. Pauli
(3, 41, 42, '2026-04-25 20:30:00', 'SVĐ RheinEnergieStadion', 'sap_dien_ra'),   -- Koln vs Leverkusen
(3, 47, 46, '2026-04-25 20:30:00', 'SVĐ WWK Arena', 'sap_dien_ra'),             -- Augsburg vs Frankfurt
(3, 50, 54, '2026-04-25 23:30:00', 'SVĐ Volksparkstadion', 'sap_dien_ra'),      -- Hamburger vs Hoffenheim
(3, 56, 58, '2026-04-26 20:30:00', 'SVĐ MHPArena', 'sap_dien_ra'),              -- Stuttgart vs Werder Bremen
(3, 44, 53, '2026-04-26 22:30:00', 'SVĐ Signal Iduna Park', 'sap_dien_ra'),     -- Dortmund vs Freiburg

-- VÒNG 32
(3, 46, 50, '2026-05-02 20:30:00', 'SVĐ Deutsche Bank Park', 'sap_dien_ra'),    -- Frankfurt vs Hamburger
(3, 54, 56, '2026-05-02 20:30:00', 'SVĐ PreZero Arena', 'sap_dien_ra'),         -- Hoffenheim vs Stuttgart
(3, 43, 48, '2026-05-02 20:30:00', 'SVĐ Allianz Arena', 'sap_dien_ra'),         -- Bayern vs Heidenheim
(3, 58, 47, '2026-05-02 20:30:00', 'SVĐ Weserstadion', 'sap_dien_ra'),          -- Werder Bremen vs Augsburg
(3, 55, 41, '2026-05-02 20:30:00', 'SVĐ An der Alten Försterei', 'sap_dien_ra'),-- Union Berlin vs Koln
(3, 42, 52, '2026-05-02 23:30:00', 'SVĐ BayArena', 'sap_dien_ra'),              -- Leverkusen vs Leipzig
(3, 49, 51, '2026-05-03 20:30:00', 'SVĐ Millerntor', 'sap_dien_ra'),            -- St. Pauli vs Mainz
(3, 45, 44, '2026-05-03 22:30:00', 'SVĐ Borussia-Park', 'sap_dien_ra'),         -- Gladbach vs Dortmund
(3, 53, 57, '2026-05-04 00:30:00', 'SVĐ Europa-Park', 'sap_dien_ra'),           -- Freiburg vs Wolfsburg

-- VÒNG 33
(3, 44, 46, '2026-05-09 01:30:00', 'SVĐ Signal Iduna Park', 'sap_dien_ra'),     -- Dortmund vs Frankfurt
(3, 56, 42, '2026-05-09 20:30:00', 'SVĐ MHPArena', 'sap_dien_ra'),              -- Stuttgart vs Leverkusen
(3, 54, 58, '2026-05-09 20:30:00', 'SVĐ PreZero Arena', 'sap_dien_ra'),         -- Hoffenheim vs Werder Bremen
(3, 47, 45, '2026-05-09 20:30:00', 'SVĐ WWK Arena', 'sap_dien_ra'),             -- Augsburg vs Gladbach
(3, 52, 49, '2026-05-09 20:30:00', 'SVĐ Red Bull Arena', 'sap_dien_ra'),        -- Leipzig vs St. Pauli
(3, 57, 43, '2026-05-09 23:30:00', 'SVĐ Volkswagen Arena', 'sap_dien_ra'),      -- Wolfsburg vs Bayern
(3, 50, 53, '2026-05-10 20:30:00', 'SVĐ Volksparkstadion', 'sap_dien_ra'),      -- Hamburger vs Freiburg
(3, 41, 48, '2026-05-10 22:30:00', 'SVĐ RheinEnergieStadion', 'sap_dien_ra'),   -- Koln vs Heidenheim
(3, 51, 55, '2026-05-11 00:30:00', 'SVĐ Mewa Arena', 'sap_dien_ra'),            -- Mainz vs Union Berlin

-- VÒNG 34
(3, 49, 57, '2026-05-16 20:30:00', 'SVĐ Millerntor', 'sap_dien_ra'),            -- St. Pauli vs Wolfsburg
(3, 48, 51, '2026-05-16 20:30:00', 'SVĐ Voith-Arena', 'sap_dien_ra'),           -- Heidenheim vs Mainz
(3, 46, 56, '2026-05-16 20:30:00', 'SVĐ Deutsche Bank Park', 'sap_dien_ra'),    -- Frankfurt vs Stuttgart
(3, 58, 44, '2026-05-16 20:30:00', 'SVĐ Weserstadion', 'sap_dien_ra'),          -- Werder Bremen vs Dortmund
(3, 42, 50, '2026-05-16 20:30:00', 'SVĐ BayArena', 'sap_dien_ra'),              -- Leverkusen vs Hamburger
(3, 45, 54, '2026-05-16 20:30:00', 'SVĐ Borussia-Park', 'sap_dien_ra'),         -- Gladbach vs Hoffenheim
(3, 43, 41, '2026-05-16 20:30:00', 'SVĐ Allianz Arena', 'sap_dien_ra'),         -- Bayern vs Koln
(3, 55, 47, '2026-05-16 20:30:00', 'SVĐ An der Alten Försterei', 'sap_dien_ra'),-- Union Berlin vs Augsburg
(3, 53, 52, '2026-05-16 20:30:00', 'SVĐ Europa-Park', 'sap_dien_ra');           -- Freiburg vs Leipzig

-- 2. CHÈN LỊCH THI ĐẤU LA LIGA (VÒNG 32 VÀ 33 - ĐÃ CẬP NHẬT ID MỚI)
-- Giải đấu ID = 4
INSERT INTO tbl_trandau (id_giaidau, id_doi_nha, id_doi_khach, thoi_gian, san_van_dong, trang_thai) VALUES

-- VÒNG 33
(4, 60, 70, '2026-04-22 00:00:00', 'SVĐ San Mamés', 'sap_dien_ra'),             -- Ath. Bilbao vs Osasuna
(4, 69, 77, '2026-04-22 00:00:00', 'SVĐ Son Moix', 'sap_dien_ra'),              -- Mallorca vs Valencia
(4, 73, 59, '2026-04-22 02:30:00', 'SVĐ Santiago Bernabéu', 'sap_dien_ra'),     -- Real Madrid vs Alavés
(4, 67, 72, '2026-04-22 02:30:00', 'SVĐ Montilivi', 'sap_dien_ra'),             -- Girona vs Betis
(4, 64, 61, '2026-04-23 00:00:00', 'SVĐ Martínez Valero', 'sap_dien_ra'),       -- Elche vs Atlético Madrid
(4, 75, 66, '2026-04-23 01:00:00', 'SVĐ Reale Arena', 'sap_dien_ra'),           -- Real Sociedad vs Getafe
(4, 62, 63, '2026-04-23 02:30:00', 'SVĐ Spotify Camp Nou', 'sap_dien_ra'),      -- Barcelona vs Celta
(4, 68, 76, '2026-04-24 00:00:00', 'SVĐ Ciutat de València', 'sap_dien_ra'),    -- Levante vs Sevilla
(4, 71, 65, '2026-04-24 01:00:00', 'SVĐ Vallecas', 'sap_dien_ra'),              -- Rayo vs Espanyol
(4, 74, 78, '2026-04-24 02:30:00', 'SVĐ Carlos Tartiere', 'sap_dien_ra'),       -- Real Oviedo vs Villarreal

-- VÒNG 32
(4, 72, 73, '2026-04-25 02:00:00', 'SVĐ Benito Villamarín', 'sap_dien_ra'),     -- Betis vs Real Madrid
(4, 59, 69, '2026-04-25 19:00:00', 'SVĐ Mendizorroza', 'sap_dien_ra'),          -- Alavés vs Mallorca
(4, 66, 62, '2026-04-25 21:15:00', 'SVĐ Coliseum', 'sap_dien_ra'),              -- Getafe vs Barcelona
(4, 77, 67, '2026-04-25 23:30:00', 'SVĐ Mestalla', 'sap_dien_ra'),              -- Valencia vs Girona
(4, 61, 60, '2026-04-26 02:00:00', 'SVĐ Cívitas Metropolitano', 'sap_dien_ra'), -- Atlético Madrid vs Ath. Bilbao
(4, 71, 75, '2026-04-26 19:00:00', 'SVĐ Vallecas', 'sap_dien_ra'),              -- Rayo vs Real Sociedad
(4, 74, 64, '2026-04-26 21:15:00', 'SVĐ Carlos Tartiere', 'sap_dien_ra'),       -- Real Oviedo vs Elche
(4, 70, 76, '2026-04-26 23:30:00', 'SVĐ El Sadar', 'sap_dien_ra'),              -- Osasuna vs Sevilla
(4, 78, 63, '2026-04-27 02:00:00', 'SVĐ Estadio de la Cerámica', 'sap_dien_ra'),-- Villarreal vs Celta
(4, 65, 68, '2026-04-28 02:00:00', 'SVĐ RCDE Stadium', 'sap_dien_ra');          -- Espanyol vs Levante
