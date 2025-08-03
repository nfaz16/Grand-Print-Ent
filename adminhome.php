<?php 

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['user_name']))

?>
<?php
include "config.php";

$sql = "SELECT * FROM users_db";

$result = $conn->query($sql);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_product':
                $name = $_POST['product_name'];
                $description = $_POST['description'];
                $base_price = $_POST['base_price'];
                $image = $_POST['image'];
                $product_type = $_POST['product_type'];
                
                $sql = "INSERT INTO products (name, description, base_price, image, product_type) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssdss", $name, $description, $base_price, $image, $product_type);
                $stmt->execute();
                break;
                
            case 'update_order_status':
                $order_id = $_POST['order_id'];
                $status = $_POST['status'];
                $customer_email = $_POST['customer_email'];
                
                $sql = "UPDATE orders SET status = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $status, $order_id);
                $stmt->execute();
                
                // Send email notification to customer
                $subject = "Order Status Update";
                $message = "Your order #$order_id status has been updated to: $status";
                $headers = "From: admin@grandprint.com";
                $message_sent = "Order status updated successfully! Email notification would be sent to: $customer_email";                break;
        }
    }
}

// Fetch data from your grandprint database
$users_sql = "SELECT * FROM users_db";
$users_result = $conn->query($users_sql);

$products_sql = "SELECT * FROM products";
$products_result = $conn->query($products_sql);

$orders_sql = "SELECT o.*, u.name as customer_name, u.email as customer_email 
               FROM orders o 
               JOIN users_db u ON o.user_id = u.id 
               ORDER BY o.created_at DESC";
$orders_result = $conn->query($orders_sql);


// Get pricing statistics
$pricing_tiers_sql = "SELECT * FROM pricing_tiers ORDER BY min_quantity";
$pricing_tiers_result = $conn->query($pricing_tiers_sql);

$size_surcharges_sql = "SELECT * FROM size_surcharges ORDER BY surcharge";
$size_surcharges_result = $conn->query($size_surcharges_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Grand Print</title>
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

        /* Dashboard Styles */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .welcome-section {
            background: linear-gradient(135deg,rgba(255, 234, 0, 0.87),rgb(181, 161, 7));
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
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
            font-size: 2.5rem;
            color: #F5CF27;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .section-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .section-header {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }

        .btn-add {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-add:hover {
            background: #218838;
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

        .btn-edit, .btn-delete, .btn-status {
            padding: 0.25rem 0.5rem;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.8rem;
            margin: 0 0.1rem;
        }

        .btn-edit {
            background: #ffc107;
            color: #212529;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-status {
            background: #007bff;
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

        /* Modal Styles */
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
            max-width: 600px;
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

        .form-group input, .form-group textarea, .form-group select {
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

        .tabs {
            display: flex;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 1rem;
        }

        .tab {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
        }

        .tab.active {
            border-bottom-color: #007bff;
		  color: #007bff;
		}

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
	</style>
</head>

<body>
	<!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="adminhome.php" class="logo">
                <img src="GPlogo.png" alt="Grand Print Logo" class="logo-img">
                <span class="grand-print">Grand Print</span>
            </a>
            <ul class="nav-menu">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
	
    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1>Welcome, <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin'; ?></h1>
            <p>Manage your catalogue, orders, and customer information from this dashboard.</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <div class="stat-number"><?php echo $users_result ? $users_result->num_rows : 0; ?></div>
                <div class="stat-label">Total Customers</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-box"></i>
                <div class="stat-number"><?php echo $products_result ? $products_result->num_rows : 0; ?></div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-shopping-cart"></i>
                <div class="stat-number"><?php echo $orders_result ? $orders_result->num_rows : 0; ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <div class="stat-number">
                    <?php 
                    if ($orders_result) {
                        $pending_count = 0;
                        while ($row = $orders_result->fetch_assoc()) {
                            if ($row['status'] == 'Pending') $pending_count++;
                        }
                        echo $pending_count;
                        $orders_result->data_seek(0); // Reset pointer
                    } else {
                        echo 0;
                    }
                    ?>
                </div>
                <div class="stat-label">Pending Orders</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" onclick="showTab('orders')">Order Management</div>
            <div class="tab" onclick="showTab('products')">Catalogue Management</div>
            <div class="tab" onclick="showTab('customers')">Customer Information</div>
        </div>

        <!-- Order Management Tab -->
        <div id="orders" class="tab-content active">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-title">Order Management</div>
                    <a href="manage_orders.php" class="btn-add" style="text-decoration: none; color: white;">Detailed Order Management</a>
                </div>
                <div class="table-container">
		<table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
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
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td><?php echo $row['customer_name']; ?><br><small><?php echo $row['customer_email']; ?></small></td>
                                    <td><?php echo $row['product_name']; ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td>RM <?php echo number_format($row['total'], 2); ?></td>
                                    <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <button class="btn-status" onclick="updateOrderStatus(<?php echo $row['id']; ?>, '<?php echo $row['customer_email']; ?>')">
                                            Update Status
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

        <!-- Catalogue Management Tab -->
        <div id="products" class="tab-content">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-title">Catalogue Management</div>
                    <div style="display: flex; gap: 1rem;">
                        <button class="btn-add" onclick="showAddProductModal()">Add New Product</button>
                        <a href="edit_product.php" class="btn-add" style="text-decoration: none; color: white;">Manage Products</a>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table">
		<thead>
			<tr>
			<th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($products_result) {
                                while ($row = $products_result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="width: 50px; height: 50px; object-fit: cover;"></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td>RM <?php echo number_format($row['price'], 2); ?></td>
                                    <td>
                                        <button class="btn-edit" onclick="editProduct(<?php echo $row['id']; ?>)">Edit</button>
                                        <button class="btn-delete" onclick="deleteProduct(<?php echo $row['id']; ?>)">Delete</button>
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

        <!-- Customer Information Tab -->
        <div id="customers" class="tab-content">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-title">Customer Information</div>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
			<th>Name</th>
			<th>Email</th>
                                <th>Phone</th>
                                <th>Registration Date</th>
			</tr>
		</thead>
		<tbody> 
			<?php
                            if ($users_result) {
                                while ($row = $users_result->fetch_assoc()) {
                            ?>
                                <tr>
						<td><?php echo $row['id']; ?></td>
						<td><?php echo $row['name']; ?></td>
						<td><?php echo $row['email']; ?></td>
						<td><?php echo $row['no phone']; ?></td>
                                    <td><?php echo isset($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : 'N/A'; ?></td>
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
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addProductModal')">&times;</span>
            <h2>Add New Product</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_product">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="product_name" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Price (RM)</label>
                    <input type="number" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image" required>
                </div>
                <button type="submit" class="btn-submit">Add Product</button>
            </form>
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
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }

        function showAddProductModal() {
            document.getElementById('addProductModal').style.display = 'block';
        }

        function updateOrderStatus(orderId, customerEmail) {
            document.getElementById('orderId').value = orderId;
            document.getElementById('customerEmail').value = customerEmail;
            document.getElementById('updateStatusModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function editProduct(productId) {
            window.location.href = 'edit_product.php?id=' + productId;
        }

        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                window.location.href = 'edit_product.php?delete=' + productId;
            }
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