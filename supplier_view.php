<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Fetch order data
$sql = "SELECT * FROM supplier_registration";
$result = $connect->query($sql);

$connect->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Supplier View</title>
    <!-- CSS file -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
         body {
            margin: 0;
            background-color: #f2f2f2;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #444;
            color: #fff;
            overflow: hidden;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: #fff;
            padding: 10px;
            text-decoration: none;
            font-size: 20px;
        }

        nav a:hover {
            background-color: #555;
        }

        .container {
            margin-top: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 16px; /* Adjust font size */
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 12px; /* Adjust padding */
            text-align: center;
        }

        table th {
            background-color: #3CB371;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<header>
    <h1>Supplier Page</h1>
</header>

<nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="product.php">Products</a>
    <a href="order_view.php">Orders</a>
    <a href="categories.php">Category</a>
    <a href="brand.php">Brand</a>
    <a href="supplier_view.php">Supplier</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Phone Number</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["supplier_name"]."</td>";
                    echo "<td>".$row["supplier_email"]."</td>";
                    echo "<td>".$row["supplier_password"]."</td>";
                    echo "<td>".$row["supplier_mobile"]."</td>";
                    echo "<td>".$row["supplier_address"]."</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No suppliers found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
