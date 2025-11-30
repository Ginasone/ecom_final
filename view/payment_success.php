<?php
require_once '../settings/core.php';

if (!check_login()) {
    header('Location: ../login/login.php');
    exit();
}

$customer_name = get_user_name();
$invoice_no = isset($_GET['invoice']) ? htmlspecialchars($_GET['invoice']) : '';
$reference = isset($_GET['reference']) ? htmlspecialchars($_GET['reference']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .btn-custom {
            background-color: #D19C97;
            border-color: #D19C97;
            color: white;
        }
        .btn-custom:hover {
            background-color: #b77a7a;
            border-color: #b77a7a;
            color: white;
        }
        .success-box {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 2px solid #6ee7b7;
            border-radius: 20px;
            padding: 50px 40px;
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce 1s ease-in-out;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        h1 {
            font-size: 3rem;
            color: #065f46;
            margin-bottom: 10px;
        }
        .order-details {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: left;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .detail-row:last-child { border-bottom: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container">
            <a href="../index.php" class="navbar-brand text-primary">
                <i class="fa fa-utensils me-2"></i>Taste of Africa
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="success-box">
            <div class="success-icon">🎉</div>
            <h1>Order Successful!</h1>
            <p class="subtitle" style="font-size: 18px; color: #047857;">Your payment has been processed successfully</p>
            
            <div class="order-details">
                <div class="detail-row">
                    <span style="font-weight: 600;">Invoice Number</span>
                    <span><?php echo $invoice_no; ?></span>
                </div>
                <div class="detail-row">
                    <span style="font-weight: 600;">Payment Reference</span>
                    <span style="word-break: break-all;"><?php echo $reference; ?></span>
                </div>
                <div class="detail-row">
                    <span style="font-weight: 600;">Order Date</span>
                    <span><?php echo date('F j, Y'); ?></span>
                </div>
                <div class="detail-row">
                    <span style="font-weight: 600;">Status</span>
                    <span style="color: #059669; font-weight: 600;">Paid ✓</span>
                </div>
            </div>
            
            <div style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                <a href="all_product.php" class="btn btn-custom">Continue Shopping</a>
                <a href="../index.php" class="btn btn-outline-secondary">Back to Home</a>
            </div>
        </div>
    </div>

    <script>
        // Confetti effect
        function createConfetti() {
            const colors = ['#D19C97', '#b77a7a', '#10b981', '#3b82f6'];
            const confettiCount = 50;
            
            for (let i = 0; i < confettiCount; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.cssText = `
                        position: fixed;
                        width: 10px;
                        height: 10px;
                        background: ${colors[Math.floor(Math.random() * colors.length)]};
                        left: ${Math.random() * 100}%;
                        top: -10px;
                        opacity: 1;
                        z-index: 10001;
                        pointer-events: none;
                    `;
                    
                    document.body.appendChild(confetti);
                    
                    const duration = 2000 + Math.random() * 1000;
                    const startTime = Date.now();
                    
                    function animateConfetti() {
                        const elapsed = Date.now() - startTime;
                        const progress = elapsed / duration;
                        
                        if (progress < 1) {
                            confetti.style.top = (progress * (window.innerHeight + 50)) + 'px';
                            confetti.style.opacity = 1 - progress;
                            requestAnimationFrame(animateConfetti);
                        } else {
                            confetti.remove();
                        }
                    }
                    
                    animateConfetti();
                }, i * 30);
            }
        }
        
        window.addEventListener('load', createConfetti);
    </script>
</body>
</html>