<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
$categories_id = $_GET['edit'];

if (isset($_POST['update_categories'])) {

    $categories_name = $_POST['categories_name'];
    $categories_status = $_POST['categories_status'];

    if (empty($categories_name) || empty($categories_status)) {
        $message[] = 'Please fill out all fields.';
    } else {
        // Update categories_active based on the selected status
        $categories_active = ($categories_status === 'Available') ? 0 : 1;
        
        $update = "UPDATE category SET categories_name='$categories_name', categories_active='$categories_active', categories_status='$categories_status' WHERE categories_id=$categories_id";
        $upload = mysqli_query($connect, $update);

        if ($upload) {
            header('location: categories.php');
        } else {
            $message[] = "Couldn't update category.";
        }
    }
}
if (isset($_GET['edit'])) {
    $categories_id = $_GET['edit'];
    $editQuery = "SELECT * FROM category WHERE categories_id = $categories_id";
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

			$select= mysqli_query($connect,"SELECT * FROM category WHERE categories_id=$categories_id");
			while($row=mysqli_fetch_assoc($select)){





			?>
			<form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
				<h3>Update brand</h3>
				
				<input type="text" name="categories_name" placeholder="Enter brand name" value="<?php echo $row['categories_name']; ?>" class="box"><br>



				
			<select class="box" name="categories_status">
                    <option value="Available" <?php echo ($editData['categories_active'] == 0) ? 'selected' : ''; ?>>Available</option>
                    <option value="Not Available" <?php echo ($editData['categories_active'] == 1) ? 'selected' : ''; ?>>Not Available</option>
                </select><br>


				<input type="submit" name="update_categories" class="btn" value="Update Category">
				<a href="categories.php" class="btn">Go Back</a>

				
			</form>
		<?php  }; ?>
			
		</div>
		
	</div>

</body>
</html>