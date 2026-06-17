<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}


if ($_SESSION['user_role'] != 'customer') {
    header("Location: admin-dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];

include 'db-connection.php';

$order_count = 0;
$order_result = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $user_id");
if ($order_result) {
    $order_count = mysqli_num_rows($order_result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Pretty's Store</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #FAFAFA;
            min-height: 100vh;
        }

        .header {
            background: #1A1A1A;
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
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
            margin-left: 20px;
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
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            border: 1px solid #E5E7EB;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .welcome-card .welcome-icon {
            font-size: 48px;
            display: block;
            margin-bottom: 10px;
        }
        
        .welcome-card h1 {
            font-size: 28px;
            color: #1A1A1A;
        }
        
        .welcome-card h1 span {
            color: #D4AF37;
        }
        
        .welcome-card .subtitle {
            color: #666;
            margin-top: 5px;
        }
        
        .welcome-card .user-email {
            display: inline-block;
            background: #F3F4F6;
            padding: 8px 20px;
            border-radius: 30px;
            margin-top: 15px;
            font-size: 14px;
            color: #1A1A1A;
        }
        
        .role-badge {
            display: inline-block;
            padding: 4px 14px;
            background: #4CAF50;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }

        .metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .metric {
            background: white;
            padding: 25px;
            border-radius: 16px;
            text-align: center;
            border: 1px solid #E5E7EB;
            transition: transform 0.3s;
        }
        
        .metric:hover {
            transform: translateY(-4px);
        }
        
        .metric .metric-icon {
            font-size: 32px;
            display: block;
            margin-bottom: 8px;
        }
        
        .metric h3 {
            font-size: 32px;
            color: #D4AF37;
        }
        
        .metric p {
            color: #666;
            font-size: 13px;
            margin-top: 5px;
        }

        .quick-links {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .quick-link {
            background: white;
            padding: 30px 20px;
            border-radius: 16px;
            text-decoration: none;
            color: #1A1A1A;
            border: 1px solid #E5E7EB;
            text-align: center;
            transition: all 0.3s;
        }
        
        .quick-link:hover {
            border-color: #D4AF37;
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        }
        
        .quick-link .icon {
            font-size: 40px;
            display: block;
            margin-bottom: 10px;
        }
        
        .quick-link .label {
            font-size: 16px;
            font-weight: 600;
        }
        
        .quick-link .desc {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        

        .btn-shop {
            display: inline-block;
            padding: 14px 40px;
            background: #D4AF37;
            color: #1A1A1A;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-shop:hover {
            background: #C49B0F;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        }
        
        /* ===== FOOTER ===== */
        .footer-text {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #E5E7EB;
            padding-top: 20px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .header {
                padding: 12px 20px;
            }
            
            .header .nav-links a {
                margin-left: 12px;
                font-size: 12px;
            }
            
            .metrics {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .quick-links {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .metrics {
                grid-template-columns: 1fr;
            }
            
            .welcome-card h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    
    <div class="header">
        <a href="customer-dashboard.php" class="logo">Pretty's <span>Fashion</span></a>
        <div class="user-info">
             <span><?php echo htmlspecialchars($user_email); ?></span>
        </div>
        <div class="nav-links">
            <a href="customer-dashboard.php" class="active">Dashboard</a>
            <a href="shop.php">Shop</a>
            <a href="my-orders.php">My Orders</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    
    <div class="container">
        <div class="welcome-card">
            <span class="welcome-icon">✨</span>
            <h1>Welcome back, <span><?php echo htmlspecialchars($user_email); ?></span></h1>
            <p class="subtitle">Discover the latest fashion trends and styles</p>
            <div>
                <span class="user-email"><?php echo htmlspecialchars($user_email); ?></span>
                <span class="role-badge">Customer</span>
            </div>
        </div>


        <div class="metrics">
            <div class="metric">
                <h3><?php echo $order_count; ?></h3>
                <p>My Orders</p>
            </div>
            <div class="metric">
                <h3>👗</h3>
                <p>Fashion Collection</p>
            </div>
            <div class="metric">
                <span class="metric-icon">⭐</span>
                <p>Style Inspiration</p>
            </div>
        </div>


        <div class="quick-links">
            <a href="shop.php" class="quick-link">
                <span class="label">Shop Now</span>
                <span class="desc">Browse our latest collection</span>
            </a>
            <a href="my-orders.php" class="quick-link">
                <span class="label">My Orders</span>
                <span class="desc">View your order history</span>
            </a>
            <a href="profile.php" class="quick-link">
                <span class="label">My Profile</span>
                <span class="desc">Update your information</span>
            </a>
        </div>

    
        <div style="text-align: center;">
            <a href="shop.php" class="btn-shop"> Start Shopping →</a>
        </div>

    
        <div class="footer-text">
            Pretty's Fashion • Style that speaks for you
        </div>
    </div>
</body>
</html>