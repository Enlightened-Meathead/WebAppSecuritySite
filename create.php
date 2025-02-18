<?php require_once "./includes/db.inc.php"; ?>
<html>
<body>

<?php include("./includes/header.inc.php");?><br>
<?php

if ($_SESSION['username']) {
	if($_GET['name']) {

		// Grab user supplied parameters
		$myname = $_REQUEST['name'];
		$myprice = $_REQUEST['price'];
		$mycomment = $_REQUEST['comment'];
		$mycal_per_cup = $_REQUEST['cal_per_cup'];

		// SQL query to be passes to the database
		$sql = "INSERT INTO products (name, price, comment, cal_per_cup) VALUES (?, ?, ?, ?)";

		//Create a prepared statement
		$stmt = mysqli_stmt_init($mysqli);

		//Prepare the statement
		if (!mysqli_stmt_prepare($stmt, $sql)){
			echo "SQL statement preperation failed:" . $mysqli->error;
		} else {
			// Bind the parameters to the placeholder values in the sql variable query
			mysqli_stmt_bind_param($stmt, "sdsi", $myname, $myprice, $mycomment, $mycal_per_cup);
			
			// Execute the statement
			if (mysqli_stmt_execute($stmt)) {
				echo "Product $myname created successfully!";
			} else {
				"Execution failed: " . mysqli_stmt_error($stmt);
			}
		}
	}

} else {
	echo "Login, otherwise, YOU CAN'T CREATE NEW PRODUCTS >:(";
}

?>

<form>
	<label>Name:</label>
	<input type="text" name="name" /><br>

	<label>Price:</label>
	<input type="number" name="price" min="0.01" step="0.01" max="99" required/><br>

	<label>Comment</label>
	<input type="text" name="comment" /><br>

	<label>Calories Per Cup</label>
	<input type="number" name="cal_per_cup"><br>

	<input type="submit" value="Create Product" />
</form>

</body>
</html>
