<?php
/**
 * Database Setup Script for Simu Fashion
 * Run this file once to create the database and tables
 */

// Database connection without selecting database first
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS simu_fashion");
    echo "Database 'simu_fashion' created successfully!<br>";
    
    // Select the database
    $pdo->exec("USE simu_fashion");
    
    // Create products table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            category VARCHAR(50) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            old_price DECIMAL(10,2) DEFAULT NULL,
            image VARCHAR(255) NOT NULL,
            badge VARCHAR(50) DEFAULT NULL,
            description TEXT,
            stock INT DEFAULT 100,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "Products table created successfully!<br>";
    
    // Create categories table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            image VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "Categories table created successfully!<br>";
    
    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            address TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "Users table created successfully!<br>";
    
    // Create orders table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT DEFAULT NULL,
            order_number VARCHAR(50) NOT NULL UNIQUE,
            total_amount DECIMAL(10,2) NOT NULL,
            status VARCHAR(50) DEFAULT 'pending',
            shipping_address TEXT,
            payment_method VARCHAR(50) DEFAULT 'cash',
            notes TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "Orders table created successfully!<br>";
    
    // Create order_items table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            product_name VARCHAR(255) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            quantity INT NOT NULL,
            subtotal DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "Order items table created successfully!<br>";
    
    // Create cart table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS cart (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(100) NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "Cart table created successfully!<br>";
    
    // Create contact_messages table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS contact_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            subject VARCHAR(200) DEFAULT NULL,
            message TEXT NOT NULL,
            status VARCHAR(20) DEFAULT 'unread',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "Contact messages table created successfully!<br>";
    
    // Insert sample products
    $products = [
        ["Classic Denim Jacket", "men", 89.99, 129.99, "img/img22.jpg", "Sale", "Premium quality denim jacket for men"],
        ["Elegant Summer Dress", "women", 79.99, 99.99, "img/g2.jpg", "New", "Beautiful summer dress for women"],
        ["Casual Polo Shirt", "men", 45.99, NULL, "img/g1.jpg", NULL, "Comfortable cotton polo shirt"],
        ["Leather Handbag", "accessories", 149.99, 199.99, "img/g11.JPG", "Hot", "Genuine leather handbag"],
        ["Running Sneakers", "men", 119.99, NULL, "img/back3.png", "New", "Comfortable running shoes"],
        ["Floral Blouse", "women", 55.99, 75.99, "img/g1.jpg", "Sale", "Beautiful floral print blouse"],
        ["Leather Belt", "accessories", 35.99, NULL, "img/g11.JPG", NULL, "Genuine leather belt"],
        ["Wool Sweater", "men", 89.99, 119.99, "img/g2.jpg", "Sale", "Warm wool sweater for winter"],
        ["High Heels", "women", 99.99, NULL, "img/back3.png", "New", "Elegant high heels for women"],
        ["Sunglasses", "men", 25.99, 45.99, "img/g1.jpg", "Sale", "Stylish sunglasses"],
        ["Formal Trousers", "men", 65.99, NULL, "img/g2.jpg", NULL, "Professional formal trousers"],
        ["Evening Gown", "women", 199.99, 299.99, "img/img22.jpg", "Luxury", "Stunning evening gown"]
    ];
    
    // Check if products already exist
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['count'] == 0) {
        $insert = $pdo->prepare("INSERT INTO products (name, category, price, old_price, image, badge, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($products as $product) {
            $insert->execute($product);
        }
        echo "Sample products inserted successfully!<br>";
    } else {
        echo "Products already exist, skipping insertion.<br>";
    }
    
    // Insert sample categories
    $categories = [
        ["Men", "img/g1.jpg"],
        ["Women", "img/g11.JPG"],
        ["Accessories", "img/g3.JPG"],
        ["Footwear", "img/g0.JPG"]
    ];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['count'] == 0) {
        $insert = $pdo->prepare("INSERT INTO categories (name, image) VALUES (?, ?)");
        
        foreach ($categories as $category) {
            $insert->execute($category);
        }
        echo "Sample categories inserted successfully!<br>";
    }
    
    echo "<br><strong>Database setup completed successfully!</strong>";
    echo "<br>You can now use the e-commerce website with database integration.";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
