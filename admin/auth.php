<?php
/**
 * Admin Authentication Middleware
 * Include this file at the top of all admin pages to protect them
 */

// Check if user is logged in (for PHP-based sessions)
session_start();

$isLoggedIn = false;
$adminUser = '';

// Check PHP session first
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $isLoggedIn = true;
    $adminUser = isset($_SESSION['admin_user']) ? $_SESSION['admin_user'] : 'Admin';
}

// If not logged in via PHP session, check JavaScript/localStorage (for hybrid apps)
// We'll handle this via JavaScript redirect on the client side

if (!$isLoggedIn) {
    // For pure PHP pages, redirect to login
    // Note: This may not work for static HTML pages, so we also provide JS fallback
    ?>
    <script>
        // Check if logged in via localStorage/sessionStorage
        if (localStorage.getItem('admin_logged_in') !== 'true' && 
            sessionStorage.getItem('admin_logged_in') !== 'true') {
            window.location.href = '/admin/login.php';
        }
    </script>
    <?php
}

/**
 * Function to handle admin login (call this from login processing)
 */
function adminLogin($username) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user'] = $username;
    $_SESSION['login_time'] = time();
}

/**
 * Function to handle admin logout
 */
function adminLogout() {
    // Clear PHP session
    session_unset();
    session_destroy();
    
    // Clear localStorage and sessionStorage
    ?>
    <script>
        localStorage.removeItem('admin_logged_in');
        localStorage.removeItem('admin_user');
        sessionStorage.removeItem('admin_logged_in');
        sessionStorage.removeItem('admin_user');
    </script>
    <?php
}

/**
 * Check if admin is logged in (returns boolean for PHP)
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Get current admin username
 */
function getAdminUsername() {
    return isset($_SESSION['admin_user']) ? $_SESSION['admin_user'] : '';
}
?>
<!-- JavaScript-based auth check for all admin pages -->
<script>
    // Run on every page load
    (function() {
        const isLoggedIn = localStorage.getItem('admin_logged_in') === 'true' || 
                          sessionStorage.getItem('admin_logged_in') === 'true';
        
        // If not logged in and not on login page, redirect
        if (!isLoggedIn && !window.location.href.includes('login.php')) {
            window.location.href = '/admin/login.php';
        }
        
        // If logged in and on login page, redirect to dashboard
        if (isLoggedIn && window.location.href.includes('login.php')) {
            window.location.href = '/admin/index.php';
        }
    })();
    
    // Function to logout
    function adminLogout() {
        localStorage.removeItem('admin_logged_in');
        localStorage.removeItem('admin_user');
        sessionStorage.removeItem('admin_logged_in');
        window.location.href = '/admin/login.php';
    }
</script>
