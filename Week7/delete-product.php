<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();<
}

if ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'manager') {
    header("Location: dashboard.php");
    exit();
}

include 'db-connection.php';

$id = intval($_GET['id']);

$sql = "DELETE FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
mysqli_query($conn, $sql);

header("Location: products.php?msg=Product deleted successfully");
exit();
?>