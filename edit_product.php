<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['id']) || !isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include "config.php";

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_product':
                $id = $_POST['product_id'];
                $name = $_POST['product_name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $image = $_POST['image'];
                
                $sql = "UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssdsi", $name, $description, $price, $image, $id);
                
                if ($stmt->execute()) {
                    $message = "Product updated successfully!";
                } else {
                    $message = "Error updating product: " . $conn->error;
                }
                break;
                
            case 'delete_product':
                $id = $_POST['product_id'];
                
                $sql = "DELETE FROM products WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    $message = "Product deleted successfully!";
                } else {
                    $message = "Error deleting product: " . $conn->error;
                }
                break;
        }
    }
}

// Get product data if ID is provided
$product = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}

// Fetch all products
$products_sql = "SELECT * FROM products ORDER BY created_at DESC";
$products_result = $conn->query($products_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Products - Admin Dashboard</title>
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

        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-group input, .form-group textarea {
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

        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            margin-left: 1rem;
        }

        .btn-delete:hover {
            background: #c82333;
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

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-info {
            padding: 1rem;
        }

        .product-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .product-price {
            color: #007bff;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .product-actions {
            padding: 1rem;
            border-top: 1px solid #dee2e6;
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit {
            background: #ffc107;
            color: #212529;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.8rem;
            text-decoration: none;
        }

        .btn-edit:hover {
            background: #e0a800;
            color: #212529;
            text-decoration: none;
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
            <h1>Edit Products</h1>
            <p>Manage your product catalogue - add, edit, or delete products.</p>
        </div>

        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'Error') !== false ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Edit Form -->
        <?php if ($product): ?>
        <div class="form-container">
            <h2>Edit Product: <?php echo $product['name']; ?></h2>
            <form method="POST">
                <input type="hidden" name="action" value="update_product">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="product_name" value="<?php echo $product['name']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" required><?php echo $product['description']; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Price (RM)</label>
                    <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image" value="<?php echo $product['image']; ?>" required>
                </div>
                
                <button type="submit" class="btn-submit">Update Product</button>
                <button type="button" class="btn-delete" onclick="deleteProduct(<?php echo $product['id']; ?>)">Delete Product</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Products Grid -->
        <div class="products-grid">
            <?php
            if ($products_result) {
                while ($row = $products_result->fetch_assoc()) {
            ?>
                <div class="product-card">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="product-image">
                    <div class="product-info">
                        <div class="product-title"><?php echo $row['name']; ?></div>
                        <div class="product-price">RM <?php echo number_format($row['price'], 2); ?></div>
                        <p><?php echo $row['description']; ?></p>
                    </div>
                    <div class="product-actions">
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                        <button class="btn-delete" onclick="deleteProduct(<?php echo $row['id']; ?>)">Delete</button>
                    </div>
                </div>
            <?php 
                }
            }
            ?>
        </div>
    </div>

    <script>
        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_product">
                    <input type="hidden" name="product_id" value="${productId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html> 