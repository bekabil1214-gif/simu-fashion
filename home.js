// ===================================
// Simu Fashion - E-commerce JavaScript
// ===================================

// ===================================
// API Configuration
// ===================================
const API_BASE = 'api';

// ===================================
// Default Products Data
// ===================================
const defaultProducts = [
    { id: 1, name: "Classic Denim Jacket", category: "men", price: 89.99, old_price: 129.99, image: "img/img22.jpg", badge: "Sale", description: "Premium quality denim jacket for men" },
    { id: 2, name: "Elegant Summer Dress", category: "women", price: 79.99, old_price: 99.99, image: "img/g2.jpg", badge: "New", description: "Beautiful summer dress for women" },
    { id: 3, name: "Casual Polo Shirt", category: "men", price: 45.99, old_price: null, image: "img/g1.jpg", badge: null, description: "Comfortable cotton polo shirt" },
    { id: 4, name: "Leather Handbag", category: "accessories", price: 149.99, old_price: 199.99, image: "img/g11.JPG", badge: "Hot", description: "Genuine leather handbag" },
    { id: 5, name: "Running Sneakers", category: "men", price: 119.99, old_price: null, image: "img/back3.png", badge: "New", description: "Comfortable running shoes" },
    { id: 6, name: "Floral Blouse", category: "women", price: 55.99, old_price: 75.99, image: "img/g1.jpg", badge: "Sale", description: "Beautiful floral print blouse" },
    { id: 7, name: "Leather Belt", category: "accessories", price: 35.99, old_price: null, image: "img/g11.JPG", badge: null, description: "Genuine leather belt" },
    { id: 8, name: "Wool Sweater", category: "men", price: 89.99, old_price: 119.99, image: "img/g2.jpg", badge: "Sale", description: "Warm wool sweater for winter" },
    { id: 9, name: "High Heels", category: "women", price: 99.99, old_price: null, image: "img/back3.png", badge: "New", description: "Elegant high heels for women" },
    { id: 10, name: "Sunglasses", category: "accessories", price: 25.99, old_price: 45.99, image: "img/g1.jpg", badge: "Sale", description: "Stylish sunglasses" },
    { id: 11, name: "Formal Trousers", category: "men", price: 65.99, old_price: null, image: "img/g2.jpg", badge: null, description: "Professional formal trousers" },
    { id: 12, name: "Evening Gown", category: "women", price: 199.99, old_price: 299.99, image: "img/img22.jpg", badge: "Luxury", description: "Stunning evening gown" }
];

// Get products from localStorage or use default
function getProducts() {
    const stored = localStorage.getItem('simu_products');
    if (stored) {
        return JSON.parse(stored);
    }
    return defaultProducts;
}

// ===================================
// Cart State
// ===================================
let cart = [];
let currentFilter = 'all';
let heroIndex = 0;

// Local cart for fallback (localStorage)
let localCart = [];

// ===================================
// DOM Elements
// ===================================
const productGrid = document.getElementById('productGrid');
const cartItems = document.getElementById('cartItems');
const cartCount = document.querySelector('.cart-count');
const cartTotal = document.getElementById('cartTotal');
const cartSidebar = document.getElementById('cartSidebar');
const cartOverlay = document.getElementById('cartOverlay');

// ===================================
// Initialize
// ===================================
document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    loadCart();
    loadLocalCart();
    updateLocalCartUI();
    initHeroSlider();
    initFilterButtons();
    initMobileMenu();
});

// ===================================
// API Functions
// ===================================

/**
 * Load products - prioritizes localStorage so admin changes appear immediately
 */
function loadProducts(category = null) {
    // First check localStorage for products (from admin panel)
    const localProducts = getProducts();
    
    if (localProducts && localProducts.length > 0) {
        // Filter by category if specified
        if (category && category !== 'all') {
            const filtered = localProducts.filter(p => p.category === category);
            renderProducts(filtered);
        } else {
            renderProducts(localProducts);
        }
        
        // Also try to fetch from API to potentially merge/update products
        fetchProductsFromAPI(category);
    } else {
        // No local products, try API
        fetchProductsFromAPI(category);
    }
}

/**
 * Fetch products from API and update localStorage
 */
async function fetchProductsFromAPI(category = null) {
    try {
        const url = category && category !== 'all' 
            ? `${API_BASE}/products.php?category=${category}`
            : `${API_BASE}/products.php`;
        
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success && result.data && result.data.length > 0) {
            // If API returns products and localStorage is empty, use API products
            const localProducts = getProducts();
            if (!localProducts || localProducts.length === 0) {
                renderProducts(result.data);
            }
            // Note: We don't overwrite localStorage with API data to preserve admin edits
        }
    } catch (error) {
        console.log('API not available, using localStorage products');
    }
}

/**
 * Load cart from database
 */
