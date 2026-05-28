<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "fashion_ecommerce";

$conn = mysqli_connect($host, $username, $password, $database);


if (!$conn) {
    die(" Connection Failed: " . mysqli_connect_error());
} else {
    echo " Connected Successfully!<br>";
    echo "Database: " . $database;
}

?>