-- Cinema City Database Schema
-- Create database and tables for the Cinema City project

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS cinemacity;
USE cinemacity;

-- Table: users (for registration and login)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: movies (for storing movie information)
CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    duration INT NOT NULL COMMENT 'Duration in minutes',
    genre VARCHAR(100),
    director VARCHAR(100),
    cast TEXT,
    release_date DATE,
    poster_url VARCHAR(500),
    trailer_url VARCHAR(500),
    rating DECIMAL(3,1) DEFAULT 0.0,
    age_rating VARCHAR(10) COMMENT 'e.g., PG, PG-13, R',
    language VARCHAR(50) DEFAULT 'Portuguese',
    subtitle VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: cinemas (cinema locations)
CREATE TABLE IF NOT EXISTS cinemas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20),
    phone VARCHAR(20),
    total_screens INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: screens (cinema screens/rooms)
CREATE TABLE IF NOT EXISTS screens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cinema_id INT NOT NULL,
    screen_number INT NOT NULL,
    screen_name VARCHAR(50),
    total_seats INT NOT NULL DEFAULT 100,
    screen_type VARCHAR(50) COMMENT 'e.g., IMAX, 3D, Standard',
    FOREIGN KEY (cinema_id) REFERENCES cinemas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: sessions (movie sessions/showtimes)
CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    screen_id INT NOT NULL,
    session_date DATE NOT NULL,
    session_time TIME NOT NULL,
    price DECIMAL(6,2) NOT NULL,
    available_seats INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (screen_id) REFERENCES screens(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: products (snacks, drinks, etc.)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    category VARCHAR(50) NOT NULL COMMENT 'e.g., Snacks, Drinks, Combos',
    price DECIMAL(6,2) NOT NULL,
    image_url VARCHAR(500),
    is_available BOOLEAN DEFAULT TRUE,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: tickets (purchased tickets)
CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_id INT NOT NULL,
    seat_number VARCHAR(10) NOT NULL,
    ticket_type VARCHAR(50) DEFAULT 'Standard' COMMENT 'e.g., Standard, Student, Senior',
    price DECIMAL(6,2) NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'active' COMMENT 'active, used, cancelled',
    qr_code VARCHAR(255) UNIQUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: orders (for product purchases)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ticket_id INT NULL COMMENT 'Optional link to ticket if bought together',
    total_amount DECIMAL(8,2) NOT NULL,
    payment_method VARCHAR(50) COMMENT 'e.g., Credit Card, Cash, MB Way',
    payment_status VARCHAR(20) DEFAULT 'pending' COMMENT 'pending, completed, failed',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: order_items (products in each order)
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(6,2) NOT NULL,
    subtotal DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: reviews (movie reviews by users)
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    movie_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_movie (user_id, movie_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data

-- Sample cinemas
INSERT INTO cinemas (name, address, city, postal_code, phone, total_screens) VALUES
('Cinema City Alegro Alfragide', 'Centro Comercial Alegro Alfragide, IC19', 'Alfragide', '2610-042', '+351 214 719 555', 10),
('Cinema City Alegro Setúbal', 'Centro Comercial Alegro Setúbal, Av. Antero de Quental', 'Setúbal', '2900-000', '+351 265 701 900', 8),
('Cinema City Alvalade', 'Rua Alves Redol, 8', 'Lisboa', '1000-029', '+351 217 933 333', 12),
('Cinema City Campo Pequeno', 'Praça de Touros do Campo Pequeno', 'Lisboa', '1000-082', '+351 217 998 450', 8),
('Cinema City Leiria', 'Rua do Olival, Edifício Allegro', 'Leiria', '2410-035', '+351 244 820 170', 6);

-- Sample screens for Alegro Alfragide (cinema_id 1)
INSERT INTO screens (cinema_id, screen_number, screen_name, total_seats, screen_type) VALUES
(1, 1, 'Sala 1', 120, 'IMAX'),
(1, 2, 'Sala 2', 100, '3D'),
(1, 3, 'Sala 3', 80, 'Standard'),
(1, 4, 'Sala 4', 80, 'Standard');

-- Sample screens for Alvalade (cinema_id 3)
INSERT INTO screens (cinema_id, screen_number, screen_name, total_seats, screen_type) VALUES
(3, 1, 'Sala 1', 150, 'IMAX'),
(3, 2, 'Sala 2', 120, '3D'),
(3, 3, 'Sala 3', 100, 'Standard');

-- Sample movies
INSERT INTO movies (title, description, duration, genre, director, cast, release_date, poster_url, rating, age_rating, language) VALUES
('The Grand Adventure', 'An epic tale of courage and discovery in uncharted lands.', 145, 'Adventure', 'John Director', 'Actor A, Actor B, Actor C', '2026-01-15', '/assets/images/movies/grand-adventure.jpg', 8.5, 'PG-13', 'Portuguese'),
('Mystery Manor', 'A thrilling mystery unfolds in an abandoned mansion.', 118, 'Thriller', 'Jane Smith', 'Actor D, Actor E', '2026-01-20', '/assets/images/movies/mystery-manor.jpg', 7.8, 'R', 'Portuguese'),
('Family Fun', 'A heartwarming comedy for the whole family.', 95, 'Comedy', 'Bob Johnson', 'Actor F, Actor G, Actor H', '2026-01-10', '/assets/images/movies/family-fun.jpg', 7.2, 'PG', 'Portuguese'),
('Space Odyssey 2026', 'Humanity\'s journey to the stars begins now.', 160, 'Sci-Fi', 'Sarah Williams', 'Actor I, Actor J', '2026-02-01', '/assets/images/movies/space-odyssey.jpg', 9.0, 'PG-13', 'Portuguese');

-- Sample sessions for movies
INSERT INTO sessions (movie_id, screen_id, session_date, session_time, price, available_seats) VALUES
(1, 1, '2026-01-15', '14:00:00', 8.50, 120),
(1, 1, '2026-01-15', '17:30:00', 8.50, 120),
(1, 1, '2026-01-15', '21:00:00', 9.00, 120),
(2, 2, '2026-01-20', '15:00:00', 8.00, 100),
(2, 2, '2026-01-20', '19:30:00', 8.50, 100),
(3, 3, '2026-01-10', '16:00:00', 7.00, 80),
(3, 3, '2026-01-10', '18:30:00', 7.00, 80),
(4, 1, '2026-02-01', '20:00:00', 10.00, 120);

-- Sample products
INSERT INTO products (name, description, category, price, image_url, stock) VALUES
('Pipocas Médias', 'Pipocas acabadas de fazer, tamanho médio', 'Snacks', 3.50, '/assets/images/products/popcorn-medium.jpg', 100),
('Pipocas Grandes', 'Pipocas acabadas de fazer, tamanho grande', 'Snacks', 5.00, '/assets/images/products/popcorn-large.jpg', 100),
('Coca-Cola 500ml', 'Refrigerante Coca-Cola 500ml', 'Drinks', 2.50, '/assets/images/products/coca-cola.jpg', 150),
('Água Mineral', 'Água mineral natural 500ml', 'Drinks', 1.50, '/assets/images/products/water.jpg', 200),
('Nachos com Queijo', 'Nachos crocantes com molho de queijo', 'Snacks', 4.50, '/assets/images/products/nachos.jpg', 80),
('Combo Casal', '2 Pipocas grandes + 2 Bebidas grandes', 'Combos', 15.00, '/assets/images/products/combo-couple.jpg', 50),
('Combo Família', '3 Pipocas grandes + 3 Bebidas + Nachos', 'Combos', 25.00, '/assets/images/products/combo-family.jpg', 40),
('M&Ms', 'Chocolate M&Ms 45g', 'Snacks', 2.00, '/assets/images/products/mnms.jpg', 120);

-- Sample user (password is 'password123' hashed with bcrypt)
INSERT INTO users (name, email, password, phone) VALUES
('João Silva', 'joao@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+351 910 123 456'),
('Maria Santos', 'maria@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+351 920 654 321');

-- Create indexes for better performance
CREATE INDEX idx_sessions_date ON sessions(session_date);
CREATE INDEX idx_sessions_movie ON sessions(movie_id);
CREATE INDEX idx_tickets_user ON tickets(user_id);
CREATE INDEX idx_tickets_session ON tickets(session_id);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_movies_active ON movies(is_active);
CREATE INDEX idx_products_category ON products(category);
CREATE INDEX idx_users_email ON users(email);
