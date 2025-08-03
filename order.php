<?php
session_start();
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

if ($_SESSION['user_type'] !== 'user') { header("Location: login.php"); exit(); }
include "config.php";

// Get pricing tiers
$pricing_tiers = $conn->query("SELECT * FROM pricing_tiers ORDER BY min_quantity");
$size_surcharges = $conn->query("SELECT * FROM size_surcharges ORDER BY surcharge");

$product_id = $_GET['product_id'] ?? 1;
$product = $conn->query("SELECT * FROM products WHERE id=$product_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jersey Customization - Grand Print</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style>
        .pricing-table { margin: 20px 0; }
        .size-options { margin: 20px 0; }
        .price-calculator { background: #f8f9fa; padding: 20px; border-radius: 10px; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h2>Customize Your Jersey</h2>
        
        <!-- Product Info -->
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo $product['image']; ?>" class="img-responsive" style="max-width: 300px;">
                <h3><?php echo $product['name']; ?></h3>
                <p><?php echo $product['description']; ?></p>
            </div>
            
            <div class="col-md-6">
                <!-- Pricing Information -->
                <div class="price-calculator">
                    <h4>Pricing Information</h4>
                    <p><strong>FREE DESIGN</strong> included</p>
                    <p><strong>RM2/PCS DISCOUNT</strong> for students</p>
                    
                    <h5>Quantity-Based Pricing:</h5>
                    <table class="table table-bordered pricing-table">
                        <tr><th>Quantity (PCS)</th><th>Price (RM)</th></tr>
                        <?php while($tier = $pricing_tiers->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $tier['min_quantity']; ?> - <?php echo $tier['max_quantity']; ?></td>
                            <td><?php echo $tier['price_per_piece']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                    
                    <h5>Size Surcharges:</h5>
                    <table class="table table-bordered">
                        <tr><th>Size Range</th><th>Additional Charge</th></tr>
                        <?php while($size = $size_surcharges->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $size['size_range']; ?></td>
                            <td>+RM<?php echo $size['surcharge']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Customization Form -->
        <form method="post" action="addtocart.php" id="jerseyForm">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            
            <div class="row">
                <div class="col-md-6">
                    <h4>Order Details</h4>
                    
                    <div class="form-group">
                        <label>Cloth Type:</label>
                        <select name="cloth_type" id="cloth_type" class="form-control" required>
                            <option value="short_sleeve" selected>Short Sleeve</option>
                            <option value="long_sleeve">Long Sleeve</option>
                            <option value="muslimah">Muslimah</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Quantity (PCS):</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" min="5" value="5" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Size:</label>
                        <select name="size" id="size" class="form-control" required>
                            <option value="">Select Size</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="2XL">2XL</option>
                            <option value="3XL">3XL</option>
                            <option value="4XL">4XL</option>
                            <option value="5XL">5XL</option>
                            <option value="6XL">6XL</option>
                            <option value="7XL">7XL</option>
                            <option value="8XL">8XL</option>
                            <option value="9XL">9XL</option>
                            <option value="10XL">10XL</option>
                            <option value="11XL">11XL</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="student_discount" value="1"> 
                            Student Discount (RM2/PCS)
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>Design Requirements:</label>
                        <textarea name="design_notes" class="form-control" rows="3" placeholder="Describe your design requirements..."></textarea>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h4>Price Calculation</h4>
                    <div id="priceBreakdown">
                        <p><strong>Base Price:</strong> <span id="basePrice">RM0.00</span></p>
                        <p><strong>Size Surcharge:</strong> <span id="sizeSurcharge">RM0.00</span></p>
                        <p><strong>Student Discount:</strong> <span id="studentDiscount">RM0.00</span></p>
                        <hr>
                        <p><strong>Total per piece:</strong> <span id="pricePerPiece">RM0.00</span></p>
                        <p><strong>Total for order:</strong> <span id="totalPrice">RM0.00</span></p>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg">Add to Cart</button>
        </form>
    </div>

    <script>
        // Price calculation logic
        const pricingTiers = [
            {min: 5, max: 9, price: 43},
            {min: 10, max: 25, price: 36},
            {min: 26, max: 50, price: 32},
            {min: 51, max: 100, price: 30},
            {min: 101, max: 300, price: 29},
            {min: 301, max: 500, price: 27},
            {min: 501, max: 1500, price: 24},
            {min: 1501, max: 3000, price: 23},
            {min: 3001, max: 5000, price: 21}
        ];
        
        const sizeSurcharges = {
            '3XL': 3, '4XL': 3, '5XL': 3,
            '6XL': 6, '7XL': 6, '8XL': 6,
            '9XL': 10, '10XL': 10, '11XL': 10
        };
        
        function calculatePrice() {
            const quantity = parseInt(document.getElementById('quantity').value);
            const size = document.getElementById('size').value;
            const studentDiscount = document.querySelector('input[name="student_discount"]').checked;
            const clothType = document.getElementById('cloth_type').value;
            
            // Find base price for quantity
            let basePrice = 0;
            for (let tier of pricingTiers) {
                if (quantity >= tier.min && quantity <= tier.max) {
                    basePrice = tier.price;
                    break;
                }
            }
            
            // Calculate surcharges
            let sizeSurcharge = 0;
            if (sizeSurcharges[size]) {
                sizeSurcharge = sizeSurcharges[size];
            }
            
            // Calculate student discount
            let studentDiscountAmount = 0;
            if (studentDiscount) {
                studentDiscountAmount = 2;
            }
            
            // Calculate final price per piece
            const pricePerPiece = basePrice + sizeSurcharge - studentDiscountAmount;
            const totalPrice = pricePerPiece * quantity;
            
            // Update display
            document.getElementById('basePrice').textContent = `RM${basePrice.toFixed(2)}`;
            document.getElementById('sizeSurcharge').textContent = `RM${sizeSurcharge.toFixed(2)}`;
            document.getElementById('studentDiscount').textContent = `-RM${studentDiscountAmount.toFixed(2)}`;
            document.getElementById('pricePerPiece').textContent = `RM${pricePerPiece.toFixed(2)}`;
            document.getElementById('totalPrice').textContent = `RM${totalPrice.toFixed(2)}`;
        }
        
        // Add event listeners
        document.getElementById('quantity').addEventListener('input', calculatePrice);
        document.getElementById('size').addEventListener('change', calculatePrice);
        document.getElementById('cloth_type').addEventListener('change', calculatePrice);
        document.querySelector('input[name="student_discount"]').addEventListener('change', calculatePrice);
        
        // Calculate initial price
        calculatePrice();
    </script>
</body>
</html>
