<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
$brand_id = $_GET['edit'];

if (isset($_POST['update_brand'])) {

    $brand_name = $_POST['brand_name'];
    $brand_status = $_POST['brand_status'];

    if (empty($brand_name) || empty($brand_status)) {
        $message[] = 'Please fill out all fields.';
    } else {
        // Update brand_active based on the selected status
        $brand_active = ($brand_status === 'Available') ? 0 : 1;
        
        $update = "UPDATE brands SET brand_name='$brand_name', brand_active='$brand_active',brand_status='$brand_status' WHERE brand_id=$brand_id";
        $upload = mysqli_query($connect, $update);

        if ($upload) {
            header('location: brand.php');
        } else {
            $message[] = "Couldn't update brand.";
        }
    }
}
if (isset($_GET['edit'])) {
    $brand_id = $_GET['edit'];
    $editQuery = "SELECT * FROM brands WHERE brand_id = $brand_id";
    $editResult = mysqli_query($connect, $editQuery);
    $editData = mysqli_fetch_assoc($editResult);
}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Brand Update</title>
	<!-- font awesome -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	<!-- css file -->
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<?php

	if(isset($message)){
		foreach($message as $message){
			echo '<span class="message">'.$message.'</span>';
		}
	}
	?>
	<div class="container">
		<div class="admin-product-form-container centered">

			<?php 

			$select= mysqli_query($connect,"SELECT * FROM brands WHERE brand_id=$brand_id");
			while($row=mysqli_fetch_assoc($select)){





			?>
			<form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
				<h3>Update brand</h3>
				
				<input type="text" name="brand_name" placeholder="Enter brand name" value="<?php echo $row['brand_name']; ?>" class="box"><br>



				
				
					<select class="box" name="brand_status">
                    <option value="Available" <?php echo ($editData['brand_active'] == 0) ? 'selected' : ''; ?>>Available</option>
                    <option value="Not Available" <?php echo ($editData['brand_active'] == 1) ? 'selected' : ''; ?>>Not Available</option>
                </select><br>

				<input type="submit" name="update_brand" class="btn" value="Update Brand">
				<a href="brand.php" class="btn">Go Back</a>

				
			</form>
		<?php  }; ?>
			
		</div>
		
	</div>

</body>
</html>