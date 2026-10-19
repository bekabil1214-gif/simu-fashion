<?php
/**
 * Cart API - Handle cart operations (add, remove, update, get)
 */

header('Content-Type: application/json');
require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Get session ID (using PHP session or generate one)
session_start();
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
}
$session_id = $_SESSION['session_id'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Get cart items
        getCart($conn, $session_id);
        break;
    case 'POST':
        // Add to cart
        $data = json_decode(file_get_contents("php://input"), true);
        addToCart($conn, $session_id, $data);
        break;
    case 'PUT':
        // Update cart item quantity
        $data = json_decode(file_get_contents("php://input"), true);
        updateCart($conn, $session_id, $data);
        break;
    case 'DELETE':
        // Remove from cart
        $data = json_decode(file_get_contents("php://input"), true);
        removeFromCart($conn, $session_id, $data);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

/**
 * Get cart items
 */
function getCart($conn, $session_id) {
    try {
        $stmt = $conn->prepare("
            SELECT c.id, c.product_id, c.quantity, p.name, p.price, p.image, p.old_price, p.badge
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.session_id = ?
        ");
        $stmt->bindParam(1, $session_id);
        $stmt->execute();
        
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format prices
        $total = 0;
        foreach ($items as &$item) {
            $item['price'] = floatval($item['price']);
            $item['old_price'] = $item['old_price'] ? floatval($item['old_price']) : null;
            $item['subtotal'] = $item['price'] * $item['quantity'];
            $total += $item['subtotal'];
        }
        
        echo json_encode([
            'success' => true,
            'data' => $items,
            'total' => $total,
            'count' => count($items)
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

/**
 * Add item to cart
 */
function addToCart($conn, $session_id, $data) {
    $product_id = isset($data['product_id']) ? intval($data['product_id']) : 0;
    $quantity = isset($data['quantity']) ? intval($data['quantity']) : 1;
    
    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        return;
    }
    
    try {
        // Check if product already in cart
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ?");
        $stmt->bindParam(1, $session_id);
        $stmt->bindParam(2, $product_id);
        $stmt->execute();
        
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            // Update quantity
            $new_qty = $existing['quantity'] + $quantity;
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->bindParam(1, $new_qty);
            $stmt->bindParam(2, $existing['id']);
            $stmt->execute();
        } else {
            // Insert new item
            $stmt = $conn->prepare("INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $session_id);
            $stmt->bindParam(2, $product_id);
            $stmt->bindParam(3, $quantity);
            $stmt->execute();
        }
        
        echo json_encode(['success' => true, 'message' => 'Product added to cart']);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

/**
 * Update cart item quantity
 */
function updateCart($conn, $session_id, $data) {
    $product_id = isset($data['product_id']) ? intval($data['product_id']) : 0;
    $quantity = isset($data['quantity']) ? intval($data['quantity']) : 1;
    
    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        return;
    }
    
    try {
        if ($quantity <= 0) {
            // Remove item if quantity is 0 or less
            $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ? AND product_id = ?");
            $stmt->bindParam(1, $session_id);
            $stmt->bindParam(2, $product_id);
            $stmt->execute();
            
            echo json_encode(['success' => true, 'message' => 'Product removed from cart']);
        } else {
            // Update quantity
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE session_id = ? AND product_id = ?");
            $stmt->bindParam(1, $quantity);
            $stmt->bindParam(2, $session_id);
            $stmt->bindParam(3, $product_id);
            $stmt->execute();
            
            echo json_encode(['success' => true, 'message' => 'Cart updated']);
        }
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

/**
 * Remove item from cart
 */
function removeFromCart($conn, $session_id, $data) {
    $product_id = isset($data['product_id']) ? intval($data['product_id']) : 0;
    
    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        return;
    }
    
    try {
        $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ? AND product_id = ?");
        $stmt->bindParam(1, $session_id);
        $stmt->bindParam(2, $product_id);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Product removed from cart']);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
