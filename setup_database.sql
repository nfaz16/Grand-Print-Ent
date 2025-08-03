-- Database setup for Grand Print Admin Dashboard
-- Run this SQL to create the necessary tables

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users_db(id) ON DELETE CASCADE
);

-- Insert sample products
INSERT INTO products (name, description, price, image) VALUES
('Modern T-Shirt', 'Comfortable cotton t-shirt with modern design', 45.00, '1.jpg'),
('Classic Polo', 'Premium polo shirt with embroidered logo', 65.00, '3.jpg'),
('Vintage Tee', 'Retro style t-shirt with vintage graphics', 55.00, '5.jpg'),
('Sport Jersey', 'Athletic performance jersey', 75.00, '7.jpg'),
('Casual Shirt', 'Everyday casual shirt for comfort', 50.00, '9.jpg'),
('Designer Tee', 'Exclusive designer t-shirt collection', 80.00, '11.jpg');

-- Insert sample orders (assuming user_id 1 exists)
INSERT INTO orders (user_id, product_name, quantity, total, status) VALUES
(1, 'Modern T-Shirt', 2, 90.00, 'Pending'),
(1, 'Classic Polo', 1, 65.00, 'Processing'),
(1, 'Vintage Tee', 3, 165.00, 'Shipped'),
(1, 'Sport Jersey', 1, 75.00, 'Delivered'),
(1, 'Casual Shirt', 2, 100.00, 'Cancelled');

-- Add created_at column to users_db if it doesn't exist
ALTER TABLE users_db ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP; 