<?php require_once "./includes/db.inc.php"; ?>
<html>
<body>
<?php include("./includes/header.inc.php");?>
<h1>Products</h1>
<form>
	<label>Search:</label>
	<input type="text" name="search" maxlength="50" required/>

	<input type="submit" value="Search" />
<form><br>

<a href="/create.php">Add a Product</a><br>

<?php
$mysearch = "%" . strip_tags($_GET['search']). "%";

// search query
$sql = "SELECT * FROM products WHERE name LIKE ? OR comment LIKE ? ORDER BY name ASC";

//Create a prepared statement
$stmt = mysqli_stmt_init($mysqli);


//Prepare the statement
if (!mysqli_stmt_prepare($stmt, $sql)){
	echo "SQL statement preperation failed:" . $mysqli->error;
} else {
	// Bind the parameters to the placeholder values in the sql variable query
	mysqli_stmt_bind_param($stmt, "ss", $mysearch, $mysearch);
	
	// Execute the statement
	if (mysqli_stmt_execute($stmt)) {
		$result = (mysqli_stmt_get_result($stmt));
		//$result = mysqli_query($mysqli, $sql);

		while($row = mysqli_fetch_array($result)) {
			echo "---<br>{$row['name']} \${$row['price']} <b>Comment:</b> {$row['comment']} <b>Calories Per Cup:</b> {$row['cal_per_cup']}
				<a href='update.php?id={$row['id']}'>update</a> 
				<a href='delete.php?id={$row['id']}'>delete</a>
				<br>";
		}
		echo "---";
	} else {
		"Execution failed: " . mysqli_stmt_error($stmt);
	}
}

?>



</body>
</html>
