<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

$message = array();

if (isset($_POST['add_order'])) {
    $order_date = $_POST['order_date'];
    $customer_name = $_POST['customer_name'];
    $customer_contact = $_POST['customer_contact'];
    $productNames = $_POST['productName'];
    $quantities = $_POST['quantity'];
    $rates = $_POST['rate'];
    $totals = $_POST['total'];
    $subtotal = $_POST['subtotal'];
    $discount = $_POST['discount'];
    $grandTotal = $_POST['grandTotal'];
    $manualPayment = $_POST['manualPayment'];
    $dueAmount = $_POST['dueAmount'];

    if (empty($order_date) || empty($customer_name) || empty($customer_contact) || empty($productNames) || empty($quantities) || empty($rates) || empty($totals)) {
        $message[] = 'Please fill out all fields.';
    } else {
        try {
            $connect->autocommit(false); // Start a transaction
            $success = true;

            // Insert the order into the "orders" table
            $insertOrderQuery = "INSERT INTO orders (order_date, customer_name, customer_contact, sub_total, discount, grand_total, paid, due, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)";
            $stmt = $connect->prepare($insertOrderQuery);
            $stmt->bind_param("ssssssss", $order_date, $customer_name, $customer_contact, $subtotal, $discount, $grandTotal, $manualPayment, $dueAmount);
            $upload = $stmt->execute();
            $order_id = $connect->insert_id;
            $stmt->close();

            if ($upload) {
                $message[] = "New order added with ID: $order_id";

                // Insert order products into the "order_products" table
                $insertOrderProductsQuery = "INSERT INTO order_products (order_id, product_id, quantity, rate, total) VALUES (?, ?, ?, ?, ?)";
                $stmt = $connect->prepare($insertOrderProductsQuery);

                for ($i = 0; $i < count($productNames); $i++) {
                    $productName = $productNames[$i];
                    $quantity = $quantities[$i];
                    $rate = $rates[$i];
                    $total = $totals[$i];

                    $stmt->bind_param("iiiss", $order_id, $productName, $quantity, $rate, $total);
                    $insertResult = $stmt->execute();

                    if (!$insertResult) {
                        $success = false;
                        break;
                    }
                }

                $stmt->close();

                if ($success) {
    $connect->commit(); // Commit the transaction if everything is successful

    // Update product quantities in the "product" table
    $updateProductQuantityQuery = "UPDATE product p
                                  INNER JOIN order_products op ON p.product_id = op.product_id
                                  SET p.quantity = p.quantity - op.quantity
                                  WHERE op.order_id = ?";
    $stmt = $connect->prepare($updateProductQuantityQuery);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();
} else {
    $connect->rollback(); // Rollback the transaction if there was an error
    $message[] = "Error inserting order products.";
}
            } else {
                $message[] = "Error adding order: " . mysqli_error($connect);
            }

            $connect->autocommit(true); // Enable autocommit again
        } catch (Exception $e) {
            $connect->rollback(); // Rollback the transaction if there was an exception
            $message[] = "Error: " . $e->getMessage();
            $connect->autocommit(true); // Enable autocommit again
        }
    }
}

// Fetch products for the dropdown
$productSql = "SELECT * FROM product WHERE active = 0 AND status = 0 AND quantity > 0";
$productData = $connect->query($productSql);

$connect->close();
?>









<!DOCTYPE html>
<html>

