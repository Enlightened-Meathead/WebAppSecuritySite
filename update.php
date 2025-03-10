<?php require_once "./includes/db.inc.php"; ?>
<html>
<body>

<?php include("./includes/force_login.inc.php");?>
<?php include("./includes/header.inc.php");?><br>
<?php
if($_SESSION['csrf_token']===$_GET['csrf_token']){
	if($_SESSION['username']) {
		if($_POST['name']) {

			$myid = strip_tags((int)$_POST['id']);
			$myname = strip_tags($_POST['name']);
			$myprice = strip_tags((float)$_POST['price']);
			$mycomment = strip_tags($_POST['comment']);
			$mycal = strip_tags((int)$_POST['cal_per_cup']);

			$sql = "UPDATE products SET name=?, price=?, comment=?, cal_per_cup=? WHERE id=?";
			//Create a prepared statement
			$stmt = mysqli_stmt_init($mysqli);

			//Prepare the statement
			if (!mysqli_stmt_prepare($stmt, $sql)){
				echo "Error T-T";
			} else {
				// Bind the parameters to the placeholder values in the sql variable query
				mysqli_stmt_bind_param($stmt, "sdsii", $myname, $myprice, $mycomment, $mycal, $myid);
				
				// Execute the statement
				if (mysqli_stmt_execute($stmt)) {
					echo "Product $myname updated successfully!";
				} else {
					echo "Error T-T";
				}
			}
		}
	} else {
		echo "Sorry partner, but you ain't logged in...";
	}
} else {
	echo "Error T-T";
}

$myid = strip_tags((int)$_GET['id']);
$sql = "SELECT * FROM products WHERE id=?";
//Create a prepared statement
$stmt = mysqli_stmt_init($mysqli);
if (!mysqli_stmt_prepare($stmt, $sql)){
	echo "Error T-T";
} else {
	// Bind the parameters to the placeholder values in the sql variable query
	mysqli_stmt_bind_param($stmt, "i", $myid);
	// Execute the statement
	if (mysqli_stmt_execute($stmt)) {
		$result = mysqli_stmt_get_result($stmt);
		//$result = mysqli_query($stmt);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		// Ensure we have a valid result           
		if ($row) {
			echo "<br>Product Name: " . htmlspecialchars($row['name']);
		}
	} else {
		echo "Error T-T";
	}
}
?>


<form method="post">
	<input type="hidden" name="id" value="<?= $row['id'] ?>" />

	<label>Name:</label>
	<input type="text" name="name" value="<?php echo $row['name'] ?>" /><br>

	<label>Price:</label>
	<input type="text" name="price" min="0.01" step="0.01" max="99"  value="<?= $row['price'] ?>" /><br>

	<label>Comments:</label>
	<input type="text" name="comment" value="<?= $row['comment'] ?>"/><br>

	<label>Calories Per Cup:</label>
	<input type="number" name="cal_per_cup" value="<?= $row['cal_per_cup'] ?>"/><br>

	<input type="submit" method="post" value="update" />
</form>

</body>
</html>
