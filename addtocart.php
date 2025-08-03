<?php
session_start();

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Checkout with selected items
    if (isset($_POST['checkout_selected'])) {
        if (isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
            $selectedItems = $_POST['selected_items'];
            $_SESSION['checkout_items'] = [];
            foreach ($selectedItems as $index) {
                if (isset($_SESSION['cart'][$index])) {
                    $_SESSION['checkout_items'][] = $_SESSION['cart'][$index];
                }
            }
            header("Location: listname.php");
            exit();
        } else {
            $error_message = "Please select at least one item to checkout.";
        }
    }
    // Handle Delete
    elseif (isset($_POST['delete'])) {
        $deleteIndex = $_POST['delete_index'];
        if (isset($_SESSION['cart'][$deleteIndex])) {
            unset($_SESSION['cart'][$deleteIndex]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
        }
        header("Location: addtocart.php");
        exit();
    }
    // Handle Update (Edit)
    elseif (isset($_POST['update']) || isset($_POST['edit_index'])) {
        $index = $_POST['edit_index'];
        if (isset($_SESSION['cart'][$index])) {
            $_SESSION['cart'][$index] = [
                'product_id' => $_POST['product_id'],
                'name' => $_POST['product_name'],
                'qty' => $_POST['qty'],
                'cloth' => $_POST['cloth'],
                'collar' => $_POST['collar'],
                'size' => $_POST['size'],
                'image' => $_POST['product_image']
            ];
        }
        header("Location: addtocart.php");
        exit();
    }
    // Handle adding new items
    else {
        $item = [
            'product_id' => $_POST['product_id'],
            'name' => $_POST['product_name'],
            'qty' => $_POST['qty'],
            'cloth' => $_POST['cloth'],
            'collar' => $_POST['collar'],
            'size' => $_POST['size'],
            'image' => $_POST['product_image']
        ];
        $_SESSION['cart'][] = $item;
        header("Location: addtocart.php");
        exit();
    }
}

require 'db_conn.php'; // Ensure this exists and connects properly