<head>
    
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <meta charset="utf-8">
    <title>Order Page</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS file -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style type="text/css">
      
   

    
     <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
             padding: 0;
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
        }  background-color: #555;  background-color: #555;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 5px;
        }

        .message {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #dff0d8;
            border: 1px solid #d0e9c6;
            color: #3c763d;
            border-radius: 5px;
        }

        form {
            margin-bottom: 20px;
        }

        .admin-product-form-container {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .admin-product-form {
            flex: 0 0 33.33%;
            padding: 5px;
        }

        .admin-product-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .admin-product-form input[type="text"],
        .admin-product-form input[type="number"],
        .admin-product-form input[type="date"] {
            width: 100%;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .order-table th,
        .order-table td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        .order-table th {
            background-color: #f2f2f2;
        }

        .order-table td.product-name,
        .order-table td.product-quantity,
        .order-table td.product-rate,
        .order-table td.product-total {
            text-align: center;
        }

        .order-table td.product-actions {
            text-align: center;
        }

        .order-actions {
            margin-top: 10px;
            text-align: right;
        }

        .order-actions input[type="submit"] {
            padding: 5px 10px;
            background-color: #555;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .order-actions .cancel-button {
            background-color: #999;
        }

        .order-total {
            text-align: right;
            margin-top: 10px;
        }

        .order-total label {
            font-weight: bold;
        }

        .order-total span {
            font-weight: normal;
        }

        /* Your custom styles */
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
        #addProductButton{
            color: white;
            background-color: #4CAF50;
            height: 30px;

        }
        #addorder{
              color: white;
            background-color: #4CAF50;
            height: 30px;
        }
        #rate${rowCounter}{
            margin-right: 5px;
            width: 50%;
        }
        
 
</style>

 


    <script>

        function updateRate(row) {
            var productId = document.getElementById('productName' + row).value;
            var rateInput = document.getElementById('rate' + row);
            var rateValueInput = document.getElementById('rateValue' + row);

            // Get the rate from the selected product using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_rate.php?product_id=' + productId, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var rate = parseFloat(xhr.responseText);
                        if (!isNaN(rate)) {
                            rateInput.value = rate.toFixed(2);
                            rateValueInput.value = rate.toFixed(2);
                            calculateTotal(row);
                        } else {
                            rateInput.value = '';
                            rateValueInput.value = '';
                            calculateTotal(row);
                        }
                    } else {
                        rateInput.value = '';
                        rateValueInput.value = '';
                        calculateTotal(row);
                    }
                }
            };
            xhr.send();
        }

        function calculateTotal(row) {
            var rate = parseFloat(document.getElementById('rateValue' + row).value);
            var quantity = parseInt(document.getElementById('quantity' + row).value);
            var totalInput = document.getElementById('total' + row);
            var totalValueInput = document.getElementById('totalValue' + row);

            var total = rate * quantity;
            if (!isNaN(total)) {
                totalInput.value = total.toFixed(2);
                totalValueInput.value = total.toFixed(2);
            } else {
                totalInput.value = '';
                totalValueInput.value = '';
            }
            calculateSubtotal();
            calculateGrandTotal();
        }

        function removeProductRow(row) {
            var rowElement = document.getElementById('row' + row);
            rowElement.parentNode.removeChild(rowElement);
            calculateSubtotal();
            calculateGrandTotal();
        }

        function calculateSubtotal() {
            var subtotal = 0;
            var totalInputs = document.getElementsByName('totalValue[]');

            for (var i = 0; i < totalInputs.length; i++) {
                var totalValue = parseFloat(totalInputs[i].value);
                if (!isNaN(totalValue)) {
                    subtotal += totalValue;
                }
            }

            document.getElementById('subtotal').value = subtotal.toFixed(2);
        }

        function calculateGrandTotal() {
            var subtotal = parseFloat(document.getElementById('subtotal').value);
            var discount = parseFloat(document.getElementById('discount').value);
            var grandTotal = subtotal - discount;
            document.getElementById('grandTotal').value = grandTotal.toFixed(2);
        }

        function calculateDueAmount() {
            var grandTotal = parseFloat(document.getElementById('grandTotal').value);
            var manualPayment = parseFloat(document.getElementById('manualPayment').value);
            var dueAmount = grandTotal - manualPayment;
            document.getElementById('dueAmount').value = dueAmount.toFixed(2);
        }
      
    </script>
</head>