async function loadCart() {
    try {
        const response = await fetch(`${API_BASE}/cart.php`);
        const result = await response.json();
        
        if (result.success) {
            cart = result.data || [];
            updateCartUI();
        } else {
            console.error('Failed to load cart:', result.message);
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        // Use local cart
    }
}

/**
 * Add product to cart
 */
async function addToCart(productId) {
    try {
        const response = await fetch(`${API_BASE}/cart.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Product added to cart!', 'success');
            await loadCart();
        } else {
            // Fallback to local cart
            addToLocalCart(productId);
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        // Fallback: Add to local cart
        addToLocalCart(productId);
    }
}

/**
 * Update cart item quantity
 */
async function updateQuantity(productId, change) {
    try {
        const item = cart.find(item => item.product_id === productId);
        if (!item) {
            // Use local cart
            updateLocalQuantity(productId, change);
            return;
        }
        
        const newQty = item.quantity + change;
        
        const response = await fetch(`${API_BASE}/cart.php`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: newQty
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            await loadCart();
        } else {
            showToast(result.message || 'Failed to update cart', 'error');
        }
    } catch (error) {
        console.error('Error updating cart:', error);
        updateLocalQuantity(productId, change);
    }
}

/**
 * Remove item from cart
 */
async function removeFromCart(productId) {
    try {
        const response = await fetch(`${API_BASE}/cart.php`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            await loadCart();
            showToast('Item removed from cart', 'success');
        } else {
            // Use local cart
            removeFromLocalCart(productId);
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
        removeFromLocalCart(productId);
    }
}

// ===================================
// Local Cart Functions (Fallback)
// ===================================

function addToLocalCart(productId) {
    const products = getProducts();
    const product = products.find(p => p.id === productId);
    if (!product) {
        showToast('Product not found', 'error');
        return;
    }
    
    const existingItem = localCart.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity++;
    } else {
        localCart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            quantity: 1
        });
    }
    
    saveLocalCart();
    updateLocalCartUI();
    showToast('Product added to cart!', 'success');
}

function saveLocalCart() {
    localStorage.setItem('simu_cart', JSON.stringify(localCart));
}

function loadLocalCart() {
    const saved = localStorage.getItem('simu_cart');
    if (saved) {
        localCart = JSON.parse(saved);
    }
}

function updateLocalCartUI() {
    if (!cartCount) return;
    
    const totalItems = localCart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = totalItems;
    
    if (cartItems) {
        if (!localCart || localCart.length === 0) {
            cartItems.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is empty</p>
                </div>
            `;
        } else {
            cartItems.innerHTML = localCart.map(item => `
                <div class="cart-item">
                    <img src="${item.image}" alt="${item.name}">
                    <div class="cart-item-info">
                        <h4>${item.name}</h4>
                        <span class="cart-item-price">$${parseFloat(item.price).toFixed(2)}</span>
                    </div>
                    <div class="cart-item-quantity">
                        <button onclick="updateLocalQuantity(${item.id}, -1)"><i class="fas fa-minus"></i></button>
                        <span>${item.quantity}</span>
                        <button onclick="updateLocalQuantity(${item.id}, 1)"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
            `).join('');
        }
    }
    
    if (cartTotal) {
        const total = localCart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        cartTotal.textContent = '$' + total.toFixed(2);
    }
}

function updateLocalQuantity(productId, change) {
    const item = localCart.find(i => i.id === productId);
    if (!item) return;
    
    item.quantity += change;
    if (item.quantity <= 0) {
        localCart = localCart.filter(i => i.id !== productId);
    }
    
    saveLocalCart();
    updateLocalCartUI();
}

function removeFromLocalCart(productId) {
    localCart = localCart.filter(i => i.id !== productId);
    saveLocalCart();
    updateLocalCartUI();
    showToast('Item removed from cart', 'success');
}

// ===================================
// Render Functions
// ===================================

/**
 * Render products to grid
 */
function renderProducts(productsToRender) {
    if (!productGrid) return;
    
    productGrid.innerHTML = '';
    
    if (!productsToRender || productsToRender.length === 0) {
        productGrid.innerHTML = '<p style="text-align:center;grid-column:1/-1;">No products found</p>';
        if (document.getElementById('productCount')) {
            document.getElementById('productCount').textContent = 'No products found';
        }
        return;
    }
    
    // Update product count
    if (document.getElementById('productCount')) {
        const countText = productsToRender.length === 1 
            ? '1 product found' 
            : `${productsToRender.length} products found`;
        document.getElementById('productCount').textContent = countText;
    }
    
    productsToRender.forEach(product => {
        const productCard = document.createElement('div');
        productCard.className = 'product-card';
        
        const badge = product.badge ? `<span class="product-badge">${product.badge}</span>` : '';
        const oldPrice = product.old_price ? `<span class="old-price">$${parseFloat(product.old_price).toFixed(2)}</span>` : '';
        
        productCard.innerHTML = `
            <div class="product-image">
                <img src="${product.image}" alt="${product.name}">
                ${badge}
                <div class="product-actions">
                    <button onclick="quickView(${product.id})" title="Quick View">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="addToWishlist(${product.id})" title="Add to Wishlist">
                        <i class="fas fa-heart"></i>
                    </button>
                    <button onclick="addToCompare(${product.id})" title="Compare">
                        <i class="fas fa-balance-scale"></i>
                    </button>
                </div>
            </div>
            <div class="product-info">
                <span class="product-category">${product.category}</span>
                <h3 class="product-title">${product.name}</h3>
                <div class="product-price">
                    <span class="current-price">$${parseFloat(product.price).toFixed(2)}</span>
                    ${oldPrice}
                </div>
                <button class="add-to-cart" onclick="addToCart(${product.id})">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </div>
        `;
        
        productGrid.appendChild(productCard);
    });
}

/**
 * Update cart UI
 */
function updateCartUI() {
    // Update cart count
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    if (cartCount) {
        cartCount.textContent = totalItems;
    }
    
    // Update cart items
    if (cartItems) {
        if (!cart || cart.length === 0) {
            cartItems.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is empty</p>
                </div>
            `;
        } else {
            cartItems.innerHTML = cart.map(item => `
                <div class="cart-item">
                    <img src="${item.image || 'img/g1.jpg'}" alt="${item.name}">
                    <div class="cart-item-info">
                        <h4>${item.name}</h4>
                        <span class="cart-item-price">$${parseFloat(item.price).toFixed(2)}</span>
                    </div>
                    <div class="cart-item-quantity">
                        <button onclick="updateQuantity(${item.product_id}, -1)"><i class="fas fa-minus"></i></button>
                        <span>${item.quantity}</span>
                        <button onclick="updateQuantity(${item.product_id}, 1)"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
            `).join('');
        }
    }
    
    // Update total
    if (cartTotal) {
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        cartTotal.textContent = '$' + total.toFixed(2);
    }
}

// ===================================
// Filter Buttons
// ===================================
function initFilterButtons() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            currentFilter = btn.dataset.filter;
            loadProducts(currentFilter);
        });
    });
}

// ===================================
// Hero Slider
// ===================================
function initHeroSlider() {
    setInterval(() => {
        changeHeroSlide(1);
    }, 5000);
}

function changeHeroSlide(direction) {
    const slides = document.querySelectorAll('.hero-slide');
    if (slides.length === 0) return;
    
    slides[heroIndex].classList.remove('active');
    
    heroIndex = (heroIndex + direction + slides.length) % slides.length;
    
    slides[heroIndex].classList.add('active');
}

// ===================================
// Mobile Menu
// ===================================
function initMobileMenu() {
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    
    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
            
            if (navLinks.style.display === 'flex') {
                navLinks.style.flexDirection = 'column';
                navLinks.style.position = 'absolute';
                navLinks.style.top = '70px';
                navLinks.style.left = '0';
                navLinks.style.width = '100%';
                navLinks.style.backgroundColor = 'white';
                navLinks.style.padding = '20px';
                navLinks.style.boxShadow = '0 5px 10px rgba(0,0,0,0.1)';
            }
        });
    }
}

// ===================================
// Cart Toggle
// ===================================
function toggleCart() {
    const cartSidebar = document.getElementById('cartSidebar');
    const cartOverlay = document.getElementById('cartOverlay');
    
    if (cartSidebar && cartOverlay) {
        cartSidebar.classList.toggle('active');
        cartOverlay.classList.toggle('active');
    }
}

// ===================================
// Toast Notification
// ===================================
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    
    if (toast && toastMessage) {
        toastMessage.textContent = message;
        toast.className = 'toast ' + type;
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }
}

// ===================================
// Quick View
// ===================================
function quickView(productId) {
    showToast('Quick view coming soon!', 'info');
}

// ===================================
// Wishlist
// ===================================
function addToWishlist(productId) {
    showToast('Added to wishlist!', 'success');
}

// ===================================
// Compare
// ===================================
function addToCompare(productId) {
    showToast('Added to compare!', 'success');
}

// ===================================
// Search Function
// ===================================
function initSearch() {
    const searchInput = document.querySelector('.search-box input');
    const searchBtn = document.querySelector('.search-box button');
    
    if (searchInput && searchBtn) {
        searchBtn.addEventListener('click', () => {
            const query = searchInput.value.toLowerCase();
            if (!query) return;
            
            const filtered = fallbackProducts.filter(p => 
                p.name.toLowerCase().includes(query) || 
                p.category.toLowerCase().includes(query)
            );
            
            renderProducts(filtered);
            showToast(`Found ${filtered.length} products`, 'success');
        });
        
        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') {
                searchBtn.click();
            }
        });
    }
}

// Initialize search
document.addEventListener('DOMContentLoaded', initSearch);

// ===================================
// Contact Form Handler
// ===================================
function initContactForm() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const name = document.getElementById('contactName').value;
            const email = document.getElementById('contactEmail').value;
            const subject = document.getElementById('contactSubject').value;
            const message = document.getElementById('contactMessage').value;
            
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Sending...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch('api/contact.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        subject: subject,
                        message: message
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    contactForm.reset();
                    // Scroll to top to see the message
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                showToast('Failed to send message. Please try again.', 'error');
            }
            
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    }
}

// Initialize contact form
document.addEventListener('DOMContentLoaded', initContactForm);
