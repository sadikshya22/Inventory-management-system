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

if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $quantity = intval($_POST['quantity']); // Convert to integer
    $buying_rate = floatval($_POST['buyingrate']); // Convert to float
    $brandname = $_POST['brandname'];
    $categories_name = $_POST['categories_name'];
    $status = $_POST['status'];

    if (empty($product_name) || empty($quantity) || empty($buying_rate) || empty($brandname) || empty($categories_name) || empty($status)) {
        $message[] = 'Please fill out all fields.';
    } elseif ($quantity <= 0 || $buying_rate <= 0) {
        $message[] = 'Quantity and buying rate should be positive numbers.';
    } else {
        $rate = $buying_rate + ($buying_rate * 0.2); // Calculate the product price with a 20% increase
        $active = ($status === 'Not Available') ? 1 : 0; // 1 if "Not Available", 0 if "Available"

        $sql = "INSERT INTO product (product_name, categories_id, brand_id, quantity, buying_rate, rate, active, status) VALUES ('$product_name', '$categories_name', '$brandname', '$quantity', '$buying_rate', '$rate', '$active', '$status')";
        $upload = mysqli_query($connect, $sql);

        if ($upload) {
            $message[] = "New product added.";
        } else {
            $message[] = "Couldn't add product.";
        }
    }
}

if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    mysqli_query($connect, "DELETE FROM product WHERE product_id = $product_id");
    header('location: product.php');
}

// Fetch brand names
$sql = "SELECT brand_id, brand_name FROM brands WHERE brand_active = 0";
$brandResult = mysqli_query($connect, $sql);
$brandOptions = "";
while ($row = $brandResult->fetch_assoc()) {
    $brandOptions .= "<option value='" . $row['brand_id'] . "'>" . $row['brand_name'] . "</option>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Product Page</title>
    <!-- font awesome -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- css file -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style type="text/css">
       

       /* div.header button {
            font-size: 16px;
        }*/
         body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        header {
            background-color: #000;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #000;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: #fff;
            padding: 10px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #333;
        }
    </style>
    <script>
        function calculatePrice() {
            var buyingRate = parseFloat(document.getElementById("buyingrate").value);
            if (!isNaN(buyingRate)) {
                var rate = buyingRate + (buyingRate * 0.2);
                document.getElementById("rate").value = rate.toFixed(2);
            }
        }
    </script>
</head>
<body>
<header>
    <h1>Manage Product</h1>
</header>

<nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="product.php">Products</a>
    <a href="order_view.php">Orders</a>
    <a href="categories.php">Category</a>
    <a href="brand.php">Brand</a>
    <a href="logout.php" style="color:red;">Logout</a>
</nav>

<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<span class="message">' . $msg . '</span>';
    }
}
?>

<div class="container">
    <div class="admin-product-form-container">
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">

            <input type="text" name="product_name" placeholder="Enter Product name" class="box"><br>
            <select class="box" name="categories_name">
                <option value="">Category Select</option>
                <?php
                $sql = "SELECT categories_id, categories_name, categories_active, categories_status FROM category WHERE categories_active = 0";
                $result = mysqli_query($connect, $sql);

                while ($row = $result->fetch_array()) {
                    echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
                }
                ?>
            </select>
            <select class="box" name="brandname">
                <option value="">Brand Select</option>
                <?php echo $brandOptions; ?>
            </select><br>

            <input type="text" pattern="[0-9]+" name="quantity" placeholder="Enter Product quantity" class="box"><br>
            <input type="text" pattern="[0-9]+(\.[0-9]{1,2})?" name="buyingrate" id="buyingrate" placeholder="Enter Product buying price" class="box"
                   onchange="calculatePrice()"><br>

            <input type="text" name="rate" id="rate" placeholder="Enter Product Price" class="box" readonly><br>

            <select class="box" name="status">
                <option value="">Select</option>
                <option value="Available"<?php if (isset($_POST['status']) && $_POST['status'] === 'Available') echo ' selected'; ?>>Available</option>
                <option value="Not Available"<?php if (isset($_POST['status']) && $_POST['status'] === 'Not Available') echo ' selected'; ?>>Not Available</option>
            </select><br>

            <input type="submit" name="add_product" class="btn" value="Add product">
        </form>
    </div>
</div>
</body>
</html>
