<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

if (isset($_POST['search'])) {
    $searchValue = $_POST['searchValue'];
    $query = "SELECT * FROM orders WHERE customer_name LIKE '%$searchValue%' OR customer_contact LIKE '%$searchValue%'";
    $result = $connect->query($query);
} else {
    // Fetch order data
    $sql = "SELECT * FROM orders";
    $result = $connect->query($sql);
}

// Delete action
if (isset($_GET['delete'])) {
    $order_id = $_GET['delete'];
    // Delete the order from the database
    $deleteSql = "DELETE FROM orders WHERE order_id = $order_id";
    $connect->query($deleteSql);
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

$connect->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order View</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin-top: 10px;
            border: none;
            cursor: pointer;
            width: 200px;
            font-size: 16px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn a {
            color: white;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn:hover a {
            color: #fff;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #3CB371;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .action-btn {
            display: flex;
            justify-content: center;
        }

        .edit-btn,
        .delete-btn {
            padding: 5px 10px;
            margin: 0 2px;
            background-color: #3CB371;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-btn {
            background-color: #FF6347;
        }

        .edit-btn:hover,
        .delete-btn:hover {
            background-color: #2E8B57;
        }
    </style>
</head>
<body>
    <header>
        <h1>Order View</h1>
    </header>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="product.php">Products</a>
        <a href="orders.php">Orders</a>
        <a href="categories.php">Category</a>
        <a href="brand.php">Brand</a>
        <a href="logout.php" style="color:red;">Logout</a>
    </nav>

     <div class="search-container">
        <form method="POST" action="">
            <input type="text" name="searchValue" placeholder="Search by customer name">
            <button type="submit" name="search"><i class="fa fa-search"></i>Search</button>
        </form>
    </div>

    <div class="btn">
        <a href="orders.php">Add Order</a>
    </div>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Customer Name</th>
                    <th>Customer Contact</th>
                    <th>Subtotal</th>
                    <th>Discount</th>
                    <th>Grand Total</th>
                    <th>Paid Amount</th>
                    <th>Due Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["order_id"]."</td>";
                        echo "<td>".$row["order_date"]."</td>";
                        echo "<td>".$row["customer_name"]."</td>";
                        echo "<td>".$row["customer_contact"]."</td>";
                        echo "<td>".$row["sub_total"]."</td>";
                        echo "<td>".$row["discount"]."</td>";
                        echo "<td>".$row["grand_total"]."</td>";
                        echo "<td>".$row["paid"]."</td>";
                        echo "<td>".$row["due"]."</td>";
                        echo "<td class='action-btn'>";
                        echo "<button class='edit-btn' onclick=\"location.href='edit_order.php?order_id=".$row['order_id']."'\">Edit</button>";
                        echo "<button class='delete-btn' onclick=\"if (confirm('Are you sure you want to delete this order?')) { location.href='?delete=".$row['order_id']."' }\">Delete</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
