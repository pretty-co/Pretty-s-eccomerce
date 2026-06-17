<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'db-connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    $errors = [];

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }

    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            if ($password == $user['password']) {
                
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['logged_in'] = true;
                $_SESSION['last_activity'] = time();
                
            
                if ($user['role'] == 'admin') {
                    header("Location: admin-dashboard.php");
                } elseif ($user['role'] == 'manager') {
                    header("Location: manager-dashboard.php");
                } else {
                    header("Location: customer-dashboard.php");
                }
                exit();
                
            } else {
                $errors[] = "Incorrect password";
            }
        } else {
            $errors[] = "User not found";
        }
        mysqli_stmt_close($stmt);
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php");
        exit();
    }
}

header("Location: index.php");
exit();
?>