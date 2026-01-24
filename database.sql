-- Databaza e y/e store

CREATE DATABASE IF NOT EXISTS ye_store;
USE ye_store;

-- Tabela e users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    profile_picture VARCHAR(255) DEFAULT 'default-profile.png',
    country VARCHAR(50),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
);

-- Tabela e kategorive
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Kategoria e produkteve
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255),
    category_id INT,
    is_top_product BOOLEAN DEFAULT FALSE,
    is_new_arrival BOOLEAN DEFAULT FALSE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_top_products (is_top_product),
    INDEX idx_new_arrivals (is_new_arrival)
);

-- about us table
CREATE TABLE about_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    subtitle TEXT,
    main_content TEXT,
    updated_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);
-- mesazhet support
CREATE TABLE support_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_is_read (is_read)
);

-- shopping cart
CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id)
);
-- sllajderat
CREATE TABLE sliders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100),
    description TEXT,
    image_path VARCHAR(255) NOT NULL,
    link VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_is_active (is_active)
);


-- mbrenda kategorive filtrimi
INSERT INTO categories (name, slug) VALUES 
('Electronics', 'electronics'),
('Home & Kitchen', 'home-kitchen'),
('Accessories', 'accessories');

-- Admin user (password: admin123 - hashed)
INSERT INTO users (username, email, password, full_name, role) VALUES 
('admin', 'admin@ye-store.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'System Administrator', 'admin');

-- Kontenti about us
INSERT INTO about_content (title, subtitle, main_content) VALUES 
('About Us',
'Y/E Store is an easily accessible, reliable, and UPS-certified shipping website created by two ambitious 19-year-olds, dedicated to bringing you the best online shopping experience.',
'Y/E Store is a fully user-focused online shopping platform, founded and managed by two driven 19-year-olds with a shared vision of revolutionizing e-commerce. 
Our platform is designed to be intuitive, responsive, and secure, ensuring that every interaction you have is seamless. 
We carefully curate our products, ranging from electronics and home essentials to unique lifestyle items, maintaining quality while keeping prices competitive. 
Our UPS-certified shipping guarantees reliable delivery, and we constantly monitor order tracking to ensure you always know where your package is. 
Customer support is available around the clock, and we take feedback seriously to continuously improve our services. 
At Y/E Store, we combine youthful energy, modern technology, and professional dedication to make online shopping simple, safe, and enjoyable for everyone. 
Whether you''re a first-time buyer or a seasoned online shopper, we aim to exceed expectations in both service and convenience.');