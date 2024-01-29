<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

if (isset($_POST['add_brand'])) {
    $brand_name = $_POST['brand_name'];
    $brand_status = $_POST['brand_status'];

    if (empty($brand_name) || empty($brand_status)) {
        $message[] = 'Please fill out all fields.';
    } else {
        $sql = "SELECT * FROM brands WHERE brand_name = '$brand_name'";
        $result = mysqli_query($connect, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<script type='text/javascript'>alert('Brand already exists, please try another.')</script>";
        } else {
            // Store the brand status as 0 for available and 1 for not available
            $brand_active = ($brand_status === 'Available') ? 0 : 1;

            $sql = "INSERT INTO brands (brand_name, brand_active, brand_status) VALUES ('$brand_name', '$brand_active', '$brand_status')";
            $upload = mysqli_query($connect, $sql);

            if ($upload) {
                $message[] = "New brand added.";
            } else {
                $message[] = "Couldn't add brand.";
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $brand_id = $_GET['delete'];
    mysqli_query($connect, "DELETE FROM brands WHERE brand_id = $brand_id");
    header('location:brand.php');
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Brand Page</title>
    <!-- font awesome -->
   

    <!-- css file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
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
        }  background-color: #555;
        
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
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 150px;
        }

        .btn-edit {
            background-color: #3CB371;
        }

        .btn-delete {
            background-color: #FF6347;
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

        .product-display {
            margin-top: 20px;
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

        .product-display-table td {
            min-width: 100px;
        }

        .product-display-table td a {
            color: #fff;
            margin-right: 5px;
            font-size: 14px;
            text-decoration: none; /* Remove underline */
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
    <h1>Manage Brand</h1>
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
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<span class="message">' . $msg . '</span>';
    }
}
?>

<div class="container">
    <div class="admin-product-form-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" name="brand_name" placeholder="Enter brand name" class="box"><br>

            <select class="box" name="brand_status">
                <option value="">Select</option>
                <option value="Available"<?php if (isset($_POST['brand_status']) && $_POST['brand_status'] === 'Available') echo ' selected'; ?>>Available</option>
                <option value="NotAvailable"<?php if (isset($_POST['brand_status']) && $_POST['brand_status'] === 'NotAvailable') echo ' selected'; ?>>Not Available</option>
            </select><br>

            <input type="submit" name="add_brand" class="btn" value="Add Brand">
        </form>
    </div>

    <?php 
    $select = mysqli_query($connect, "SELECT * FROM brands");
    ?>

    <div class="product-display">
        <table class="product-display-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Brand name</th>
                    <th>Brand status</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <?php
            $sn = 1;
            while ($row = mysqli_fetch_assoc($select)) {
                $brand_status = ($row['brand_active'] === '0') ? 'Available' : 'Not Available';
            ?>
                <tr>
                    <td><?php echo $sn; ?></td>
                    <td><?php echo $row['brand_name']; ?></td>
                    <td><?php echo $brand_status; ?></td>
                    <td>
                        <a href="brand_update.php?edit=<?php echo $row['brand_id']; ?>" class="btn"><i class="fas fa-edit"></i>Edit</a>
                        <a href="brand.php?delete=<?php echo $row['brand_id']; ?>" class="btn" style="background-color: #FF6347;"><i class="fas fa-trash"></i>Delete</a>
                    </td>
                </tr>
            <?php  
                $sn++;
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>
