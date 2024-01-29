 
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname="stock";
// Create connection
$conn = mysqli_connect($servername, $username, $password,$dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
session_start();
if (!isset($_SESSION['supplier_username'])) {
    header("location:supplier_login.php");
    exit;
}



?>

<!DOCTYPE html>
<html>
<head>
    <title>Supplier Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
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
            display: flex;
            flex-wrap: wrap;
            margin: 20px;
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
    </style>
</head>
<body>
    <header>
        <h1><?php

echo"welcome " .$_SESSION['supplier_username'];
?></h1>
    
    </header>

    <nav>
        <a href="supplier_dashboard.php">Dashboard</a>
        <a href="admin_order.php">Orders</a>  
        <a href="supplier_logout.php">Logout</a>
    </nav>

    <div class="container">
        

        <div class="card">
            <h2>Orders</h2>
           <!--  <?php $select_orders="SELECT * FROM placeorder";
            $query_orders= mysqli_query($conn, $select_orders);
            $res_orders= mysqli_num_rows($query_orders);
             ?> -->
            
            <!-- <p><!-- <?php echo $res_orders ?> --></p> -->
            <p><a href="admin_order.php">Check Order</a></p>
        </div>
    </div>
    
</body>
</html>
