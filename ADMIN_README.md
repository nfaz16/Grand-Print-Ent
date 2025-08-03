# Grand Print Admin Dashboard

## Overview
The Grand Print Admin Dashboard provides comprehensive management tools for administrators to handle catalogue management, order processing, and customer information.

## Features

### 1. Dashboard Overview (`adminhome.php`)
- **Statistics Dashboard**: View total customers, products, orders, and pending orders
- **Tabbed Interface**: Easy navigation between different management sections
- **Real-time Data**: Live statistics and order information

### 2. Catalogue Management
- **Add New Products**: Modal form to add products with name, description, price, and image
- **Edit Products**: Dedicated page (`edit_product.php`) for detailed product management
- **Delete Products**: Remove products from the catalogue
- **Product Grid**: Visual display of all products with edit/delete options

### 3. Order Management
- **Order Overview**: View all orders with customer details
- **Status Updates**: Update order status (Pending, Processing, Shipped, Delivered, Cancelled)
- **Email Notifications**: Automatic email notifications to customers when status changes
- **Detailed Management**: Dedicated page (`manage_orders.php`) for comprehensive order management
- **Order Statistics**: Revenue tracking and status breakdown

### 4. Customer Information
- **Customer Database**: View all registered customers
- **Contact Details**: Access customer names, emails, and phone numbers
- **Registration Dates**: Track customer registration history

## Database Setup

### Required Tables
Run the SQL commands in `setup_database.sql` to create the necessary tables:

```sql
-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users_db(id) ON DELETE CASCADE
);
```

## File Structure

### Core Admin Files
- `adminhome.php` - Main admin dashboard
- `edit_product.php` - Product management page
- `manage_orders.php` - Detailed order management
- `setup_database.sql` - Database setup script

### Configuration
- `config.php` - Database connection settings
- `logout.php` - Admin logout functionality

## Usage Instructions

### 1. Access Admin Dashboard
1. Navigate to `adminhome.php`
2. Login with admin credentials
3. View dashboard overview with statistics

### 2. Manage Products
1. Click "Catalogue Management" tab
2. Use "Add New Product" button for new products
3. Click "Manage Products" for detailed editing
4. Edit or delete existing products

### 3. Manage Orders
1. Click "Order Management" tab for overview
2. Click "Detailed Order Management" for full features
3. Update order status with customer notifications
4. View order statistics and revenue

### 4. View Customer Information
1. Click "Customer Information" tab
2. View all registered customers
3. Access contact details and registration dates

## Order Status Workflow

1. **Pending** - New order received
2. **Processing** - Order being prepared
3. **Shipped** - Order dispatched
4. **Delivered** - Order completed
5. **Cancelled** - Order cancelled

## Email Notifications

When order status is updated, the system automatically sends email notifications to customers with:
- Order ID
- New status
- Professional message format

## Security Features

- Session-based authentication
- Admin-only access control
- SQL injection prevention with prepared statements
- Input validation and sanitization

## Customization

### Adding New Product Fields
1. Modify the products table structure
2. Update forms in `adminhome.php` and `edit_product.php`
3. Adjust display logic in product grids

### Custom Order Statuses
1. Modify the ENUM in the orders table
2. Update status options in forms
3. Add corresponding CSS classes for status badges

### Email Templates
Modify the email message format in `adminhome.php` and `manage_orders.php` for custom notifications.

## Troubleshooting

### Common Issues
1. **Database Connection**: Check `config.php` settings
2. **Email Notifications**: Ensure server mail configuration
3. **Image Display**: Verify image file paths and permissions
4. **Session Issues**: Check session configuration

### Performance Tips
1. Use database indexes for large datasets
2. Implement pagination for large order lists
3. Optimize image sizes for product display
4. Cache frequently accessed data

## Support

For technical support or feature requests, contact the development team.

---

**Version**: 1.0  
**Last Updated**: 2025  
**Compatibility**: PHP 7.4+, MySQL 5.7+ 