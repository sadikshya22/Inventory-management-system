<?php
session_start();

$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Check if the supplier is logged in
if (!isset($_SESSION['supplier_username'])) {
    header('Location: login.php'); // Redirect to the login page
    exit();
}

// Get the logged-in supplier's username
$supplierUsername = $_SESSION['supplier_username'];

// Fetch the supplier's ID from the database based on the username
$getIdQuery = "SELECT supplier_id FROM supplier_registration WHERE supplier_name = '$supplierUsername'";
$getIdResult = mysqli_query($connect, $getIdQuery);

if ($getIdResult && mysqli_num_rows($getIdResult) > 0) {
    $supplierRow = mysqli_fetch_assoc($getIdResult);
    $supplierId = $supplierRow['supplier_id'];

    // Fetch data from the "placeorder" table for the logged-in supplier
    $dataQuery = "SELECT * FROM placeorder WHERE supplier_name = '$supplierUsername'";
    $dataResult = mysqli_query($connect, $dataQuery);
} else {
    // Supplier ID not found
    // Handle the situation accordingly (e.g., redirect to an error page)
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Details</title>
    <!-- font awesome -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- css file -->
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
        }
         header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        div.header button{
            font-size: 16px;
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
        }

        nav a:hover {
            background-color: #555;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
    <h2>Order Details</h2>
</header>
 <nav>
        <a href="supplier_dashboard.php">Dashboard</a>
        <a href="admin_order.php">Orders</a>  
        <a href="supplier_logout.php">Logout</a>
    </nav>
    <div class="container">
        <h2>Order Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th> Rate</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($dataResult)) {
                    echo "<tr>";
                    echo "<td>" . $row['product_name'] . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td>" . $row['buying_rate'] . "</td>";
                     $total = $row['quantity'] * $row['buying_rate'];
                     echo "<td>" . $total . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
