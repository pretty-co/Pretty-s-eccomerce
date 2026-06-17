<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}


if ($_SESSION['user_role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

include 'db-connection.php';

$success = '';
$error = '';


if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    

    if ($delete_id == $_SESSION['user_id']) {
        $error = "You cannot delete your own account.";
    } else {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        if (mysqli_stmt_execute($stmt)) {
            $success = "User deleted successfully!";
        } else {
            $error = "Error deleting user: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    
    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $email, $password, $role);
        if (mysqli_stmt_execute($stmt)) {
            $success = "User added successfully!";
        } else {
            $error = "Error adding user: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['role'];
    

    if ($user_id == $_SESSION['user_id']) {
        $error = "You cannot change your own role.";
    } else {
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $new_role, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $success = "User role updated successfully!";
        } else {
            $error = "Error updating role: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}


$sql = "SELECT id, email, role, created_at FROM users ORDER BY id ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Pretty's Store</title>
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
        .success { background: #4CAF50; color: white; padding: 10px; border-radius: 8px; margin-bottom: 20px; }
        .error { background: #C62828; color: white; padding: 10px; border-radius: 8px; margin-bottom: 20px; }
        table { width: 100%; background: white; border-radius: 8px; border-collapse: collapse; }
        th { background: #1A1A1A; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #E5E7EB; }
        .btn-delete { background: #C62828; color: white; padding: 5px 12px; text-decoration: none; border-radius: 5px; }
        .btn-delete:hover { background: #B71C1C; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-admin { background: #C62828; color: white; }
        .badge-manager { background: #D4AF37; color: #1A1A1A; }
        .badge-customer { background: #4CAF50; color: white; }
        .form-inline { display: inline; }
        .btn-edit-role { background: #D4AF37; color: #1A1A1A; padding: 4px 10px; border-radius: 5px; border: none; cursor: pointer; }
        .btn-edit-role:hover { background: #C49B0F; }
        .add-user-section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #E5E7EB; }
        .add-user-section h3 { margin-bottom: 15px; }
        .add-user-section input, .add-user-section select { padding: 8px; border: 1px solid #ccc; border-radius: 5px; margin-right: 10px; }
        .add-user-section button { background: #D4AF37; padding: 8px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .add-user-section button:hover { background: #C49B0F; }
    </style>
</head>
<body>
    <div class="header">
        <span>Pretty's Admin</span>
        <div>
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="orders.php">Orders</a>
            <a href="users.php" style="color: #D4AF37;">Users</a>
            <a href="logout.php" style="color: #C62828;">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1>Manage Users</h1>
            <a href="admin-dashboard.php" class="btn-back">← Back to Dashboard</a>
        </div>

        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>


        <div class="add-user-section">
            <h3> Add New User</h3>
            <form method="POST">
                <input type="email" name="email" placeholder="Email address" required>
                <input type="password" name="password" placeholder="Password (min 8 chars)" required>
                <select name="role">
                    <option value="customer">Customer</option>
                    <option value="manager">Manager</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" name="add_user">Add User</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $row['role']; ?>">
                            <?php echo ucfirst($row['role']); ?>
                        </span>
                    </td>
                    <td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <?php if($row['id'] != $_SESSION['user_id']): ?>

                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <select name="role" onchange="this.form.submit()">
                                    <option value="customer" <?php echo $row['role']=='customer'?'selected':''; ?>>Customer</option>
                                    <option value="manager" <?php echo $row['role']=='manager'?'selected':''; ?>>Manager</option>
                                    <option value="admin" <?php echo $row['role']=='admin'?'selected':''; ?>>Admin</option>
                                </select>
                                <input type="hidden" name="update_role" value="1">
                            </form>
                            <a href="users.php?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this user?')">Delete</a>
                        <?php else: ?>
                            <span style="color: #999; font-size: 12px;">(You)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>