<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$user_role = $_SESSION['user_role'] ??'customer';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

include 'db-connection.php';

$sql = "SELECT * FROM products ORDER BY id ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - Fashion Store</title>
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
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        h1 {
            color: #1A1A1A;
        }
        .btn-add {
            background: #D4AF37;
            color: #1A1A1A;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
        }
        table {
            width: 100%;
            background: white;
            border-radius: 8px;
            border-collapse: collapse;
        }
        th {
            background: #1A1A1A;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #E5E7EB;
        }
        .btn-edit {
            background: #D4AF37;
            color: #1A1A1A;
            padding: 5px 12px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
        }
        .btn-delete {
            background: #C62828;
            color: white;
            padding: 5px 12px;
            text-decoration: none;
            border-radius: 5px;
        }
        .success-msg {
            background: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #D4AF37;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Product Management</h1>
            <a href="add-product.php" class="btn-add">Add New Product</a>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div class="success-msg"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr><th>ID</th><th>Product Name</th><th>Category</th><th>Price</th><th>Size</th><th>Color</th><th>Stock</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td>Ksh<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['size']; ?></td>
                    <td><?php echo $row['color']; ?></td>
                    <td><?php echo $row['stock_quantity']; ?></td>
                    <td>
                        <?php if($user_role == 'admin' || $user_role == 'manager'); ?>
                        <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                        <a href="delete-product.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                        <?php else: ?>
                            <span style="color: #999; font-size: 12px;">View only</span>
                            <php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>