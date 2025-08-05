<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include "config.php";

$user_id = $_SESSION['id'];
$user_name = $_SESSION['user_name'];

// Fetch user's orders
$orders_sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($orders_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();

// Get order statistics for the user
$stats_sql = "SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) as processing_orders,
                SUM(CASE WHEN status = 'Shipped' THEN 1 ELSE 0 END) as shipped_orders,
                SUM(CASE WHEN status = 'Delivered' THEN 1 ELSE 0 END) as delivered_orders,
                SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
                SUM(total) as total_spent
               FROM orders WHERE user_id = ?";
$stats_stmt = $conn->prepare($stats_sql);
$stats_stmt->bind_param("i", $user_id);
$stats_stmt->execute();
$stats_result = $stats_stmt->get_result();
$stats = $stats_result->fetch_assoc();

$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order History - Grand Print</title>
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

        .nav-menu a.active {
            color: #007bff;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
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

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .page-header h1 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: #666;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .btn-back:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }

        /* Statistics */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        /* Orders Container */
        .orders-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .orders-header {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .orders-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }

        .table-container {
            padding: 1.5rem;
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .table th, .table td {
            padding: 0.75rem;
            border-bottom: 1px solid #dee2e6;
            text-align: left;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-shipped {
            background: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .no-orders {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .no-orders i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        .btn-shop {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
        }

        .btn-shop:hover {
            background: #0056b3;
            color: white;
            text-decoration: none;
        }

        /* Footer */
        footer {
            background: white;
            padding: 10px 20px;
            display: flex;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 3rem;
        }

        .footer-container {
            color: #000; 
            max-width: 1200px;
            padding: 10px 50px 50px 50px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            line-height: 1;
            align-items: flex-start;
            width: 100%
        }

        .footer-logo {
            flex: 1 1 200px;
        }

        .footer-logo img {
            height: auto;
            width: 100px;
        }

        .footer-logo h3 {
            color: #000;
            font-size: 1.5rem;
        }

        .footer-logo p {
            line-height: 1.5;
            font-size: 1.2rem;
        }

        .footer-contact {
            flex: 1 1 200px;
            margin: 10px;
        }

        .footer-contact h4 {
            margin-bottom: 8px;
            font-size: 1.3rem;
        }

        .footer-contact p {
            line-height: 1.5;
            font-size: 1.2rem;
        }

        .footer-social {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 10px;
        }

        .footer-social a img{
            width: 50px;
            height: 40px;
            transition: all 0.3s ease;
        }

        .footer-social a img:hover {
            transform: scale(1.2);
        }

        @media(max-width: 768px) {
          .footer-container {
            flex-direction: column;
            align-items: center;
          }
          .footer-social {
            justify-content: center;
          }
        }

        .footer-c{
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 1.1rem;
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
                <li><a href="order_history.php" class="active">Order History</a></li>
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

    <div class="container">
        <div class="page-header">
            <a href="userhome.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
            <h1>Order History</h1>
            <p>Welcome back, <?php echo htmlspecialchars($user_name); ?>! Here's your complete order history.</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-shopping-cart" style="color: #007bff;"></i>
                <div class="stat-number"><?php echo $stats['total_orders']; ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock" style="color: #ffc107;"></i>
                <div class="stat-number"><?php echo $stats['pending_orders']; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-cog" style="color: #17a2b8;"></i>
                <div class="stat-number"><?php echo $stats['processing_orders']; ?></div>
                <div class="stat-label">Processing</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-shipping-fast" style="color: #28a745;"></i>
                <div class="stat-number"><?php echo $stats['shipped_orders']; ?></div>
                <div class="stat-label">Shipped</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle" style="color: #20c997;"></i>
                <div class="stat-number"><?php echo $stats['delivered_orders']; ?></div>
                <div class="stat-label">Delivered</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-dollar-sign" style="color: #6f42c1;"></i>
                <div class="stat-number">RM <?php echo number_format($stats['total_spent'], 2); ?></div>
                <div class="stat-label">Total Spent</div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="orders-container">
            <div class="orders-header">
                <div class="orders-title">Your Orders</div>
            </div>
            <div class="table-container">
                <?php if ($orders_result->num_rows > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $orders_result->fetch_assoc()): 
                                $status_class = 'status-' . strtolower($row['status']);
                            ?>
                                <tr>
                                    <td><strong>#<?php echo $row['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td><strong>RM <?php echo number_format($row['total'], 2); ?></strong></td>
                                    <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span></td>
                                    <td><?php echo date('M d, Y H:i', strtotime($row['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-orders">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>No Orders Yet</h3>
                        <p>You haven't placed any orders yet. Start shopping to see your order history here!</p>
                        <a href="productlogin.php" class="btn-shop">Start Shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class ="footer">
        <div class="footer-container">
            <!-- Logo and tagline -->
            <div class="footer-logo">
                <img src="GPlogo.png" alt="Grand Print Logo">
                <h3>Grand Print Enterprise</h3>
                <p>"From Student to Student"</p>
            </div>
        
            <!-- About Section -->
            <div class="footer-contact">
                <h3>Contact Us</h3>
                <p>No. 61, Jln TPS 3/13,</p>
                <p>Taman Pelangi Semenyih,</p>
                <p>43500 Semenyih, Selangor.</p>
                <br>
                </br>
                <h3>Number Phone:</h3>
                <p>+60 12-653 8249</p>
            </div>

            <!-- Email and Social Media -->
            <div class="footer-social">
                <a href="https://www.instagram.com/grandprint.my/?hl=en" target="_blank" aria-label="Instagram">
                    <img src="instagram.png" alt="Instagram" style="height: 50px;">
                </a>
                <a href="https://www.tiktok.com/@by.makcik?is_from_webapp=1&sender_device=pc" target="_blank" aria-label="Twitter">
                    <img src="twitter.png" alt="TikTok" style="height: 50px;">
                </a>
                <a href="https://www.facebook.com/MakCikSCH/" target="_blank" aria-label="Facebook">
                    <img src="facebook.png" alt="Facebook" style="height: 53px;">
                </a>
            </div>
            
            <div class="footer-c">
            <p>&copy; 2025 My Web Application</p>
            </div>
        </div>
    </footer>
</body>
</html>