<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("connection failed" . $connect->connect_error);
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $query = "SELECT rate FROM product WHERE product_id = '$product_id'";

    $result = $connect->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $rate = $row['rate'];
        echo $rate;
    } else {
        echo "0";
    }
}

$connect->close();
?>
