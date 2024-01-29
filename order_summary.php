<?php
session_start(); // Start the session

// Check if the order details are available in the session
if (isset($_SESSION['order_details'])) {
    $order_details = $_SESSION['order_details'];
} else {
    // Redirect to placeorder.php if no order details are found
    header("Location: placeorder.php");
    exit;
}

// Calculate the total amount
$totalAmount = 0;
foreach ($order_details['buying_rates'] as $index => $buying_rate) {
    $totalAmount += $buying_rate * $order_details['quantities'][$index];
}

// Get today's date and time
$todayDate = date("Y-m-d");
$currentTime = date("H:i:s");
?>

<!DOCTYPE html>
<html>
<head>
   <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 20px;
        }

        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #e2f1e7; /* Green-themed background */
            border: 2px solid #ccc;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        .receipt-heading {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .supplier-name {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            border-top: 1px dashed #333; /* Dotted line */
            border-bottom: 1px dashed #333; /* Dotted line */
            padding: 10px 0;
            margin-bottom: 20px;
        }

        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .receipt-item label {
            font-weight: bold;
        }

        .receipt-item .value {
            flex-grow: 1;
            text-align: right;
        }

        .receipt-total {
            display: flex;
            justify-content: space-between;
            border-top: 2px solid #ccc;
            margin-top: 10px;
            padding-top: 10px;
        }

        .receipt-total label {
            font-weight: bold;
        }

        .receipt-total .value {
            flex-grow: 1;
            text-align: right;
        }

        .date-time-section {
            text-align: right;
            margin-top: 20px;
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-heading"> Receipt</div>


        <div class="supplier-name"><h1>PAYMENT TO SUPPLIER</h1>
             <h6>IMADOL LALITPUR</h6>

         </div>
        
        <?php foreach ($order_details['product_names'] as $index => $product_name) : ?>
            <div class="receipt-item">
                <label><?php echo $product_name; ?></label>
                <span class="value">
                    Quantity: <?php echo $order_details['quantities'][$index]; ?> |
                    Price: <?php echo $order_details['buying_rates'][$index]; ?> |
                    Supplier: <?php echo $order_details['supplier_names'][$index]; ?>
                </span>
            </div>
        <?php endforeach; ?>

        <div class="receipt-total">
            <label>Total:</label>
            <span class="value">
                <?php echo $totalAmount; ?>
            </span>
        </div>

        <div class="date-time-section">
            Date: <?php echo $todayDate; ?> | Time: <?php echo $currentTime; ?>
        </div>

        <?php 
        require('config.php');
        ?>

        <form action="submit.php" method="post">
            <!-- Your other order summary details -->
            <input type="hidden" name="totalAmount" value="<?php echo $totalAmount; ?>">
            <script 
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="<?php echo $publishableKey ?>"
                data-amount="<?php echo $totalAmount * 100; ?>"
                data-name="Payment"
                data-description="Payment Description"
                data-image="https://static.vecteezy.com/system/resources/previews/005/569/519/original/money-cash-wealth-payment-solid-icon-illustration-logo-template-suitable-for-many-purposes-free-vector.jpg"
                data-currency="usd"
                data-email="info@payment.com"
            ></script>
        </form>
    </div>
</body>
</html>
