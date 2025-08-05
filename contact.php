<?php
session_start();
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Us - Grand Print</title>
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
            padding: 80px 0;
            text-align: center;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .contact-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .contact-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
			height: 300px; /* Fixed height for all cards */
        }

        .contact-card:hover {
            transform: translateY(-5px);
        }

        .contact-icon {
            font-size: 2.5rem;
            color: #F5CF27;
            margin-bottom: 20px;
        }

        .contact-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .contact-info {
            color: #666;
            line-height: 1.6;
        }

        .form-section {
            padding: 80px 0;
            background: white;
			display: flex;
            justify-content: center;
            align-items: center;
        }

        .contact-form {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
        }

		.form-group label {
            display: block;
            margin-bottom: 8px; /* Space between label and input */
            font-weight: 500;
            color: #333;
        }

        .form-group {
            margin-bottom: 35px;
			margin-right: 40px;
        }

		.form-row {
            display: flex;
            gap: 20px; /* Gap between side-by-side form fields */
            margin-bottom: 30px;
        }

		.form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px 18px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
			margin-bottom: 5px;
			width: 100%;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
			outline: none;
        }

        .btn-submit {
            background: #F5CF27;
            border: none;
            color: black;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 500;
            transition: all 0.3s ease;
			cursor: pointer;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .map-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .map-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .map-frame {
            width: 100%;
            height: 400px;
            border: none;
        }

        .hours-section {
            padding: 80px 0;
            background: white;
			display: flex;
            justify-content: center;
            align-items: center;
        }

        .hours-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
        }

        .day-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .day-row:last-child {
            border-bottom: none;
        }

        .day-name {
            font-weight: 500;
            color: #333;
			margin-right: 40px;
        }

        .day-hours {
            color: #666;
        }

        .social-section {
            padding: 60px 0;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-align: center;
        }

        .social-icon {
            font-size: 2rem;
            margin: 0 15px;
            color: white;
            transition: transform 0.3s ease;
        }

        .social-icon:hover {
            transform: translateY(-5px);
        }

        .faq-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .faq-item {
            background: white;
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .faq-question {
            background: #F5CF27;
            color: white;
            padding: 20px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .faq-question:hover {
            background: #5a6fd8;
        }

        .faq-answer {
            padding: 20px;
            color: #666;
            line-height: 1.6;
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
            <h1 class="hero-title">Get In Touch</h1>
            <p class="hero-subtitle">We'd love to hear from you. Contact us for any questions about our services.</p>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="contact-card animate-on-scroll">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3 class="contact-title">Our Location</h3>
                        <p class="contact-info">
							No. 61, Jln TPS 3/13,<br>
                            Taman Pelangi Semenyih,<br>
                            43500 Semenyih, Selangor.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card animate-on-scroll">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3 class="contact-title">Phone</h3>
                        <p class="contact-info">
                            +60 12-653 8249<br>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card animate-on-scroll">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="contact-title">Email</h3>
                        <p class="contact-info">
                            grandprint@gmail.com<br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="form-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="contact-form animate-on-scroll">
                        <h2 class="text-center mb-4" style="color: #333;">Send Us a Message</h2><br>
                        <form method="post" action="process_contact.php">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="first_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="last_name" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="tel" name="phone" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Subject</label>
                                <select name="subject" class="form-control" required>
                                    <option value="">Select a subject</option>
                                    <option value="General Inquiry">General Inquiry</option>
                                    <option value="Jersey Order">Jersey Order</option>
                                    <option value="Design Consultation">Design Consultation</option>
                                    <option value="Pricing Quote">Pricing Quote</option>
                                    <option value="Support">Technical Support</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea name="message" class="form-control" rows="5" required placeholder="Tell us about your project..."></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-submit">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <h2 class="text-center mb-5" style="font-size: 2.5rem; color: #333;">Find Us</h2>
            <div class="map-container animate-on-scroll">
                <iframe 
                    class="map-frame"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.819123456789!2d101.686855!3d3.139003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM8KwMDgnMjAuNCJOIDEwMcKwNDEnMTIuNyJF!5e0!3m2!1sen!2smy!4v1234567890"
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </section>

    <!-- Business Hours Section -->
    <section class="hours-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="hours-card animate-on-scroll">
                        <h3 class="text-center mb-4" style="color: #333;">Business Hours</h3>
                        <div class="day-row">
                            <span class="day-name">Monday - Friday</span>
                            <span class="day-hours">9:00 AM - 6:00 PM</span>
                        </div>
                        <div class="day-row">
                            <span class="day-name">Saturday</span>
                            <span class="day-hours">9:00 AM - 4:00 PM</span>
                        </div>
                        <div class="day-row">
                            <span class="day-name">Sunday</span>
                            <span class="day-hours">Closed</span>
                        </div>
                        <div class="day-row">
                            <span class="day-name">Public Holidays</span>
                            <span class="day-hours">Closed</span>
                        </div>
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