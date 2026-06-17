<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}


if ($_SESSION['user_role'] != 'manager') {
    header("Location: customer-dashboard.php");
    exit();
}

$user_email = $_SESSION['user_email'];
include 'db-connection.php';

$product_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM products"));
$order_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - Pretty's Store</title>
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
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 260px;
            background: #1A1A1A;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .logo {
            padding: 32px 24px;
            font-size: 22px;
            font-weight: bold;
            color: #D4AF37;
            border-bottom: 1px solid #333;
        }
        
        .logo span {
            color: white;
        }
        
        .nav-menu {
            padding: 20px 16px;
        }
        
        .nav-item {
            display: block;
            padding: 12px 16px;
            color: #AAAAAA;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 4px;
            transition: all 0.3s;
        }
        
        .nav-item:hover {
            background: #333;
            color: white;
        }
        
        .nav-item.active {
            background: #D4AF37;
            color: #1A1A1A;
            font-weight: bold;
        }
        
        .nav-item .icon {
            margin-right: 10px;
        }
        
        .user-info {
            padding: 20px 24px;
            border-top: 1px solid #333;
            position: absolute;
            bottom: 0;
            width: 100%;
            background: #1A1A1A;
        }
        
        .user-info .email {
            color: #AAAAAA;
            font-size: 13px;
        }
        
        .user-info .email span {
            color: #D4AF37;
        }
        
        .user-info .role-badge {
            display: inline-block;
            padding: 3px 12px;
            background: #D4AF37;
            color: #1A1A1A;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 5px;
        }
        
        .logout-btn {
            display: block;
            margin-top: 10px;
            color: #C62828;
            text-decoration: none;
            font-size: 13px;
        }
        
        .logout-btn:hover {
            color: #ff1744;
        }
        

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 32px 40px 40px 40px;
        }
        
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .top-bar h2 {
            font-size: 28px;
            color: #1A1A1A;
        }
        
        .top-bar h2 span {
            color: #D4AF37;
        }
        
        .top-bar .date {
            color: #666;
            font-size: 14px;
        }
        
        
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .metric-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #E5E7EB;
            transition: transform 0.3s;
        }
        
        .metric-card:hover {
            transform: translateY(-4px);
        }
        
        .metric-card .metric-label {
            color: #666;
            font-size: 13px;
            font-weight: 500;
        }
        
        .metric-card h3 {
            font-size: 32px;
            color: #1A1A1A;
            margin: 8px 0 4px 0;
        }
        
        .metric-card .metric-change {
            font-size: 13px;
        }
        
        .metric-card .positive {
            color: #4CAF50;
        }
        
        .metric-card .negative {
            color: #C62828;
        }
        
        
        .orders-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            border: 1px solid #E5E7EB;
        }
        
        .orders-section .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .orders-section .section-header h3 {
            color: #1A1A1A;
        }
        
        .orders-section .section-header a {
            color: #D4AF37;
            text-decoration: none;
            font-size: 13px;
        }
        
        .orders-section .section-header a:hover {
            text-decoration: underline;
        }
        
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .orders-table th {
            text-align: left;
            padding: 12px 8px;
            color: #666;
            font-size: 12px;
            font-weight: 600;
            border-bottom: 2px solid #E5E7EB;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .orders-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #F3F4F6;
            font-size: 14px;
            color: #1A1A1A;
        }
        
        .status-delivered {
            color: #4CAF50;
            font-weight: 600;
        }
        
        .status-shipped {
            color: #2196F3;
            font-weight: 600;
        }
        
        .status-pending {
            color: #FF9800;
            font-weight: 600;
        }
        
        .status-processing {
            color: #9C27B0;
            font-weight: 600;
        }
        
        .status-cancelled {
            color: #C62828;
            font-weight: 600;
        }
        
    
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }
        
        .quick-action {
            background: white;
            padding: 24px;
            border-radius: 16px;
            text-decoration: none;
            color: #1A1A1A;
            border: 1px solid #E5E7EB;
            text-align: center;
            transition: all 0.3s;
        }
        
        .quick-action:hover {
            border-color: #D4AF37;
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }
        
        .quick-action .icon {
            font-size: 36px;
            display: block;
            margin-bottom: 8px;
        }
        
        .quick-action .label {
            font-size: 14px;
            font-weight: 600;
        }
        
        .quick-action .desc {
            font-size: 12px;
            color: #999;
            margin-top: 4px;
        }
        
        
        .charts-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        
        .chart-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #E5E7EB;
        }
        
        .chart-card h4 {
            color: #1A1A1A;
            margin-bottom: 16px;
            font-size: 16px;
        }
        
        .size-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 10px 0;
        }
        
        .size-row span {
            width: 30px;
            color: #666;
            font-size: 13px;
            font-weight: 500;
        }
        
        .size-row .bar {
            height: 24px;
            background: #D4AF37;
            border-radius: 4px;
            transition: width 1s ease;
        }
        
        .size-row .bar.dark {
            background: #1A1A1A;
        }
        
        .stock-list p {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #F3F4F6;
            font-size: 14px;
        }
        
        .stock-list p:last-child {
            border-bottom: none;
        }
        
        .stock-low {
            color: #C62828;
            font-weight: bold;
        }
        
        .stock-warning {
            color: #FF9800;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="dashboard-container"
        <div class="sidebar">
            <div class="logo">Pretty's <span>Fashion</span></div>
            <nav class="nav-menu">
                <a href="manager-dashboard.php" class="nav-item active">
                    <span class="icon"></span> Dashboard
                </a>
                <a href="products.php" class="nav-item">
                    <span class="icon"></span> Products
                </a>
                <a href="add-product.php" class="nav-item">
                    <span class="icon"></span> Add Product
                </a>
                <a href="orders.php" class="nav-item">
                    <span class="icon"></span> Orders
                </a>
                <a href="shop.php" class="nav-item">
                    <span class="icon"></span> Shop
                </a>
                <a href="profile.php" class="nav-item">
                    <span class="icon"></span> Profile
                </a>
            </nav>
            <div class="user-info">
                <p class="email"> <span><?php echo htmlspecialchars($user_email); ?></span></p>
                <span class="role-badge">Manager</span>
                <a href="logout.php" class="logout-btn"> Logout</a>
            </div>
        </div>


        <div class="main-content">
            <div class="top-bar">
                <h2> <span>Manager</span> Dashboard</h2>
                <span class="date"><?php echo date('F j, Y'); ?></span>
            </div>


            <div class="metrics-grid">
                <div class="metric-card">
                    <p class="metric-label">Total Revenue</p>
                    <h3>Ksh 450,000</h3>
                    <p class="metric-change positive">↑ 12% vs last week</p>
                </div>
                <div class="metric-card">
                    <p class="metric-label">Products</p>
                    <h3><?php echo $product_count; ?></h3>
                    <p class="metric-change positive">In your catalog</p>
                </div>
                <div class="metric-card">
                    <p class="metric-label">Total Orders</p>
                    <h3><?php echo $order_count; ?></h3>
                    <p class="metric-change">All orders</p>
                </div>
            </div>

            <div class="quick-actions">
                <a href="products.php" class="quick-action">
                    <span class="icon"></span>
                    <span class="label">Manage Products</span>
                    <span class="desc">View, edit, or delete products</span>
                </a>
                <a href="add-product.php" class="quick-action">
                    <span class="icon"></span>
                    <span class="label">Add New Product</span>
                    <span class="desc">Add new product to catalog</span>
                </a>
            </div>

            <!-- Recent Orders -->
            <div class="orders-section">
                <div class="section-header">
                    <h3> Recent Orders</h3>
                    <a href="orders.php">View All →</a>
                </div>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Item</th>
                            <th>Size</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#ORD-001</td>
                            <td>Silk Maxi Dress</td>
                            <td>M</td>
                            <td class="status-delivered"> Delivered</td>
                            <td>Ksh 2,000</td>
                        </tr>
                        <tr>
                            <td>#ORD-002</td>
                            <td>Denim Jacket</td>
                            <td>L</td>
                            <td class="status-shipped"> Shipped</td>
                            <td>Ksh 2,000</td>
                        </tr>
                        <tr>
                            <td>#ORD-003</td>
                            <td>Cotton T-Shirt</td>
                            <td>S</td>
                            <td class="status-delivered"> Delivered</td>
                            <td>Ksh 600</td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="charts-row">
                <div class="chart-card">
                    <h4> Size Performance</h4>
                    <div class="size-row">
                        <span>XS</span>
                        <div class="bar" style="width: 60px;"></div>
                    </div>
                    <div class="size-row">
                        <span>S</span>
                        <div class="bar" style="width: 150px;"></div>
                    </div>
                    <div class="size-row">
                        <span>M</span>
                        <div class="bar dark" style="width: 210px;"></div>
                    </div>
                    <div class="size-row">
                        <span>L</span>
                        <div class="bar" style="width: 80px;"></div>
                    </div>
                    <div class="size-row">
                        <span>XL</span>
                        <div class="bar" style="width: 40px;"></div>
                    </div>
                </div>
                <div class="chart-card">
                    <h4> Low Stock Alerts</h4>
                    <div class="stock-list">
                        <p>Denim Jacket - Size S <span class="stock-low">3 left</span></p>
                        <p>Silk Blouse - Size M <span class="stock-warning"> 5 left</span></p>
                        <p>Cotton T-Shirt - Black L <span class="stock-low"> 2 left</span></p>
                        <p>Wool Sweater - Size XS <span class="stock-warning"> 4 left</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>