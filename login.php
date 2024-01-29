<?php

require_once 'php_action/db_connect.php'; 

session_start();

$errors = array();

if($_POST){
	$username =$_POST['username'];
	$password =$_POST['password'];

	if(empty($username) || empty($password)){
		if($username== ""){
			$errors[] ="username is required";
		}
		if($password== ""){
			$errors[] ="password is required";
		}

	}
	else{
		$sql="SELECT *FROM admin_login WHERE username='$username'";
		$result=$connect->query($sql);

		if($result->num_rows==1){
			$password = md5($password);
			//exits
			$mainSql="SELECT*FROM admin_login WHERE username ='$username' AND password ='$password'";
			$mainResult= $connect->query($mainSql);

			if($mainResult->num_rows==1){
				$value =$mainResult->fetch_assoc();
				$user_id=$value['user_id'];

				//set session
				$_SESSION['userId'] = $user_id;

				header('location: http://localhost/Inventory Management System/dashboard.php');
			}
			else{
				$errors[]="Incorrect username/password combination";
			}
		}
		else{
			$error[] ="Username doesnot exits";
		}
		}
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
					<input type="text" name="username" placeholder="Username" />
				</div>
				<div class="loginInputContainer">
					<label for="">Password</label>
					<input type="password" name="password" placeholder="Password" />
				</div>

				<div class="loginButtonContainer">
					<button>login</button>

				</div>	
			</form>			
		</div>

	</div>
	

</body>
</html>