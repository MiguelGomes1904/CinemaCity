-- Reset cinemas data
-- This script cleans old cinemas and inserts the correct Cinema City locations

-- Delete old data (in correct order due to foreign keys)
DELETE FROM tickets;
DELETE FROM sessions;
DELETE FROM screens;
DELETE FROM cinemas;

-- Reset auto increment
ALTER TABLE cinemas AUTO_INCREMENT = 1;
ALTER TABLE screens AUTO_INCREMENT = 1;
ALTER TABLE sessions AUTO_INCREMENT = 1;
ALTER TABLE tickets AUTO_INCREMENT = 1;

-- Insert correct Cinema City cinemas
INSERT INTO cinemas (name, address, city, postal_code, phone, total_screens) VALUES
('Cinema City Alegro Alfragide', 'Centro Comercial Alegro Alfragide, IC19', 'Alfragide', '2610-042', '+351 214 719 555', 10),
('Cinema City Alegro Setúbal', 'Centro Comercial Alegro Setúbal, Av. Antero de Quental', 'Setúbal', '2900-000', '+351 265 701 900', 8),
('Cinema City Alvalade', 'Rua Alves Redol, 8', 'Lisboa', '1000-029', '+351 217 933 333', 12),
('Cinema City Campo Pequeno', 'Praça de Touros do Campo Pequeno', 'Lisboa', '1000-082', '+351 217 998 450', 8),
('Cinema City Leiria', 'Rua do Olival, Edifício Allegro', 'Leiria', '2410-035', '+351 244 820 170', 6);

-- Insert screens for all cinemas
-- Alegro Alfragide (10 salas)
INSERT INTO screens (cinema_id, screen_number, screen_name, total_seats, screen_type) VALUES
(1, 1, 'Sala 1', 150, 'IMAX'),
(1, 2, 'Sala 2', 120, '3D'),
(1, 3, 'Sala 3', 100, 'Standard'),
(1, 4, 'Sala 4', 100, 'Standard'),
(1, 5, 'Sala 5', 80, 'Standard'),
(1, 6, 'Sala 6', 80, 'Standard'),
(1, 7, 'Sala 7', 80, 'VIP'),
(1, 8, 'Sala 8', 60, 'Standard'),
(1, 9, 'Sala 9', 60, 'Standard'),
(1, 10, 'Sala 10', 50, 'Standard');

-- Alegro Setúbal (8 salas)
INSERT INTO screens (cinema_id, screen_number, screen_name, total_seats, screen_type) VALUES
(2, 1, 'Sala 1', 140, 'IMAX'),
(2, 2, 'Sala 2', 110, '3D'),
(2, 3, 'Sala 3', 90, 'Standard'),
(2, 4, 'Sala 4', 90, 'Standard'),
(2, 5, 'Sala 5', 80, 'Standard'),
(2, 6, 'Sala 6', 80, 'VIP'),
(2, 7, 'Sala 7', 70, 'Standard'),
(2, 8, 'Sala 8', 60, 'Standard');

-- Alvalade (12 salas)
INSERT INTO screens (cinema_id, screen_number, screen_name, total_seats, screen_type) VALUES
(3, 1, 'Sala 1', 180, 'IMAX'),
(3, 2, 'Sala 2', 150, '3D'),
(3, 3, 'Sala 3', 120, 'Standard'),
(3, 4, 'Sala 4', 120, 'Standard'),
(3, 5, 'Sala 5', 100, 'Standard'),
(3, 6, 'Sala 6', 100, 'VIP'),
(3, 7, 'Sala 7', 90, 'Standard'),
(3, 8, 'Sala 8', 90, 'Standard'),
(3, 9, 'Sala 9', 80, 'Standard'),
(3, 10, 'Sala 10', 80, '3D'),
(3, 11, 'Sala 11', 70, 'Standard'),
(3, 12, 'Sala 12', 60, 'Standard');

-- Campo Pequeno (8 salas)
INSERT INTO screens (cinema_id, screen_number, screen_name, total_seats, screen_type) VALUES
(4, 1, 'Sala 1', 160, 'IMAX'),
(4, 2, 'Sala 2', 130, '3D'),
(4, 3, 'Sala 3', 100, 'Standard'),
(4, 4, 'Sala 4', 100, 'Standard'),
(4, 5, 'Sala 5', 90, 'VIP'),
(4, 6, 'Sala 6', 80, 'Standard'),
(4, 7, 'Sala 7', 80, 'Standard'),
(4, 8, 'Sala 8', 70, 'Standard');

-- Leiria (6 salas)
INSERT INTO screens (cinema_id, screen_number, screen_name, total_seats, screen_type) VALUES
(5, 1, 'Sala 1', 130, 'IMAX'),
(5, 2, 'Sala 2', 110, '3D'),
(5, 3, 'Sala 3', 90, 'Standard'),
(5, 4, 'Sala 4', 90, 'Standard'),
(5, 5, 'Sala 5', 80, 'VIP'),
(5, 6, 'Sala 6', 70, 'Standard');

-- Insert sample sessions for all cinemas
-- Get movie IDs (assuming movies 1-4 exist)
INSERT INTO sessions (movie_id, screen_id, session_date, session_time, price, available_seats) VALUES
-- Alegro Alfragide (cinema 1, screens 1-10) - Movie 1
(1, 1, '2026-01-15', '14:00:00', 8.50, 150),
(1, 1, '2026-01-15', '17:30:00', 8.50, 150),
(1, 1, '2026-01-15', '21:00:00', 9.00, 150),
-- Alegro Alfragide - Movie 2
(2, 2, '2026-01-20', '15:00:00', 8.00, 120),
(2, 2, '2026-01-20', '19:30:00', 8.50, 120),
-- Alegro Setúbal (cinema 2, screens 11-18) - Movie 1
(1, 11, '2026-01-15', '14:00:00', 8.00, 140),
(1, 11, '2026-01-15', '19:00:00', 8.50, 140),
-- Alegro Setúbal - Movie 3
(3, 12, '2026-01-10', '15:00:00', 7.50, 110),
(3, 12, '2026-01-10', '18:00:00', 7.50, 110),
-- Alvalade (cinema 3, screens 19-30) - Movie 1
(1, 19, '2026-01-15', '14:30:00', 9.00, 180),
(1, 19, '2026-01-15', '18:00:00', 9.00, 180),
(1, 19, '2026-01-15', '21:30:00', 9.50, 180),
-- Alvalade - Movie 2
(2, 20, '2026-01-20', '15:30:00', 8.50, 150),
(2, 20, '2026-01-20', '20:00:00', 9.00, 150),
-- Campo Pequeno (cinema 4, screens 31-38) - Movie 3
(3, 31, '2026-01-10', '14:00:00', 7.50, 160),
(3, 31, '2026-01-10', '17:00:00', 7.50, 160),
-- Campo Pequeno - Movie 4
(4, 32, '2026-02-01', '20:00:00', 10.00, 130),
(4, 32, '2026-02-01', '22:30:00', 10.00, 130),
-- Leiria (cinema 5, screens 39-44) - Movie 1
(1, 39, '2026-01-15', '15:00:00', 7.00, 130),
(1, 39, '2026-01-15', '18:30:00', 7.50, 130),
-- Leiria - Movie 4
(4, 40, '2026-02-01', '19:00:00', 8.00, 110),
(4, 40, '2026-02-01', '21:30:00', 8.50, 110);
