<?php
require_once 'settings/core.php';

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    session_destroy();
    header('Location: index.php');
    exit();
}

// check if user is logged in
$is_logged_in = check_login();
$customer_name = $is_logged_in ? get_user_name() : '';
$user_role = $is_logged_in ? get_user_role() : 2;
$is_admin = check_admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>K-Connect Ghana - Your K-Pop Merchandise Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --kpop-primary: #FF1493;      /* Hot Pink - K-Pop energy */
            --kpop-secondary: #9D4EDD;    /* Purple - K-Pop aesthetic */
            --kpop-accent: #00D9FF;       /* Cyan - Modern pop */
            --kpop-dark: #1a1a2e;         /* Dark background */
            --kpop-light: #FFE5F7;        /* Light pink */
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-brand {
            color: var(--kpop-primary) !important;
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .btn-kpop-primary {
            background: linear-gradient(135deg, var(--kpop-primary), var(--kpop-secondary));
            border: none;
            color: white;
            font-weight: 600;
        }
        
        .btn-kpop-primary:hover {
            background: linear-gradient(135deg, var(--kpop-secondary), var(--kpop-primary));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 20, 147, 0.4);
        }
        
        .btn-kpop-outline {
            border: 2px solid var(--kpop-primary);
            color: var(--kpop-primary);
            background: transparent;
            font-weight: 600;
        }
        
        .btn-kpop-outline:hover {
            background: var(--kpop-primary);
            color: white;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--kpop-dark), var(--kpop-secondary));
            color: white;
            padding: 120px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path fill="%23FF1493" fill-opacity="0.1" d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"></path></svg>') repeat-x;
            background-size: cover;
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .text-kpop-primary {
            color: var(--kpop-primary) !important;
        }
        
        .badge-kpop {
            background: linear-gradient(135deg, var(--kpop-primary), var(--kpop-accent));
            color: white;
        }
        
        .feature-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(157, 78, 221, 0.2);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--kpop-primary), var(--kpop-secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .stats-section {
            background: var(--kpop-light);
            padding: 60px 0;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: var(--kpop-primary);
        }
        
        .cta-section {
            background: linear-gradient(135deg, var(--kpop-secondary), var(--kpop-primary));
            color: white;
            padding: 80px 0;
        }
        
        footer {
            background: var(--kpop-dark);
            color: white;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            background: var(--kpop-primary);
            transform: translateY(-3px);
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fa fa-star me-2"></i>K-Connect Ghana
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view/all_product.php">Shop Merchandise</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#delivery">Delivery</a>
                    </li>
                </ul>
                
                <!-- Search Box -->
                <form class="d-flex me-3" action="view/product_search_result.php" method="GET">
                    <input class="form-control me-2" type="search" name="query" placeholder="Search groups, albums, merch..." style="min-width: 250px;">
                    <button class="btn btn-kpop-primary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
                
                <ul class="navbar-nav">
                    <?php if (!$is_logged_in): ?>
                        <li class="nav-item me-2">
                            <a class="btn btn-kpop-outline btn-sm" href="login/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-kpop-primary btn-sm" href="login/register.php">Join Us</a>
                        </li>
                    <?php elseif ($is_admin): ?>
                        <li class="nav-item me-2">
                            <a class="btn btn-success btn-sm" href="admin/category.php">
                                <i class="fa fa-list me-1"></i>Categories
                            </a>
                        </li>
                        <li class="nav-item me-2">
                            <a class="btn btn-info btn-sm" href="admin/brand.php">
                                <i class="fa fa-users me-1"></i>Groups
                            </a>
                        </li>
                        <li class="nav-item me-2">
                            <a class="btn btn-warning btn-sm" href="admin/product.php">
                                <i class="fa fa-box me-1"></i>Products
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fa fa-user-shield me-1"></i>
                                <?php echo htmlspecialchars($customer_name); ?>
                                <span class="badge badge-kpop">Admin</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="admin/category.php"><i class="fa fa-list me-2"></i>Manage Categories</a></li>
                                <li><a class="dropdown-item" href="admin/brand.php"><i class="fa fa-users me-2"></i>Manage K-Pop Groups</a></li>
                                <li><a class="dropdown-item" href="admin/product.php"><i class="fa fa-box me-2"></i>Manage Products</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="logout()"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item me-2">
                            <a class="btn btn-kpop-outline btn-sm" href="view/cart.php">
                                <i class="fa fa-shopping-cart"></i> Cart
                                <span class="badge bg-danger" id="cart-count">0</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fa fa-user me-1"></i>
                                <?php echo htmlspecialchars($customer_name); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="view/my_orders.php"><i class="fa fa-shopping-bag me-2"></i>My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="logout()"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container text-center hero-content">
            <?php if ($is_logged_in): ?>
                <h1 class="display-3 mb-4 fw-bold">
                    Welcome back, <?php echo htmlspecialchars($customer_name); ?>! 🎵
                </h1>
                <p class="lead mb-5 fs-4">
                    <?php if ($is_admin): ?>
                        Manage your K-Pop merchandise platform
                    <?php else: ?>
                        Ready to discover the latest K-Pop merch?
                    <?php endif; ?>
                </p>
                <div class="mb-4">
                    <?php if ($is_admin): ?>
                        <a href="admin/product.php" class="btn btn-light btn-lg me-3 px-5">
                            <i class="fa fa-box me-2"></i>Manage Products
                        </a>
                        <a href="view/all_product.php" class="btn btn-outline-light btn-lg px-5">View Store</a>
                    <?php else: ?>
                        <a href="view/all_product.php" class="btn btn-light btn-lg me-3 px-5">
                            <i class="fa fa-shopping-bag me-2"></i>Shop Now
                        </a>
                        <a href="#about" class="btn btn-outline-light btn-lg px-5">Learn More</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <h1 class="display-2 mb-4 fw-bold">K-Pop Merch for All of Ghana 🇬🇭</h1>
                <p class="lead mb-5 fs-3">
                    To Anywhere • Albums • Lightsticks • Photocards • Official Merch
                </p>
                <p class="mb-5 fs-5">
                    <i class="fa fa-check-circle me-2"></i>Nationwide Delivery via Pickup Points<br>
                    <i class="fa fa-check-circle me-2"></i>Mobile Money & Installment Payment<br>
                    <i class="fa fa-check-circle me-2"></i>Same Price Everywhere - GH₵25 Max Delivery
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="login/register.php" class="btn btn-light btn-lg px-5 py-3">
                        <i class="fa fa-user-plus me-2"></i>Join K-Connect
                    </a>
                    <a href="view/all_product.php" class="btn btn-outline-light btn-lg px-5 py-3">
                        <i class="fa fa-store me-2"></i>Browse Merch
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="stat-number">50+</div>
                    <p class="text-muted fw-bold">K-Pop Groups</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-number">500+</div>
                    <p class="text-muted fw-bold">Products</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-number">16</div>
                    <p class="text-muted fw-bold">Regions Served</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-number">GH₵25</div>
                    <p class="text-muted fw-bold">Max Delivery</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" id="about">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col">
                    <h2 class="display-5 fw-bold text-kpop-primary">Why Choose K-Connect Ghana?</h2>
                    <p class="lead text-muted">Bringing K-Pop closer to every Ghanaian fan</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="feature-icon">
                            <i class="fa fa-map-marked-alt fa-2x text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Nationwide Coverage</h5>
                        <p class="text-muted">Pickup points across all 16 regions. From Greater Accra to Northern Region, we deliver everywhere.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="feature-icon">
                            <i class="fa fa-mobile-alt fa-2x text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Mobile Money Payment</h5>
                        <p class="text-muted">Pay with MTN, Vodafone, or AirtelTigo Mobile Money. Installment plans available for large orders.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="feature-icon">
                            <i class="fa fa-coins fa-2x text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Fair Pricing</h5>
                        <p class="text-muted">Same price for everyone. GH₵25 max delivery fee nationwide - no location penalties.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="feature-icon">
                            <i class="fa fa-star fa-2x text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Official Merchandise</h5>
                        <p class="text-muted">100% authentic K-Pop albums, lightsticks, photocards, and official merchandise from your favorite groups.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="feature-icon">
                            <i class="fa fa-users fa-2x text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Community Driven</h5>
                        <p class="text-muted">Request products from your favorite groups. We stock what Ghana's K-Pop community wants.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="feature-icon">
                            <i class="fa fa-shield-alt fa-2x text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Secure & Reliable</h5>
                        <p class="text-muted">Safe packaging, order tracking, and guaranteed delivery to your nearest pickup point.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works / Delivery Section -->
    <section class="py-5 bg-light" id="delivery">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-kpop-primary">How K-Connect Works</h2>
                <p class="lead text-muted">Easy ordering, nationwide delivery</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <div class="feature-icon mx-auto">
                            <span class="text-white fw-bold fs-2">1</span>
                        </div>
                    </div>
                    <h5 class="fw-bold">Browse & Order</h5>
                    <p class="text-muted">Search for your favorite K-Pop groups and merchandise. Add to cart and checkout.</p>
                </div>
                
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <div class="feature-icon mx-auto">
                            <span class="text-white fw-bold fs-2">2</span>
                        </div>
                    </div>
                    <h5 class="fw-bold">Pay via Mobile Money</h5>
                    <p class="text-muted">Use MTN, Vodafone, or AirtelTigo Mobile Money. Choose installment if needed.</p>
                </div>
                
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <div class="feature-icon mx-auto">
                            <span class="text-white fw-bold fs-2">3</span>
                        </div>
                    </div>
                    <h5 class="fw-bold">We Process & Ship</h5>
                    <p class="text-muted">Your order is packed and shipped to your selected pickup point in your region.</p>
                </div>
                
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <div class="feature-icon mx-auto">
                            <span class="text-white fw-bold fs-2">4</span>
                        </div>
                    </div>
                    <h5 class="fw-bold">Collect & Enjoy!</h5>
                    <p class="text-muted">Get notified when your order arrives. Collect from your chosen pickup point.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <?php if (!$is_logged_in): ?>
    <section class="cta-section">
        <div class="container text-center">
            <h2 class="display-4 fw-bold mb-4">Join Ghana's Growing K-Pop Community</h2>
            <p class="lead mb-5">Be part of the movement bringing K-Pop merchandise to every corner of Ghana</p>
            <a href="login/register.php" class="btn btn-light btn-lg px-5 py-3 me-3">
                <i class="fa fa-user-plus me-2"></i>Create Account
            </a>
            <a href="view/all_product.php" class="btn btn-outline-light btn-lg px-5 py-3">
                <i class="fa fa-store me-2"></i>Start Shopping
            </a>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-kpop-primary fw-bold mb-3">
                        <i class="fa fa-star me-2"></i>K-Connect Ghana
                    </h5>
                    <p class="text-light">Bringing K-Pop merchandise to every Ghanaian fan, regardless of location.</p>
                    <?php if ($is_logged_in): ?>
                    <p class="text-light"><small>Logged in as: <?php echo htmlspecialchars($customer_name); ?> 
                        <?php echo $is_admin ? '(Administrator)' : '(Customer)'; ?></small></p>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <a href="#" class="social-icon text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon text-white"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h6 class="text-white fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="view/all_product.php" class="text-light text-decoration-none"><i class="fa fa-angle-right me-2"></i>Shop Merchandise</a></li>
                        <li class="mb-2"><a href="#about" class="text-light text-decoration-none"><i class="fa fa-angle-right me-2"></i>About Us</a></li>
                        <li class="mb-2"><a href="#delivery" class="text-light text-decoration-none"><i class="fa fa-angle-right me-2"></i>Delivery Info</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none"><i class="fa fa-angle-right me-2"></i>FAQs</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h6 class="text-white fw-bold mb-3">Contact Us</h6>
                    <ul class="list-unstyled text-light">
                        <li class="mb-2"><i class="fa fa-map-marker-alt me-2 text-kpop-primary"></i>Accra, Ghana</li>
                        <li class="mb-2"><i class="fa fa-phone me-2 text-kpop-primary"></i>+233 XX XXX XXXX</li>
                        <li class="mb-2"><i class="fa fa-envelope me-2 text-kpop-primary"></i>info@kconnectghana.com</li>
                    </ul>
                </div>
            </div>
            
            <hr class="bg-light my-4">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-light mb-0">&copy; 2025 K-Connect Ghana. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-light text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-light text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = 'index.php?logout=1';
            }
        }
        
        // Update cart count if user is logged in
        <?php if ($is_logged_in && !$is_admin): ?>
        $(document).ready(function() {
            $.ajax({
                url: 'actions/get_cart_count_action.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.count > 0) {
                        $('#cart-count').text(response.count).show();
                    }
                }
            });
        });
        <?php endif; ?>
    </script>
</body>
</html>