<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

$user_email = $_SESSION['user_email'];
include 'db-connection.php';

$product_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM products"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Pretty's Store</title>
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
            background: #FFFFFF;
            border-right: 1px solid #E5E7EB;
            position: fixed;
            height: 100vh;
        }
        .logo {
            padding: 32px 24px;
            font-size: 20px;
            font-weight: bold;
            color: #D4AF37;
        }
        .nav-menu {
            padding: 0 16px;
        }
        .nav-item {
            display: block;
            padding: 12px 16px;
            color: #666666;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 4px;
        }
        .nav-item:hover {
            background: #F3F4F6;
        }
        .nav-item.active {
            background: #F3F4F6;
            color: #D4AF37;
        }
        .user-info {
            padding: 20px;
            border-top: 1px solid #E5E7EB;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        .logout-btn {
            color: #C62828;
            text-decoration: none;
        }
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 32px;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }
        .metric-card {
            background: #FFFFFF;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .metric-card h3 {
            font-size: 28px;
            color: #1A1A1A;
            margin: 8px 0;
        }
        .metric-label {
            color: #666666;
        }
        .positive {
            color: #4CAF50;
        }
        .orders-section {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 32px;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        .orders-table th, .orders-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #E5E7EB;
        }
        .status-delivered {
            color: #4CAF50;
        }
        .status-shipped {
            color: #2196F3;
        }
        .charts-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        .chart-card {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 20px;
        }
        .size-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 10px 0;
        }
        .bar {
            height: 24px;
            background: #D4AF37;
            border-radius: 4px;
        }
        .stock-low {
            color: #C62828;
        }
        .color-swatch {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
        }
        .color-swatch.black { background: #1A1A1A; }
        .color-swatch.white { background: #F3F4F6; border: 1px solid #ccc; }
        .color-swatch.navy { background: #1E3A5F; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="logo">Pretty's</div>
            <nav class="nav-menu">
                <a href="dashboard.php" class="nav-item active">Dashboard</a>
                <a href="products.php" class="nav-item">Products</a>
                <a href="#" class="nav-item">Orders</a>
                <a href="#" class="nav-item">Customers</a>
            </nav>
            <div class="user-info">
                <p><?php echo htmlspecialchars($user_email); ?></p>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <div class="main-content">
            <div class="top-bar">
                <h2>Dashboard</h2>
            </div>

            <div class="metrics-grid">
                <div class="metric-card">
                    <p class="metric-label">Total Revenue</p>
                    <h3>Ksh 450,000</h3>
                    <p class="positive">Up 12% vs last week</p>
                </div>
                <div class="metric-card">
                    <p class="metric-label">Products</p>
                    <h3><?php echo $product_count; ?></h3>
                    <p class="positive">In your catalog</p>
                </div>
                <div class="metric-card">
                    <p class="metric-label">Returns Rate</p>
                    <h3>8.2%</h3>
                    <p class="positive">Down 2% vs last week</p>
                </div>
                <div class="metric-card">
                    <p class="metric-label">Top Item</p>
                    <h3>Silk Dress</h3>
                    <p class="metric-label">Size M bestseller</p>
                </div>
            </div>

            <div class="orders-section">
                <h3>Recent Orders</h3>
                <table class="orders-table">
                    <thead>
                        <tr><th>Order ID</th><th>Item</th><th>Size</th><th>Status</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        <td><td>#ORD-001</td><td>Silk Maxi Dress</td><td>M</td><td class="status-delivered">Delivered</td><td>Ksh2000.00</td></tr>
                        <td><td>#ORD-002</td><td>Denim Jacket</td><td>L</td><td class="status-shipped">Shipped</td><td>Ksh2000.00</td></tr>
                        <td><td>#ORD-003</td><td>Cotton T-Shirt</td><td>S</td><td class="status-delivered">Delivered</td><td>Ksh600.00</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="charts-row">
                <div class="chart-card">
                    <h4>Size Performance</h4>
                    <div class="size-row"><span>XS</span><div class="bar" style="width: 60px;"></div></div>
                    <div class="size-row"><span>S</span><div class="bar" style="width: 150px;"></div></div>
                    <div class="size-row"><span>M</span><div class="bar" style="width: 210px;"></div></div>
                    <div class="size-row"><span>L</span><div class="bar" style="width: 80px;"></div></div>
                    <div class="size-row"><span>XL</span><div class="bar" style="width: 40px;"></div></div>
                </div>
                <div class="chart-card">
                    <h4>Top Colors</h4>
                    <p><span class="color-swatch black"></span> Black 45%</p>
                    <p><span class="color-swatch white"></span> White 30%</p>
                    <p><span class="color-swatch navy"></span> Navy 25%</p>
                </div>
                <div class="chart-card">
                    <h4>Low Stock Alerts</h4>
                    <p>Denim Jacket - Size S <span class="stock-low">3 left</span></p>
                    <p>Silk Blouse - Size M <span class="stock-low">5 left</span></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>