<body>
    <header>
        <h1>Manage Order</h1>
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
    if (!empty($message)) {
        foreach ($message as $msg) {
            echo '<span class="message">' . $msg . '</span>';
        }
    }
    ?>

    <div class="container">
        <div class="admin-product-form-container">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
               <body>
  <input type="date" name="order_date" id="order_date" placeholder="Enter order date" class="box" required><br>

  <script>
    $(document).ready(function() {
      // Get today's date
      var currentDate = new Date();

      // Calculate the date 10 days before
      var startDate = new Date(currentDate);
      startDate.setDate(startDate.getDate() - 10);

      // Calculate the date 10 days after
      var endDate = new Date(currentDate);
      endDate.setDate(endDate.getDate() + 10);

      // Format the dates as strings in "yyyy-mm-dd" format
      var startDateString = startDate.toISOString().split("T")[0];
      var endDateString = endDate.toISOString().split("T")[0];

      // Set the minimum and maximum values for the date input field
      $("#order_date").attr("min", startDateString);
      $("#order_date").attr("max", endDateString);
    });
  </script>
</body>

                <input type="text" name="customer_name" placeholder="Enter customer name" class="box" required><br>
               <input type="text" name="customer_contact" id="customer_contact" placeholder="Enter customer number" class="box" required><br>

<script>
  // Function to handle the phone number input
  function handlePhoneNumberInput() {
    var phoneNumber = document.getElementById("customer_contact").value;
    phoneNumber = phoneNumber.replace(/\D/g, ""); // Remove non-digit characters
    phoneNumber = phoneNumber.slice(0, 10); // Keep only the first 10 digits
    document.getElementById("customer_contact").value = phoneNumber;
  }

  // Attach an event listener to the input field
  document.getElementById("customer_contact").addEventListener("input", handlePhoneNumberInput);
</script>

             

<button id="addProductButton" class="btn-primary" onclick="addProduct()">Add Product</button>

