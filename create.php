<?php require_once "./includes/db.inc.php"; ?>
<html>
<body>

<?php include("./includes/force_login.inc.php");?>
<?php include("./includes/header.inc.php");?><br>
<?php

if($_SESSION['csrf_token']===$_GET['csrf_token']){
	if (strip_tags($_SESSION['username'])) {
		if(strip_tags($_POST['name'])) {

			// Grab user supplied parameters
			$myname = strip_tags($_POST['name']);
			$myprice = strip_tags($_POST['price']);
			$mycomment = strip_tags($_POST['comment']);
			$mycal_per_cup = strip_tags($_POST['cal_per_cup']);

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
} else {
	echo "CRSF Token Invalid.";

}

?>

<form method="POST">
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
