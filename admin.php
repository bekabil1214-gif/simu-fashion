<?php
require_once 'admin/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Simu Fashion Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin/style.css">
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>
    <div class="admin-container">
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Shop
        </a>
        
        <div class="success-message" id="successMessage">
            <i class="fas fa-check-circle"></i> <span id="successText">Operation completed successfully!</span>
        </div>
        
        <table class="products-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Old Price</th>
                    <th>Badge</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <!-- Products will be loaded here -->
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
            
            <form id="productForm" onsubmit="saveProduct(event)">
                <input type="hidden" id="productId">
                
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" id="productName" required placeholder="Enter product name">
                </div>
                
                <div class="form-group">
                    <label>Category</label>
                    <select id="productCategory" required>
                        <option value="">Select Category</option>
                        <option value="men">Men</option>
                        <option value="women">Women</option>
                        <option value="accessories">Accessories</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Price ($)</label>
                        <input type="number" id="productPrice" step="0.01" required placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Old Price ($) - Optional</label>
                        <input type="number" id="productOldPrice" step="0.01" placeholder="0.00">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Image</label>
                    <select id="productImage" onchange="previewImage()">
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
                    <select id="productBadge">
                        <option value="">No Badge</option>
                        <option value="Sale">Sale</option>
                        <option value="New">New</option>
                        <option value="Hot">Hot</option>
                        <option value="Luxury">Luxury</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="productDescription" rows="3" placeholder="Enter product description"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-save">Save Product</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Admin Products Management
        let adminProducts = [];
        
        // Load products on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAdminProducts();
        });
        
        // Load products from localStorage or use fallback
        function loadAdminProducts() {
            const stored = localStorage.getItem('admin_products');
            if (stored) {
                adminProducts = JSON.parse(stored);
            } else {
                // Initialize with default products
                adminProducts = getDefaultProducts();
                saveToStorage();
            }
            renderProductTable();
        }
        
        function getDefaultProducts() {
            return [
                { id: 1, name: "Classic Denim Jacket", category: "men", price: 89.99, old_price: 129.99, image: "img/img22.jpg", badge: "Sale", description: "Premium quality denim jacket" },
                { id: 2, name: "Elegant Summer Dress", category: "women", price: 79.99, old_price: 99.99, image: "img/g2.jpg", badge: "New", description: "Beautiful summer dress" },
                { id: 3, name: "Casual Polo Shirt", category: "men", price: 45.99, old_price: null, image: "img/g1.jpg", badge: null, description: "Comfortable cotton polo" },
                { id: 4, name: "Leather Handbag", category: "accessories", price: 149.99, old_price: 199.99, image: "img/g11.JPG", badge: "Hot", description: "Genuine leather handbag" },
                { id: 5, name: "Running Sneakers", category: "men", price: 119.99, old_price: null, image: "img/back3.png", badge: "New", description: "Comfortable running shoes" },
                { id: 6, name: "Floral Blouse", category: "women", price: 55.99, old_price: 75.99, image: "img/g1.jpg", badge: "Sale", description: "Beautiful floral blouse" },
                { id: 7, name: "Leather Belt", category: "accessories", price: 35.99, old_price: null, image: "img/g11.JPG", badge: null, description: "Genuine leather belt" },
                { id: 8, name: "Wool Sweater", category: "men", price: 89.99, old_price: 119.99, image: "img/g2.jpg", badge: "Sale", description: "Warm wool sweater" },
                { id: 9, name: "High Heels", category: "women", price: 99.99, old_price: null, image: "img/back3.png", badge: "New", description: "Elegant high heels" },
                { id: 10, name: "Sunglasses", category: "accessories", price: 25.99, old_price: 45.99, image: "img/g1.jpg", badge: "Sale", description: "Stylish sunglasses" },
                { id: 11, name: "Formal Trousers", category: "men", price: 65.99, old_price: null, image: "img/g2.jpg", badge: null, description: "Professional trousers" },
                { id: 12, name: "Evening Gown", category: "women", price: 199.99, old_price: 299.99, image: "img/img22.jpg", badge: "Luxury", description: "Stunning evening gown" }
            ];
        }
        
        function saveToStorage() {
            localStorage.setItem('admin_products', JSON.stringify(adminProducts));
            // Also update the main shop products
            localStorage.setItem('simu_products', JSON.stringify(adminProducts));
        }
        
        function renderProductTable() {
            const tbody = document.getElementById('productTableBody');
            tbody.innerHTML = '';
            
            adminProducts.forEach(product => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><img src="${product.image}" alt="${product.name}"></td>
                    <td class="product-name">${product.name}</td>
                    <td><span class="product-category category-${product.category}">${product.category}</span></td>
                    <td class="price-cell">$${parseFloat(product.price).toFixed(2)}</td>
                    <td class="old-price-cell">${product.old_price ? '$' + parseFloat(product.old_price).toFixed(2) : '-'}</td>
                    <td>${product.badge ? `<span class="product-badge">${product.badge}</span>` : '-'}</td>
                    <td class="action-btns">
                        <button class="btn-edit" onclick="editProduct(${product.id})"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn-delete" onclick="deleteProduct(${product.id})"><i class="fas fa-trash"></i> Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }
        
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Product';
            document.getElementById('productForm').reset();
            document.getElementById('productId').value = '';
            document.getElementById('imagePreview').classList.remove('show');
            document.getElementById('productModal').classList.add('show');
        }
        
        function editProduct(id) {
            const product = adminProducts.find(p => p.id === id);
            if (!product) return;
            
            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('productId').value = product.id;
            document.getElementById('productName').value = product.name;
            document.getElementById('productCategory').value = product.category;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productOldPrice').value = product.old_price || '';
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
                preview.src = imageSelect.value;
                preview.classList.add('show');
            } else {
                preview.classList.remove('show');
            }
        }
        
        function saveProduct(event) {
            event.preventDefault();
            
            const id = document.getElementById('productId').value;
            const productData = {
                name: document.getElementById('productName').value,
                category: document.getElementById('productCategory').value,
                price: parseFloat(document.getElementById('productPrice').value),
                old_price: document.getElementById('productOldPrice').value ? parseFloat(document.getElementById('productOldPrice').value) : null,
                image: document.getElementById('productImage').value,
                badge: document.getElementById('productBadge').value || null,
                description: document.getElementById('productDescription').value
            };
            
            if (id) {
                // Edit existing product
                const index = adminProducts.findIndex(p => p.id === parseInt(id));
                if (index !== -1) {
                    productData.id = parseInt(id);
                    adminProducts[index] = productData;
                }
            } else {
                // Add new product
                productData.id = Date.now();
                adminProducts.push(productData);
            }
            
            saveToStorage();
            renderProductTable();
            closeModal();
            showSuccess('Product saved successfully!');
        }
        
        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                adminProducts = adminProducts.filter(p => p.id !== id);
                saveToStorage();
                renderProductTable();
                showSuccess('Product deleted successfully!');
            }
        }
        
        function showSuccess(message) {
            const successDiv = document.getElementById('successMessage');
            document.getElementById('successText').textContent = message;
            successDiv.classList.add('show');
            setTimeout(() => {
                successDiv.classList.remove('show');
            }, 3000);
        }
        
        // Close modal when clicking outside
        document.getElementById('productModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
