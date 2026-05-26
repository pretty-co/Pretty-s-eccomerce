<!DOCTYPE html>
<html>
<head>
    <title>Week 1 - Fashion Project</title>
</head>
<body>
    <h1>Hello World!</h1>
    <p>Fashion E-commerce Project - Week 1</p>
    <p>Status:  XAMPP installed and running</p>
    <p>Date: <?php echo date('Y-m-d'); ?></p>
    
    <?php
    $conn = mysqli_connect("localhost", "root", "", "week1db");
    
    if (!$conn) {
        echo "<p style='color:red;'> Database connection failed</p>";
    } else {
        echo "<p style='color:green;'> Database connected successfully!</p>";
        
        $result = mysqli_query($conn, "SELECT * FROM test");
        if ($row = mysqli_fetch_assoc($result)) {
            echo "<p> Database message: " . $row['message'] . "</p>";
        }
        mysqli_close($conn);
    }
    ?>
</body>
</html>