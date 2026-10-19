<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Simu Fashion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e8846 0%, #0d4a2e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 400px;
            max-width: 90%;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header i {
            font-size: 48px;
            color: #1e8846;
            margin-bottom: 15px;
        }
        
        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 8px;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            border-color: #1e8846;
            outline: none;
        }
        
        .form-group .input-icon {
            position: relative;
        }
        
        .form-group .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        
        .form-group .input-icon input {
            padding-left: 40px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .remember-me input {
            margin-right: 8px;
        }
        
        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: #1e8846;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .login-btn:hover {
            background-color: #0d4a2e;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .error-message.show {
            display: block;
        }
        
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
        }
        
        .back-link:hover {
            color: #1e8846;
        }
        
        .demo-credentials {
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-store-alt"></i>
            <h1>Admin Login</h1>
            <p>Enter your credentials to access the admin panel</p>
        </div>
        
        <div class="error-message" id="errorMessage">
            <i class="fas fa-exclamation-circle"></i> 
            <span id="errorText">Invalid username or password</span>
        </div>
        
        <form id="loginForm" onsubmit="handleLogin(event)">
            <div class="form-group">
                <label>Username</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" placeholder="Enter username" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" placeholder="Enter password" required>
                </div>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember">
                <label for="remember">Remember me</label>
            </div>
            
            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <a href="../index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Shop
        </a>
    </div>
    
    <script>
        // Check if already logged in
        if (localStorage.getItem('admin_logged_in') === 'true') {
            window.location.href = 'index.php';
        }
        
        function handleLogin(event) {
            event.preventDefault();
            
            // Simple validation - replace with proper database authentication
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;
            
            // Check if credentials are provided
            if (username && password) {
                // Login successful
                if (remember) {
                    localStorage.setItem('admin_logged_in', 'true');
                    localStorage.setItem('admin_user', username);
                    sessionStorage.setItem('admin_logged_in', 'true');
                    sessionStorage.setItem('admin_user', username);
                } else {
                    sessionStorage.setItem('admin_logged_in', 'true');
                    sessionStorage.setItem('admin_user', username);
                }
                
                window.location.href = 'index.php';
            } else {
                // Login failed
                document.getElementById('errorMessage').classList.add('show');
                setTimeout(() => {
                    document.getElementById('errorMessage').classList.remove('show');
                }, 3000);
            }
        }
    </script>
</body>
</html>
