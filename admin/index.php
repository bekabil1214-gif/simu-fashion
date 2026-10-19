<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Simu Fashion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin/style.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="admin-logo">
            <i class="fas fa-store"></i>
            <span>Simu Fashion</span>
        </div>
        
        <ul class="admin-nav">
            <li>
                <a href="index.php" class="active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="products.php">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
            </li>
            <li>
                <a href="orders.php">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                    <?php if($pendingCount > 0): ?>
                        <span class="badge"><?php echo $pendingCount; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li>
                <a href="messages.php">
                    <i class="fas fa-envelope"></i>
                    <span>Messages</span>
                    <?php if($unreadCount > 0): ?>
                        <span class="badge"><?php echo $unreadCount; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li>
                <a href="../index.php">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Shop</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="adminLogout(); return false;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="admin-main">
        <?php
        require_once 'functions.php';
        
        $admin = new Admin();
        $stats = $admin->getStats();
        $recentOrders = $admin->getRecentOrders(5);
        $recentMessages = $admin->getRecentMessages(5);
        
        $pendingCount = $stats['pending_orders'];
        $unreadCount = $stats['unread_messages'];
        ?>
        
        <a href="../index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Shop
        </a>
        
        <div class="admin-header">
            <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
            <div class="admin-user">
                <i class="fas fa-user-circle"></i>
                <span>Administrator</span>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon products">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_products']; ?></h3>
                    <p>Total Products</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orders">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_orders']; ?></h3>
                    <p>Total Orders</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon revenue">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <h3>$<?php echo number_format($stats['total_revenue'], 2); ?></h3>
                    <p>Total Revenue</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon messages">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['unread_messages']; ?></h3>
                    <p>Unread Messages</p>
                </div>
            </div>
        </div>
        
        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Recent Orders -->
            <div class="content-card">
                <div class="card-header">
                    <h2><i class="fas fa-shopping-cart"></i> Recent Orders</h2>
                    <a href="orders.php">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($recentOrders)): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #999;">No orders yet</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($recentOrders as $order): ?>
                                    <tr>
                                        <td class="order-number"><?php echo htmlspecialchars($order['order_number']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                        <td class="amount">$<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $order['status']; ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Recent Messages -->
            <div class="content-card">
                <div class="card-header">
                    <h2><i class="fas fa-envelope"></i> Recent Messages</h2>
                    <a href="messages.php">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>From</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($recentMessages)): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #999;">No messages yet</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($recentMessages as $message): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($message['name']); ?></td>
                                        <td><?php echo htmlspecialchars($message['subject'] ?: 'No subject'); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($message['created_at'])); ?></td>
                                        <td>
                                            <?php if($message['status'] == 'unread'): ?>
                                                <span class="message-badge"></span> Unread
                                            <?php else: ?>
                                                <span class="message-badge read"></span> Read
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2 style="margin-bottom: 15px; color: #333;"><i class="fas fa-bolt"></i> Quick Actions</h2>
            <div class="actions-grid">
                <a href="products.php?action=add" class="action-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add New Product</span>
                </a>
                <a href="orders.php" class="action-btn">
                    <i class="fas fa-list"></i>
                    <span>Manage Orders</span>
                </a>
                <a href="messages.php" class="action-btn">
                    <i class="fas fa-inbox"></i>
                    <span>View Messages</span>
                </a>
                <a href="../index.php" class="action-btn">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Visit Shop</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
