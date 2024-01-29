<?php
session_start();

$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Form validation
    $errors = array();
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        $sql = "SELECT * FROM supplier_registration WHERE supplier_name = '$username' AND supplier_password = '$password'";
        $result = $connect->query($sql);

        if ($result->num_rows == 1) {
            // Login successful
            $row = $result->fetch_assoc();
            $_SESSION['supplier_username'] = $username; // Store supplier username in session
            header("Location: supplier_dashboard.php");
            exit();
        } else {
            // Login failed
            $message = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Supplier Registration & Login</title>
    <!-- CSS file -->
    <style type="text/css">
         
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background: url('images/login-bg.png') no-repeat center center fixed;
            background-size: cover;
        }
        div.loginHeader{
    text-align: center;
    margin-bottom: 150px;
}
div.loginHeader h1{
    font-size: 100px;
    color: #f685a2;
    padding: 0px;
    margin: 0px;
}
div.loginHeader p{
    color: #ffdae3;
    font-size: 50px; 
    
    margin: 0px;
    text-transform: uppercase;
    display: inline-block;
}
div.loginHeader p:after{
    content: '';
    display: block;
    height: 5px;
    background:#f685a2 ;
    
    margin: 0px auto;
}
/*
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 100px;
        }*/
        div.container form{
             margin: 0 auto;
    width: 300px;
    background: rgba(0, 0, 0, .5);
    padding: 30px;
    border: 2px solid #fff;
    border-radius: 8px;
        }
       /* div.form-group input{
            height: 30px;
    width: 100%;
    border: 2px solid #d40339;
    font-size: 20px;
    padding: 5px;
    text-align: center;
    font-style: italic;
        }*/
        div.form-group label{
            display: block;
    text-transform: uppercase;
    font-size: 20px;
    font-weight: bold;
    color: #fff;
        }
        div.form-group{
            margin-top: 10px;
        }

        h3 {
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
        .form-group a{
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-align: right;
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
    <div class="loginHeader">
            <h1>IMS</h1>
            <p>Inventory Management System</p>
            
        </div>
    <div class="container">
        <h3 style="font-size: 25px;  color: #f685a2;">Supplier Login</h3>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="login-username">Username:</label>
                <input type="text" name="username" id="login-username" required>
            </div>
            <div class="form-group">
                <label for="login-password">Password:</label>
                <input type="password" name="password" id="login-password" required>
            </div>
            <div class="form-group">
                <input type="submit" name="login" value="Login" style=" border-radius: 3px;">
            </div>
            <div class="form-group">
               <a href="supplier_register.php">Register</a>
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
