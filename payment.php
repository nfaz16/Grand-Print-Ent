<?php
session_start();

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: addtocart.php');
    exit();
}

include 'db_conn.php'; // Make sure this sets $conn

$cartCount = count($_SESSION['cart']);
$total = 0;
$orderProcessed = false;
$orderSuccess = false;
$errorMessage = '';

// Calculate total (you can add pricing logic here)
$basePrice = 25.00; // Base price per item
foreach ($_SESSION['cart'] as $item) {
    $total += $basePrice * $item['qty'];
}

// Process payment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_payment'])) {
    // Get form data
    $customer_name = trim($_POST['customer_name']);
    $customer_email = trim($_POST['customer_email']);
    $customer_phone = trim($_POST['customer_phone']);
    $shipping_address = trim($_POST['shipping_address']);
    $city = trim($_POST['city']);
    $postal_code = trim($_POST['postal_code']);
    $state = trim($_POST['state']);
    $payment_method = $_POST['payment_method'];
    $card_number = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';
    $card_expiry = isset($_POST['card_expiry']) ? trim($_POST['card_expiry']) : '';
    $card_cvv = isset($_POST['card_cvv']) ? trim($_POST['card_cvv']) : '';
    
    // Basic validation
    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || 
        empty($shipping_address) || empty($city) || empty($postal_code) || empty($state)) {
        $errorMessage = "Please fill in all required fields.";
    } elseif (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Please enter a valid email address.";
    } elseif ($payment_method === 'card' && (empty($card_number) || empty($card_expiry) || empty($card_cvv))) {
        $errorMessage = "Please fill in all card details.";
    } else {
        try {
            // Start transaction
            $conn->autocommit(FALSE);
            
            // Generate order ID
            $order_id = 'ORD' . date('Ymd') . rand(1000, 9999);
            
            // Insert order
            $stmt = $conn->prepare("INSERT INTO orders (order_id, customer_name, customer_email, customer_phone, shipping_address, city, postal_code, state, payment_method, total_amount, order_status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
            $stmt->bind_param("sssssssssd", $order_id, $customer_name, $customer_email, $customer_phone, $shipping_address, $city, $postal_code, $state, $payment_method, $total);
            $stmt->execute();
            $stmt->close();
            
            // Insert order items
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, quantity, type, size, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            foreach ($_SESSION['cart'] as $item) {
                $item_price = $basePrice * $item['qty'];
                $stmt->bind_param("ssssissd", $order_id, $item['id'], $item['name'], $item['image'], $item['qty'], $item['type'], $item['size'], $item_price);
                $stmt->execute();
            }
            $stmt->close();
            
            // Clear cart from database
            $session_id = session_id();
            $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
            $stmt->bind_param("s", $session_id);
            $stmt->execute();
            $stmt->close();
            
            // Simulate payment processing
            if ($payment_method === 'card') {
                // In real implementation, integrate with payment gateway (Stripe, PayPal, etc.)
                // For demo, we'll simulate success
                $payment_status = 'completed';
            } else {
                $payment_status = 'pending'; // For bank transfer, cash on delivery
            }
            
            // Update order status
            $stmt = $conn->prepare("UPDATE orders SET order_status = ?, payment_status = ? WHERE order_id = ?");
            $stmt->bind_param("sss", $payment_status, $payment_status, $order_id);
            $stmt->execute();
            $stmt->close();
            
            // Commit transaction
            $conn->commit();
            
            // Clear session cart
            unset($_SESSION['cart']);
            
            $orderProcessed = true;
            $orderSuccess = true;
            $_SESSION['last_order_id'] = $order_id;
            
        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();
            $errorMessage = "Payment processing failed. Please try again.";
            error_log("Payment error: " . $e->getMessage());
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment - Grand Print</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 3rem;
        }
		
		.logo {
			display: flex;
			align-items: center;
			text-decoration: none;
			font-weight: bold;
			font-size: 1.7rem;
			color: #333;
			gap: 15px;
		}

		.logo-img {
			height: 50px;
			width: auto;
            margin-right: 10px;
		}

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 4rem;
            align-items: center;
        }

        .nav-menu a {
            text-decoration: none;
			font-size: 1.2rem;
            color: #666;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-menu a:hover {
            color: #007bff;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .language-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }

		.grand-print {
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            border: none;
            outline: none;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            width: 100%;
        }

        .payment-container {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .payment-container {
                grid-template-columns: 1fr;
            }
        }

        .payment-form {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .order-summary {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            height: fit-content;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .payment-methods {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .payment-method {
            flex: 1;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .payment-method:hover {
            border-color: #007bff;
        }

        .payment-method.active {
            border-color: #007bff;
            background-color: #f8f9ff;
        }

        .payment-method input[type="radio"] {
            display: none;
        }

        .card-details {
            display: none;
            margin-top: 20px;
        }

        .card-details.show {
            display: block;
        }

        .order-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .item-specs {
            font-size: 0.9rem;
            color: #666;
        }

        .item-price {
            font-weight: 600;
            color: #007bff;
        }

        .total-section {
            border-top: 2px solid #eee;
            padding-top: 20px;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .total-final {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #003d82);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            margin-right: 10px;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .footer-c {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 10px;
            margin-top: 50px;
            width: 100%;
            font-size: 1.1rem;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
		
		.cart-link {
		  position: relative;
		  display: inline-block;
		  color: #333;
		  text-decoration: none;
		  transition: color 0.2s;
		}
		
		.cart-link:hover {
		  color: #007bff;
		}
		
		.cart-count {
		  position: absolute;
		  top: -8px;
		  right: -10px;
		  background: #dc3545;
		  color: #fff;
		  border-radius: 50%;
		  padding: 2px 7px;
		  font-size: 0.9rem;
		  font-weight: bold;
		  min-width: 22px;
		  text-align: center;
		  line-height: 1.2;
		}
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="userhome.php" class="logo">
                <img src="GPlogo.png" alt="Grand Print Logo" class="logo-img">
                <span class="grand-print">Grand Print</span>
            </a>
            <ul class="nav-menu">
                <li><a href="productlogin.php">Products</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
				<li><a href="logout.php">Logout</a></li>
				<a href="addtocart.php" class="cart-link" style="position:relative; margin-left: 18px;">
				  <i class="fas fa-shopping-cart" style="font-size: 1.5rem;"></i>
				  <span class="cart-count" id="cartCount">
					  <?php echo $cartCount; ?>
					</span>
				</a>
            </ul>
        </nav>
    </header>

    <div class="main-content">
        <h1 class="page-title">Checkout & Payment</h1>

        <?php if ($orderSuccess): ?>
            <div class="success-message">
                <i class="fas fa-check-circle" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h2>Order Placed Successfully!</h2>
                <p>Your order ID is: <strong><?php echo $_SESSION['last_order_id']; ?></strong></p>
                <p>You will receive a confirmation email shortly.</p>
                <div style="margin-top: 20px;">
                    <a href="productlogin.php" class="btn btn-primary">Continue Shopping</a>
                    <a href="order-status.php?order_id=<?php echo $_SESSION['last_order_id']; ?>" class="btn btn-secondary">Track Order</a>
                </div>
            </div>

        <?php else: ?>
            
            <?php if ($errorMessage): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <div class="payment-container">
                <!-- Payment Form -->
                <div class="payment-form">
                    <h2 style="margin-bottom: 20px;">Payment Details</h2>
                    
                    <form method="POST" id="paymentForm">
                        <!-- Customer Information -->
                        <div class="form-group">
                            <label for="customer_name">Full Name *</label>
                            <input type="text" id="customer_name" name="customer_name" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="customer_email">Email Address *</label>
                                <input type="email" id="customer_email" name="customer_email" required>
                            </div>
                            <div class="form-group">
                                <label for="customer_phone">Phone Number *</label>
                                <input type="tel" id="customer_phone" name="customer_phone" required>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <h3 style="margin: 30px 0 20px 0;">Shipping Address</h3>
                        
                        <div class="form-group">
                            <label for="shipping_address">Street Address *</label>
                            <textarea id="shipping_address" name="shipping_address" rows="3" required></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" id="city" name="city" required>
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Postal Code *</label>
                                <input type="text" id="postal_code" name="postal_code" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="state">State *</label>
                            <select id="state" name="state" required>
                                <option value="">Select State</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                <option value="Johor">Johor</option>
                                <option value="Penang">Penang</option>
                                <option value="Perak">Perak</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Terengganu">Terengganu</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Malacca">Malacca</option>
                                <option value="Perlis">Perlis</option>
                                <option value="Sabah">Sabah</option>
                                <option value="Sarawak">Sarawak</option>
                            </select>
                        </div>

                        <!-- Payment Method -->
                        <h3 style="margin: 30px 0 20px 0;">Payment Method</h3>
                        
                        <div class="payment-methods">
                            <div class="payment-method active" onclick="selectPayment('card')">
                                <input type="radio" name="payment_method" value="card" checked>
                                <i class="fas fa-credit-card" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <div>Credit/Debit Card</div>
                            </div>
                            <div class="payment-method" onclick="selectPayment('bank_transfer')">
                                <input type="radio" name="payment_method" value="bank_transfer">
                                <i class="fas fa-university" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <div>Bank Transfer</div>
                            </div>
                            <div class="payment-method" onclick="selectPayment('cod')">
                                <input type="radio" name="payment_method" value="cod">
                                <i class="fas fa-money-bill-wave" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                <div>Cash on Delivery</div>
                            </div>
                        </div>

                        <!-- Card Details -->
                        <div class="card-details show" id="cardDetails">
                            <div class="form-group">
                                <label for="card_number">Card Number</label>
                                <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="card_expiry">Expiry Date</label>
                                    <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" maxlength="5">
                                </div>
                                <div class="form-group">
                                    <label for="card_cvv">CVV</label>
                                    <input type="text" id="card_cvv" name="card_cvv" placeholder="123" maxlength="4">
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 30px;">
                            <a href="addtocart.php" class="btn btn-secondary">Back to Cart</a>
                            <button type="submit" name="process_payment" class="btn btn-primary">
                                <i class="fas fa-lock"></i> Process Payment
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h2 style="margin-bottom: 20px;">Order Summary</h2>
                    
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="order-item">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-image">
                        <div class="item-details">
                            <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="item-specs">
                                Qty: <?php echo $item['qty']; ?> | 
                                Type: <?php echo htmlspecialchars($item['type']); ?> | 
                                Size: <?php echo htmlspecialchars($item['size']); ?>
                            </div>
                        </div>
                        <div class="item-price">RM <?php echo number_format($basePrice * $item['qty'], 2); ?></div>
                    </div>
                    <?php endforeach; ?>

                    <div class="total-section">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span>RM <?php echo number_format($total, 2); ?></span>
                        </div>
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span>RM 10.00</span>
                        </div>
                        <div class="total-row">
                            <span>Tax (6%):</span>
                            <span>RM <?php echo number_format($total * 0.06, 2); ?></span>
                        </div>
                        <div class="total-row total-final">
                            <span>Total:</span>
                            <span>RM <?php echo number_format($total + 10 + ($total * 0.06), 2); ?></span>
                        </div>
                    </div>

					
                    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                        <h4 style="margin-bottom: 10px;"><i class="fas fa-shield-alt"></i> Secure Payment</h4>
                        <p style="font-size: 0.9rem; color: #666;">Your payment information is encrypted and secure.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer-c">
        <p>&copy; 2025 My Web Application</p>
    </div>

    <script>
        function selectPayment(method) {
            // Remove active class from all payment methods
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('active');
            });
            
            // Add active class to selected method
            event.target.closest('.payment-method').classList.add('active');
            
            // Update radio button
            document.querySelector(`input[value="${method}"]`).checked = true;
            
            // Show/hide card details
            const cardDetails = document.getElementById('cardDetails');
            if (method === 'card') {
                cardDetails.classList.add('show');
                // Make card fields required
                document.getElementById('card_number').required = true;
                document.getElementById('card_expiry').required = true;
                document.getElementById('card_cvv').required = true;
            } else {
                cardDetails.classList.remove('show');
                // Remove required from card fields
                document.getElementById('card_number').required = false;
                document.getElementById('card_expiry').required = false;
                document.getElementById('card_cvv').required = false;
            }
        }

        // Format card number input
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let matches = value.match(/\d{4,16}/g);
            let match = matches && matches[0] || '';
            let parts = [];
            for (let i = 0, len = match.length; i < len; i += 4) {
                parts.push(match.substring(i, i + 4));
            }
            if (parts.length) {
                e.target.value = parts.join(' ');
            } else {
                e.target.value = value;
            }
        });

        // Format expiry date input
        document.getElementById('card_expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });

        // Only allow numbers in CVV
        document.getElementById('card_cvv').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        // Form validation
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (paymentMethod === 'card') {
                const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
                const cardExpiry = document.getElementById('card_expiry').value;
                const cardCvv = document.getElementById('card_cvv').value;
                
                if (cardNumber.length < 13 || cardNumber.length > 19) {
                    alert('Please enter a valid card number');
                    e.preventDefault();
                    return;
                }
                
                if (!/^\d{2}\/\d{2}$/.test(cardExpiry)) {
                    alert('Please enter a valid expiry date (MM/YY)');
                    e.preventDefault();
                    return;
                }
                
                if (cardCvv.length < 3 || cardCvv.length > 4) {
                    alert('Please enter a valid CVV');
                    e.preventDefault();
                    return;
                }
            }
        });
    </script>
</body>
</html>