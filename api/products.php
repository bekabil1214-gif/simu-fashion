<?php
/**
 * Products API - Get all products or filter by category
 */

header('Content-Type: application/json');
require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Get category filter from query string
$category = isset($_GET['category']) ? $_GET['category'] : null;

try {
    if ($category && $category !== 'all') {
        $stmt = $conn->prepare("SELECT * FROM products WHERE category = ? ORDER BY created_at DESC");
        $stmt->bindParam(1, $category);
        $stmt->execute();
    } else {
        $stmt = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
    }
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format prices
    foreach ($products as &$product) {
        $product['price'] = floatval($product['price']);
        $product['old_price'] = $product['old_price'] ? floatval($product['old_price']) : null;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $products
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
