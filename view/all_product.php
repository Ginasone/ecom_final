<?php
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';
require_once '../controllers/category_controller.php';
require_once '../controllers/brand_controller.php';

$is_logged_in = check_login();
$customer_name = $is_logged_in ? get_user_name() : '';
$is_admin = check_admin();

// get all products
$products = view_all_products_ctr();

// Ensure $products is always an array
if ($products === false || !is_array($products)) {
    $products = array();
}

// get all categories and brands for filters
$all_categories = get_all_categories_ctr();
$all_brands = get_all_brands_ctr();

// Ensure categories and brands are arrays
if ($all_categories === false || !is_array($all_categories)) {
    $all_categories = array();
}
if ($all_brands === false || !is_array($all_brands)) {
    $all_brands = array();
}

// pagination
$items_per_page = 12;
$total_items = count($products);
$total_pages = max(1, ceil($total_items / $items_per_page));
$current_page = isset($_GET['page']) ? max(1, min($total_pages, (int)$_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;
$products_to_display = array_slice($products, $offset, $items_per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop K-Pop Merch - K-Connect Ghana</title>
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
        
        .page-header {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
            border-radius: 0 0 30px 30px;
        }
        
        .page-header h2 {
            font-weight: bold;
            font-size: 36px;
        }
        
        .filter-section {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border: 3px solid var(--kpop-light);
        }
        
        .filter-section h5 {
            color: var(--kpop-pink);
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .form-select {
            border: 2px solid var(--kpop-light);
            border-radius: 15px;
            padding: 12px;
        }
        
        .form-select:focus {
            border-color: var(--kpop-pink);
            box-shadow: 0 0 0 0.2rem rgba(255, 20, 147, 0.25);
        }
        
        .product-card {
            transition: all 0.3s;
            height: 100%;
            border-radius: 20px;
            overflow: hidden;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            background: white;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(255, 20, 147, 0.3);
        }
        
        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background: linear-gradient(135deg, var(--kpop-light), white);
        }
        
        .product-card .card-body {
            padding: 20px;
        }
        
        .product-title {
            font-weight: bold;
            color: var(--kpop-dark);
            font-size: 16px;
            min-height: 48px;
        }
        
        .product-price {
            color: var(--kpop-pink);
            font-size: 24px;
            font-weight: bold;
        }
        
        .product-meta {
            font-size: 13px;
            color: #6c757d;
        }
        
        .badge-kpop {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 11px;
        }
        
        .btn-add-cart {
            background: linear-gradient(135deg, var(--kpop-cyan), var(--kpop-purple));
            border: none;
            color: white;
            border-radius: 15px;
            font-weight: 600;
        }
        
        .btn-add-cart:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 217, 255, 0.4);
            color: white;
        }
        
        .btn-view-details {
            border: 2px solid var(--kpop-pink);
            color: var(--kpop-pink);
            border-radius: 15px;
            font-weight: 600;
        }
        
        .btn-view-details:hover {
            background: var(--kpop-pink);
            color: white;
        }
        
        .pagination .page-link {
            color: var(--kpop-pink);
            border: 2px solid var(--kpop-light);
            border-radius: 10px;
            margin: 0 5px;
            font-weight: 600;
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--kpop-pink), var(--kpop-purple));
            border-color: var(--kpop-pink);
        }
        
        .stats-banner {
            background: linear-gradient(135deg, var(--kpop-light), white);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            border: 3px solid var(--kpop-pink);
        }
        
        .stats-banner h3 {
            color: var(--kpop-purple);
            font-size: 48px;
            font-weight: bold;
            margin: 0;
        }
        
        .stats-banner p {
            color: var(--kpop-dark);
            margin: 0;
            font-weight: 600;
        }
        
        .search-box {
            border: 2px solid var(--kpop-pink);
            border-radius: 25px;
            padding: 8px 15px;
        }
        
        .search-box:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 20, 147, 0.25);
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
                        <a class="nav-link active" href="all_product.php">Shop</a>
                    </li>
                </ul>
                
                <!-- Search Box -->
                <form class="d-flex me-3" action="product_search_result.php" method="GET">
                    <input class="form-control search-box me-2" type="search" name="query" placeholder="Search K-Pop merch..." required>
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
                        <li class="nav-item me-2">
                            <a class="btn btn-info btn-sm" href="cart.php">
                                <i class="fa fa-shopping-cart me-1"></i>Cart
                                <span class="badge bg-danger" id="cart-count" style="display:none;">0</span>
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
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container text-center">
            <h2>
                <i class="fa fa-store me-3"></i>
                Shop K-Pop Merchandise
            </h2>
            <p class="mb-0 mt-2">From BTS to BLACKPINK - Find Your Favorite Groups!</p>
        </div>
    </div>

    <div class="container">
        <!-- Stats Banner -->
        <div class="stats-banner">
            <div class="row">
                <div class="col-md-4">
                    <h3><?php echo $total_items; ?>+</h3>
                    <p>K-Pop Products</p>
                </div>
                <div class="col-md-4">
                    <h3>50+</h3>
                    <p>K-Pop Groups</p>
                </div>
                <div class="col-md-4">
                    <h3>GH₵25</h3>
                    <p>Fixed Delivery</p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h5><i class="fa fa-filter me-2"></i>Filter Products</h5>
            <div class="row">
                <div class="col-md-5">
                    <label class="form-label fw-bold">By Category</label>
                    <select class="form-select" id="filter-category">
                        <option value="">🎵 All Categories</option>
                        <?php if ($all_categories): ?>
                            <?php foreach ($all_categories as $category): ?>
                                <option value="<?php echo $category['cat_id']; ?>">
                                    <?php echo htmlspecialchars($category['cat_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold">By K-Pop Group</label>
                    <select class="form-select" id="filter-brand">
                        <option value="">💜 All Groups</option>
                        <?php if ($all_brands): ?>
                            <?php foreach ($all_brands as $brand): ?>
                                <option value="<?php echo $brand['brand_id']; ?>">
                                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-custom w-100" onclick="applyFilters()">
                        <i class="fa fa-check me-2"></i>Apply
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row">
            <?php if (!$products || count($products) == 0): ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fa fa-box-open" style="font-size: 100px; color: var(--kpop-pink); opacity: 0.5;"></i>
                        <h3 class="mt-4" style="color: var(--kpop-purple);">No Products Available</h3>
                        <p class="text-muted">Check back soon for new K-Pop merchandise!</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($products_to_display as $product): ?>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card product-card">
                            <?php if (!empty($product['product_image'])): ?>
                                <img src="../<?php echo htmlspecialchars($product['product_image']); ?>" 
                                     class="card-img-top product-image" 
                                     alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                                     onerror="this.src='../images/no-image.png'">
                            <?php else: ?>
                                <div class="card-img-top product-image d-flex align-items-center justify-content-center">
                                    <i class="fa fa-compact-disc fa-4x" style="color: var(--kpop-pink); opacity: 0.3;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <span class="badge-kpop mb-2">
                                    <i class="fa fa-users me-1"></i><?php echo htmlspecialchars($product['brand_name']); ?>
                                </span>
                                <h6 class="product-title"><?php echo htmlspecialchars($product['product_title']); ?></h6>
                                <p class="product-meta mb-2">
                                    <i class="fa fa-tag me-1"></i><?php echo htmlspecialchars($product['cat_name']); ?>
                                </p>
                                <div class="product-price mb-3">GH₵<?php echo number_format($product['product_price'], 2); ?></div>
                            </div>
                            <div class="card-footer bg-white border-0 pb-3">
                                <a href="single_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-view-details w-100 mb-2">
                                    <i class="fa fa-eye me-1"></i>View Details
                                </a>
                                <?php if ($is_logged_in): ?>
                                    <button class="btn btn-add-cart w-100" onclick="addToCart(<?php echo $product['product_id']; ?>)">
                                        <i class="fa fa-cart-plus me-1"></i>Add to Cart
                                    </button>
                                <?php else: ?>
                                    <a href="../login/login.php" class="btn btn-add-cart w-100">
                                        <i class="fa fa-sign-in-alt me-1"></i>Login to Buy
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Product pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
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
        
        function applyFilters() {
            var category = $('#filter-category').val();
            var brand = $('#filter-brand').val();
            
            var url = 'product_search_result.php?';
            var params = [];
            
            if (category) {
                params.push('category=' + category);
            }
            if (brand) {
                params.push('brand=' + brand);
            }
            
            if (params.length > 0) {
                window.location.href = url + params.join('&');
            } else {
                alert('Please select at least one filter');
            }
        }
        
        // Add to cart function
        function addToCart(productId, qty) {
            qty = qty || 1;
            
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
                        alert('✨ Added to cart! ' + response.message);
                        if ($('#cart-count').length) {
                            $('#cart-count').text(response.cart_count).show();
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

        // Update cart count on page load
        $(document).ready(function() {
            updateCartCount();
        });

        function updateCartCount() {
            $.ajax({
                url: '../actions/get_cart_count_action.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.count > 0) {
                        $('#cart-count').text(response.count).show();
                    }
                }
            });
        }
    </script>
</body>
</html>