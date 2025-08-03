<?php
session_start();

$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

$checkoutItems = isset($_SESSION['checkout_items']) ? $_SESSION['checkout_items'] : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
		
		.container { 
		max-width: 1200px; 
		margin-top: 30px;
		margin-bottom: 50px;
		background: #fff; 
		padding: 60px; 
		border-radius: 12px; 
		box-shadow: 0 0 15px rgba(0,0,0,0.1);
		margin-left: 100px;
		align-items: center;
		justify-content: center;
		}
		
		h2 { 
		margin-bottom: 5px; 
		font-size: 2rem;
		text-align: center;
		}
		
		.item-block { 
		border-bottom: 1px solid #ccc; 
		padding: 20px 0; 
		margin-bottom: 15px; 
		}
		
		label { 
		font-weight: bold; 
		margin-bottom: 5px; 
		display: block;
		font-size: 1rem;
		}
		
		.name-columns {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
		gap: 10px;
		margin-top: 15px;
		}
		
		input[type="text"] { 
		width: 100%; 
		padding: 10px; 
		border: 1px solid #ccc; 
		border-radius: 6px; 
		font-size: 1rem; 
		}
		
		.submit-btn { 
		display: block;
		margin: 10px auto 0 auto;
		padding: 15px 40px; 
		background: #28a745; 
		color: white; 
		border: none; 
		border-radius: 10px; 
		font-size: 1.2rem; 
		cursor: pointer; 
		transition: background 0.3s;
		}
		
		.submit-btn:hover {
		background: #218838;
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
	
	<div class="container">
    <h2>Checkout - List Names</h2>
    <form action="payment.php" method="POST">
      <?php foreach ($checkoutItems as $index => $item): ?>
        <div class="item-block">
          <h4><?php echo htmlspecialchars($item['name']); ?> (<?php echo htmlspecialchars($item['qty']); ?> pcs)</h4>
          <p>Type: <?php echo htmlspecialchars($item['type']); ?> | Size: <?php echo htmlspecialchars($item['size']); ?></p>
          <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Item Image" style="max-width: 150px; margin-bottom: 10px;">
          
          <?php for ($i = 1; $i <= $item['qty']; $i++): ?>
            <label for="names_<?php echo $index; ?>_<?php echo $i; ?>">Name #<?php echo $i; ?></label>
            <input type="text" name="names[<?php echo $index; ?>][]" id="names_<?php echo $index; ?>_<?php echo $i; ?>" required>
          <?php endfor; ?>

          <!-- Pass product data -->
          <input type="hidden" name="items[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($item['name']); ?>">
          <input type="hidden" name="items[<?php echo $index; ?>][qty]" value="<?php echo $item['qty']; ?>">
          <input type="hidden" name="items[<?php echo $index; ?>][type]" value="<?php echo $item['type']; ?>">
          <input type="hidden" name="items[<?php echo $index; ?>][size]" value="<?php echo $item['size']; ?>">
          <input type="hidden" name="items[<?php echo $index; ?>][image]" value="<?php echo $item['image']; ?>">
        </div>
      <?php endforeach; ?>

      <button type="submit" class="submit-btn">Proceed to Payment</button>
    </form>
	</div>
	
    <div class="footer-c">
        <p>&copy; 2025 My Web Application</p>
    </div>

	<script>
	</script>
	
</body>
</html>