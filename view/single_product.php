<?php
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

$is_logged_in = check_login();
$customer_name = $is_logged_in ? get_user_name() : '';
$is_admin = check_admin();

// get product id from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    header('Location: all_product.php');
    exit();
}

// get product details
$product = view_single_product_ctr($product_id);

if (!$product) {
    header('Location: all_product.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_title']); ?> - K-Connect Ghana</title>
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
            background: linear-gradient(135deg, rgba(255,20,147,0.05) 0%, rgba(157,78,221,0.05) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            color: var(--kpop-pink) !important;
            font-weight: bold;
            font-size: 24px;
        }
        
        .navbar-brand:hover {
            color: var(--kpop-purple) !important;
        }
        
        .btn-custom {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
            border: none;
            color: white;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 20, 147, 0.4);
            color: white;
        }
        
        .breadcrumb {
            background: white;
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .breadcrumb-item a {
            color: var(--kpop-pink);
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: var(--kpop-dark);
        }
        
        .product-image-card {
            background: white;
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 3px solid var(--kpop-light);
        }
        
        .product-image {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--kpop-light), white);
        }
        
        .product-details-card {
            background: white;
            border-radius: 25px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 3px solid var(--kpop-light);
        }
        
        .product-title {
            color: var(--kpop-dark);
            font-weight: bold;
            font-size: 32px;
            margin-bottom: 20px;
        }
        
        .product-price {
            font-size: 48px;
            font-weight: bold;
            color: var(--kpop-pink);
            margin: 20px 0;
        }
        
        .badge-group {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
            margin-right: 10px;
        }
        
        .badge-category {
            background: linear-gradient(135deg, var(--kpop-cyan), var(--kpop-purple));
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
        }
        
        .info-section {
            background: linear-gradient(135deg, var(--kpop-light), white);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 5px solid var(--kpop-pink);
        }
        
        .info-section h5 {
            color: var(--kpop-purple);
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .keyword-badge {
            background-color: var(--kpop-light);
            color: var(--kpop-purple);
            padding: 8px 15px;
            border-radius: 20px;
            margin-right: 8px;
            margin-bottom: 8px;
            display: inline-block;
            font-weight: 600;
            border: 2px solid var(--kpop-pink);
        }
        
        .quantity-selector {
            border: 3px solid var(--kpop-pink);
            border-radius: 15px;
            padding: 12px;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
            max-width: 120px;
        }
        
        .quantity-selector:focus {
            border-color: var(--kpop-purple);
            box-shadow: 0 0 0 0.2rem rgba(157, 78, 221, 0.25);
        }
        
        .btn-add-to-cart {
            background: linear-gradient(135deg, var(--kpop-cyan), var(--kpop-purple));
            border: none;
            color: white;
            border-radius: 20px;
            padding: 15px 40px;
            font-weight: bold;
            font-size: 18px;
            transition: all 0.3s;
        }
        
        .btn-add-to-cart:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 217, 255, 0.4);
            color: white;
        }
        
        .btn-back {
            border: 2px solid var(--kpop-purple);
            color: var(--kpop-purple);
            border-radius: 20px;
            padding: 15px 40px;
            font-weight: bold;
            background: white;
        }
        
        .btn-back:hover {
            background: var(--kpop-purple);
            color: white;
        }
        
        .product-id-badge {
            background: var(--kpop-light);
            color: var(--kpop-purple);
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 13px;
            display: inline-block;
            border: 2px solid var(--kpop-pink);
        }
        
        .delivery-info-box {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
            color: white;
            padding: 20px;
            border-radius: 20px;
            margin-top: 20px;
        }
        
        .delivery-info-box h6 {
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .delivery-feature {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .delivery-feature i {
            font-size: 20px;
            margin-right: 12px;
        }
        
        .divider-kpop {
            height: 3px;
            background: linear-gradient(90deg, var(--kpop-pink), var(--kpop-purple), var(--kpop-cyan));
            border-radius: 10px;
            margin: 25px 0;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fa fa-music me-2"></i>K-Connect Ghana
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="all_product.php">Shop</a>
                    </li>
                </ul>
                
                <!-- Search Box -->
                <form class="d-flex me-3" action="product_search_result.php" method="GET">
                    <input class="form-control me-2" type="search" name="query" placeholder="Search..." required style="border-radius: 20px;">
                    <button class="btn btn-custom" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
                
                <ul class="navbar-nav">
                    <?php if (!$is_logged_in): ?>
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-secondary btn-sm" href="../login/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-custom btn-sm" href="../login/register.php">Register</a>
                        </li>
                    <?php else: ?>
                        <?php if ($is_admin): ?>
                            <li class="nav-item me-2">
                                <a class="btn btn-success btn-sm" href="../admin/product.php">
                                    <i class="fa fa-cog me-1"></i>Manage
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fa fa-user me-1"></i><?php echo htmlspecialchars($customer_name); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fa fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fa fa-box me-2"></i>My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="logout()"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="../index.php"><i class="fa fa-home me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="all_product.php">Shop</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['product_title']); ?></li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-6 mb-4">
                <div class="product-image-card">
                    <?php if (!empty($product['product_image'])): ?>
                        <img src="../<?php echo htmlspecialchars($product['product_image']); ?>" 
                             class="product-image" 
                             alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                             onerror="this.src='../images/no-image.png'">
                    <?php else: ?>
                        <div class="product-image d-flex align-items-center justify-content-center" style="min-height: 400px;">
                            <i class="fa fa-compact-disc" style="font-size: 150px; color: var(--kpop-pink); opacity: 0.3;"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-6">
                <div class="product-details-card">
                    <div class="mb-3">
                        <span class="badge-group">
                            <i class="fa fa-users me-2"></i><?php echo htmlspecialchars($product['brand_name']); ?>
                        </span>
                        <span class="badge-category">
                            <i class="fa fa-tag me-2"></i><?php echo htmlspecialchars($product['cat_name']); ?>
                        </span>
                    </div>
                    
                    <h1 class="product-title"><?php echo htmlspecialchars($product['product_title']); ?></h1>
                    
                    <div class="product-price">
                        GH₵<?php echo number_format($product['product_price'], 2); ?>
                    </div>
                    
                    <div class="divider-kpop"></div>
                    
                    <div class="info-section">
                        <h5><i class="fa fa-info-circle me-2"></i>Product Description</h5>
                        <p style="line-height: 1.8; color: var(--kpop-dark);">
                            <?php echo nl2br(htmlspecialchars($product['product_desc'])); ?>
                        </p>
                    </div>
                    
                    <?php if (!empty($product['product_keywords'])): ?>
                        <div class="info-section">
                            <h5><i class="fa fa-tags me-2"></i>Tags</h5>
                            <div>
                                <?php 
                                $keywords = explode(',', $product['product_keywords']);
                                foreach ($keywords as $keyword): 
                                    $keyword = trim($keyword);
                                    if (!empty($keyword)):
                                ?>
                                    <span class="keyword-badge">
                                        #<?php echo htmlspecialchars($keyword); ?>
                                    </span>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($is_logged_in): ?>
                        <div class="mb-4">
                            <label for="product-quantity" class="form-label fw-bold" style="color: var(--kpop-purple);">
                                <i class="fa fa-shopping-basket me-2"></i>Quantity
                            </label>
                            <input type="number" 
                                   class="form-control quantity-selector" 
                                   id="product-quantity" 
                                   value="1" 
                                   min="1" 
                                   max="10">
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-grid gap-3 mb-4">
                        <?php if ($is_logged_in): ?>
                            <button class="btn btn-add-to-cart" onclick="addProductToCart(<?php echo $product['product_id']; ?>)">
                                <i class="fa fa-cart-plus me-2"></i>Add to Cart
                            </button>
                        <?php else: ?>
                            <a href="../login/login.php" class="btn btn-add-to-cart">
                                <i class="fa fa-sign-in-alt me-2"></i>Login to Purchase
                            </a>
                        <?php endif; ?>
                        <a href="all_product.php" class="btn btn-back">
                            <i class="fa fa-arrow-left me-2"></i>Back to Shop
                        </a>
                    </div>
                    
                    <div class="delivery-info-box">
                        <h6><i class="fa fa-truck me-2"></i>Delivery Information</h6>
                        <div class="delivery-feature">
                            <i class="fa fa-map-marked-alt"></i>
                            <span>Delivery to all 16 regions of Ghana</span>
                        </div>
                        <div class="delivery-feature">
                            <i class="fa fa-money-bill-wave"></i>
                            <span>Fixed delivery fee: <strong>GH₵25</strong></span>
                        </div>
                        <div class="delivery-feature">
                            <i class="fa fa-mobile-alt"></i>
                            <span>Pay via MTN, Vodafone, or AirtelTigo</span>
                        </div>
                        <div class="delivery-feature">
                            <i class="fa fa-calendar-check"></i>
                            <span>Installment plans available (orders >GH₵200)</span>
                        </div>
                    </div>
                    
                    <p class="text-center mt-3 mb-0">
                        <span class="product-id-badge">
                            <i class="fa fa-barcode me-2"></i>Product ID: <?php echo $product['product_id']; ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-2"><strong>K-Connect Ghana</strong> - Your #1 K-Pop Merchandise Store</p>
            <p class="mb-0">
                <i class="fa fa-map-marked-alt me-2"></i>Delivering to all 16 regions of Ghana
            </p>
            <p class="mt-2 mb-0">&copy; 2025 K-Connect Ghana. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '../index.php?logout=1';
            }
        }
        
        function addProductToCart(productId) {
            var qty = $('#product-quantity').val() || 1;
            
            $.ajax({
                url: '../actions/add_to_cart_action.php',
                type: 'POST',
                data: {
                    product_id: productId,
                    qty: qty
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('✨ ' + response.message + '\n\n🛒 Check your cart to proceed to checkout!');
                        // Optionally redirect to cart
                        if (confirm('Go to cart now?')) {
                            window.location.href = 'cart.php';
                        }
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Failed to add item to cart. Please try again.');
                }
            });
        }
    </script>
</body>
</html>