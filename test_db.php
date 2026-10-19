<?php
/**
 * Complete Database Setup Script
 * Run this to create all database tables and sample data
 */

$host = 'localhost';
$username = 'root';
$password = '';

echo "<h2>Setting up Simu Fashion Database...</h2>";

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS simu_fashion");
    $pdo->exec("USE simu_fashion");
    echo "<p style='color:green'>✓ Database 'simu_fashion' ready</p>";
    
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
    echo "<p style='color:green'>✓ Products table created</p>";
    
    // Create categories table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            image VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p style='color:green'>✓ Categories table created</p>";
    
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
    echo "<p style='color:green'>✓ Users table created</p>";
    
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
    echo "<p style='color:green'>✓ Orders table created</p>";
    
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
    echo "<p style='color:green'>✓ Order items table created</p>";
    
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
    echo "<p style='color:green'>✓ Cart table created</p>";
    
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
    echo "<p style='color:green'>✓ Contact messages table created</p>";
    
    // Insert sample products
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] == 0) {
        $products = [
            ["Classic Denim Jacket", "men", 89.99, 129.99, "img/img22.jpg", "Sale", "Premium quality denim jacket for men"],
            ["Elegant Summer Dress", "women", 79.99, 99.99, "img/g2.jpg", "New", "Beautiful summer dress for women"],
            ["Casual Polo Shirt", "men", 45.99, NULL, "img/g1.jpg", NULL, "Comfortable cotton polo shirt"],
            ["Leather Handbag", "accessories", 149.99, 199.99, "img/g11.JPG", "Hot", "Genuine leather handbag"],
            ["Running Sneakers", "men", 119.99, NULL, "img/back3.png", "New", "Comfortable running shoes"]
        ];
        
        $insert = $pdo->prepare("INSERT INTO products (name, category, price, old_price, image, badge, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($products as $p) {
            $insert->execute($p);
        }
        echo "<p style='color:green'>✓ Sample products added</p>";
    }
    
    // Insert sample categories
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
    if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] == 0) {
        $categories = [
            ["Men", "img/g1.jpg"],
            ["Women", "img/g11.JPG"],
            ["Accessories", "img/g3.JPG"],
            ["Footwear", "img/g0.JPG"]
        ];
        $insert = $pdo->prepare("INSERT INTO categories (name, image) VALUES (?, ?)");
        foreach ($categories as $c) {
            $insert->execute($c);
        }
        echo "<p style='color:green'>✓ Sample categories added</p>";
    }
    
    echo "<h3 style='color:green'>✓ All database tables created successfully!</h3>";
    echo "<p><strong>Your e-commerce site is now fully integrated with the database!</strong></p>";
    echo "<ul>";
    echo "<li>Products - stored in database</li>";
    echo "<li>Categories - stored in database</li>";
    echo "<li>Users - stored in database</li>";
    echo "<li>Orders - stored in database</li>";
    echo "<li>Cart - stored in database</li>";
    echo "<li>Contact Messages - stored in database</li>";
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>
