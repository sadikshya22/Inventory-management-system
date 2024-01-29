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
if(isset($_POST['signin'])){
				$query="SELECT * FROM admin_login WHERE username='$_POST[username]' AND password='$_POST[password]'";
				$result=mysqli_query($conn,$query);   
       if(mysqli_num_rows($result)==1){
       session_start();
        $_SESSION['AdminLoginID']=$_POST['username'];
       header("location: dashboard.php");
       
       

       
       }
       else{
       	 	echo"<script>alert('Incorrect password');</script>";

       } 
            
    }



?>

<?php 
if(isset($_SESSION['AdminLoginID'])){
	redirect(web_root."dashboard.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inventory Management System</title>
	<link rel="stylesheet" type="text/css" href="css/login.css">
	
</head>
<body>
	<div class="container">
		<div class="loginHeader">
			<h1>IMS</h1>
			<p>Inventory Management System</p>
			
		</div>
		<div class="loginBody">
			<form  action="<?php echo $_SERVER ['PHP_SELF'] ?>" method="POST" id="loginform">
				<div class="loginInputContainer">
					<label for="">Username</label>
					<input type="text" name="username" placeholder="Username" required />
				</div>
				<div class="loginInputContainer">
					<label for="">Password</label>
					<input type="password" name="password" placeholder="Password" required />
				</div>

				<div class="loginButtonContainer">
					<button type="submit" name="signin">signin</button>

				</div>	
			</form>			
		</div>

	</div>
	

</body>
</html>