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
$success = '';
$error = '';

$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header("Location: products.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = floatval($_POST['price']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $stock_quantity = intval($_POST['stock_quantity']);

    $update_sql = "UPDATE products SET 
                   product_name = '$product_name',
                   category = '$category',
                   price = $price,
                   size = '$size',
                   color = '$color',
                   stock_quantity = $stock_quantity
                   WHERE id = $id";

    if (mysqli_query($conn, $update_sql)) {
        $success = "Product updated successfully!";
        $sql = "SELECT * FROM products WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        $product = mysqli_fetch_assoc($result);
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product - Fashion Store</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #FAFAFA;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 16px;
            width: 500px;
        }
        h1 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            background: #D4AF37;
            color: #1A1A1A;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .success {
            background: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        a {
            color: #D4AF37;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Product</h1>

        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <option <?php echo $product['category']=='Dresses'?'selected':''; ?>>Dresses</option>
                    <option <?php echo $product['category']=='Tops'?'selected':''; ?>>Tops</option>
                    <option <?php echo $product['category']=='Outerwear'?'selected':''; ?>>Outerwear</option>
                </select>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
            </div>
            <div class="form-group">
                <label>Size</label>
                <select name="size">
                    <option <?php echo $product['size']=='XS'?'selected':''; ?>>XS</option>
                    <option <?php echo $product['size']=='S'?'selected':''; ?>>S</option>
                    <option <?php echo $product['size']=='M'?'selected':''; ?>>M</option>
                    <option <?php echo $product['size']=='L'?'selected':''; ?>>L</option>
                    <option <?php echo $product['size']=='XL'?'selected':''; ?>>XL</option>
                </select>
            </div>
            <div class="form-group">
                <label>Color</label>
                <input type="text" name="color" value="<?php echo $product['color']; ?>">
            </div>
            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="stock_quantity" value="<?php echo $product['stock_quantity']; ?>">
            </div>
            <button type="submit">Update Product</button>
            <a href="products.php" style="margin-left: 15px;">Cancel</a>
        </form>
    </div>
</body>
</html>