/**
 * Checkout JavaScript for Taste of Africa
 * Handles Paystack payment integration
 */

$(document).ready(function() {
    
    // Handle "Proceed to Payment" button click
    $('#proceed-to-payment-btn').on('click', function() {
        processPaystackCheckout();
    });
    
    /**
     * Process checkout via Paystack
     */
    function processPaystackCheckout() {
        const btn = $('#proceed-to-payment-btn');
        const originalText = btn.html();
        
        // Disable button and show loading
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Initializing payment...');
        
        // Get customer email - you can modify this to get from session/form
        const customerEmail = prompt('Please confirm your email for payment receipt:', '');
        
        if (!customerEmail || !validateEmail(customerEmail)) {
            btn.prop('disabled', false).html(originalText);
            alert('Please enter a valid email address');
            return;
        }
        
        // Initialize Paystack transaction
        $.ajax({
            url: '../actions/paystack_init_transaction.php',
            type: 'POST',
            data: {
                email: customerEmail
            },
            dataType: 'json',
            success: function(response) {
                console.log('Paystack init response:', response);
                
                if (response.status === 'success') {
                    // Show success message briefly
                    showNotification('Redirecting to secure payment gateway...', 'success');
                    
                    // Small delay for user to see message, then redirect
                    setTimeout(function() {
                        window.location.href = response.authorization_url;
                    }, 1000);
                } else {
                    btn.prop('disabled', false).html(originalText);
                    showNotification(response.message || 'Failed to initialize payment', 'error');
                }
            },
            error: function(xhr, status, error) {
                btn.prop('disabled', false).html(originalText);
                console.error('Payment initialization error:', error);
                showNotification('Payment initialization failed. Please try again.', 'error');
            }
        });
    }
    
    /**
     * Validate email format
     */
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    /**
     * Show notification to user
     */
    function showNotification(message, type) {
        // Remove existing notifications
        $('.notification-toast').remove();
        
        const bgColor = type === 'success' ? '#d1fae5' : '#fee2e2';
        const textColor = type === 'success' ? '#065f46' : '#991b1b';
        const borderColor = type === 'success' ? '#6ee7b7' : '#fecaca';
        const icon = type === 'success' ? '✓' : '✕';
        
        const notification = $('<div>')
            .addClass('notification-toast')
            .css({
                position: 'fixed',
                top: '20px',
                right: '20px',
                backgroundColor: bgColor,
                color: textColor,
                border: `2px solid ${borderColor}`,
                padding: '15px 20px',
                borderRadius: '8px',
                boxShadow: '0 4px 20px rgba(0,0,0,0.15)',
                zIndex: 10000,
                maxWidth: '400px',
                fontWeight: '600',
                display: 'flex',
                alignItems: 'center',
                gap: '10px',
                animation: 'slideInRight 0.3s ease'
            })
            .html(`<span style="font-size: 20px;">${icon}</span><span>${message}</span>`);
        
        $('body').append(notification);
        
        // Auto remove after 4 seconds
        setTimeout(function() {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 4000);
    }
    
    // Calculate and display totals (if you have checkout summary section)
    function calculateTotals() {
        var subtotal = 0;
        
        $('.checkout-item').each(function() {
            var price = parseFloat($(this).data('price')) || 0;
            var qty = parseInt($(this).data('qty')) || 0;
            var itemTotal = price * qty;
            
            subtotal += itemTotal;
        });
        
        // You can adjust these values as needed
        var tax = subtotal * 0.00; // 0% tax (adjust as needed)
        var shipping = 0; // Free shipping
        var total = subtotal + tax + shipping;
        
        // Update display if elements exist
        if ($('#checkout-subtotal').length) {
            $('#checkout-subtotal').text('$' + subtotal.toFixed(2));
        }
        if ($('#checkout-tax').length) {
            $('#checkout-tax').text('$' + tax.toFixed(2));
        }
        if ($('#checkout-shipping').length) {
            $('#checkout-shipping').text('$' + shipping.toFixed(2));
        }
        if ($('#checkout-total').length) {
            $('#checkout-total').text('$' + total.toFixed(2));
        }
    }
    
    // Calculate totals on page load if checkout items exist
    if ($('.checkout-item').length > 0) {
        calculateTotals();
    }
    
});

// Add CSS animation for notification
if (!document.getElementById('notification-styles')) {
    const style = document.createElement('style');
    style.id = 'notification-styles';
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
}