<?php
/**
 * Admin Functions - Shared functions for the admin panel
 */

require_once '../config/database.php';

class Admin {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    /**
     * Get dashboard statistics
     */
    public function getStats() {
        $stats = [
            'total_products' => 0,
            'total_orders' => 0,
            'total_revenue' => 0,
            'pending_orders' => 0,
            'unread_messages' => 0
        ];
        
        try {
            // Total products
            $stmt = $this->conn->query("SELECT COUNT(*) as count FROM products");
            $stats['total_products'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Total orders
            $stmt = $this->conn->query("SELECT COUNT(*) as count FROM orders");
            $stats['total_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Total revenue
            $stmt = $this->conn->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE status != 'cancelled'");
            $stats['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Pending orders
            $stmt = $this->conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
            $stats['pending_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Unread messages
            $stmt = $this->conn->query("SELECT COUNT(*) as count FROM contact_messages WHERE status = 'unread'");
            $stats['unread_messages'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
        } catch(PDOException $e) {
            // Return default stats if error
        }
        
        return $stats;
    }
    
    /**
     * Get all products
     */
    public function getProducts() {
        $products = [];
        try {
            $stmt = $this->conn->query("SELECT * FROM products ORDER BY created_at DESC");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // Return empty array
        }
        return $products;
    }
    
    /**
     * Get single product
     */
    public function getProduct($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    /**
     * Add new product
     */
    public function addProduct($data) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO products (name, category, price, old_price, image, badge, description, stock)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bindParam(1, $data['name']);
            $stmt->bindParam(2, $data['category']);
            $stmt->bindParam(3, $data['price']);
            $stmt->bindParam(4, $data['old_price']);
            $stmt->bindParam(5, $data['image']);
            $stmt->bindParam(6, $data['badge']);
            $stmt->bindParam(7, $data['description']);
            $stmt->bindParam(8, $data['stock']);
            $stmt->execute();
            return ['success' => true, 'message' => 'Product added successfully'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Update product
     */
    public function updateProduct($id, $data) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE products 
                SET name = ?, category = ?, price = ?, old_price = ?, image = ?, badge = ?, description = ?, stock = ?
                WHERE id = ?
            ");
            $stmt->bindParam(1, $data['name']);
            $stmt->bindParam(2, $data['category']);
            $stmt->bindParam(3, $data['price']);
            $stmt->bindParam(4, $data['old_price']);
            $stmt->bindParam(5, $data['image']);
            $stmt->bindParam(6, $data['badge']);
            $stmt->bindParam(7, $data['description']);
            $stmt->bindParam(8, $data['stock']);
            $stmt->bindParam(9, $id);
            $stmt->execute();
            return ['success' => true, 'message' => 'Product updated successfully'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Delete product
     */
    public function deleteProduct($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return ['success' => true, 'message' => 'Product deleted successfully'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Get all orders
     */
    public function getOrders() {
        $orders = [];
        try {
            $stmt = $this->conn->query("SELECT * FROM orders ORDER BY created_at DESC");
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get order items for each order
            foreach ($orders as &$order) {
                $stmt = $this->conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
                $stmt->bindParam(1, $order['id']);
                $stmt->execute();
                $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch(PDOException $e) {
            // Return empty array
        }
        return $orders;
    }
    
    /**
     * Get single order
     */
    public function getOrder($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($order) {
                $stmt = $this->conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
                $stmt->bindParam(1, $order['id']);
                $stmt->execute();
                $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $order;
        } catch(PDOException $e) {
            return false;
        }
    }
    
    /**
     * Update order status
     */
    public function updateOrderStatus($id, $status) {
        try {
            $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->bindParam(1, $status);
            $stmt->bindParam(2, $id);
            $stmt->execute();
            return ['success' => true, 'message' => 'Order status updated successfully'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Delete order
     */
    public function deleteOrder($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM orders WHERE id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return ['success' => true, 'message' => 'Order deleted successfully'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Get all contact messages
     */
    public function getMessages() {
        $messages = [];
        try {
            $stmt = $this->conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // Return empty array
        }
        return $messages;
    }
    
    /**
     * Get single message
     */
    public function getMessage($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM contact_messages WHERE id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    /**
     * Mark message as read
     */
    public function markMessageRead($id) {
        try {
            $stmt = $this->conn->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return ['success' => true, 'message' => 'Message marked as read'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Delete message
     */
    public function deleteMessage($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM contact_messages WHERE id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return ['success' => true, 'message' => 'Message deleted successfully'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Get recent orders for dashboard
     */
    public function getRecentOrders($limit = 5) {
        $orders = [];
        try {
            $stmt = $this->conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT $limit");
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // Return empty array
        }
        return $orders;
    }
    
    /**
     * Get recent messages for dashboard
     */
    public function getRecentMessages($limit = 5) {
        $messages = [];
        try {
            $stmt = $this->conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT $limit");
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // Return empty array
        }
        return $messages;
    }
}
?>
