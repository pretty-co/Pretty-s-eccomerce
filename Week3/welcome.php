<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

$user_email = $_SESSION['user_email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Pretty's</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lucida calligraphy', sans-serif;
            background: #FAFAFA;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-container {
            width: 100%;
            max-width: 600px;
            padding: 32px;
        }

        .welcome-card {
            background: #FFFFFF;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #E5E7EB;
            text-align: center;
        }

        .welcome-icon {
            font-size: 64px;
            margin-bottom: 16px;
        }

        .welcome-card h1 {
            font-size: 32px;
            font-weight: 600;
            color: #1A1A1A;
            margin-bottom: 8px;
        }

        .welcome-card .subtitle {
            color: #666666;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .user-email {
            background: #F3F4F6;
            padding: 12px 24px;
            border-radius: 40px;
            display: inline-block;
            margin: 16px 0;
            font-size: 14px;
            color: #1A1A1A;
            font-weight: 500;
        }

        .user-email::before {
            content: "✨ ";
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin: 32px 0;
        }

        .stat-card {
            background: #F9FAFB;
            padding: 16px;
            border-radius: 16px;
            text-align: center;
        }

        .stat-card .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #1A1A1A;
        }

        .stat-card .stat-label {
            font-size: 12px;
            color: #666666;
            margin-top: 4px;
        }

        .dynamic-menu {
            margin: 32px 0 24px;
            padding-top: 24px;
            border-top: 1px solid #E5E7EB;
        }

        .dynamic-menu h3 {
            font-size: 14px;
            color: #666666;
            margin-bottom: 16px;
            font-weight: 500;
        }

        .menu-items {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .menu-item {
            padding: 8px 20px;
            background: #F3F4F6;
            border-radius: 40px;
            color: #1A1A1A;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background: #D4AF37;
            color: #1A1A1A;
            transform: translateY(-2px);
        }

        .button-group {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 24px;
        }

        .btn {
            padding: 12px 28px;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary {
            background: #1A1A1A;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #D4AF37;
            color: #1A1A1A;
            transform: translateY(-2px);
        }

        .btn-logout {
            background: transparent;
            border: 1px solid #E5E7EB;
            color: #666666;
        }

        .btn-logout:hover {
            border-color: #C62828;
            color: #C62828;
            transform: translateY(-2px);
        }

        .footer-text {
            margin-top: 24px;
            font-size: 11px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-card">
            <div class="welcome-icon">✨</div>
            <h1>Welcome back!</h1>
            <p class="subtitle">You're now logged into Pretty's</p>

            <div class="user-email"><?php echo htmlspecialchars($user_email); ?></div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">Ksh 40,000</div>
                    <div class="stat-label">Revenue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">1,284</div>
                    <div class="stat-label">Units Sold</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">8.2%</div>
                    <div class="stat-label">Returns</div>
                </div>
            </div>

            <div class="dynamic-menu">
                <h3> Quick Navigation</h3>
                <div class="menu-items">
                    <a href="#" class="menu-item"> Dashboard</a>
                    <a href="#" class="menu-item"> Catalog</a>
                    <a href="#" class="menu-item"> Orders</a>
                    <a href="#" class="menu-item"> Analytics</a>
                    <a href="#" class="menu-item"> Customers</a>
                    <a href="#" class="menu-item"> Settings</a>
                </div>
            </div>

            <div class="button-group">
                <a href="dashboard.php" class="btn btn-primary">Go to Dashboard →</a>
                <a href="logout.php" class="btn btn-logout"> Logout</a>
            </div>
            <div class="footer-text">
                Last login: <?php echo date('F j, Y, g:i a'); ?>
            </div>
        </div>
    </div>
</body>
</html>