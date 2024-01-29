<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

$message = [];

if (isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $buying_price = $_POST['buying_price'];
    $rate = $buying_price + ($buying_price * 0.2); // Calculate product price with 20% increase
    $brandname = $_POST['brandname'];
    $categories_name = $_POST['categories_name'];
    $status = ($_POST['status'] == 'Available') ? 0 : 1;

    if (empty($product_name) || empty($quantity) || empty($buying_price) || empty($brandname) || empty($categories_name)) {
        $message[] = 'Please fill out all fields.';
    } elseif (!preg_match('/^[0-9]+$/', $quantity) || !preg_match('/^[0-9]+$/', $buying_price)) {
        $message[] = "Quantity and Buying Price should be numeric.";
    } elseif ($buying_price <= 0) {
        $message[] = "Buying Price should be greater than zero.";
    } else {
        $updateQuery = "UPDATE product SET product_name = '$product_name', categories_id = '$categories_name', brand_id = '$brandname', quantity = '$quantity', buying_rate = '$buying_price', rate = '$rate', active = '$status' WHERE product_id = $product_id";
        $updateResult = mysqli_query($connect, $updateQuery);
        if ($updateResult) {
            $message[] = "Product updated successfully.";
            header("Location: product.php"); // Redirect to product.php page
            exit();
        } else {
            $message[] = "Failed to update product.";
        }
    }
}

if (isset($_GET['edit'])) {
    $product_id = $_GET['edit'];
    $editQuery = "SELECT * FROM product WHERE product_id = $product_id";
    $editResult = mysqli_query($connect, $editQuery);
    $editData = mysqli_fetch_assoc($editResult);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Product</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .container {
            margin-top: 20px;
        }

        .admin-product-form-container {
            width: 400px;
            margin: 0 auto;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
        }

        .admin-product-form-container h2 {
            text-align: center;
        }

        .admin-product-form-container .box {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .admin-product-form-container .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .admin-product-form-container .btn:hover {
            background-color: #45a049;
        }

        .message {
            display: block;
            color: #ff0000;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Product</h1>
    </header>

    <div class="container">
        <div class="admin-product-form-container">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $editData['product_id']; ?>">
                <input type="text" name="product_name" placeholder="Enter Product name" class="box" value="<?php echo $editData['product_name']; ?>"><br>
                <select class="box" name="categories_name">
                    <option>Select Category</option>
                    <?php 
                    $categoryQuery = "SELECT categories_id, categories_name, categories_active, categories_status FROM category WHERE categories_active = 0";
                    $categoryResult = mysqli_query($connect, $categoryQuery);

                    while ($row = mysqli_fetch_assoc($categoryResult)) {
                        $selected = ($row['categories_id'] == $editData['categories_id']) ? 'selected' : '';
                        echo "<option value='" . $row['categories_id'] . "' " . $selected . ">" . $row['categories_name'] . "</option>";
                    }
                    ?>
                </select>
                <select class="box" name="brandname">
                    <option value="">Select Brand</option>
                    <?php 
                    $brandQuery = "SELECT brand_id, brand_name, brand_active, brand_status FROM brands WHERE brand_active = 0";
                    $brandResult = mysqli_query($connect, $brandQuery);

                    while ($row = mysqli_fetch_assoc($brandResult)) {
                        $selected = ($row['brand_id'] == $editData['brand_id']) ? 'selected' : '';
                        echo "<option value='" . $row['brand_id'] . "' " . $selected . ">" . $row['brand_name'] . "</option>";
                    }
                    ?>
                </select><br>
                <input type="text" name="quantity" placeholder="Enter Product quantity (0-9 only)" class="box" pattern="[0-9]+" value="<?php echo $editData['quantity']; ?>"><br>
                <input type="text" name="buying_price" placeholder="Enter Buying Price (0-9 only)" class="box" pattern="[0-9]+" value="<?php echo $editData['buying_rate']; ?>"><br>
                <input type="text" name="rate" placeholder="Product Price" class="box" value="<?php echo $editData['rate']; ?>" readonly><br>
                <select class="box" name="status">
                    <option value="Available" <?php echo ($editData['active'] == 0) ? 'selected' : ''; ?>>Available</option>
                    <option value="Not Available" <?php echo ($editData['active'] == 1) ? 'selected' : ''; ?>>Not Available</option>
                </select><br>
                <input type="submit" name="update_product" class="btn" value="Update Product">
            </form>

            <?php if (!empty($message)) : ?>
                <?php foreach ($message as $msg) : ?>
                    <span class="message"><?php echo $msg; ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