$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand Print</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            min-height: 100vh;
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
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
		
		.title{
			font-size: 15px;
			position: center;
			display: flex;
		    justify-content: center;
		    margin-top: 30px;
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
			
		.footer-c{
			background-color: #000;
			color: #fff;
			text-align: center;
			padding: 10px;
			margin-top: 50px;
			width: 100%;
			font-size: 1.1rem;
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
		
		/* Modal Overlay */
		#editModal {
		  display: none;
		  position: fixed;
		  top: 0;
		  left: 0;
		  width: 100vw;
		  height: 100vh;
		  background-color: rgba(0,0,0,0.5);
		  z-index: 9999;
		  justify-content: center;
		  align-items: center;
		  transition: all 0.3s ease;
		}

		/* Modal Container */
		#editModal .modal-dialog {
		  background-color: #fff;
		  border-radius: 15px;
		  width: 100%;
		  max-width: 450px;
		  padding: 25px 30px;
		  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
		  position: relative;
		}

		/* Modal Header */
		#editModal .modal-header {
		  display: flex;
		  justify-content: space-between;
		  align-items: center;
		  border-bottom: none;
		  margin-bottom: 10px;
		}

		#editModal .modal-title {
		  font-size: 1.4rem;
		  font-weight: 600;
		  color: #333;
		}

		/* Close button */
		#editModal .btn-close {
		  background: none;
		  border: none;
		  font-size: 1.5rem;
		  color: #999;
		  cursor: pointer;
		}

		/* Modal Body */
		#editModal .modal-body {
		  padding: 10px 0;
		}

		/* Labels and Inputs */
		#editModal .form-label {
		  font-weight: 500;
		  margin-bottom: 5px;
		  display: block;
		  color: #444;
		}

		#editModal input[type="number"],
		#editModal select {
		  width: 100%;
		  padding: 10px 14px;
		  border: 1px solid #ddd;
		  border-radius: 8px;
		  margin-bottom: 15px;
		  font-size: 0.95rem;
		  outline: none;
		  transition: 0.3s;
		}

		#editModal input:focus,
		#editModal select:focus {
		  border-color: #007bff;
		  box-shadow: 0 0 0 2px rgba(0,123,255,0.2);
		}

		/* Footer Buttons */
		#editModal .modal-footer {
		  display: flex;
		  justify-content: flex-end;
		  gap: 10px;
		  margin-top: 10px;
		}

		#editModal .btn {
		  padding: 8px 20px;
		  font-size: 0.95rem;
		  font-weight: 500;
		  border-radius: 8px;
		  border: none;
		  cursor: pointer;
		  transition: 0.3s ease-in-out;
		}

		#editModal .btn-primary {
		  background-color: #007bff;
		  color: #fff;
		}

		#editModal .btn-danger {
		  background-color: #dc3545;
		  color: #fff;
		}

		#editModal .btn-secondary {
		  background-color: #6c757d;
		  color: #fff;
		}

		/* Checkbox styling */
		.checkbox-container {
		  display: flex;
		  align-items: center;
		  justify-content: center;
		  margin-top: 20px;
		}

		.custom-checkbox {
		  width: 18px;
		  height: 18px;
		  cursor: pointer;
		  accent-color: #007bff;
		}

		.checkout-section {
		  margin-top: 25px;
		  padding: 20px;
		  background: #f8f9fa;
		  border-radius: 10px;
		  display: flex;
		  justify-content: space-between;
		  align-items: center;
		}

		.selected-count {
		  color: #666;
		  font-size: 0.95rem;
		}

		.error-message {
		  color: #dc3545;
		  background: #f8d7da;
		  border: 1px solid #f5c6cb;
		  padding: 10px;
		  border-radius: 5px;
		  margin-bottom: 15px;
		  text-align: center;
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
    <!-- Your cart display code -->
    <div style="max-width: 1000px; margin: 0px auto 0 auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); padding: 30px;">
        <h1 style="text-align:center; margin-bottom: 30px;">Your Cart</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($_SESSION['cart'])): ?>
            <form method="POST" action="addtocart.php" id="cartForm">
                
                <table style="width:900px; border-collapse: collapse;">
                    <tr style="background: #f5f5f5;">
                        <th style="padding: 10px; width: 50px;">Select</th>
                        <th style="padding: 10px;">Image</th>
                        <th style="padding: 10px;">Product</th>
                        <th style="padding: 10px;">Quantity</th>
                        <th style="padding: 10px;">Cloth</th>
                        <th style="padding: 10px;">Collar</th>
                        <th style="padding: 10px;">Size</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                    <?php foreach ($_SESSION['cart'] as $idx => $item): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td class="checkbox-container">
                            <input type="checkbox" name="selected_items[]" value="<?php echo $idx; ?>" class="item-checkbox custom-checkbox">
                        </td>
                        <td style="text-align:center;"><img src="<?php echo htmlspecialchars($item['image'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? 'Product'); ?>" style="height:60px; width:auto; border-radius:8px; margin-top: 10px;"></td>
                        <td style="text-align:center; font-weight:600;"> <?php echo htmlspecialchars($item['name'] ?? 'N/A'); ?> </td>
                        <td style="text-align:center;"><?php echo htmlspecialchars($item['qty'] ?? '1'); ?> </td>
                        <td style="text-align:center;"><?php echo htmlspecialchars($item['cloth'] ?? 'Not specified'); ?></td>
                        <td style="text-align:center;"><?php echo htmlspecialchars($item['collar'] ?? 'Not specified'); ?></td>
                        <td style="text-align:center;"><?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?></td>
                        <td style="text-align:center;">
                            <button type="button" onclick='openEditModal(<?php echo $idx; ?>, <?php echo htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8'); ?>)' class="btn btn-sm btn-primary" style="margin-right:10px; padding:10px 20px; background:#F5CF27; color:black; border:none; border-radius:5px; cursor:pointer;">Edit</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <!-- Checkout Button -->
                <div class="checkout-section">
                    <div class="selected-count">
                        <span id="selectedCount">0</span> item(s) selected
                    </div>
                    <button type="submit" name="checkout_selected" id="checkoutBtn" style="background: maroon; color: #fff; padding: 12px 30px; border-radius: 25px; border: none; font-weight: 600; font-size: 1rem; cursor: pointer;" disabled>Checkout</button>
                </div>
            </form>
        <?php else: ?>
            <p style="text-align:center; color:#888; font-size:1.2rem;">Your cart is empty.</p>
        <?php endif; ?>
    </div>
