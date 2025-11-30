<?php
session_start();

// redirect if already logged in
if (isset($_SESSION['customer_id'])) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - K-Connect Ghana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --kpop-pink: #FF1493;
            --kpop-purple: #9D4EDD;
            --kpop-cyan: #00D9FF;
            --kpop-dark: #1a1a2e;
            --kpop-light: #FFE5F7;
        }
        
        body {
            background: linear-gradient(135deg, var(--kpop-pink) 0%, var(--kpop-purple) 50%, var(--kpop-cyan) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
        }
        
        .card {
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            border: none;
            overflow: hidden;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
            color: white;
            padding: 30px 20px;
            border: none;
            text-align: center;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: bold;
            font-size: 28px;
        }
        
        .card-header .subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .brand-logo {
            font-size: 48px;
            margin-bottom: 10px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .card-body {
            padding: 40px 30px;
        }
        
        .form-label {
            color: var(--kpop-dark);
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--kpop-pink);
            box-shadow: 0 0 0 0.2rem rgba(255, 20, 147, 0.25);
        }
        
        .password-container {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
            transition: color 0.3s;
        }
        
        .password-toggle:hover {
            color: var(--kpop-pink);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(255, 20, 147, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 20, 147, 0.4);
            background: linear-gradient(135deg, var(--kpop-purple), var(--kpop-pink));
        }
        
        .form-check-input:checked {
            background-color: var(--kpop-pink);
            border-color: var(--kpop-pink);
        }
        
        .card-footer {
            background-color: #f8f9fa;
            border-top: 2px solid #e9ecef;
            padding: 20px;
            text-align: center;
        }
        
        .card-footer a {
            color: var(--kpop-pink);
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        
        .card-footer a:hover {
            color: var(--kpop-purple);
            text-decoration: underline;
        }
        
        .kpop-icon {
            display: inline-block;
            margin: 0 3px;
            animation: bounce 1s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        .welcome-text {
            text-align: center;
            color: white;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .welcome-text h2 {
            font-weight: bold;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .welcome-text p {
            font-size: 16px;
            opacity: 0.95;
        }
        
        .alert-info {
            background: linear-gradient(135deg, var(--kpop-light), #fff);
            border: 2px solid var(--kpop-pink);
            border-radius: 10px;
            color: var(--kpop-dark);
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="welcome-text">
            <h2>
                <span class="kpop-icon">✨</span>
                K-Connect Ghana
                <span class="kpop-icon">💜</span>
            </h2>
            <p>Your Gateway to K-Pop Merch Across Ghana</p>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="brand-logo">🎵</div>
                <h4>Welcome Back!</h4>
                <div class="subtitle">Login to continue your K-Pop journey</div>
            </div>
            <div class="card-body">
                <form id="login-form">
                    <div class="mb-3">
                        <label for="customer_email" class="form-label">
                            <i class="fa fa-envelope me-2"></i>Email Address
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="customer_email" 
                               name="email" 
                               placeholder="your.email@example.com"
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="customer_pass" class="form-label">
                            <i class="fa fa-lock me-2"></i>Password
                        </label>
                        <div class="password-container">
                            <input type="password" 
                                   class="form-control" 
                                   id="customer_pass" 
                                   name="password" 
                                   placeholder="Enter your password"
                                   required>
                            <i class="fa fa-eye password-toggle" id="password-toggle"></i>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember_me">
                            <label class="form-check-label" for="remember_me">
                                Keep me logged in
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-sign-in-alt me-2"></i>Sign In to My Account
                    </button>
                </form>
                
                <div class="alert alert-info mt-4 mb-0">
                    <small>
                        <i class="fa fa-info-circle me-2"></i>
                        <strong>New to K-Connect?</strong> Get access to exclusive K-Pop merchandise from all your favorite groups, delivered to any region in Ghana!
                    </small>
                </div>
            </div>
            <div class="card-footer">
                Don't have an account? 
                <a href="register.php">
                    <i class="fa fa-user-plus me-1"></i>Register Now
                </a>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="../index.php" style="color: white; text-decoration: none;">
                <i class="fa fa-arrow-left me-2"></i>Back to Home
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/login.js"></script>
</body>
</html>