<table class="table" id="productTable" style="display: none;">
    <thead>
        <tr>
            <th style="width: 50%;">Product</th>
            <th style="width: 50%;">Rate</th>
            <th style="width: 25%;">Quantity</th>
            <th style="width: 25%;">Total</th>
            <th style="width: 10%;"></th>
        </tr>
    </thead>
    <tbody id="productRows">
        <tr id="row1">
            <td>
                <div class="form-group">
                    <select class="form-control" name="productName[]" id="productName1" onchange="updateRate(1)" multiple>
                        <option value="">~~SELECT~~</option>
                        <?php while ($row = $productData->fetch_array()) { ?>
                            <option value="<?php echo $row['product_id']; ?>" id="changeProduct<?php echo $row['product_id']; ?>"><?php echo $row['product_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </td>
            <td>
                <input type="text" name="rate[]" id="rate1" class="box" readonly>
                <input type="hidden" name="rateValue[]" id="rateValue1">
            </td>
             <td>
        <input type="number" name="quantity[]" id="quantity1" class="box" min="1" oninput="calculateTotal(1)" required>
    </td>
    <td>
        <input type="text" name="total[]" id="total1" class="box" readonly>
        <input type="hidden" name="totalValue[]" id="totalValue1">
    </td>
            <td></td>
        </tr>
    </tbody>
</table>

<script>
    var rowCounter = 1;

    function addProduct() {
        rowCounter++;
        var productRows = document.getElementById("productRows");

        var newRow = document.createElement("tr");
        newRow.id = "row" + rowCounter;

        newRow.innerHTML = `
            <td>
                <div class="form-group">
                    <select class="form-control" name="productName[]" id="productName${rowCounter}" onchange="updateRate(${rowCounter})" multiple>
                        <option value="">~~SELECT~~</option>
                        <?php while ($row = $productData->fetch_array()) { ?>
                            <option value="<?php echo $row['product_id']; ?>" id="changeProduct<?php echo $row['product_id']; ?>"><?php echo $row['product_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </td>
            <td>
                <input type="text" name="rate[]" id="rate${rowCounter}" class="box" readonly>
                <input type="hidden" name="rateValue[]" id="rateValue${rowCounter}">
            </td>
            <td>
                <input type="number" name="quantity[]" id="quantity${rowCounter}" class="box" min="1" oninput="calculateTotal(${rowCounter})" required>
            </td>
            <td>
                <input type="text" name="total[]" id="total${rowCounter}" class="box" readonly>
                <input type="hidden" name="totalValue[]" id="totalValue${rowCounter}">
            </td>
            <td>
                <button type="button" class="btn-remove" onclick="removeProductRow(${rowCounter})"><i class="fas fa-minus"></i></button>
            </td>
        `;

        productRows.appendChild(newRow);
        document.getElementById("productTable").style.display = "block";
        document.getElementById("addProductButton").innerHTML = "Add Another Product";
    }

    function removeProductRow(row) {
        var rowToRemove = document.getElementById("row" + row);
        rowToRemove.remove();
    }
</script>


<script>
    var rowCounter = 1;

function addProduct() {
    rowCounter++;
    var productRows = document.getElementById("productRows");

    var newRow = document.createElement("tr");
    newRow.id = "row" + rowCounter;

    newRow.innerHTML = `
        <td>
            <div class="form-group">
                <select class="form-control" name="productName[]" id="productName${rowCounter}" onchange="updateRate(${rowCounter})" multiple>
                    <option value="">~~SELECT~~</option>
                    <?php $productData->data_seek(0); // Reset the product data pointer ?>
                    <?php while ($row = $productData->fetch_array()) { ?>
                        <option value="<?php echo $row['product_id']; ?>" id="changeProduct<?php echo $row['product_id']; ?>"><?php echo $row['product_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </td>
        <td>
            <input type="text" name="rate[]" id="rate${rowCounter}" class="box" readonly>
            <input type="hidden" name="rateValue[]" id="rateValue${rowCounter}">
        </td>
        <td>
            <input type="number" name="quantity[]" id="quantity${rowCounter}" class="box" min="1" oninput="calculateTotal(${rowCounter})" required>
        </td>
        <td>
            <input type="text" name="total[]" id="total${rowCounter}" class="box" readonly>
            <input type="hidden" name="totalValue[]" id="totalValue${rowCounter}">
        </td>
        <td>
            <button type="button" class="btn-remove" onclick="removeProductRow(${rowCounter})"><i class="fas fa-minus"></i></button>
        </td>
    `;

    productRows.appendChild(newRow);
    document.getElementById("productTable").style.display = "block";
    document.getElementById("addProductButton").innerHTML = "Add Another Product";
}

function removeProductRow(row) {
    var rowToRemove = document.getElementById("row" + row);
    rowToRemove.remove();
}
</script>
    


<script>
    function toggleProductTable() {
        var productTable = document.getElementById("productTable");
        var addProductButton = document.getElementById("addProductButton");

        if (productTable.style.display === "none") {
            productTable.style.display = "block";
            addProductButton.innerHTML = "Hide Product";
        } else {
            productTable.style.display = "none";
            addProductButton.innerHTML = "Add Product";
        }
    }
</script>


                <div class="form-group">
                    <input type="text" name="subtotal" id="subtotal" placeholder="Subtotal" class="box" readonly>
                </div>
                
                <div class="form-group">
                    <input type="text" name="discount" id="discount" placeholder="Discount" class="box" oninput="calculateGrandTotal()">
                </div>
                <div class="form-group">
                    <input type="text" name="grandTotal" id="grandTotal" placeholder="Grand Total" class="box" readonly>
                </div>
                <div class="form-group">
                    <input type="text" name="manualPayment" id="manualPayment" placeholder="Paid Amount" class="box" oninput="calculateDueAmount()">
                </div>
                <div class="form-group">
                    <input type="text" name="dueAmount" id="dueAmount" placeholder="Due Amount" class="box" readonly>
                </div>
                
                <input id="addorder" type="submit" name="add_order" value="Add Order" class="btn-primary">
            </form>
        </div>
    </div>
</body>

</html>


