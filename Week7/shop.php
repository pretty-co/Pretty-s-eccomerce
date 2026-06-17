<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

include 'db-connection.php';

$user_email = $_SESSION['user_email'];
$user_role = $_SESSION['user_role'];


$sql = "SELECT * FROM products ORDER BY id ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Pretty's Store</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #FAFAFA;
        }
        

        .header {
            background: #1A1A1A;
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header .logo {
            color: #D4AF37;
            font-size: 22px;
            font-weight: bold;
            text-decoration: none;
        }
        
        .header .logo span {
            color: white;
        }
        
        .header .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .header .nav-links a:hover {
            color: #D4AF37;
        }
        
        .header .nav-links a.active {
            color: #D4AF37;
        }
        
        .header .nav-links a.logout {
            color: #C62828;
        }
        
        .header .nav-links a.logout:hover {
            color: #ff1744;
        }
        
        .header .user-info {
            color: #999;
            font-size: 13px;
        }
        
        .header .user-info span {
            color: #D4AF37;
        }
        

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .page-header h1 {
            font-size: 28px;
            color: #1A1A1A;
        }
        
        .page-header h1 span {
            color: #D4AF37;
        }
        
        .page-header .subtitle {
            color: #666;
            font-size: 14px;
        }
        
        .page-header .cart-icon {
            background: #D4AF37;
            color: #1A1A1A;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .page-header .cart-icon:hover {
            background: #C49B0F;
        }
        

        .filter-bar {
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .filter-bar .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .filter-bar select {
            padding: 8px 15px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            background: white;
            font-size: 13px;
            cursor: pointer;
        }
        
        .filter-bar .results-count {
            color: #666;
            font-size: 13px;
        }
        
        /* ===== PRODUCTS GRID ===== */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }
        
        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #E5E7EB;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            border-color: #D4AF37;
        }
        
        .product-image {
            height: 200px;
            background: #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 56px;
            position: relative;
        }
        
        .product-image .badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .badge-new {
            background: #D4AF37;
            color: #1A1A1A;
        }
        
        .badge-sale {
            background: #C62828;
            color: white;
        }
        
        .badge-low-stock {
            background: #FF9800;
            color: white;
        }
        
        .product-details {
            padding: 16px 18px;
        }
        
        .product-details .product-name {
            font-size: 16px;
            font-weight: 600;
            color: #1A1A1A;
            margin-bottom: 4px;
        }
        
        .product-details .product-name a {
            color: #1A1A1A;
            text-decoration: none;
        }
        
        .product-details .product-name a:hover {
            color: #D4AF37;
        }
        
        .product-details .category {
            color: #999;
            font-size: 12px;
            margin-bottom: 8px;
        }
        
        .product-details .price {
            font-size: 20px;
            font-weight: bold;
            color: #D4AF37;
            margin: 8px 0;
        }
        
        .product-details .variants {
            font-size: 12px;
            color: #666;
            margin: 8px 0;
        }
        
        .product-details .stock {
            font-size: 12px;
            margin: 8px 0;
        }
        
        .stock-in {
            color: #4CAF50;
        }
        
        .stock-low {
            color: #FF9800;
        }
        
        .stock-out {
            color: #C62828;
        }
        
        .btn-view {
            display: block;
            width: 100%;
            padding: 10px;
            background: #1A1A1A;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-view:hover {
            background: #D4AF37;
            color: #1A1A1A;
        }
        
        .btn-view.out-of-stock {
            background: #E5E7EB;
            color: #999;
            cursor: not-allowed;
        }
        
        .btn-view.out-of-stock:hover {
            background: #E5E7EB;
            color: #999;
        }
        
        .no-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            border: 1px solid #E5E7EB;
        }
        
        .no-products .icon {
            font-size: 64px;
            display: block;
            margin-bottom: 16px;
        }
        
        .no-products h3 {
            color: #1A1A1A;
            margin-bottom: 8px;
        }
        
        .no-products p {
            color: #666;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 40px;
            color: #D4AF37;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .back-link:hover {
            color: #C49B0F;
        }
        
        @media (max-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .header {
                padding: 12px 20px;
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .header .nav-links a {
                margin-left: 15px;
                font-size: 12px;
            }
        }
        
        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .page-header h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="shop.php" class="logo">Pretty's <span>Fashion</span></a>
        <div class="user-info">
             <span><?php echo htmlspecialchars($user_email); ?></span>
        </div>
        <div class="nav-links">
            <?php if($user_role == 'admin'): ?>
                <a href="dashboard.php">Dashboard</a>
            <?php elseif($user_role == 'manager'): ?>
                <a href="manager-dashboard.php">Dashboard</a>
            <?php else: ?>
                <a href="customer-dashboard.php">Dashboard</a>
            <?php endif; ?>
            <a href="shop.php" class="active">Shop</a>
            <a href="my-orders.php">My Orders</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <div>
                <h1><span>Our Collection</span></h1>
                <p class="subtitle">Discover the latest fashion trends</p>
            </div>
            <a href="#" class="cart-icon">View Cart</a>
        </div>

        <div class="filter-bar">
            <div class="filter-group">
                <label for="category" style="color: #666; font-size: 13px;">Category:</label>
                <select id="category">
                    <option value="all">All Categories</option>
                    <option value="Dresses">Dresses</option>
                    <option value="Tops">Tops</option>
                    <option value="Outerwear">Outerwear</option>
                    <option value="Bottoms">Bottoms</option>
                    <option value="Accessories">Accessories</option>
                </select>
                
                <label for="sort" style="color: #666; font-size: 13px; margin-left: 15px;">Sort:</label>
                <select id="sort">
                    <option value="newest">Newest</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>
            <div class="results-count">
                <?php echo mysqli_num_rows($result); ?> products found
            </div>
        </div>

        <div class="products-grid">
            <?php if(mysqli_num_rows($result) == 0): ?>
                <div class="no-products">
                    <h3>No products available</h3>
                    <p>Check back later for new arrivals</p>
                </div>
            <?php else: ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php 
                            $image_emoji = '👗';
                            if($row['category'] == 'Tops') $image_emoji = '👕';
                            elseif($row['category'] == 'Outerwear') $image_emoji = '🧥';
                            elseif($row['category'] == 'Bottoms') $image_emoji = '👖';
                            elseif($row['category'] == 'Accessories') $image_emoji = '👜';
                            elseif($row['category'] == 'Dresses') $image_emoji = '👗';
                            elseif($row['category'] == 'Knits') $image_emoji = '🧶';
                        ?>
                        <?php echo $image_emoji; ?>
                        
                        <?php if($row['stock_quantity'] <= 0): ?>
                            <span class="badge badge-sale">Out of Stock</span>
                        <?php elseif($row['stock_quantity'] <= 5): ?>
                            <span class="badge badge-low-stock">Low Stock</span>
                        <?php else: ?>
                            <span class="badge badge-new">In Stock</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-details">
                        <h3 class="product-name">
                            <a href="#"><?php echo htmlspecialchars($row['product_name']); ?></a>
                        </h3>
                        <p class="category"><?php echo htmlspecialchars($row['category']); ?></p>
                        <p class="price">Ksh <?php echo number_format($row['price'], 2); ?></p>
                        <p class="variants">
                            Size: <?php echo $row['size']; ?> | Color: <?php echo $row['color']; ?>
                        </p>
                        <p class="stock">
                            <?php if($row['stock_quantity'] > 0): ?>
                                <span class="stock-in"><?php echo $row['stock_quantity']; ?> available</span>
                            <?php else: ?>
                                <span class="stock-out">Out of stock</span>
                            <?php endif; ?>
                        </p>
                        <?php if($row['stock_quantity'] > 0): ?>
                            <a href="#" class="btn-view">Add to Cart</a>
                        <?php else: ?>
                            <a href="#" class="btn-view out-of-stock">Out of Stock</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>s


        <?php if($user_role == 'admin'): ?>
            <a href="dashboard.php" class="back-link">Back to Admin Dashboard</a>
        <?php elseif($user_role == 'manager'): ?>
            <a href="manager-dashboard.php" class="back-link">Back to Manager Dashboard</a>
        <?php else: ?>
            <a href="customer-dashboard.php" class="back-link">Back to Dashboard</a>
        <?php endif; ?>
    </div>
</body>
</html>