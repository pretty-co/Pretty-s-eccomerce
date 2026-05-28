<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'db-connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

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
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if ($password == $user['password']) {
                $_SESSION['user_email'] = $email;
                $_SESSION['logged_in'] = true;
                header("Location: welcome.php");
                exit();
            } else {
                $errors[] = "Incorrect password";
            }
        } else {
            $insert_sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
            mysqli_query($conn, $insert_sql);
            $_SESSION['user_email'] = $email;
            $_SESSION['logged_in'] = true;
            header("Location: welcome.php");
            exit();
        }
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