<?php
/**
 * Orders API - Handle order creation and retrieval
 */

header('Content-Type: application/json');
require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Get session ID
session_start();
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
}
$session_id = $_SESSION['session_id'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        // Create new order
        $data = json_decode(file_get_contents("php://input"), true);
        createOrder($conn, $session_id, $data);
        break;
    case 'GET':
        // Get orders (for logged in users)
        getOrders($conn, $session_id);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

/**
 * Create new order
 */
function createOrder($conn, $session_id, $data) {
    try {
        // Get cart items
        $stmt = $conn->prepare("
            SELECT c.product_id, c.quantity, p.name, p.price
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.session_id = ?
        ");
        $stmt->bindParam(1, $session_id);
        $stmt->execute();
        
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($cart_items)) {
            echo json_encode(['success' => false, 'message' => 'Cart is empty']);
            return;
        }
        
        // Calculate total
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        // Generate order number
        $order_number = 'SIMU-' . date('Ymd') . '-' . rand(1000, 9999);
        
        // Get shipping address from data
        $shipping_address = isset($data['shipping_address']) ? $data['shipping_address'] : '';
        $payment_method = isset($data['payment_method']) ? $data['payment_method'] : 'cash';
        $notes = isset($data['notes']) ? $data['notes'] : '';
        
        // Insert order
        $stmt = $conn->prepare("
            INSERT INTO orders (session_id, order_number, total_amount, shipping_address, payment_method, notes, status)
            VALUES (?, ?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->bindParam(1, $session_id);
        $stmt->bindParam(2, $order_number);
        $stmt->bindParam(3, $total);
        $stmt->bindParam(4, $shipping_address);
        $stmt->bindParam(5, $payment_method);
        $stmt->bindParam(6, $notes);
        $stmt->execute();
        
        $order_id = $conn->lastInsertId();
        
        // Insert order items
        foreach ($cart_items as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            
            $stmt = $conn->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bindParam(1, $order_id);
            $stmt->bindParam(2, $item['product_id']);
            $stmt->bindParam(3, $item['name']);
            $stmt->bindParam(4, $item['price']);
            $stmt->bindParam(5, $item['quantity']);
            $stmt->bindParam(6, $subtotal);
            $stmt->execute();
        }
        
        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
        $stmt->bindParam(1, $session_id);
        $stmt->execute();
        
        echo json_encode([
            'success' => true,
            'message' => 'Order placed successfully',
            'order_number' => $order_number,
            'total' => $total
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

/**
 * Get orders
 */
function getOrders($conn, $session_id) {
    try {
        $stmt = $conn->prepare("
            SELECT * FROM orders 
            WHERE session_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->bindParam(1, $session_id);
        $stmt->execute();
        
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get order items for each order
        foreach ($orders as &$order) {
            $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $stmt->bindParam(1, $order['id']);
            $stmt->execute();
            $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $order['total_amount'] = floatval($order['total_amount']);
        }
        
        echo json_encode([
            'success' => true,
            'data' => $orders
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
