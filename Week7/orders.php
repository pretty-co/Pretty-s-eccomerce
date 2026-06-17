<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'manager') {
    header("Location: customer-dashboard.php");
    exit();
}

include 'db-connection.php';

$sql = "SELECT o.*, u.email FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.order_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Orders - Pretty's Store</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #FAFAFA; }
        .header { background: #1A1A1A; color: white; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; margin-left: 20px; }
        .header a:hover { color: #D4AF37; }
        .container { max-width: 1200px; margin: 40px auto; padding: 20px; }
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
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; }
        .badge-admin { background: #C62828; color: white; }
        .badge-manager { background: #D4AF37; color: #1A1A1A; }
        .badge-customer { background: #4CAF50; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <span>Orders Management</span>
        <div>
            <a href="<?php echo $_SESSION['user_role'] == 'admin' ? 'admin-dashboard.php' : 'manager-dashboard.php'; ?>">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="orders.php" style="color: #D4AF37;">Orders</a>
            <a href="logout.php" style="color: #C62828;">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1>All Orders</h1>
            <a href="<?php echo $_SESSION['user_role'] == 'admin' ? 'admin-dashboard.php' : 'manager-dashboard.php'; ?>" class="btn-back">← Back</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>#ORD-<?php echo str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo date('M j, Y g:i a', strtotime($row['order_date'])); ?></td>
                    <td>Ksh <?php echo number_format($row['total_amount'], 2); ?></td>
                    <td class="status-<?php echo $row['status']; ?>">
                        <?php echo ucfirst($row['status']); ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>