<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

if (isset($_POST['update'])) {
    $order_id = $_POST['order_id'];
    $customer_name = $_POST['customer_name'];
    $customer_contact = $_POST['customer_contact'];
    $sub_total = $_POST['sub_total'];
    $discount = $_POST['discount'];
    $grand_total = $_POST['grand_total'];
    $paid = $_POST['paid'];
    $due = $_POST['due'];

    // Perform update operation here using the submitted form data
    $updateSql = "UPDATE orders SET customer_name = '$customer_name', customer_contact = '$customer_contact', sub_total = '$sub_total', discount = '$discount', grand_total = '$grand_total', paid = '$paid', due = '$due' WHERE order_id = $order_id";
    if ($connect->query($updateSql) === TRUE) {
        echo "Order updated successfully";
    } else {
        echo "Error updating order: " . $connect->error;
    }
}

$connect->close();
?>
