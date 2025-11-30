<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - K-Connect Ghana</title>
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
            padding: 40px 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .register-container {
            max-width: 700px;
            margin: 0 auto;
        }
        
        .card {
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            border: none;
            overflow: hidden;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            margin-bottom: 30px;
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
            animation: spin 3s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .card-body {
            padding: 40px 30px;
        }
        
        .form-label {
            color: var(--kpop-dark);
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
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
        
        .form-select {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
        }
        
        .form-select:focus {
            border-color: var(--kpop-pink);
            box-shadow: 0 0 0 0.2rem rgba(255, 20, 147, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
            border: none;
            border-radius: 10px;
            padding: 14px 30px;
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
        
        .form-check-input[type="radio"]:checked {
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
        
        .welcome-text {
            text-align: center;
            color: white;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .welcome-text h2 {
            font-weight: bold;
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .welcome-text p {
            font-size: 16px;
            opacity: 0.95;
        }
        
        .kpop-icon {
            display: inline-block;
            margin: 0 3px;
        }
        
        .benefits-section {
            background: linear-gradient(135deg, var(--kpop-light), #fff);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px solid var(--kpop-pink);
        }
        
        .benefits-section h6 {
            color: var(--kpop-pink);
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .benefit-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: var(--kpop-dark);
        }
        
        .benefit-item i {
            color: var(--kpop-pink);
            margin-right: 10px;
            font-size: 18px;
        }
        
        .account-type-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .account-type-card:hover {
            border-color: var(--kpop-pink);
            background-color: var(--kpop-light);
        }
        
        .account-type-card.selected {
            border-color: var(--kpop-pink);
            background-color: var(--kpop-light);
            box-shadow: 0 0 15px rgba(255, 20, 147, 0.3);
        }
        
        .ghana-regions {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .required-star {
            color: var(--kpop-pink);
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="welcome-text">
            <h2>
                <span class="kpop-icon">🎤</span>
                Join K-Connect Ghana
                <span class="kpop-icon">💖</span>
            </h2>
            <p>Start Your K-Pop Collection Journey Today!</p>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="brand-logo">💿</div>
                <h4>Create Your Account</h4>
                <div class="subtitle">Get exclusive access to K-Pop merchandise nationwide</div>
            </div>
            <div class="card-body">
                <!-- Benefits Section -->
                <div class="benefits-section">
                    <h6><i class="fa fa-star me-2"></i>Why Join K-Connect Ghana?</h6>
                    <div class="benefit-item">
                        <i class="fa fa-map-marked-alt"></i>
                        <span><strong>Nationwide Delivery:</strong> All 16 regions of Ghana for just GH₵25</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fa fa-users"></i>
                        <span><strong>50+ K-Pop Groups:</strong> BTS, BLACKPINK, SEVENTEEN & more</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fa fa-box-open"></i>
                        <span><strong>500+ Products:</strong> Albums, lightsticks, photocards & merch</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fa fa-credit-card"></i>
                        <span><strong>Flexible Payment:</strong> Mobile Money & installment plans available</span>
                    </div>
                </div>
                
                <form id="register-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label">
                                <i class="fa fa-user me-2"></i>Full Name <span class="required-star">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="customer_name" 
                                   name="name" 
                                   placeholder="e.g. Kwame Mensah"
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customer_email" class="form-label">
                                <i class="fa fa-envelope me-2"></i>Email Address <span class="required-star">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="customer_email" 
                                   name="email" 
                                   placeholder="your.email@example.com"
                                   required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_pass" class="form-label">
                                <i class="fa fa-lock me-2"></i>Password <span class="required-star">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="customer_pass" 
                                   name="password" 
                                   placeholder="At least 6 characters"
                                   required>
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customer_contact" class="form-label">
                                <i class="fa fa-phone me-2"></i>Phone Number <span class="required-star">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="customer_contact" 
                                   name="contact" 
                                   placeholder="e.g. 0244123456"
                                   required>
                            <small class="text-muted">For order updates via SMS</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_country" class="form-label">
                                <i class="fa fa-globe me-2"></i>Country <span class="required-star">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="customer_country" 
                                   name="country" 
                                   value="Ghana"
                                   readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customer_city" class="form-label">
                                <i class="fa fa-map-marker-alt me-2"></i>Region/City <span class="required-star">*</span>
                            </label>
                            <select class="form-select" id="customer_city" name="city" required>
                                <option value="">Select Your Region</option>
                                <option value="Greater Accra">Greater Accra</option>
                                <option value="Ashanti">Ashanti</option>
                                <option value="Western">Western</option>
                                <option value="Central">Central</option>
                                <option value="Eastern">Eastern</option>
                                <option value="Northern">Northern</option>
                                <option value="Volta">Volta</option>
                                <option value="Bono">Bono</option>
                                <option value="Upper East">Upper East</option>
                                <option value="Upper West">Upper West</option>
                                <option value="Western North">Western North</option>
                                <option value="Ahafo">Ahafo</option>
                                <option value="Bono East">Bono East</option>
                                <option value="Savannah">Savannah</option>
                                <option value="North East">North East</option>
                                <option value="Oti">Oti</option>
                            </select>
                            <small class="ghana-regions">We deliver to all 16 regions!</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fa fa-user-tag me-2"></i>Account Type <span class="required-star">*</span>
                        </label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="account-type-card" id="customer-card" onclick="selectAccountType('customer')">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="role" 
                                               id="customer" 
                                               value="2" 
                                               checked>
                                        <label class="form-check-label" for="customer">
                                            <strong>💜 K-Pop Fan</strong>
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Browse and buy K-Pop merchandise from your favorite groups
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="account-type-card" id="admin-card" onclick="selectAccountType('admin')">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="role" 
                                               id="admin" 
                                               value="1">
                                        <label class="form-check-label" for="admin">
                                            <strong>🎪 Store Owner</strong>
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Sell K-Pop merchandise and manage your inventory
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" style="color: var(--kpop-pink);">Terms & Conditions</a> and <a href="#" style="color: var(--kpop-pink);">Privacy Policy</a>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-user-plus me-2"></i>Create My K-Connect Account
                    </button>
                </form>
                
                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        <i class="fa fa-shield-alt me-2"></i>
                        Your information is safe and secure with us
                    </p>
                </div>
            </div>
            <div class="card-footer">
                Already have an account? 
                <a href="login.php">
                    <i class="fa fa-sign-in-alt me-1"></i>Login Here
                </a>
            </div>
        </div>
        
        <div class="text-center">
            <a href="../index.php" style="color: white; text-decoration: none;">
                <i class="fa fa-arrow-left me-2"></i>Back to Home
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/register.js"></script>
    
    <script>
        // Account type selection with visual feedback
        function selectAccountType(type) {
            // Remove selected class from all cards
            document.getElementById('customer-card').classList.remove('selected');
            document.getElementById('admin-card').classList.remove('selected');
            
            // Add selected class to clicked card
            if (type === 'customer') {
                document.getElementById('customer-card').classList.add('selected');
                document.getElementById('customer').checked = true;
            } else {
                document.getElementById('admin-card').classList.add('selected');
                document.getElementById('admin').checked = true;
            }
        }
        
        // Initialize customer card as selected
        document.addEventListener('DOMContentLoaded', function() {
            selectAccountType('customer');
        });
    </script>
</body>
</html>