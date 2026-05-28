<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

include 'db-connection.php';

$id = intval($_GET['id']);

$sql = "DELETE FROM products WHERE id = $id";
mysqli_query($conn, $sql);

header("Location: products.php?msg=Product deleted successfully");
exit();
?>