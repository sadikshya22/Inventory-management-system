<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $number = $_POST['number'];
    $address = $_POST['address'];

    // Form validation
    $errors = array();
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        // Check if email already exists
        $checkEmailQuery = "SELECT * FROM supplier_registration WHERE supplier_email='$email'";
        $emailResult = $connect->query($checkEmailQuery);
        if ($emailResult->num_rows > 0) {
            $errors[] = "Supplier with this email already exists. Please provide a different email.";
        }
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    if (empty($number)) {
        $errors[] = "Number is required.";
    } elseif (!preg_match("/^[0-9]{10}$/", $number)) {
        $errors[] = "Invalid phone number format. Please enter a 10-digit number.";
    } else {
        // Check if phone number already exists
        $checkNumberQuery = "SELECT * FROM supplier_registration WHERE supplier_mobile='$number'";
        $numberResult = $connect->query($checkNumberQuery);
        if ($numberResult->num_rows > 0) {
            $errors[] = "Supplier with this phone number already exists. Please provide a different number.";
        }
    }
    if (empty($address)) {
        $errors[] = "Address is required.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO supplier_registration (supplier_name, supplier_email, supplier_password, supplier_mobile, supplier_address) VALUES ('$name', '$email', '$password', '$number', '$address')";

        if ($connect->query($sql) === true) {
            $message = "Registration successful!";
        } else {
            $message = "Error: " . $sql . "<br>" . $connect->error;
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Supplier Registration</title>
    <!-- CSS file -->
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 100px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="number"],
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        .form-group textarea {
            height: 80px;
        }

        .form-group input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            color: #4caf50;
        }

        .errors {
            color: red;
            margin-bottom: 15px;
        }

        .errors ul {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        .errors ul li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Supplier Registration</h2>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="number">Number:</label>
                <input type="number" name="number" id="number" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea name="address" id="address" required></textarea>
            </div>
            <div class="form-group">
                <input type="submit" name="register" value="Register">
            </div>
        </form>
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
