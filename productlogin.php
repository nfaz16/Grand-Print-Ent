<?php
session_start();
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Product - Grand Print</title>
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
		
		.title {
			font-size: 15px;
			position: center;
			display: flex;
			justify-content: center;
			margin-top: 30px;
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

        /* Products Container */
        .products-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-bottom: 50px;
        }

        @media (max-width: 800px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        /* Slideshow Styles */
        .slideshow-container {
            position: relative;
            width: 100%;
            height: 350px;
            overflow: hidden;
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .slide.active {
            opacity: 1;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .slide-nav {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .slide-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: background 0.3s;
        }

        .slide-dot.active {
            background: white;
        }

        .slide-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
            z-index: 10;
        }

        .slide-arrow:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        .slide-arrow.prev {
            left: 15px;
        }

        .slide-arrow.next {
            right: 15px;
        }

        .product-details {
            padding: 20px;
            text-align: center;
        }

        .buy-button {
            display: inline-block;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .buy-button:hover {
            background: linear-gradient(135deg, #0056b3, #003d82);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        .buy-button:active {
            transform: translateY(0);
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
                <span>Grand Print</span>
            </a>
            <ul class="nav-menu">
                <li><a href="products.php">Products</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="order_history.php">Order History</a></li>
				<a href="addtocart.php" class="cart-link" style="position:relative; margin-left: 18px;">
				  <i class="fas fa-shopping-cart" style="font-size: 1.5rem;"></i>
				  <span class="cart-count" id="cartCount">
					  <?php echo $cartCount; ?>
					</span>
				</a>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
	
	<div class = "title">
	<h1>Discover Our Latest Collection!</h1>
	</div>

    <div class="products-container">
        <div class="products-grid">
            <!-- Product 1 -->
            <div class="product-card">
                <div class="slideshow-container">
                    <div class="slide active">
                        <img src="1.jpg" alt="Custom T-Shirt Design - Front">
                    </div>
                    <div class="slide">
                        <img src="2.jpg" alt="Custom T-Shirt Design - Back">
                    </div>
                    <div class="slide-nav">
                        <span class="slide-dot active" onclick="currentSlide(this.parentElement.parentElement, 0)"></span>
                        <span class="slide-dot" onclick="currentSlide(this.parentElement.parentElement, 1)"></span>
                    </div>
                </div>
                <div class="product-details">
					<button class="buy-button" onclick="openModal('Modern T-Shirt Design 1', '1.jpg', 'product001')">Add to cart</button>
                </div>
            </div>

            <!-- Product 2 -->
            <div class="product-card">
                <div class="slideshow-container">
                    <div class="slide active">
                        <img src="3.jpg" alt="Custom T-Shirt Design - Front">
                    </div>
                    <div class="slide">
                        <img src="4.jpg" alt="Custom T-Shirt Design - Back">
                    </div>
                    <div class="slide-nav">
                        <span class="slide-dot active" onclick="currentSlide(this.parentElement.parentElement, 0)"></span>
                        <span class="slide-dot" onclick="currentSlide(this.parentElement.parentElement, 1)"></span>
                    </div>
                </div>
                <div class="product-details">
					<button class="buy-button" onclick="openModal('Modern T-Shirt Design 2', '3.jpg', 'product002')">Add to cart</button>
                </div>
            </div>

            <!-- Product 3 -->
            <div class="product-card">
                <div class="slideshow-container">
                    <div class="slide active">
                        <img src="5.jpg" alt="Custom T-Shirt Design - Front">
                    </div>
                    <div class="slide">
                        <img src="6.jpg" alt="Custom T-Shirt Design - Back">
                    </div>
                    <div class="slide-nav">
                        <span class="slide-dot active" onclick="currentSlide(this.parentElement.parentElement, 0)"></span>
                        <span class="slide-dot" onclick="currentSlide(this.parentElement.parentElement, 1)"></span>
                    </div>
                </div>
                <div class="product-details">
					<button class="buy-button" onclick="openModal('Modern T-Shirt Design 3', '5.jpg', 'product003')">Add to cart</button>
                </div>
            </div>

            <!-- Product 4 -->
            <div class="product-card">
                <div class="slideshow-container">
                    <div class="slide active">
                        <img src="7.jpg" alt="Custom T-Shirt Design - Front">
                    </div>
                    <div class="slide">
                        <img src="8.jpg" alt="Custom T-Shirt Design - Back">
                    </div>
                    <div class="slide-nav">
                        <span class="slide-dot active" onclick="currentSlide(this.parentElement.parentElement, 0)"></span>
                        <span class="slide-dot" onclick="currentSlide(this.parentElement.parentElement, 1)"></span>
                    </div>
                </div>
                <div class="product-details">
					<button class="buy-button" onclick="openModal('Modern T-Shirt Design 4', '7.jpg', 'product004')">Add to cart</button>
                </div>
            </div>

            <!-- Product 5 -->
            <div class="product-card">
                <div class="slideshow-container">
                    <div class="slide active">
                        <img src="9.jpg" alt="Custom T-Shirt Design - Front">
                    </div>
                    <div class="slide">
                        <img src="10.jpg" alt="Custom T-Shirt Design - Back">
                    </div>
                    <div class="slide-nav">
                        <span class="slide-dot active" onclick="currentSlide(this.parentElement.parentElement, 0)"></span>
                        <span class="slide-dot" onclick="currentSlide(this.parentElement.parentElement, 1)"></span>
                    </div>
                </div>
                <div class="product-details">
					<button class="buy-button" onclick="openModal('Modern T-Shirt Design 5', '9.jpg', 'product005')">Add to cart</button>
                </div>
            </div>

            <!-- Product 6 - FIXED -->
            <div class="product-card">
                <div class="slideshow-container">
                    <div class="slide active">
                        <img src="11.jpg" alt="Custom T-Shirt Design - Front">
                    </div>
                    <div class="slide">
                        <img src="12.jpg" alt="Custom T-Shirt Design - Back">
                    </div>
                    <div class="slide-nav">
                        <span class="slide-dot active" onclick="currentSlide(this.parentElement.parentElement, 0)"></span>
                        <span class="slide-dot" onclick="currentSlide(this.parentElement.parentElement, 1)"></span>
                    </div>
                </div>
                <div class="product-details">
					<button class="buy-button" onclick="openModal('Modern T-Shirt Design 6', '11.jpg', 'product006')">Add to cart</button>
                </div>
            </div>
        </div>
    </div>
	
    <div class="footer-c">
        <p>&copy; 2025 My Web Application</p>
    </div>

    <script>
        // Slideshow functionality
        function changeSlide(container, direction) {
            const slides = container.querySelectorAll('.slide');
            const dots = container.querySelectorAll('.slide-dot');
            let currentIndex = 0;
            
            // Find current active slide
            slides.forEach((slide, index) => {
                if (slide.classList.contains('active')) {
                    currentIndex = index;
                }
            });
            
            // Calculate new index
            let newIndex = currentIndex + direction;
            if (newIndex >= slides.length) {
                newIndex = 0;
            } else if (newIndex < 0) {
                newIndex = slides.length - 1;
            }
            
            // Update slides and dots
            slides[currentIndex].classList.remove('active');
            slides[newIndex].classList.add('active');
            dots[currentIndex].classList.remove('active');
            dots[newIndex].classList.add('active');
        }
        
        function currentSlide(container, index) {
            const slides = container.querySelectorAll('.slide');
            const dots = container.querySelectorAll('.slide-dot');
            
            // Remove active class from all slides and dots
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Add active class to selected slide and dot
            slides[index].classList.add('active');
            dots[index].classList.add('active');
        }
        
        // Auto-advance slides every 5 seconds
        setInterval(function() {
            const containers = document.querySelectorAll('.slideshow-container');
            containers.forEach(container => {
                const slides = container.querySelectorAll('.slide');
                const dots = container.querySelectorAll('.slide-dot');
                let currentIndex = 0;
                
                // Find current active slide
                slides.forEach((slide, index) => {
                    if (slide.classList.contains('active')) {
                        currentIndex = index;
                    }
                });
                
                // Move to next slide
                let nextIndex = (currentIndex + 1) % slides.length;
                slides[currentIndex].classList.remove('active');
                slides[nextIndex].classList.add('active');
                dots[currentIndex].classList.remove('active');
                dots[nextIndex].classList.add('active');
            });
        }, 5000);
		
		function openModal(productName, productImage, productId) {
			document.getElementById('modalProductName').value = productName;
			document.getElementById('modalProductImage').value = productImage;
			document.getElementById('modalProductId').value = productId;
			document.getElementById('modalImagePreview').src = productImage;
			document.getElementById('cartModal').style.display = 'flex';
		}

		function closeModal() {
			document.getElementById('cartModal').style.display = 'none';
		}
		
		// Close modal when clicking outside
		document.getElementById('cartModal').addEventListener('click', function(e) {
			if (e.target === this) {
				closeModal();
			}
		});
	
    </script>

</body>

<!-- Modal -->
<div id="cartModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:650px; background-color:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
    <div style="background:#fff; padding:30px; border-radius:12px; margin-top: 15px; max-width:500px; height: 650px; width:90%; box-shadow:0 5px 20px rgba(0,0,0,0.3); position:relative;">
        <h2 style="margin-bottom:10px; font-size:25px; align--items:center;">Customize Your Order</h2>
        <img id="modalImagePreview" src="" alt="Product Image" style="width: 50%; height: auto; border-radius: 10px; margin-bottom: 20px;">
		<form action="addtocart.php" method="POST">
            <input type="hidden" name="product_name" id="modalProductName">
            <input type="hidden" name="product_image" id="modalProductImage">
            <input type="hidden" name="product_id" id="modalProductId">

            <label style="display:block; margin-bottom:5px; font-weight:500;">Quantity:</label>
            <input type="number" name="qty" value="1" min="1" max="1000" required style="width:100%; padding:8px; margin-bottom:5px; border:1px solid #ddd; border-radius:5px;">

            <label style="display:block; margin-bottom:5px; font-weight:500;">Cloth:</label>
            <select name="cloth" required style="width:100%; padding:8px; margin-bottom:10px; border:1px solid #ddd; border-radius:5px;">
                <option value="">Select cloth</option>
                <option value="short">Short sleeve</option>
				<option value="long">Long sleeve</option>
                <option value="muslimah">Muslimah</option>
            </select>

            <label style="display:block; margin-bottom:5px; font-weight:500;">Collar:</label>
            <select name="collar" required style="width:100%; padding:8px; margin-bottom:10px; border:1px solid #ddd; border-radius:5px;">
                <option value="">Select collar</option>
                <option value="V-Neck">V-Neck</option>
				<option value="Polo">Polo</option>
                <option value="Mandarin button">Mandarin Button</option>
				<option value="Round Neck">Round Neck</option>
				<option value="Retro insert">Retro insert</option>
				<option value="Insert open">Insert open</option>
            </select>

            <label style="display:block; margin-bottom:5px; font-weight:500;">Size:</label>
            <select name="size" required style="width:100%; padding:8px; margin-bottom:10px; border:1px solid #ddd; border-radius:5px;">
                <option value="">Select size</option>
                <option value="S">Small (S)</option>
                <option value="M">Medium (M)</option>
                <option value="L">Large (L)</option>
                <option value="XL">Extra Large (XL)</option>
                <option value="XXL">Double Large (XXL)</option>
            </select>

            <div style="text-align:right; margin-top:20px;">
                <button type="button" onclick="closeModal()" style="margin-right:10px; padding:10px 10px; background:#6c757d; color:white; border:none; border-radius:5px; cursor:pointer;">Cancel</button>
                <button type="submit" style="padding:10px 10px; background:#007bff; color:white; border:none; border-radius:5px; cursor:pointer;">Add to Cart</button>
            </div>
        </form>
    </div>
</div>

</html>