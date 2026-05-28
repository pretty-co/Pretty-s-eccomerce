<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Pretty's Store</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Lucida Calligraphy, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: rgba(255,255,255,0.1);
            padding: 40px;
            border-radius: 20px;
            width: 400px;
        }
        h1 {
            color: #D4AF37;
            text-align: center;
            margin-bottom: 30px;
        }
        .input-group {
            margin-bottom: 20px;
        }
        label {
            color: white;
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .error {
            color: #ff6b6b;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #D4AF37;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #C49B0F;
        }
        .error-banner {
            background: rgba(255,107,107,0.2);
            border: 1px solid #ff6b6b;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 20px;
        }
        .error-banner p {
            color: #ff6b6b;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>FASHION</h1>

        <?php if(isset($_SESSION['errors'])): ?>
            <div class="error-banner">
                <?php
                    foreach($_SESSION['errors'] as $error) {
                        echo "<p>$error</p>";
                    }
                    unset($_SESSION['errors']);
                ?>
            </div>
        <?php endif; ?>

        <form action="form-processor.php" method="POST">
            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="email@example.com" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit">Sign In</button>
        </form>
    </div>
</body>
</html>