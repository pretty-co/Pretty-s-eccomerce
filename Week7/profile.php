<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];
$user_role = $_SESSION['user_role'];

include 'db-connection.php';

$success = '';
$error = '';

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);


$profile_exists = false;
$sql = "SELECT * FROM profiles WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$profile_result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($profile_result) > 0) {
    $profile = mysqli_fetch_assoc($profile_result);
    $profile_exists = true;
}
mysqli_stmt_close($stmt);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    if ($profile_exists) {
        $sql = "UPDATE profiles SET first_name = ?, last_name = ?, phone = ?, address = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $first_name, $last_name, $phone, $address, $user_id);
    } else {
        $sql = "INSERT INTO profiles (user_id, first_name, last_name, phone, address) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "issss", $user_id, $first_name, $last_name, $phone, $address);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } else {
        $error = "Error updating profile: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Pretty's Store</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #FAFAFA; }
        .header { background: #1A1A1A; color: white; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; margin-left: 20px; }
        .header a:hover { color: #D4AF37; }
        .container { max-width: 600px; margin: 40px auto; padding: 20px; }
        .card { background: white; border-radius: 16px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; }
        h1 { color: #1A1A1A; margin-bottom: 10px; }
        .subtitle { color: #666; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; font-family: Arial; }
        textarea { resize: vertical; height: 80px; }
        button { background: #D4AF37; color: #1A1A1A; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
        button:hover { background: #C49B0F; }
        .success { background: #4CAF50; color: white; padding: 10px; border-radius: 8px; margin-bottom: 20px; }
        .error { background: #C62828; color: white; padding: 10px; border-radius: 8px; margin-bottom: 20px; }
        .btn-back { display: inline-block; margin-top: 20px; color: #D4AF37; text-decoration: none; }
        .email-display { background: #F9FAFB; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #E5E7EB; }
        .role-badge { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; color: white; }
        .role-admin { background: #C62828; }
        .role-manager { background: #D4AF37; color: #1A1A1A; }
        .role-customer { background: #4CAF50; }
    </style>
</head>
<body>
    <div class="header">
        <span> My Profile</span>
        <div>
            <?php if($user_role == 'admin'): ?>
                <a href="admin-dashboard.php">Dashboard</a>
            <?php elseif($user_role == 'manager'): ?>
                <a href="manager-dashboard.php">Dashboard</a>
            <?php else: ?>
                <a href="customer-dashboard.php">Dashboard</a>
            <?php endif; ?>
            <a href="profile.php" style="color: #D4AF37;">Profile</a>
            <a href="logout.php" style="color: #C62828;">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h1>My Profile</h1>
            <p class="subtitle">Update your personal information</p>

            <?php if($success): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="email-display">
                <strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?>
                <span class="role-badge role-<?php echo $user_role; ?>" style="margin-left: 15px;">
                    <?php echo ucfirst($user_role); ?>
                </span>
            </div>

            <form method="POST">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="<?php echo $profile_exists ? htmlspecialchars($profile['first_name']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="<?php echo $profile_exists ? htmlspecialchars($profile['last_name']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?php echo $profile_exists ? htmlspecialchars($profile['phone']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address"><?php echo $profile_exists ? htmlspecialchars($profile['address']) : ''; ?></textarea>
                </div>
                <button type="submit">Save Profile</button>
            </form>

            <?php if($user_role == 'admin'): ?>
                <a href="admin-dashboard.php" class="btn-back"> Back to Dashboard</a>
            <?php elseif($user_role == 'manager'): ?>
                <a href="manager-dashboard.php" class="btn-back">←Back to Dashboard</a>
            <?php else: ?>
                <a href="customer-dashboard.php" class="btn-back">Back to Dashboard</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>