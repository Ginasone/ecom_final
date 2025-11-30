<?php
require_once '../settings/core.php';

// check if user is logged in and is admin
require_admin();

$user_name = get_user_name();

// Ghana regions
$ghana_regions = [
    'Greater Accra', 'Ashanti', 'Western', 'Western North', 'Central', 'Eastern', 
    'Volta', 'Oti', 'Northern', 'Savannah', 'North East', 'Upper East', 'Upper West',
    'Bono', 'Bono East', 'Ahafo'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pickup Points Management - K-Connect Ghana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --kpop-primary: #FF1493;
            --kpop-secondary: #9D4EDD;
        }
        
        .btn-kpop {
            background: linear-gradient(135deg, var(--kpop-primary), var(--kpop-secondary));
            border: none;
            color: white;
            font-weight: 600;
        }
        
        .btn-kpop:hover {
            background: linear-gradient(135deg, var(--kpop-secondary), var(--kpop-primary));
            color: white;
        }
        
        .text-kpop {
            color: var(--kpop-primary);
        }
        
        .bg-kpop {
            background: linear-gradient(135deg, var(--kpop-primary), var(--kpop-secondary));
        }
        
        .region-section {
            margin-bottom: 2rem;
        }
        
        .region-header {
            background: linear-gradient(135deg, #f8e8ff, #fff);
            padding: 10px 15px;
            border-left: 4px solid var(--kpop-primary);
            margin-bottom: 10px;
            border-radius: 5px;
        }
        
        .pickup-point-card {
            border: 2px solid #f0f0f0;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .pickup-point-card:hover {
            border-color: var(--kpop-primary);
            box-shadow: 0 3px 10px rgba(255, 20, 147, 0.1);
        }
        
        .status-badge {
            font-size: 0.75rem;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand text-kpop fw-bold" href="../index.php">
                <i class="fa fa-star me-2"></i>K-Connect Ghana - Admin
            </a>
            
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3 text-light">Welcome, <?php echo htmlspecialchars($user_name); ?></span>
                <a class="btn btn-outline-light btn-sm me-2" href="category.php">Categories</a>
                <a class="btn btn-outline-light btn-sm me-2" href="brand.php">Groups</a>
                <a class="btn btn-outline-light btn-sm me-2" href="product.php">Products</a>
                <a class="btn btn-outline-light btn-sm me-2" href="../index.php">Home</a>
                <a class="btn btn-outline-danger btn-sm" href="#" onclick="logout()">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-kpop text-white">
                        <h5 class="mb-0">
                            <i class="fa fa-map-marker-alt me-2"></i>Pickup Points Management
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Filter -->
                        <div class="mb-3">
                            <label class="form-label">Filter by Region:</label>
                            <select class="form-select" id="region-filter" onchange="filterPickupPoints()">
                                <option value="">All Regions (16)</option>
                                <?php foreach ($ghana_regions as $region): ?>
                                    <option value="<?php echo $region; ?>"><?php echo $region; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Pickup Points List -->
                        <div id="pickup-points-list">
                            <div class="text-center">
                                <div class="spinner-border text-kpop" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading pickup points...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fa fa-plus me-2"></i>Add New Pickup Point
                        </h6>
                    </div>
                    <div class="card-body">
                        <form id="add-pickup-point-form">
                            <div class="mb-3">
                                <label for="point_name" class="form-label">Point Name</label>
                                <input type="text" class="form-control" id="point_name" name="point_name" 
                                       placeholder="e.g., Accra Central Hub" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="region" class="form-label">Region</label>
                                <select class="form-select" id="region" name="region" required>
                                    <option value="">Select Region</option>
                                    <?php foreach ($ghana_regions as $region): ?>
                                        <option value="<?php echo $region; ?>"><?php echo $region; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="city" class="form-label">City/Town</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       placeholder="e.g., Accra, Kumasi" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2" 
                                          placeholder="Full address with landmarks" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="contact_person" class="form-label">Contact Person</label>
                                <input type="text" class="form-control" id="contact_person" name="contact_person">
                            </div>
                            
                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="tel" class="form-control" id="contact_phone" name="contact_phone" 
                                       placeholder="0XX XXX XXXX">
                            </div>
                            
                            <div class="mb-3">
                                <label for="operating_hours" class="form-label">Operating Hours</label>
                                <input type="text" class="form-control" id="operating_hours" name="operating_hours" 
                                       value="Mon-Sat: 9AM-6PM">
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Active (visible to customers)
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fa fa-plus me-2"></i>Add Pickup Point
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fa fa-info-circle me-2"></i>Nationwide Coverage
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><small><strong>All 16 Regions:</strong></small></p>
                        <small class="text-muted">
                            Greater Accra • Ashanti • Western • Central • Eastern • Volta • Northern • 
                            Bono • Upper East • Upper West • Western North • Oti • Savannah • 
                            North East • Bono East • Ahafo
                        </small>
                        <hr>
                        <p class="mb-1"><small><strong>Delivery Fee:</strong></small></p>
                        <p class="text-kpop fw-bold mb-0">GH₵25.00</p>
                        <small class="text-muted">Same price everywhere!</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pickup Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-pickup-point-form">
                    <div class="modal-body">
                        <input type="hidden" id="edit_point_id" name="point_id">
                        
                        <div class="mb-3">
                            <label for="edit_point_name" class="form-label">Point Name</label>
                            <input type="text" class="form-control" id="edit_point_name" name="point_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_region" class="form-label">Region</label>
                            <select class="form-select" id="edit_region" name="region" required>
                                <?php foreach ($ghana_regions as $region): ?>
                                    <option value="<?php echo $region; ?>"><?php echo $region; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_city" class="form-label">City/Town</label>
                            <input type="text" class="form-control" id="edit_city" name="city" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_address" class="form-label">Address</label>
                            <textarea class="form-control" id="edit_address" name="address" rows="2" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_contact_person" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="edit_contact_person" name="contact_person">
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_contact_phone" class="form-label">Contact Phone</label>
                            <input type="tel" class="form-control" id="edit_contact_phone" name="contact_phone">
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_operating_hours" class="form-label">Operating Hours</label>
                            <input type="text" class="form-control" id="edit_operating_hours" name="operating_hours">
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active">
                            <label class="form-check-label" for="edit_is_active">
                                Active
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-kpop">Update Pickup Point</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/pickup_points.js"></script>
    
    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '../index.php?logout=1';
            }
        }
    </script>
</body>
</html>