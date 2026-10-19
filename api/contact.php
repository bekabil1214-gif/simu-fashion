<?php
/**
 * Contact API - Handle contact form submissions
 */

header('Content-Type: application/json');
require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $name = isset($data['name']) ? trim($data['name']) : '';
    $email = isset($data['email']) ? trim($data['email']) : '';
    $subject = isset($data['subject']) ? trim($data['subject']) : '';
    $message = isset($data['message']) ? trim($data['message']) : '';
    
    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Please fill in all required fields'
        ]);
        return;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Please enter a valid email address'
        ]);
        return;
    }
    
    try {
        $stmt = $conn->prepare("
            INSERT INTO contact_messages (name, email, subject, message, status)
            VALUES (?, ?, ?, ?, 'unread')
        ");
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $subject);
        $stmt->bindParam(4, $message);
        $stmt->execute();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Message sent successfully! We will get back to you soon.'
        ]);
        
    } catch(PDOException $e) {
        // For debugging - show actual error
        $errorMsg = $e->getMessage();
        
        // Check if table doesn't exist
        if (strpos($errorMsg, 'contact_messages') !== false || strpos($errorMsg, "Table 'simu_fashion.contact_messages' doesn't exist") !== false) {
            echo json_encode([
                'success' => false, 
                'message' => 'Database not set up. Please run setup_database.php first.'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Error: ' . $errorMsg
            ]);
        }
    }
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid request method'
    ]);
}
?>
