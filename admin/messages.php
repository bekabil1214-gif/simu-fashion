<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Simu Fashion Admin</title>
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
            text-decoration: none;
        }
        
        .filter-tab:hover, .filter-tab.active {
            background: #1e8846;
            color: white;
        }
        
        /* Messages List */
        .messages-container {
            display: grid;
            gap: 15px;
        }
        
        .message-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        
        .message-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        }
        
        .message-card.unread {
            border-left-color: #1e8846;
            background: #f8fdf8;
        }
        
        .message-card.read {
            border-left-color: #ccc;
        }
        
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        
        .message-sender {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sender-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1e8846;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .sender-info h3 {
            font-size: 16px;
            color: #333;
        }
        
        .sender-info p {
            font-size: 13px;
            color: #666;
        }
        
        .message-meta {
            text-align: right;
        }
        
        .message-date {
            font-size: 13px;
            color: #999;
        }
        
        .message-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 5px;
        }
        
        .message-badge.unread {
            background: #1e8846;
            color: white;
        }
        
        .message-badge.read {
            background: #e0e0e0;
            color: #666;
        }
        
        .message-subject {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .message-preview {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .message-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn-reply, .btn-delete, .btn-mark {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-reply {
            background: #1e8846;
            color: white;
        }
        
        .btn-mark {
            background: #17a2b8;
            color: white;
        }
        
        .btn-delete {
            background: #dc3545;
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
        
        .full-message-content {
            line-height: 1.8;
            color: #333;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 25px;
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
                <a href="orders.php">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li>
                <a href="messages.php" class="active">
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
        
        // Handle mark as read
        if (isset($_GET['mark_read'])) {
            $result = $admin->markMessageRead($_GET['mark_read']);
            $message = $result['message'];
        }
        
        // Handle delete
        if (isset($_GET['delete'])) {
            $result = $admin->deleteMessage($_GET['delete']);
            $message = $result['message'];
            echo "<script>window.location.href='messages.php';</script>";
        }
        
        $allMessages = $admin->getMessages();
        
        // Count by status
        $unreadCount = 0;
        $readCount = 0;
        foreach ($allMessages as $msg) {
            if ($msg['status'] === 'unread') {
                $unreadCount++;
            } else {
                $readCount++;
            }
        }
        
        // Current filter
        $currentFilter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
        
        // Filter messages
        $messages = $allMessages;
        if ($currentFilter === 'unread') {
            $messages = array_filter($messages, function($msg) {
                return $msg['status'] === 'unread';
            });
        } elseif ($currentFilter === 'read') {
            $messages = array_filter($messages, function($msg) {
                return $msg['status'] === 'read';
            });
        }
        ?>
        
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <div class="admin-header">
            <h1><i class="fas fa-envelope"></i> Contact Messages</h1>
        </div>
        
        <?php if($message): ?>
            <div class="success-message show">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="?filter=all" class="filter-tab <?php echo $currentFilter === 'all' ? 'active' : ''; ?>">
                All Messages (<?php echo count($allMessages); ?>)
            </a>
            <a href="?filter=unread" class="filter-tab <?php echo $currentFilter === 'unread' ? 'active' : ''; ?>">
                Unread (<?php echo $unreadCount; ?>)
            </a>
            <a href="?filter=read" class="filter-tab <?php echo $currentFilter === 'read' ? 'active' : ''; ?>">
                Read (<?php echo $readCount; ?>)
            </a>
        </div>
        
        <!-- Messages List -->
        <div class="messages-container">
            <?php if(empty($messages)): ?>
                <div style="text-align: center; color: #999; padding: 40px; background: white; border-radius: 12px;">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <p>No messages found</p>
                </div>
            <?php else: ?>
                <?php foreach($messages as $msg): ?>
                    <div class="message-card <?php echo $msg['status']; ?>" onclick="viewMessage(<?php echo htmlspecialchars(json_encode($msg)); ?>)">
                        <div class="message-header">
                            <div class="message-sender">
                                <div class="sender-avatar">
                                    <?php echo strtoupper(substr($msg['name'], 0, 1)); ?>
                                </div>
                                <div class="sender-info">
                                    <h3><?php echo htmlspecialchars($msg['name']); ?></h3>
                                    <p><?php echo htmlspecialchars($msg['email']); ?></p>
                                </div>
                            </div>
                            <div class="message-meta">
                                <div class="message-date"><?php echo date('M d, Y g:i A', strtotime($msg['created_at'])); ?></div>
                                <span class="message-badge <?php echo $msg['status']; ?>">
                                    <?php echo ucfirst($msg['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="message-subject">
                            <?php echo htmlspecialchars($msg['subject'] ?: 'No Subject'); ?>
                        </div>
                        
                        <div class="message-preview">
                            <?php echo htmlspecialchars($msg['message']); ?>
                        </div>
                        
                        <div class="message-actions" onclick="event.stopPropagation();">
                            <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" class="btn-reply">
                                <i class="fas fa-reply"></i> Reply
                            </a>
                            <?php if($msg['status'] === 'unread'): ?>
                                <a href="?mark_read=<?php echo $msg['id']; ?>" class="btn-mark">
                                    <i class="fas fa-check"></i> Mark Read
                                </a>
                            <?php endif; ?>
                            <button class="btn-delete" onclick="deleteMessage(<?php echo $msg['id']; ?>)">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- View Message Modal -->
    <div class="modal" id="messageModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Message Details</h2>
                <button class="close-modal" onclick="closeMessageModal()">&times;</button>
            </div>
            
            <div id="messageContent">
                <!-- Content loaded dynamically -->
            </div>
            
            <div class="form-actions" id="messageActions">
                <!-- Actions loaded dynamically -->
            </div>
        </div>
    </div>
    
    <script>
        function viewMessage(msg) {
            const content = document.getElementById('messageContent');
            const actions = document.getElementById('messageActions');
            
            content.innerHTML = `
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <div class="sender-avatar" style="width: 50px; height: 50px; font-size: 20px;">
                            ${msg.name.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <h3 style="margin-bottom: 5px;">${msg.name}</h3>
                            <p style="color: #666; font-size: 14px;">${msg.email}</p>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 15px; padding: 10px; background: #f8f9fa; border-radius: 8px;">
                        <strong>Subject:</strong> ${msg.subject || 'No Subject'}
                    </div>
                    
                    <div style="margin-bottom: 15px; color: #999; font-size: 13px;">
                        Received: ${new Date(msg.created_at).toLocaleString()}
                    </div>
                    
                    <div class="full-message-content" style="padding: 20px; background: #fafafa; border-radius: 8px; border-left: 4px solid #1e8846;">
                        ${msg.message.replace(/\n/g, '<br>')}
                    </div>
                </div>
            `;
            
            actions.innerHTML = `
                <a href="mailto:${msg.email}" class="btn-save">
                    <i class="fas fa-reply"></i> Reply via Email
                </a>
                ${msg.status === 'unread' ? `<a href="?mark_read=${msg.id}" class="btn-cancel">Mark as Read</a>` : ''}
                <button class="btn-delete" onclick="deleteMessage(${msg.id})">Delete</button>
            `;
            
            document.getElementById('messageModal').classList.add('show');
        }
        
        function closeMessageModal() {
            document.getElementById('messageModal').classList.remove('show');
        }
        
        function deleteMessage(id) {
            if (confirm('Are you sure you want to delete this message?')) {
                window.location.href = '?delete=' + id;
            }
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('messageModal');
            if (event.target === modal) {
                closeMessageModal();
            }
        }
    </script>
</body>
</html>
