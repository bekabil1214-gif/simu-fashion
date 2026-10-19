<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Simu Fashion Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Page-specific styles for Products */
        .admin-header h1 {
            color: #1e8846;
            font-size: 32px;
        }
        
        .product-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            background: #1e8846;
            color: white;
        }
        
        .btn-view {
            background-color: #17a2b8;
            color: white;
        }
        
        /* Modal overrides */
        .modal-content {
            width: 600px;
        }
        
        .badge-select {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        
        .badge-option {
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .badge-option:hover,
        .badge-option.selected {
            border-color: #1e8846;
            background-color: #e8f5e9;
        }
        
        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
            display: none;
        }
        
        .image-preview.show {
            display: block;
        }
    </style>
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        
        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
            display: none;
        }
        
        .image-preview.show {
            display: block;
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

        /* Badge styles */
        .product-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge-sale { background-color: #ffebee; color: #c62828; }
        .badge-new { background-color: #e3f2fd; color: #1976d2; }
        .badge-hot { background-color: #fff3e0; color: #f57c00; }
        .badge-luxury { background-color: #f3e5f5; color: #7b1fa2; }
        
        .no-badge { color: #999; font-size: 12px; }
        
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
            
            .products-table {
                font-size: 14px;
            }
            
            .products-table th, .products-table td {
                padding: 10px 8px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
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
                <a href="products.php" class="active">
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
        
        // Handle form submissions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                $data = [
                    'name' => $_POST['name'],
                    'category' => $_POST['category'],
                    'price' => $_POST['price'],
                    'old_price' => !empty($_POST['old_price']) ? $_POST['old_price'] : null,
                    'image' => $_POST['image'],
                    'badge' => !empty($_POST['badge']) ? $_POST['badge'] : null,
                    'description' => $_POST['description'],
                    'stock' => !empty($_POST['stock']) ? $_POST['stock'] : 100
                ];
                
                if ($_POST['action'] === 'add') {
                    $result = $admin->addProduct($data);
                    $message = $result['message'];
                } elseif ($_POST['action'] === 'edit' && isset($_POST['id'])) {
                    $result = $admin->updateProduct($_POST['id'], $data);
                    $message = $result['message'];
                }
            }
        }
        
        // Handle delete
        if (isset($_GET['delete'])) {
            $result = $admin->deleteProduct($_GET['delete']);
            $message = $result['message'];
            echo "<script>window.location.href='products.php';</script>";
        }
        
        $products = $admin->getProducts();
        ?>
        
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <div class="admin-header">
            <h1><i class="fas fa-box"></i> Product Management</h1>
            <div class="admin-actions">
                <button class="btn-add" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add New Product
                </button>
            </div>
        </div>
        
        <?php if($message): ?>
            <div class="success-message show">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <table class="products-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Old Price</th>
                    <th>Badge</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($products)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #999; padding: 30px;">No products found. Add your first product!</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($products as $product): ?>
                        <tr>
                            <td><img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></td>
                            <td class="product-name"><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><span class="product-category category-<?php echo htmlspecialchars($product['category']); ?>"><?php echo htmlspecialchars($product['category']); ?></span></td>
                            <td class="price-cell">$<?php echo number_format($product['price'], 2); ?></td>
                            <td class="old-price-cell"><?php echo $product['old_price'] ? '$' . number_format($product['old_price'], 2) : '-'; ?></td>
                            <td>
                                <?php if($product['badge']): ?>
                                    <span class="product-badge badge-<?php echo strtolower($product['badge']); ?>"><?php echo htmlspecialchars($product['badge']); ?></span>
                                <?php else: ?>
                                    <span class="no-badge">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $product['stock']; ?></td>
                            <td class="action-btns">
                                <button class="btn-edit" onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn-delete" onclick="deleteProduct(<?php echo $product['id']; ?>)"><i class="fas fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Add/Edit Modal -->
    <div class="modal" id="productModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Product</h2>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            
            <form id="productForm" method="POST">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="productId">
                
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" id="productName" required placeholder="Enter product name">
                </div>
                
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" id="productCategory" required>
                        <option value="">Select Category</option>
                        <option value="men">Men</option>
                        <option value="women">Women</option>
                        <option value="accessories">Accessories</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Price ($)</label>
                        <input type="number" name="price" id="productPrice" step="0.01" required placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Old Price ($) - Optional</label>
                        <input type="number" name="old_price" id="productOldPrice" step="0.01" placeholder="0.00">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock" id="productStock" value="100" placeholder="100">
                </div>
                
                <div class="form-group">
                    <label>Image</label>
                    <select name="image" id="productImage" onchange="previewImage()">
                        <option value="">Select Image</option>
                        <option value="img/img22.jpg">img22.jpg (Main)</option>
                        <option value="img/g2.jpg">g2.jpg</option>
                        <option value="img/g1.jpg">g1.jpg</option>
                        <option value="img/g11.JPG">g11.JPG</option>
                        <option value="img/back3.png">back3.png</option>
                        <option value="img/b1.JPG">b1.JPG</option>
                        <option value="img/b2.JPG">b2.JPG</option>
                        <option value="img/b3.JPG">b3.JPG</option>
                        <option value="img/b4.JPG">b4.JPG</option>
                        <option value="img/b5.JPG">b5.JPG</option>
                        <option value="img/g0.JPG">g0.JPG</option>
                        <option value="img/g3.JPG">g3.JPG</option>
                        <option value="img/img1.jpg">img1.jpg</option>
                    </select>
                    <img id="imagePreview" class="image-preview" alt="Preview">
                </div>
                
                <div class="form-group">
                    <label>Badge (Optional)</label>
                    <select name="badge" id="productBadge">
                        <option value="">No Badge</option>
                        <option value="Sale">Sale</option>
                        <option value="New">New</option>
                        <option value="Hot">Hot</option>
                        <option value="Luxury">Luxury</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="productDescription" rows="3" placeholder="Enter product description"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-save">Save Product</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Product';
            document.getElementById('formAction').value = 'add';
            document.getElementById('productForm').reset();
            document.getElementById('productId').value = '';
            document.getElementById('imagePreview').classList.remove('show');
            document.getElementById('productModal').classList.add('show');
        }
        
        function editProduct(product) {
            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('productId').value = product.id;
            document.getElementById('productName').value = product.name;
            document.getElementById('productCategory').value = product.category;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productOldPrice').value = product.old_price || '';
            document.getElementById('productStock').value = product.stock || 100;
            document.getElementById('productImage').value = product.image;
            document.getElementById('productBadge').value = product.badge || '';
            document.getElementById('productDescription').value = product.description || '';
            
            previewImage();
            document.getElementById('productModal').classList.add('show');
        }
        
        function closeModal() {
            document.getElementById('productModal').classList.remove('show');
        }
        
        function previewImage() {
            const imageSelect = document.getElementById('productImage');
            const preview = document.getElementById('imagePreview');
            
            if (imageSelect.value) {
                preview.src = '../' + imageSelect.value;
                preview.classList.add('show');
            } else {
                preview.classList.remove('show');
            }
        }
        
        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                window.location.href = '?delete=' + id;
            }
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('productModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
