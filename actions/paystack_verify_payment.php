<?php
/**
 * Verify Paystack Payment and Create Order
 * Called after user returns from Paystack payment page
 */

header('Content-Type: application/json');

require_once '../settings/core.php';
require_once '../settings/paystack_config.php';
require_once '../controllers/cart_controller.php';
require_once '../controllers/order_controller.php';

$response = array();

try {
    // Check if user is logged in
    if (!check_login()) {
        $response['status'] = 'error';
        $response['message'] = 'Session expired. Please login again.';
        echo json_encode($response);
        exit;
    }
    
    // Get reference from request
    $reference = isset($_GET['reference']) ? trim($_GET['reference']) : '';
    
    if (empty($reference)) {
        $response['status'] = 'error';
        $response['message'] = 'No payment reference provided';
        echo json_encode($response);
        exit;
    }
    
    // Verify reference matches session (optional security check)
    if (isset($_SESSION['paystack_ref']) && $_SESSION['paystack_ref'] !== $reference) {
        error_log("Reference mismatch - Expected: {$_SESSION['paystack_ref']}, Got: $reference");
    }
    
    // Verify transaction with Paystack
    $verification = paystack_verify_transaction($reference);
    
    if (!$verification || !isset($verification['status'])) {
        throw new Exception('Payment verification failed');
    }
    
    if ($verification['status'] !== true) {
        $error_msg = $verification['message'] ?? 'Payment verification failed';
        throw new Exception($error_msg);
    }
    
    // Extract transaction data
    $transaction_data = $verification['data'] ?? [];
    $payment_status = $transaction_data['status'] ?? null;
    $amount_paid = isset($transaction_data['amount']) ? $transaction_data['amount'] / 100 : 0;
    
    if ($payment_status !== 'success') {
        throw new Exception('Payment was not successful. Status: ' . ucfirst($payment_status));
    }
    
    $customer_id = get_user_id();
    
    // Get cart items and verify amount
    $cart_items = get_user_cart_ctr($customer_id);
    $cart_total = get_cart_total_ctr($customer_id);
    
    if (!$cart_items || count($cart_items) == 0) {
        throw new Exception('Cart is empty');
    }
    
    // Verify amount matches (allow 1 pesewa tolerance for rounding)
    if (abs($amount_paid - $cart_total) > 0.01) {
        throw new Exception('Payment amount mismatch');
    }
    
    // Create order
    $order_date = date('Y-m-d H:i:s');
    $order_status = 'Paid';
    
    $order_id = create_order_ctr($customer_id, $cart_total, $order_date, $order_status);
    
    if (!$order_id) {
        throw new Exception('Failed to create order');
    }
    
    // Add order details
    foreach ($cart_items as $item) {
        $result = add_order_details_ctr(
            $order_id,
            $item['p_id'],
            $item['qty'],
            $item['product_price']
        );
        
        if (!$result) {
            throw new Exception('Failed to add order details');
        }
    }
    
    // Record payment
    $payment_date = date('Y-m-d H:i:s');
    $payment_result = record_payment_ctr($order_id, $cart_total, $customer_id, $payment_date);
    
    if (!$payment_result) {
        throw new Exception('Failed to record payment');
    }
    
    // Empty cart
    empty_cart_ctr($customer_id);
    
    // Generate invoice/reference
    $invoice_no = generate_order_reference();
    
    // Clear session payment data
    unset($_SESSION['paystack_ref']);
    unset($_SESSION['paystack_amount']);
    unset($_SESSION['paystack_timestamp']);
    
    // Return success
    $response['status'] = 'success';
    $response['verified'] = true;
    $response['message'] = 'Payment successful! Order confirmed.';
    $response['order_id'] = $order_id;
    $response['invoice_no'] = $invoice_no;
    $response['total_amount'] = number_format($cart_total, 2);
    $response['currency'] = 'GHS';
    $response['order_date'] = date('F j, Y');
    $response['item_count'] = count($cart_items);
    $response['payment_reference'] = $reference;
    
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['verified'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>