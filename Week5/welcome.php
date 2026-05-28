<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
    <title>Welcome - Pretty's Store</title>
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
        h1 {
            color: #D4AF37;
            margin-bottom: 20px;
        }
        .user-email {
            background: #F3F4F6;
            padding: 12px 24px;
            border-radius: 40px;
            display: inline-block;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #1A1A1A;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 5px;
        }
        .btn:hover {
            background: #D4AF37;
            color: #1A1A1A;
        }
        .btn-logout {
            background: transparent;
            border: 1px solid #C62828;
            color: #C62828;
        }
        .btn-logout:hover {
            background: #C62828;
            color: white;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-card">
            <h1>Welcome back</h1>
            <div class="user-email"><?php echo htmlspecialchars($user_email); ?></div>
            <p>You have successfully logged in.</p>
            <br>
            <a href="dashboard.php" class="btn">Go to Dashboard</a>
            <a href="products.php" class="btn">Manage Products</a>
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>
    </div>
</body>
</html>