</div>

	
	<div class="footer-c">
        <p>&copy; 2025 My Web Application</p>
    </div>

    <script>
        function openModal(productName, productImage) {
            document.getElementById('modalProductName').value = productName;
            document.getElementById('modalProductImage').value = productImage;
            document.getElementById('modalImagePreview').src = productImage;
            document.getElementById('cartModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('cartModal').style.display = 'none';
            document.getElementById('editModal').style.display = 'none';
        }

        function openEditModal(index, product) {
            document.getElementById('edit_index').value = index;
            document.getElementById('edit_product_id').value = product.product_id;
            document.getElementById('edit_product_name').value = product.name;
            document.getElementById('edit_product_image').value = product.image;
            document.getElementById('edit_qty').value = product.qty;
            document.getElementById('edit_cloth').value = product.cloth || '';
            document.getElementById('edit_collar').value = product.collar || '';
            document.getElementById('edit_size').value = product.size || '';
            document.getElementById('delete_index').value = index;
            document.getElementById('editModal').style.display = 'flex';
        }

        // Checkbox functionality
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const selectedCountSpan = document.getElementById('selectedCount');
            const checkoutBtn = document.getElementById('checkoutBtn');

            // Select All functionality
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateSelectedCount();
                });
            }

            // Individual checkbox functionality
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllState();
                    updateSelectedCount();
                });
            });

            function updateSelectAllState() {
                const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
                const allBoxes = document.querySelectorAll('.item-checkbox');
                
                if (selectAllCheckbox) {
                    if (checkedBoxes.length === 0) {
                        selectAllCheckbox.indeterminate = false;
                        selectAllCheckbox.checked = false;
                    } else if (checkedBoxes.length === allBoxes.length) {
                        selectAllCheckbox.indeterminate = false;
                        selectAllCheckbox.checked = true;
                    } else {
                        selectAllCheckbox.indeterminate = true;
                        selectAllCheckbox.checked = false;
                    }
                }
            }

            function updateSelectedCount() {
                const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
                const count = checkedBoxes.length;
                
                if (selectedCountSpan) {
                    selectedCountSpan.textContent = count;
                }
                
                if (checkoutBtn) {
                    if (count > 0) {
                        checkoutBtn.disabled = false;
                        checkoutBtn.style.opacity = '1';
                        checkoutBtn.style.cursor = 'pointer';
                    } else {
                        checkoutBtn.disabled = true;
                        checkoutBtn.style.opacity = '0.5';
                        checkoutBtn.style.cursor = 'not-allowed';
                    }
                }
            }

            // Initialize the state
            updateSelectedCount();
            updateSelectAllState();
        });
    </script>
	
	<!-- Modal -->
