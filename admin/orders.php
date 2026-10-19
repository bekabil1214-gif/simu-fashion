<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - Simu Fashion Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
        
        .admin-nav {
            list-style: none;
        }
        
        .admin-nav li {
            margin-bottom: 5px;
        }
        
        .admin-nav a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .admin-nav a:hover, .admin-nav a.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        
        .admin-nav a i {
            width: 25px;
            margin-right: 12px;
        }
        
        /* Main Content */
        .admin-main {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        
        .admin-header h1 {
            color: #1e8846;
            font-size: 32px;
        }
        
        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .filter-tab {
            padding: 10px 20px;
            border: none;
            background: white;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            color: #666;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        }
        
        .filter-tab:hover, .filter-tab.active {
            background: #1e8846;
            color: white;
        }
        
        .filter-tab .count {
            background: rgba(0,0,0,0.1);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            margin-left: 5px;
        }
        
        /* Table Styles */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .orders-table th,
        .orders-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .orders-table th {
            background-color: #1e8846;
            color: white;
            font-weight: 600;
        }
        
        .orders-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .order-number {
            font-weight: 600;
            color: #1e8846;
        }
        
        .order-total {
            font-weight: 600;
            color: #333;
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-badge.pending { background: #fff3e0; color: #f57c00; }
        .status-badge.processing { background: #e3f2fd; color: #1976d2; }
        .status-badge.shipped { background: #f3e5f5; color: #7b1fa2; }
        .status-badge.delivered { background: #e8f5e9; color: #388e3c; }
        .status-badge.cancelled { background: #ffebee; color: #c62828; }
        
        .action-btns {
            display: flex;
            gap: 8px;
        }
        
        .btn-view, .btn-status, .btn-delete {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-view {
            background-color: #17a2b8;
            color: white;
        }
        
        .btn-status {
            background-color: #ffc107;
            color: #333;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .modal.show {
            display: flex;
        }
        
        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            width: 600px;
            max-width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }
        
        .modal-header h2 {
            color: #1e8846;
            font-size: 24px;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }
        
        /* Order Details */
        .order-details {
            margin-bottom: 20px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        
        .detail-value {
            color: #333;
        }
        
        .order-items {
            margin-top: 20px;
        }
        
        .order-items h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .item-name {
            font-weight: 600;
        }
        
        .item-qty {
            color: #666;
        }
        
        .item-price {
            font-weight: 600;
            color: #1e8846;
        }
        
        /* Status Update */
        .status-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }
        
        .btn-save {
            background-color: #1e8846;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        
        .btn-cancel {
            background-color: #6c757d;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #1e8846;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .success-message.show {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 70px;
                padding: 10px;
            }
            
            .admin-logo span, .admin-nav a span {
                display: none;
            }
            
            .admin-nav a {
                justify-content: center;
                padding: 15px;
            }
            
            .admin-nav a i {
                margin: 0;
            }
            
            .admin-main {
                margin-left: 70px;
            }
            
            .orders-table {
                font-size: 12px;
            }
            
            .orders-table th, .orders-table td {
                padding: 8px 5px;
            }
        }
    </style>
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
                <a href="index.php">
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
                <a href="orders.php" class="active">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li>
                <a href="messages.php">
                    <i class="fas fa-envelope"></i>
                    <span>Messages</span>
                </a>
            </li>
            <li>
                <a href="../index.php">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Shop</span>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="admin-main">
        <?php
        require_once 'functions.php';
        
        $admin = new Admin();
        $message = '';
        
        // Handle status update
        if (isset($_POST['update_status'])) {
            $result = $admin->updateOrderStatus($_POST['order_id'], $_POST['status']);
            $message = $result['message'];
        }
        
        // Handle delete
        if (isset($_GET['delete'])) {
            $result = $admin->deleteOrder($_GET['delete']);
            $message = $result['message'];
            echo "<script>window.location.href='orders.php';</script>";
        }
        
        $orders = $admin->getOrders();
        
        // Filter counts
        $statusCounts = [
            'all' => count($orders),
            'pending' => 0,
            'processing' => 0,
            'shipped' => 0,
            'delivered' => 0,
            'cancelled' => 0
        ];
        
        foreach ($orders as $order) {
            if (isset($statusCounts[$order['status']])) {
                $statusCounts[$order['status']]++;
            }
        }
        
        // Current filter
        $currentFilter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
        
        // Filter orders
        if ($currentFilter !== 'all') {
            $orders = array_filter($orders, function($order) use ($currentFilter) {
                return $order['status'] === $currentFilter;
            });
        }
        ?>
        
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <div class="admin-header">
            <h1><i class="fas fa-shopping-cart"></i> Order Management</h1>
        </div>
        
        <?php if($message): ?>
            <div class="success-message show">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="?filter=all" class="filter-tab <?php echo $currentFilter === 'all' ? 'active' : ''; ?>">
                All <span class="count"><?php echo $statusCounts['all']; ?></span>
            </a>
            <a href="?filter=pending" class="filter-tab <?php echo $currentFilter === 'pending' ? 'active' : ''; ?>">
                Pending <span class="count"><?php echo $statusCounts['pending']; ?></span>
            </a>
            <a href="?filter=processing" class="filter-tab <?php echo $currentFilter === 'processing' ? 'active' : ''; ?>">
                Processing <span class="count"><?php echo $statusCounts['processing']; ?></span>
            </a>
            <a href="?filter=shipped" class="filter-tab <?php echo $currentFilter === 'shipped' ? 'active' : ''; ?>">
                Shipped <span class="count"><?php echo $statusCounts['shipped']; ?></span>
            </a>
            <a href="?filter=delivered" class="filter-tab <?php echo $currentFilter === 'delivered' ? 'active' : ''; ?>">
                Delivered <span class="count"><?php echo $statusCounts['delivered']; ?></span>
            </a>
            <a href="?filter=cancelled" class="filter-tab <?php echo $currentFilter === 'cancelled' ? 'active' : ''; ?>">
                Cancelled <span class="count"><?php echo $statusCounts['cancelled']; ?></span>
            </a>
        </div>
        
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($orders)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #999; padding: 30px;">No orders found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($orders as $order): ?>
                        <tr>
                            <td class="order-number"><?php echo htmlspecialchars($order['order_number']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($order['shipping_address'] ?: 'Guest'); ?></td>
                            <td><?php echo count($order['items']); ?> items</td>
                            <td class="order-total">$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($order['payment_method'])); ?></td>
                            <td>
                                <span class="status-badge <?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td class="action-btns">
                                <button class="btn-view" onclick="viewOrder(<?php echo htmlspecialchars(json_encode($order)); ?>)">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn-status" onclick="openStatusModal(<?php echo $order['id']; ?>, '<?php echo $order['status']; ?>')">
                                    <i class="fas fa-edit"></i> Status
                                </button>
                                <button class="btn-delete" onclick="deleteOrder(<?php echo $order['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- View Order Modal -->
    <div class="modal" id="viewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Order Details</h2>
                <button class="close-modal" onclick="closeViewModal()">&times;</button>
            </div>
            
            <div id="orderDetailsContent">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>
    
    <!-- Update Status Modal -->
    <div class="modal" id="statusModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Update Order Status</h2>
                <button class="close-modal" onclick="closeStatusModal()">&times;</button>
            </div>
            
            <form method="POST">
                <input type="hidden" name="order_id" id="statusOrderId">
                <input type="hidden" name="update_status" value="1">
                
                <div class="form-group">
                    <label>Select New Status</label>
                    <select name="status" id="statusSelect" class="status-select" required>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeStatusModal()">Cancel</button>
                    <button type="submit" class="btn-save">Update Status</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function viewOrder(order) {
            const content = document.getElementById('orderDetailsContent');
            
            let itemsHtml = '';
            order.items.forEach(item => {
                itemsHtml += `
                    <div class="item-row">
                        <div>
                            <div class="item-name">${item.product_name}</div>
                            <div class="item-qty">Qty: ${item.quantity}</div>
                        </div>
                        <div class="item-price">$${parseFloat(item.subtotal).toFixed(2)}</div>
                    </div>
                `;
            });
            
            content.innerHTML = `
                <div class="order-details">
                    <div class="detail-row">
                        <span class="detail-label">Order Number:</span>
                        <span class="detail-value">${order.order_number}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value">${new Date(order.created_at).toLocaleString()}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value"><span class="status-badge ${order.status}">${order.status}</span></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Shipping Address:</span>
                        <span class="detail-value">${order.shipping_address || 'Not provided'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Method:</span>
                        <span class="detail-value">${order.payment_method}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Notes:</span>
                        <span class="detail-value">${order.notes || 'None'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Total Amount:</span>
                        <span class="detail-value" style="font-size: 18px; font-weight: bold; color: #1e8846;">$${parseFloat(order.total_amount).toFixed(2)}</span>
                    </div>
                </div>
                
                <div class="order-items">
                    <h3>Order Items</h3>
                    ${itemsHtml}
                </div>
            `;
            
            document.getElementById('viewModal').classList.add('show');
        }
        
        function closeViewModal() {
            document.getElementById('viewModal').classList.remove('show');
        }
        
        function openStatusModal(orderId, currentStatus) {
            document.getElementById('statusOrderId').value = orderId;
            document.getElementById('statusSelect').value = currentStatus;
            document.getElementById('statusModal').classList.add('show');
        }
        
        function closeStatusModal() {
            document.getElementById('statusModal').classList.remove('show');
        }
        
        function deleteOrder(id) {
            if (confirm('Are you sure you want to delete this order?')) {
                window.location.href = '?delete=' + id;
            }
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const viewModal = document.getElementById('viewModal');
            const statusModal = document.getElementById('statusModal');
            
            if (event.target === viewModal) {
                closeViewModal();
            }
            if (event.target === statusModal) {
                closeStatusModal();
            }
        }
    </script>
</body>
</html>
