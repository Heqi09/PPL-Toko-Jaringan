CREATE DATABASE IF NOT EXISTS toko_jaringan;
USE toko_jaringan;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    deskripsi TEXT,
    brand VARCHAR(100) DEFAULT NULL,
    gambar VARCHAR(255) DEFAULT 'default.jpg'
);

CREATE TABLE IF NOT EXISTS keranjang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    produk_id INT NOT NULL,
    qty INT NOT NULL DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
);

-- Akun Default (Password: admin123 & user123)
INSERT INTO users (nama, username, password, role) VALUES
('Administrator', 'admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1g.9.c2iYdGj8kFfV31U/D9p2XnNl.O', 'admin'),
('Customer Test', 'customer', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1g.9.c2iYdGj8kFfV31U/D9p2XnNl.O', 'customer');