<div id="cartModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
    <div style="background:#fff; padding:30px; border-radius:12px; max-width:400px; width:90%; box-shadow:0 5px 20px rgba(0,0,0,0.3); position:relative;">
        <h2 style="margin-bottom:20px;">Customize Your Order</h2>
        <img id="modalImagePreview" src="" alt="Product Image" style="width: 100%; height: auto; border-radius: 10px; margin-bottom: 20px;">
		<form action="addtocart.php" method="POST">
            <input type="hidden" name="product_name" id="modalProductName">
            <input type="hidden" name="product_image" id="modalProductImage">
            <input type="hidden" name="product_id" value="custom001">

            <label>Quantity:</label>
            <input type="number" name="qty" value="1" min="1" required style="width:100%; padding:8px; margin-bottom:15px;">

            <label>Cloth:</label>
            <select name="cloth" required style="width:100%; padding:8px; margin-bottom:15px;">
                <option value="Select cloth">Select cloth</option>
                <option value="Short sleeve">Short sleeve</option>
				<option value="Long sleeve">Long sleeve</option>
                <option value="Muslimah">Muslimah</option>	
            </select>

            <label>Collar:</label>
            <select name="collar" required style="width:100%; padding:8px; margin-bottom:15px;">
                <option value="">Select collar</option>
                <option value="V-Neck">V-Neck</option>
                <option value="Polo">Polo</option>
                <option value="Mandarin button">Mandarin Button</option>
                <option value="Round Neck">Round Neck</option>
                <option value="Retro insert">Retro insert</option>
                <option value="Insert open">Insert open</option>
            </select>

            <label>Size:</label>
            <select name="size" required style="width:100%; padding:8px; margin-bottom:15px;">
                <option value="">Select size</option>
                <option value="S">Small (S)</option>
                <option value="M">Medium (M)</option>
                <option value="L">Large (L)</option>
                <option value="XL">Extra Large (XL)</option>
                <option value="XXL">Double Large (XXL)</option>
            </select>

            <div style="text-align:right;">
                <button type="submit" onclick="closeModal()" style="padding:10px 20px; background:#007bff; color:white; border:none; border-radius:5px;">Add</button>
                <button type="button" onclick="closeModal()" style="margin-left:10px; padding:10px 20px; background:#6c757d; color:white; border:none; border-radius:5px;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background-color:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Cancel Button -->
      <button type="button" class="btn-close" onclick="closeModal()" style="position: absolute; top: 10px; right: 15px; font-size: 1.5rem; border: none; background: none;">&times;</button>
      
      <!-- Update Form -->
      <form method="POST" action="addtocart.php" id="editForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Product</h5>
        </div>
        <div class="modal-body">
          <input type="hidden" name="edit_index" id="edit_index">
          <input type="hidden" name="product_id" id="edit_product_id">
          <input type="hidden" name="product_name" id="edit_product_name">
          <input type="hidden" name="product_image" id="edit_product_image">
		  <input type="hidden" name="delete_index" id="delete_index" value="">
          
		  <label>Quantity:</label>
          <input type="number" class="form-control" name="qty" id="edit_qty" min="1" required>

          <label>Cloth Type:</label>
          <select name="cloth" id="edit_cloth" required style="width:100%; padding:10px 14px; border:1px solid #ddd; border-radius:8px; margin-bottom:15px;">
                <option value="">Select cloth</option>
                <option value="Short sleeve">Short sleeve</option>
				<option value="Long sleeve">Long sleeve</option>
                <option value="Muslimah">Muslimah</option>	
          </select>

          <label>Collar Type:</label>
          <select class="form-select" name="collar" id="edit_collar" required>
            <option value="">Select collar</option>
            <option value="V-Neck">V-Neck</option>
            <option value="Polo">Polo</option>
            <option value="Mandarin button">Mandarin Button</option>
            <option value="Round Neck">Round Neck</option>
            <option value="Retro insert">Retro insert</option>
            <option value="Insert open">Insert open</option>
          </select>

          <label>Size:</label>
          <select class="form-select" name="size" id="edit_size" required>
            <option value="">Select size</option>
            <option value="S">Small (S)</option>
            <option value="M">Medium (M)</option>
            <option value="L">Large (L)</option>
            <option value="XL">Extra Large (XL)</option>
            <option value="XXL">Double Large (XXL)</option>
          </select>
        </div>
        <div class="modal-footer" style="justify-content: space-between;">
            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');" style="background:#dc3545; color:#fff; border:none; border-radius:5px; padding:10px 20px;">Delete</button>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>