<?php
session_start(); // Start the session

$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_names = $_POST['product_name'];
    $quantities = $_POST['quantity'];
    $buying_rates = $_POST['price'];
    $supplier_names = $_POST['supplier_name'];

    $count = count($product_names);

    for ($i = 0; $i < $count; $i++) {
        $product_name = $product_names[$i];
        $quantity = $quantities[$i];
        $buying_rate = $buying_rates[$i];
        $supplier_name = $supplier_names[$i];

        $insertQuery = "INSERT INTO placeorder (product_name, quantity, buying_rate, supplier_name) VALUES ('$product_name', $quantity, $buying_rate, '$supplier_name')";

        if ($connect->query($insertQuery) !== TRUE) {
            echo "Error: " . $insertQuery . "<br>" . $connect->error;
        }
    }

    // Store the order details in the session variable
    $_SESSION['order_details'] = array(
        'product_names' => $product_names,
        'quantities' => $quantities,
        'buying_rates' => $buying_rates,
        'supplier_names' => $supplier_names
    );

    // Redirect to order_summary.php
    header("Location: order_summary.php");
    exit; // Make sure to add exit here to stop further execution
}

// Fetch product names
$productQuery = "SELECT product_name FROM product";
$productResult = mysqli_query($connect, $productQuery);

// Fetch buying rates
$rateQuery = "SELECT product_name, buying_rate FROM product";
$rateResult = mysqli_query($connect, $rateQuery);

// Fetch supplier names
$supplierQuery = "SELECT supplier_name FROM supplier_registration";
$supplierResult = mysqli_query($connect, $supplierQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Product Form</title>
    <!-- font awesome -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- css file -->
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-group .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .form-group .btn:hover {
            background-color: #45a049;
        }

        .form-group .add-more-btn {
            background-color: #008CBA;
        }

        .form-group .add-more-btn:hover {
            background-color: #0077A3;
        }

        .form-section {
            border-top: 1px solid #ccc;
            margin-top: 20px;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Product</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="order-form">
            <div class="form-section" id="product-sections">
                <div class="product-form">
                    <div class="form-group">
                        <label for="product_name">Product Name:</label>
                        <select name="product_name[]" class="product_name">
                            <?php
                            while ($row = mysqli_fetch_assoc($productResult)) {
                                echo "<option value='" . $row['product_name'] . "'>" . $row['product_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity[]" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <select name="price[]" class="price">
                            <?php
                            while ($row = mysqli_fetch_assoc($rateResult)) {
                                echo "<option value='" . $row['buying_rate'] . "' data-product='" . $row['product_name'] . "'>" . $row['buying_rate'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="supplier_name">Supplier Name:</label>
                        <select name="supplier_name[]" class="supplier_name">
                            <?php
                            while ($row = mysqli_fetch_assoc($supplierResult)) {
                                echo "<option value='" . $row['supplier_name'] . "'>" . $row['supplier_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="button" id="addMoreBtn" class="btn add-more-btn"><i class="fas fa-plus"></i> Add More</button>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Place Order</button>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
         $(document).ready(function() {
        // Add more product sections
        $('#addMoreBtn').click(function() {
            var productSection = $('.product-form').first().clone();
            productSection.find('.btn.add-more-btn').remove();
            productSection.find('select, input').val('');
            $('#product-sections').append(productSection);
        });

        // Update price based on the selected product
        $('.product_name').change(function() {
            var selectedProduct = $(this).val();
            var priceDropdown = $(this).closest('.product-form').find('.price');

            priceDropdown.find('option').hide();
            priceDropdown.find('option[data-product="' + selectedProduct + '"]').show();
            priceDropdown.val(priceDropdown.find('option[data-product="' + selectedProduct + '"]:first').val());
        });
            

            // Submit the form
            $('#order-form').submit(function() {
                // No need for AJAX, let the form submit normally
            });
        });
    </script>
</body>
</html>
