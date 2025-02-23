<?php require_once "./includes/db.inc.php"; ?>
<html>
<body>

<?php include("./includes/force_login.inc.php");?>
<?php include("./includes/header.inc.php");?><br>
<?php

$myid = strip_tags((int)$_REQUEST['id']);
$myname = strip_tags($_REQUEST['name']);
$myprice = strip_tags((float)$_REQUEST['price']);
$mycomment = strip_tags($_REQUEST['comment']);
$mycal = strip_tags((int)$_REQUEST['cal_per_cup']);

if ($_SESSION['username']) {
	if($_REQUEST['name']) {
		$sql = "UPDATE products SET name=?, price=?, comment=?, cal_per_cup=? WHERE id=?";
		//Create a prepared statement
		$stmt = mysqli_stmt_init($mysqli);

		//Prepare the statement
		if (!mysqli_stmt_prepare($stmt, $sql)){
			echo "SQL statement preperation failed:" . $mysqli->error;
		} else {
			// Bind the parameters to the placeholder values in the sql variable query
			mysqli_stmt_bind_param($stmt, "sdsii", $myname, $myprice, $mycomment, $mycal, $myid);
			
			// Execute the statement
			if (mysqli_stmt_execute($stmt)) {
				echo "Product $myname updated successfully!";
			} else {
				"Execution failed: " . mysqli_stmt_error($stmt);
			}
		}
	}
} else {
	echo "Sorry partner, but you ain't logged in...";
}

$sql = "SELECT * FROM products WHERE id=$myid";

// This is the procedural style to query the database
$result = mysqli_query($mysqli, $sql);

$row = mysqli_fetch_array($result);
?>

<form>
	<input type="hidden" name="id" value="<?= $row['id'] ?>" />

	<label>Name:</label>
	<input type="text" name="name" value="<?php echo $row['name'] ?>" /><br>

	<label>Price:</label>
	<input type="text" name="price" min="0.01" step="0.01" max="99"  value="<?= $row['price'] ?>" /><br>

	<label>Comments:</label>
	<input type="text" name="comment" value="<?= $row['comment'] ?>"/><br>

	<label>Calories Per Cup:</label>
	<input type="number" name="cal_per_cup" value="<?= $row['cal_per_cup'] ?>"/><br>

	<input type="submit" value="update" />
</form>

</body>
</html>
