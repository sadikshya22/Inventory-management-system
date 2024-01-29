<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if (!isset($_SESSION['AdminLoginID'])) {
    header("location:index.php");
    exit;
}

// Fetch total revenue from the 'paid' column in the 'orders' table
$select_revenue = "SELECT SUM(paid) AS total_revenue FROM orders";
$query_revenue = mysqli_query($conn, $select_revenue);
$row_revenue = mysqli_fetch_assoc($query_revenue);
$total_revenue = $row_revenue['total_revenue'];

// Fetch products with low quantity (less than 10)
$select_low_stock = "SELECT * FROM product WHERE quantity < 10";
$query_low_stock = mysqli_query($conn, $select_low_stock);
$total_low_stock = mysqli_num_rows($query_low_stock);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="path/to/fontawesome/css/all.min.css">

    <style>
  
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f2f2f2;
           /* background-image: url("bgimage.png");*/
        }

       
        div.header button {
            font-size: 16px;
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
        .logout {
  /* Styles for the logout link (icon) */
  margin-left: 20px; /* Adjust the margin to separate it from other links */
}

        .container {
            display: flex;
            flex-wrap: wrap;
            margin: 20px;
            justify-content: space-between;
        }

        .card {
            background-color: #50D8D7;
            border-radius: 10px;
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2);
            margin: 20px;
            padding: 20px;
            text-align: center;
            width: calc(25% - 40px);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }
        .card {
    /* ... (existing CSS rules) ... */
    transition: transform 0.2s, box-shadow 0.2s, background-color 0.2s; /* Add background-color to the transition */
    cursor: pointer;
}

.card:hover,
.card:focus,
.card:active {
    transform: scale(1.05);
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    background-color: #e6ffe6; /* Green background color on hover, focus, and active */
}
.card a {
    text-decoration: none; /* Remove underline from the link */
    color: #333; /* Set custom color for the link text */
}

.card a:hover,
.card a:focus,
.card a:active {
    color: #fff; /* Change text color when link is hovered, focused, or active */
}

        @media screen and (max-width: 1200px) {
            .card {
                width: calc(33.33% - 40px);
            }
        }

        @media screen and (max-width: 768px) {
            .card {
                width: calc(50% - 40px);
            }
        }

        @media screen and (max-width: 480px) {
            .card {
                width: calc(100% - 40px);
            }
        }
        /* Style for the dashboard and pie chart position */
.dashboard-chart {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f2f2f2; /* Set the background color for the chart area */
    padding: 20px;
}


    </style>
   <link rel="stylesheet" href="path/to/fontawesome/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

</head>
<body>
<header>
    <h1>Admin Dashboard</h1>
</header>

<nav>
  <a href="dashboard.php">Dashboard</a>
  <a href="product.php">Products</a>
  <a href="order_view.php">Orders</a>
  <a href="categories.php">Category</a>
  <a href="brand.php">Brand</a>
 <a href="logout.php" class="logout" style="color:red;">Logout</a>

</nav>


<div class="container">
    <div class="card">
        <a href="product.php">
            <h2>Products</h2>
            <?php
            $select_product = "SELECT * FROM product";
            $query_product = mysqli_query($conn, $select_product);
            $res_product = mysqli_num_rows($query_product);
            ?>
            <p><?php echo $res_product ?></p>
        </a>
    </div>



    <div class="card">
        <a href="order_view.php">
        <h2>Orders</h2>
        <?php
        $select_orders = "SELECT * FROM orders";
        $query_orders = mysqli_query($conn, $select_orders);
        $res_orders = mysqli_num_rows($query_orders);
        ?>

        <p><?php echo $res_orders ?></p>
    </a>
    </div>

    <div class="card">
        <a href="order_view.php">
        <h2>Revenue</h2>
        <p>NPR <?php echo $total_revenue ?></p>
    </a>
    </div>
     <div class="card">
        <a href="product.php">
        <h2>Low Stock</h2>
        <p><?php echo $total_low_stock ?></p>
    </a>
    </div>


<!-- Create a canvas element for the pie chart -->
<div class="dashboard-chart">
   <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
</div>

<!-- Pass PHP variables to JavaScript and create the line graph -->
 <script>
    const labels = ["Products", "Orders", "Revenue", "Low Stock"];
    const data = [
        <?php echo $res_product ?>,
        <?php echo $res_orders ?>,
        <?php echo $total_revenue; ?>,
        <?php echo $total_low_stock; ?>
    ];
    const colors = ["red", "green", "blue", "orange"];

    new Chart("myChart", {
        type: "line",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Products",
                    data: [0, <?php echo $res_product ?>],
                    borderColor: colors[0],
                    backgroundColor: colors[0],
                    fill: false,
                },
                {
                    label: "Orders",
                    data: [0, <?php echo $res_orders ?>],
                    borderColor: colors[1],
                    backgroundColor: colors[1],
                    fill: false,
                },
                {
                    label: "Revenue",
                    data: [0, <?php echo $total_revenue; ?>],
                    borderColor: colors[2],
                    backgroundColor: colors[2],
                    fill: false,
                },
                {
                    label: "Low Stock",
                    data: [0, <?php echo $total_low_stock; ?>],
                    borderColor: colors[3],
                    backgroundColor: colors[3],
                    fill: false,
                },
            ],
        },
        options: {
            legend: { display: false },
            responsive: true,
            maintainAspectRatio: false,
        },
    });
</script>


</div>

</body>
</html>



