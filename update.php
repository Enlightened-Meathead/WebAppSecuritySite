<?php require_once "db.inc.php"; ?>
<html>
<body>

<?php include("./header.inc.php");?><br>
<?php

$myid = (int)$_REQUEST['id'];
$myname = $_REQUEST['name'];
$myprice = (float)$_REQUEST['price'];
$mycomment = $_REQUEST['comment'];
$mycal_per_cup = (int)$_REQUEST['cal_per_cup'];

if($_REQUEST['name']) {
	$sql = "UPDATE products SET name=?, price=?, comment=?, cal_per_cup=? WHERE id=?";
	echo "Name: $myname, Price: $myprice, Comment: $mycomment, Calories: $mycal_per_cup, ID: $myid";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($mysqli);

	//Prepare the statement
	if (!mysqli_stmt_prepare($stmt, $sql)){
		echo "SQL statement preperation failed:" . $mysqli->error;
	} else {
		// Bind the parameters to the placeholder values in the sql variable query
		mysqli_stmt_bind_param($stmt, "sdsii", $myname, $myprice, $mycomment, $mycal_per_cup, $myid);
		
		// Execute the statement
		if (mysqli_stmt_execute($stmt)) {
			echo "Product $myname updated successfully!";
		} else {
			"Execution failed: " . mysqli_stmt_error($stmt);
		}
	}

	/*
	// This is the procedural style to query the database
	if(mysqli_query($mysqli, $sql) === TRUE){
		echo "$myname updated successfully!";
	} else {
		echo "Error: $sql <br>" . mysqli_error($mysqli);
	}
	*/
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
	<input type="text" name="price" value="<?= $row['price'] ?>" /><br>

	<label>Comments:</label>
	<input type="text" name="comment" value="<?= $row['comment'] ?>"/><br>

	<label>Calories Per Cup:</label>
	<input type="text" name="cal_per_cup" value="<?= $row['cal_per_cup'] ?>"/><br>

	<input type="submit" value="update" />
</form>

</body>
</html>
