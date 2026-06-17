<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// ONLY CUSTOMER CAN ACCESS
if ($_SESSION['user_role'] !== 'customer') {
    header("Location: admin-dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
include 'db-connection.php';

$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date ASC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - Pretty's Store</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #FAFAFA; }
        .header { background: #1A1A1A; color: white; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; margin-left: 20px; }
        .header a:hover { color: #D4AF37; }
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-back { background: #666; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; }
        .btn-back:hover { background: #1A1A1A; }
        table { width: 100%; background: white; border-radius: 8px; border-collapse: collapse; }
        th { background: #1A1A1A; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #E5E7EB; }
        .status-pending { color: #FF9800; }
        .status-processing { color: #2196F3; }
        .status-shipped { color: #9C27B0; }
        .status-delivered { color: #4CAF50; }
        .status-cancelled { color: #C62828; }
        .no-orders { text-align: center; padding: 40px; color: #666; }
        .no-orders .icon { font-size: 48px; display: block; margin-bottom: 10px; }
        .btn-shop { display: inline-block; margin-top: 15px; padding: 10px 20px; background: #D4AF37; color: #1A1A1A; text-decoration: none; border-radius: 8px; }
        .btn-shop:hover { background: #C49B0F; }
    </style>
</head>
<body>
    <div class="header">
        <span>My Orders</span>
        <div>
            <a href="customer-dashboard.php">Dashboard</a>
            <a href="shop.php">Shop</a>
            <a href="my-orders.php" style="color: #D4AF37;">My Orders</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php" style="color: #C62828;">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1> My Orders</h1>
            <a href="customer-dashboard.php" class="btn-back">← Back to Dashboard</a>
        </div>

        <?php if(mysqli_num_rows($result) == 0): ?>
            <div class="no-orders">
                <p>You haven't placed any orders yet.</p>
                <a href="shop.php" class="btn-shop">Start Shopping →</a>
            </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>#ORD-<?php echo str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                    <td><?php echo date('M j, Y g:i a', strtotime($row['order_date'])); ?></td>
                    <td>Ksh <?php echo number_format($row['total_amount'], 2); ?></td>
                    <td class="status-<?php echo $row['status']; ?>">
                        <?php echo ucfirst($row['status']); ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</body>
</html>