<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $editSql = "SELECT * FROM orders WHERE order_id = $order_id";
    $editResult = $connect->query($editSql);
    $editData = $editResult->fetch_assoc();
}

$connect->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Order</title>
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

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 10px;
            width: 100%;
        }

        input[type="submit"] {
            padding: 10px;
            width: 150px;
            background-color: #3CB371;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
    <script>
        function calculateDueAmount() {
            var grandTotal = parseFloat(document.getElementById('grand_total').value);
            var paidAmount = parseFloat(document.getElementById('paid').value);
            var dueAmount = grandTotal - paidAmount;

            if (isNaN(dueAmount)) {
                dueAmount = 0;
            }

            document.getElementById('due').value = dueAmount.toFixed(2);
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Edit Order</h2>
        <form method="POST" action="update_order.php">
            <input type="hidden" name="order_id" value="<?php echo $editData['order_id']; ?>">
            <input type="text" name="customer_name" value="<?php echo $editData['customer_name']; ?>" readonly>
            <input type="text" name="customer_contact" value="<?php echo $editData['customer_contact']; ?>" readonly>
            <input type="text" name="sub_total" value="<?php echo $editData['sub_total']; ?>" readonly>
            <input type="text" name="discount" value="<?php echo $editData['discount']; ?>" readonly>
            <input type="text" name="grand_total" id="grand_total" value="<?php echo $editData['grand_total']; ?>" readonly>
            <input type="text" name="paid" id="paid" value="<?php echo $editData['paid']; ?>" placeholder="Paid Amount" oninput="calculateDueAmount()">
            <input type="text" name="due" id="due" value="<?php echo $editData['due']; ?>" readonly>
            <input type="submit" name="update" value="Update">
        </form>
    </div>
</body>
</html>
