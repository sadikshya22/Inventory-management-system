<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Search functionality
if (isset($_POST['search'])) {
    $searchValue = $_POST['searchValue'];
    $query = "SELECT p.*, c.categories_name, b.brand_name FROM product p INNER JOIN category c ON p.categories_id = c.categories_id LEFT JOIN brands b ON p.brand_id = b.brand_id WHERE p.product_name LIKE '%$searchValue%'";
} else {
    $query = "SELECT p.*, c.categories_name, b.brand_name FROM product p INNER JOIN category c ON p.categories_id = c.categories_id LEFT JOIN brands b ON p.brand_id = b.brand_id";
}

$result = mysqli_query($connect, $query);

if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    mysqli_query($connect, "DELETE FROM product WHERE product_id = $product_id");
    header('location: product.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Product Page</title>
    <style>
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            margin-top: 20px;
        }

        .search-container {
            float: right;
            margin-top: 10px;
        }

        .search-container form {
            display: inline-block;
        }

        .search-container input[type=text] {
            padding: 8px;
            font-size: 16px;
        }

        .search-container button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .search-container button i {
            margin-right: 5px;
        }

        .search-container button:hover {
            background-color: #45a049;
        }

        .product-display {
            margin-top: 20px;
            float: left;
            width: 100%;
        }

        .product-display-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-display-table th,
        .product-display-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .product-display-table th {
            background-color: #3CB371;
            color: #fff;
        }

        .product-display-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .product-display-table tr:hover {
            background-color: #f2f2f2;
        }

        .product-display-table td a {
            margin-right: 5px;
            color: #fff;
            font-size: 14px;
        }

        .product-display-table td a i {
            margin-right: 3px;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 14px;
            margin-top: 10px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            display: inline-block;
            transition: background-color 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .btn a {
            color: white;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #45a049;
        }

    </style>
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

     <div class="search-container">
        <form method="POST" action="">
            <input type="text" name="searchValue" placeholder="Search by product name">
            <button type="submit" name="search"><i class="fa fa-search"></i>Search</button>
        </form>
    </div>

    <div class="btn">
        <a href="add_product.php">Add Product</a>
    </div>
    <div class="btn">
        <a href="placeorder.php">Place Order</a>
    </div>

    <div class="container">
        <table class="product-display-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Product Name</th>
                    <th>Buying price</th>
                    <th>Product Price</th>
                    <th>Product Quantity</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sn = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['buying_rate']; ?></td>
                        <td><?php echo $row['rate']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['brand_name']; ?></td>
                        <td><?php echo $row['categories_name']; ?></td>
                        <td><?php echo ($row['active'] === '0') ? 'Available' : 'Not Available'; ?></td>
                        <td>
                            <a href="product_update.php?edit=<?php echo $row['product_id']; ?>" class="btn" style="background-color: #3CB371;"><i class="fas fa-edit"></i>Edit</a>
                            <a href="product.php?delete=<?php echo $row['product_id']; ?>" class="btn" style="background-color: #FF6347;"><i class="fas fa-trash"></i>Delete</a>
                        </td>
                    </tr>
                <?php }; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
