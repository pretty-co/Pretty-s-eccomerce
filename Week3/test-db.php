<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "fashion_ecommerce";

$conn = mysqli_connect($host, $username, $password, $database);

echo "<h2>Database Connection Test</h2>";
echo "<hr>";

if (!$conn) {
    echo "<p style='color: red;'> Connection Failed!</p>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
} else {
    echo "<p style='color: green;'> Connected Successfully!</p>";
    echo "<p>Host: " . $host . "</p>";
    echo "<p>Database: " . $database . "</p>";
    echo "<p>Username: " . $username . "</p>";
    mysqli_close($conn);
}
?>