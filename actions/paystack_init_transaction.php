<?php
/**
 * Initialize Paystack Transaction
 * Called when user clicks "Proceed to Payment" on checkout page
 */

header('Content-Type: application/json');

require_once '../settings/core.php';
require_once '../settings/paystack_config.php';
require_once '../controllers/cart_controller.php';

$response = array();

try {
    // Check if user is logged in
    if (!check_login()) {
        $response['status'] = 'error';
        $response['message'] = 'Please login to complete payment';
        echo json_encode($response);
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Invalid request method');
    }
    
    $customer_id = get_user_id();
    $customer_name = get_user_name();
    
    // Get customer email from POST or session
    $customer_email = isset($_POST['email']) ? trim($_POST['email']) : '';
    
    // Get customer data to extract email if not provided
    if (empty($customer_email)) {
        require_once '../controllers/customer_controller.php';
        $customer_data = get_customer_by_id_ctr($customer_id);
        $customer_email = $customer_data['customer_email'] ?? '';
    }
    
    if (empty($customer_email)) {
        $response['status'] = 'error';
        $response['message'] = 'Customer email not found';
        echo json_encode($response);
        exit;
    }
    
    // Get cart total
    $cart_total = get_cart_total_ctr($customer_id);
    
    if ($cart_total <= 0) {
        $response['status'] = 'error';
        $response['message'] = 'Cart is empty';
        echo json_encode($response);
        exit;
    }
    
    // Generate unique reference
    $reference = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    
    // Initialize Paystack transaction
    $paystack_response = paystack_initialize_transaction($cart_total, $customer_email, $reference);
    
    if (!$paystack_response || !isset($paystack_response['status'])) {
        throw new Exception('No response from payment gateway');
    }
    
    if ($paystack_response['status'] === true && isset($paystack_response['data']['authorization_url'])) {
        // Store transaction reference in session for verification
        $_SESSION['paystack_ref'] = $reference;
        $_SESSION['paystack_amount'] = $cart_total;
        $_SESSION['paystack_timestamp'] = time();
        
        $response['status'] = 'success';
        $response['authorization_url'] = $paystack_response['data']['authorization_url'];
        $response['reference'] = $reference;
        $response['message'] = 'Redirecting to payment gateway...';
    } else {
        $error_message = $paystack_response['message'] ?? 'Payment initialization failed';
        throw new Exception($error_message);
    }
    
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>