<?php
require_once '../settings/core.php';
require_once '../controllers/cart_controller.php';

// Check if user is logged in
if (!check_login()) {
    header('Location: ../login/login.php');
    exit();
}

$is_logged_in = check_login();
$customer_name = get_user_name();
$is_admin = check_admin();
$customer_id = get_user_id();

// Get cart items
$cart_items = get_user_cart_ctr($customer_id);
$cart_total = get_cart_total_ctr($customer_id);

// Ensure cart_items is an array
if ($cart_items === false || !is_array($cart_items)) {
    $cart_items = array();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - K-Connect Ghana</title>
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
            background: linear-gradient(135deg, rgba(255,20,147,0.1) 0%, rgba(157,78,221,0.1) 100%);
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
        
        .page-header {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            border-radius: 0 0 30px 30px;
        }
        
        .page-header h2 {
            font-weight: bold;
            margin: 0;
        }
        
        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
            color: white;
            border-radius: 20px 20px 0 0 !important;
            border: none;
            padding: 20px;
            font-weight: bold;
        }
        
        .cart-item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 15px;
            border: 3px solid var(--kpop-light);
        }
        
        .cart-quantity {
            width: 80px;
            border: 2px solid var(--kpop-pink);
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
        }
        
        .cart-quantity:focus {
            border-color: var(--kpop-purple);
            box-shadow: 0 0 0 0.2rem rgba(157, 78, 221, 0.25);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
            border: none;
            border-radius: 10px;
        }
        
        .btn-danger:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(238, 90, 111, 0.4);
        }
        
        .btn-outline-secondary {
            border: 2px solid var(--kpop-purple);
            color: var(--kpop-purple);
            border-radius: 20px;
            font-weight: 600;
        }
        
        .btn-outline-secondary:hover {
            background: var(--kpop-purple);
            color: white;
        }
        
        .summary-card {
            position: sticky;
            top: 20px;
            background: linear-gradient(135deg, var(--kpop-light), white);
            border: 3px solid var(--kpop-pink);
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .summary-row:last-child {
            border-bottom: none;
            font-size: 20px;
            font-weight: bold;
            color: var(--kpop-pink);
        }
        
        .table {
            border-radius: 15px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
            color: white;
        }
        
        .empty-cart-icon {
            font-size: 100px;
            color: var(--kpop-pink);
            opacity: 0.5;
        }
        
        .delivery-info {
            background: linear-gradient(135deg, var(--kpop-light), white);
            border-left: 4px solid var(--kpop-pink);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .badge {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
        }
        
        .kpop-divider {
            height: 3px;
            background: linear-gradient(90deg, var(--kpop-pink), var(--kpop-purple), var(--kpop-cyan));
            border-radius: 10px;
            margin: 20px 0;
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
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="all_product.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="cart.php">
                            <i class="fa fa-shopping-cart"></i> Cart
                            <span class="badge" id="cart-count"><?php echo count($cart_items); ?></span>
                        </a>
                    </li>
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
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h2>
                <i class="fa fa-shopping-cart me-3"></i>
                Your K-Pop Shopping Cart
            </h2>
            <p class="mb-0 mt-2">Review your items and proceed to checkout</p>
        </div>
    </div>

    <div class="container mb-5">
        <?php if (count($cart_items) == 0): ?>
            <!-- Empty Cart -->
            <div class="text-center py-5">
                <i class="fa fa-shopping-cart empty-cart-icon"></i>
                <h3 class="mt-4" style="color: var(--kpop-purple);">Your cart is empty!</h3>
                <p class="text-muted mb-4">Start adding your favorite K-Pop merchandise</p>
                <a href="all_product.php" class="btn btn-custom btn-lg">
                    <i class="fa fa-music me-2"></i>Browse Products
                </a>
            </div>
        <?php else: ?>
            <!-- Delivery Info -->
            <div class="delivery-info">
                <strong><i class="fa fa-truck me-2" style="color: var(--kpop-pink);"></i>Delivery Information:</strong>
                <p class="mb-0 mt-2">
                    <i class="fa fa-check-circle me-2" style="color: var(--kpop-pink);"></i>
                    Fixed delivery fee of <strong>GH₵25</strong> to any pickup point across all 16 regions of Ghana
                </p>
            </div>

            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fa fa-box me-2"></i>Cart Items (<?php echo count($cart_items); ?>)</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cart_items as $item): ?>
                                            <tr class="cart-item" data-price="<?php echo $item['product_price']; ?>">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if (!empty($item['product_image'])): ?>
                                                            <img src="../<?php echo htmlspecialchars($item['product_image']); ?>" 
                                                                 class="cart-item-image me-3" 
                                                                 alt="<?php echo htmlspecialchars($item['product_title']); ?>">
                                                        <?php else: ?>
                                                            <div class="cart-item-image bg-secondary me-3 d-flex align-items-center justify-content-center">
                                                                <i class="fa fa-image text-white"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($item['product_title']); ?></strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fa fa-music me-1"></i>K-Pop Merch
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <strong style="color: var(--kpop-pink);">
                                                        GH₵<?php echo number_format($item['product_price'], 2); ?>
                                                    </strong>
                                                </td>
                                                <td class="align-middle">
                                                    <input type="number" 
                                                           class="form-control cart-quantity" 
                                                           value="<?php echo $item['qty']; ?>" 
                                                           min="1" 
                                                           max="10"
                                                           data-cart-id="<?php echo $item['cart_id']; ?>">
                                                </td>
                                                <td class="align-middle item-subtotal">
                                                    <strong style="color: var(--kpop-purple);">
                                                        GH₵<?php echo number_format($item['product_price'] * $item['qty'], 2); ?>
                                                    </strong>
                                                </td>
                                                <td class="align-middle">
                                                    <button class="btn btn-sm btn-danger remove-from-cart" 
                                                            data-cart-id="<?php echo $item['cart_id']; ?>"
                                                            data-product-name="<?php echo htmlspecialchars($item['product_title']); ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="all_product.php" class="btn btn-outline-secondary me-2">
                            <i class="fa fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                        <button class="btn btn-outline-danger" id="empty-cart-btn">
                            <i class="fa fa-trash me-2"></i>Empty Cart
                        </button>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card summary-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fa fa-receipt me-2"></i>Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span id="cart-total"><strong>GH₵<?php echo number_format($cart_total, 2); ?></strong></span>
                            </div>
                            <div class="summary-row">
                                <span>Delivery Fee:</span>
                                <span><strong>GH₵25.00</strong></span>
                            </div>
                            <div class="summary-row">
                                <span>Items:</span>
                                <span><strong><?php echo count($cart_items); ?></strong></span>
                            </div>
                            <div class="kpop-divider"></div>
                            <div class="summary-row">
                                <span>Total:</span>
                                <span>GH₵<?php echo number_format($cart_total + 25, 2); ?></span>
                            </div>
                            
                            <a href="checkout.php" class="btn btn-custom w-100 mt-3 btn-lg">
                                <i class="fa fa-credit-card me-2"></i>Proceed to Checkout
                            </a>
                            
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fa fa-shield-alt me-1"></i>
                                    Secure Mobile Money Payment
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Options Info -->
                    <div class="card mt-3">
                        <div class="card-body text-center">
                            <h6 style="color: var(--kpop-purple);">
                                <i class="fa fa-mobile-alt me-2"></i>Payment Options
                            </h6>
                            <p class="small mb-2">We accept:</p>
                            <div class="d-flex justify-content-around align-items-center">
                                <span class="badge bg-warning text-dark">MTN</span>
                                <span class="badge bg-danger">Vodafone</span>
                                <span class="badge bg-info">AirtelTigo</span>
                            </div>
                            <p class="small text-muted mt-3 mb-0">
                                💳 Installment plans available for orders over GH₵200
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/cart.js"></script>
    
    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '../index.php?logout=1';
            }
        }
    </script>
</body>
</html>