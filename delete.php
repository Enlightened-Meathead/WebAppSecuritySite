<?php require_once "./includes/db.inc.php"; ?>
<html>
<body>

<?php include("./includes/header.inc.php");?><br>
<?php

$myid = $_REQUEST['id'];

if($_SESSION['username']) {
	$sql = "DELETE FROM products WHERE id=?";

	//Create a prepared statement
	$stmt = mysqli_stmt_init($mysqli);

	//Prepare the statement
	if (!mysqli_stmt_prepare($stmt, $sql)){
		echo "SQL statement preperation failed:" . $mysqli->error;
	} else {
		// Bind the parameters to the placeholder values in the sql variable query
		mysqli_stmt_bind_param($stmt, "s", $myid);
		
		// Execute the statement
		if (mysqli_stmt_execute($stmt)) {
			echo "Product $myname deleted.";
		} else {
			"Execution failed: " . mysqli_stmt_error($stmt);
		}
	}


} else {
	echo "Slow down there hacker... You aren't allowed to do that without logging in <.<";
}

/*
// This is the object-oriented style to query the database
if($mysqli->query($sql) === TRUE) {
	echo "Successfully deleted.";
} else {
	echo "Error: $sql <br>" . $mysqli->error;
}
	*/

?>

</body>
</html>
