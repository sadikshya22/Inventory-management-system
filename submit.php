<?php
require('config.php');

if (isset($_POST['stripeToken'])) {
    \Stripe\Stripe::setVerifySslCerts(false);

    // Retrieve the total amount from the hidden input field and convert it to cents (integer)
    $totalAmount = floatval($_POST['totalAmount']) * 100; // Convert dollars to cents

    // Make sure the amount is greater than or equal to 1 cent
    if ($totalAmount >= 100) {
        $totalAmount = intval($totalAmount); // Convert to integer (remove decimals)

        $token = $_POST['stripeToken'];

        $data = \Stripe\Charge::create(array(
            "amount" => $totalAmount,
            "currency" => "usd",
            "description" => "Payment Description",
            "source" => $token,
        ));

        // Payment was successful, display a success message
        echo "<h1>Your payment has been successful!</h1>";

        // Display the date and time when the payment was made
        echo "<p>Payment Date and Time: " . date("Y-m-d H:i:s") . "</p>";

        // Display a note about the payment amount in NPR
        echo "<p>Note: The amount paid is equivalent to NPR " . $totalAmount / 100 . "</p>";

        // Add a button to redirect to dashboard.php
        echo '<a href="dashboard.php" class="btn btn-primary" style="display: block; margin-top: 20px; padding: 10px 20px; text-align: center; background-color: #007BFF; color: #fff; text-decoration: none; border-radius: 5px;">Go to Dashboard</a>';
    } else {
        // If the payment amount is less than 1 cent, show an error message
        echo "Error: The total amount must be at least 1 cent.";
    }
}
?>
