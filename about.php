<?php
session_start();
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>About Us - Grand Print</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }

        .col-md-3 {
            flex: 0 0 25%;
            max-width: 25%;
            padding: 0 15px;
        }

        .col-md-4 {
            flex: 0 0 33.333%;
            max-width: 33.333%;
            padding: 0 15px;
        }

        .text-center {
            text-align: center;
        }

        .mb-5 {
            margin-bottom: 3rem;
        }

        .hero-section {
            background: linear-gradient(135deg,rgb(234, 225, 102) 0%,rgba(239, 227, 53, 0.83) 100%);
            color: black;
            padding: 100px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        .stats-section {
            background: white;
            padding: 80px 0;
			
        }

        .stat-card {
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
        }

        .stat-icon {
            font-size: 3rem;
            color: #F5CF27;
            margin-bottom: 20px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #666;
            font-size: 1.1rem;
        }

        .story-section {
            background: #f8f9fa;
            padding: 80px 0;
        }

        .story-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .story-title {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #333;
        }

        .story-text {
            font-size: 1.2rem;
            line-height: 1.8;
            color: #666;
            margin-bottom: 30px;
        }

        .services-section {
            background: white;
            padding: 80px 0;
        }

        .service-card {
            text-align: center;
            padding: 40px 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .service-icon {
            font-size: 3rem;
            color:rgb(222, 205, 15);
            margin-bottom: 20px;
        }

        .service-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        .service-description {
            color: #666;
            line-height: 1.6;
        }

        .team-section {
            background: #f8f9fa;
            padding: 80px 0;
        }

        .team-card {
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .team-card:hover {
            transform: translateY(-5px);
        }

        .team-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #F5CF27,rgb(208, 199, 17));
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }

        .team-name {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .team-role {
            color: #667eea;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .team-description {
            color: #666;
            line-height: 1.6;
        }

        .values-section {
            background: white;
            padding: 80px 0;
        }

        .value-card {
            text-align: center;
            padding: 40px 20px;
            border-radius: 15px;
            border: 2px solid #f0f0f0;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .value-card:hover {
            border-color: #F5CF27;
            transform: translateY(-5px);
        }

        .value-icon {
            font-size: 2.5rem;
            color: #F5CF27;
            margin-bottom: 20px;
        }

        .value-title {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .value-description {
            color: #666;
            line-height: 1.6;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
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
            <a href="userhome.php" class="logo">
                <img src="GPlogo.png" alt="Grand Print Logo" class="logo-img">
                <span class="grand-print">Grand Print</span>
            </a>
            <ul class="nav-menu">
                <li><a href="productlogin.php">Products</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="order_history.php">Order History</a></li>
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
	
	<!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">About Grand Print</h1>
                <p class="hero-subtitle">Your trusted partner in custom jersey printing and design</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card animate-on-scroll">
                        <div class="stat-icon">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <div class="stat-number">10,000+</div>
                        <div class="stat-label">Jerseys Printed</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card animate-on-scroll">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Happy Customers</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card animate-on-scroll">
                        <div class="stat-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="stat-number">5+</div>
                        <div class="stat-label">Years Experience</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card animate-on-scroll">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-number">4.9</div>
                        <div class="stat-label">Customer Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="story-section">
        <div class="container">
            <div class="story-content animate-on-scroll">
                <h2 class="story-title">Our Story</h2>
                <p class="story-text">
                    Founded in 2019, Grand Print started as a small local business with a big dream: to provide high-quality, 
                    custom jersey printing services to sports teams, schools, and organizations across Malaysia. 
                    What began as a passion project has grown into a trusted name in the industry.
                </p>
                <p class="story-text">
                    We believe that every team deserves to look professional and feel confident. That's why we've 
                    invested in the latest printing technology and partnered with the best suppliers to ensure 
                    your jerseys not only look great but last through countless games and washes.
                </p>
                <p class="story-text">
                    Today, we're proud to serve customers nationwide, offering everything from small team orders 
                    to large-scale corporate events. Our commitment to quality, customer service, and competitive 
                    pricing remains at the heart of everything we do.
                </p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <h2 class="text-center mb-5" style="font-size: 2.5rem; color: #333;">Our Services</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="service-card animate-on-scroll">
                        <div class="service-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h3 class="service-title">Custom Design</h3>
                        <p class="service-description">
                            Our expert designers work with you to create unique, professional jersey designs 
                            that represent your team's identity and spirit.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card animate-on-scroll">
                        <div class="service-icon">
                            <i class="fas fa-print"></i>
                        </div>
                        <h3 class="service-title">High-Quality Printing</h3>
                        <p class="service-description">
                            Using state-of-the-art printing technology to ensure vibrant colors, 
                            durable prints, and professional results that last.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card animate-on-scroll">
                        <div class="service-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3 class="service-title">Fast Delivery</h3>
                        <p class="service-description">
                            Quick turnaround times and reliable delivery to get your jerseys 
                            to you when you need them, no matter the order size.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <h2 class="text-center mb-5" style="font-size: 2.5rem; color: #333;">Our Team</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="team-card animate-on-scroll">
                        <div class="team-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 class="team-name">Aniq Aiman</h3>
                        <p class="team-role">Founder & CEO</p>
                        <p class="team-description">
                            With over 2 years of experience in the printing industry, 
                            Aniq leads our company with vision and dedication to quality.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="team-card animate-on-scroll">
                        <div class="team-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 class="team-name">Hafizul Azim</h3>
                        <p class="team-role">Design Manager</p>
                        <p class="team-description">
                            Hafizul's creative expertise ensures every jersey design 
                            is unique, professional, and perfectly represents your team.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="team-card animate-on-scroll">
                        <div class="team-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 class="team-name">Husnina</h3>
                        <p class="team-role">Production Manager</p>
                        <p class="team-description">
                            Husnina oversees our production process, ensuring 
                            every jersey meets our high quality standards.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <h2 class="text-center mb-5" style="font-size: 2.5rem; color: #333;">Our Values</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="value-card animate-on-scroll">
                        <div class="value-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="value-title">Quality</h3>
                        <p class="value-description">
                            We never compromise on quality, using only the best materials 
                            and printing techniques for every order.
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="value-card animate-on-scroll">
                        <div class="value-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="value-title">Trust</h3>
                        <p class="value-description">
                            Building long-term relationships through honest communication, 
                            reliable service, and consistent results.
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="value-card animate-on-scroll">
                        <div class="value-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 class="value-title">Innovation</h3>
                        <p class="value-description">
                            Continuously improving our processes and technology 
                            to deliver better results for our customers.
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="value-card animate-on-scroll">
                        <div class="value-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="value-title">Community</h3>
                        <p class="value-description">
                            Supporting local sports teams and organizations, 
                            helping build stronger communities through sport.
                        </p>
                    </div>
                </div>
            </div>
        </div>
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
        // Animate elements on scroll
        function animateOnScroll() {
            const elements = document.querySelectorAll('.animate-on-scroll');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.classList.add('animated');
                }
            });
        }

        // Add scroll event listener
        window.addEventListener('scroll', animateOnScroll);
        
        // Trigger on page load
        animateOnScroll();
    </script>
</body>
</html>