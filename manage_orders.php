<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['id']) || !isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include "config.php";

$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_order_status':
                $order_id = $_POST['order_id'];
                $status = $_POST['status'];
                $customer_email = $_POST['customer_email'];
                $customer_name = $_POST['customer_name'];
                
                $sql = "UPDATE orders SET status = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $status, $order_id);
                
                if ($stmt->execute()) {
                    // Send email notification to customer
                    $subject = "Order Status Update - Grand Print";
                    $message_body = "Dear $customer_name,\n\n";
                    $message_body .= "Your order #$order_id status has been updated to: $status\n\n";
                    $message_body .= "Thank you for choosing Grand Print!\n";
                    $message_body .= "Best regards,\nGrand Print Team";
                    
                    $headers = "From: admin@grandprint.com";
                    
                    if (mail($customer_email, $subject, $message_body, $headers)) {
                        $message = "Order status updated successfully! Email notification sent to customer.";
                    } else {
                        $message = "Order status updated successfully! Email notification failed to send.";
                    }
                } else {
                    $message = "Error updating order status: " . $conn->error;
                }
                break;
                
            case 'delete_order':
                $order_id = $_POST['order_id'];
                
                $sql = "DELETE FROM orders WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $order_id);
                
                if ($stmt->execute()) {
                    $message = "Order deleted successfully!";
                } else {
                    $message = "Error deleting order: " . $conn->error;
                }
                break;
        }
    }
}

// Fetch orders with customer information
$orders_sql = "SELECT o.*, u.name as customer_name, u.email as customer_email, u.`no phone` as customer_phone 
               FROM orders o 
               JOIN users_db u ON o.user_id = u.id 
               ORDER BY o.created_at DESC";
$orders_result = $conn->query($orders_sql);

// Get statistics
$stats_sql = "SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) as processing_orders,
                SUM(CASE WHEN status = 'Shipped' THEN 1 ELSE 0 END) as shipped_orders,
                SUM(CASE WHEN status = 'Delivered' THEN 1 ELSE 0 END) as delivered_orders,
                SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
                SUM(total) as total_revenue
               FROM orders";
$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders - Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
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
            padding: 0.6rem 7rem;
        }
        
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.7rem;
            color: #333;
            gap: 10px;
        }

        .logo-img {
            height: 50px;
            width: auto;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 5rem;
        }

        .nav-menu a {
            text-decoration: none;
            font-size: 1.3rem;
            color: #666;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-menu a:hover {
            color: #007bff;
        }

        .container {
            max-width: 1400px;
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

        .btn-back {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .btn-back:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }

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

        .btn-status, .btn-delete {
            padding: 0.25rem 0.5rem;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.8rem;
            margin: 0 0.1rem;
        }

        .btn-status {
            background: #007bff;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
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

        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .btn-submit {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-submit:hover {
            background: #0056b3;
        }

        .filters {
            background: white;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="adminhome.php" class="logo">
                <img src="GPlogo.png" alt="Grand Print Logo" class="logo-img">
                <span>Grand Print - Admin</span>
            </a>
            <ul class="nav-menu">
                <li><a href="adminhome.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="page-header">
            <a href="adminhome.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <h1>Order Management</h1>
            <p>Manage customer orders, update status, and track order progress.</p>
        </div>

        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'Error') !== false ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

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
                <div class="stat-number">RM <?php echo number_format($stats['total_revenue'], 2); ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="orders-container">
            <div class="orders-header">
                <div class="orders-title">All Orders</div>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($orders_result) {
                            while ($row = $orders_result->fetch_assoc()) {
                                $status_class = 'status-' . strtolower($row['status']);
                        ?>
                            <tr>
                                <td><strong>#<?php echo $row['id']; ?></strong></td>
                                <td>
                                    <strong><?php echo $row['customer_name']; ?></strong>
                                </td>
                                <td>
                                    <div><?php echo $row['customer_email']; ?></div>
                                    <small><?php echo $row['customer_phone']; ?></small>
                                </td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><strong>RM <?php echo number_format($row['total'], 2); ?></strong></td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span></td>
                                <td><?php echo date('M d, Y H:i', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <button class="btn-status" onclick="updateOrderStatus(<?php echo $row['id']; ?>, '<?php echo $row['customer_email']; ?>', '<?php echo $row['customer_name']; ?>')">
                                        Update Status
                                    </button>
                                    <button class="btn-delete" onclick="deleteOrder(<?php echo $row['id']; ?>)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php 
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Update Order Status Modal -->
    <div id="updateStatusModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('updateStatusModal')">&times;</span>
            <h2>Update Order Status</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update_order_status">
                <input type="hidden" name="order_id" id="orderId">
                <input type="hidden" name="customer_email" id="customerEmail">
                <input type="hidden" name="customer_name" id="customerName">
                <div class="form-group">
                    <label>New Status</label>
                    <select name="status" required>
                        <option value="Pending">Pending</option>
                        <option value="Processing">Processing</option>
                        <option value="Shipped">Shipped</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Update Status</button>
            </form>
        </div>
    </div>

    <script>
        function updateOrderStatus(orderId, customerEmail, customerName) {
            document.getElementById('orderId').value = orderId;
            document.getElementById('customerEmail').value = customerEmail;
            document.getElementById('customerName').value = customerName;
            document.getElementById('updateStatusModal').style.display = 'block';
        }

        function deleteOrder(orderId) {
            if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_order">
                    <input type="hidden" name="order_id" value="${orderId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html> 