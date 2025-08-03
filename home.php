<?php
session_start();
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
		
		.title{
			font-size: 15px;
			position: center;
			display: flex;
		    justify-content: center;
		    margin-top: 30px;
		}
		
		.banner-container {
            overflow: hidden;
            height: 600px;
			max-width: 1200px;
			display: flex;
			justify-content: center;
			align-items: center;
			margin: 0 auto;
			position: relative;
        }
		
		/* Gallery Grid for Home */
        .home-gallery {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            max-width: 1000px;
            margin: 40px auto 0 auto;
            padding: 0 10px;
        }
        .home-gallery-box {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 390px;
        }
        .home-gallery-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        @media (max-width: 800px) {
            .home-gallery {
                grid-template-columns: 1fr;
            }
        }
		
		/* Container to center the button */
		.button-container {
		    display: flex;
		    justify-content: center;
		    padding: 50px 30px;
		    margin-top: 20px; /* Optional: space above the button */
		}

		/* Style the button link */
		.order-button {
		    padding: 15px 30px;
		    background-color: #000;
		    color: white;
		    font-size: 18px;
		    text-decoration: none;
		    border-radius: 8px;
		    transition: background-color 0.3s, transform 0.3s; /* Smooth hover effects */
		}

		/* Hover effect */
		.order-button:hover {
		    background-color: #F5CF27;
		    transform: scale(1.05); /* Slightly enlarge on hover */
			color: black;
		}
		
		/* About */
		about {
            display: flex;
            line-weight: 1.6;
			justify-content: center;
			font-size: 1rem;
        }
		
		/* Container to organize about content */
		.about-container{
			background: #F5CF27;
            font-size: 1.3rem;
			max-width: 1200px;
			font-weight: bold;
			margin-bottom: 30px;
			justify-content: center;
			color: #000;
			padding: 30px 90px 30px 90px;
			text-align: center;
			margin: 0 auto;			
        }
		
		.about-container h2{
            text-align: center;		
        }
		
		/* Footer */
		footer {
            background: white;
			padding: 10px 20px;
			display: flex;
			justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
		
		/* Container to organize footer content */
		.footer-container{
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
            <a href="home.php" class="logo">
			<img src="GPlogo.png" alt="Grand Print Logo" class="logo-img">
    <span>Grand Print</span></a>
            <ul class="nav-menu">
                <li><a href="products.php">Products</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
				<a href="addtocart.php" class="cart-link" style="position:relative; margin-left: 18px;">
				  <i class="fas fa-shopping-cart" style="font-size: 1.5rem;"></i>
				  <span class="cart-count" id="cartCount" style="position:absolute; top:-8px; right:-10px; background:#dc3545; color:#fff; border-radius:50%; padding:2px 7px; font-size:0.9rem; font-weight:bold;">
					0
				  </span>
				</a>
				<br><li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>
	
	<div class = "title">
	<h1>Welcome to Grand Print Enterprise</h1>
	</div><br>
	
	<section class="banner-container">
        <div class="slide fade">
            <img src="offer1.jpg" alt="1">
            <div class="caption"></div>
        </div>
		
        <div class="slide fade">
            <img src="offer2.jpg" alt="2">
            <div class="caption"></div>
        </div>
	</section>
	<br>
	<!-- Replace slideshow with gallery grid -->
	<div class="home-gallery">
		<div class="home-gallery-box">
			<img src="orderprocess.jpg" alt="Order Process">
		</div>
		<div class="home-gallery-box">
			<img src="sizechart.jpg" alt="Size Chart">
		</div>
		<div class="home-gallery-box">
			<img src="collartype.jpg" alt="Collar Type">
		</div>
		<div class="home-gallery-box">
			<img src="textile.jpg" alt="Textile">
		</div>
	</div>
	
		<div class="button-container">
		  <a href="login.php" class="order-button">Create 3D design Now!</a>
		</div>
		
	<section class=about>
		<div class="about-container">
		<h2>About Grand Print</h2>
		<p>Grand Print Enterprise, established in September 2023, is a growing business dedicated to providing high-quality production and design services. The company specializes in the production of jerseys, corporate apparel, lanyards, and tote bags while offering custom design services to meet the unique needs of its clients.</p>
		<br>
		<h2>Our Aims</h2>
		<p>Grand Print Enterprise aims to cater to the younger generation and student communities, delivering professional-grade products at competitive prices.</p>
		<br>
		<h2>Our Motto</h2>
		<p>“From Student to Student"</p>
		</div><br>
	</section>
	
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
	
    <script>
		 let currentSlide = 0;

        // Function to show a specific slide
        function showSlide(index) {
            const slides = document.querySelectorAll(".slide");

            // Reset slide index if it goes out of bounds
            if (index >= slides.length) {
                currentSlide = 0;
            }
            if (index < 0) {
                currentSlide = slides.length - 1;
            }

            // Hide all slides
            slides.forEach(slide => (slide.style.display = "none"));

            // Display the current slide
            slides[currentSlide].style.display = "block";
        }

        // Function to change slides
        function changeSlide(step) {
            showSlide((currentSlide += step));
        }

        // Automatically cycle through slides
        function autoPlaySlides() {
            changeSlide(1);
            setTimeout(autoPlaySlides, 5000); // Change every 5 seconds
        }

        // Initialize slideshow on page load
        document.addEventListener("DOMContentLoaded", () => {
            showSlide(currentSlide);
            autoPlaySlides();
        });
    </script>
</body>
</html>