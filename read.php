<?php require_once "./includes/db.inc.php"; ?>
<html>
<body>
<?php 
// Set the csrf_token to a value that's unpredictable
$_SESSION["csrf_token"] = bin2hex(random_bytes(64));
include("./includes/header.inc.php");
?>
<h1>Products</h1>
<form method="post">
	<label>Search:</label>
	<input type="text" name="search" maxlength="50"/>

	<input type="submit" value="Search" />
</form>


<?php
if($_SESSION['username']) {
	echo "
	<a href='create.php?csrf_token={$_SESSION['csrf_token']}'>Add a product</a>
	<br/>";
}
$mysearch = "%" . strip_tags($_POST['search']). "%";

// search query
$sql = "SELECT * FROM products WHERE name LIKE ? OR comment LIKE ? ORDER BY name ASC";

//Create a prepared statement
$stmt = mysqli_stmt_init($mysqli);


//Prepare the statement
if (!mysqli_stmt_prepare($stmt, $sql)){
	echo "Error T-T";
} else {
	// Bind the parameters to the placeholder values in the sql variable query
	mysqli_stmt_bind_param($stmt, "ss", $mysearch, $mysearch);
	
	// Execute the statement
	if (mysqli_stmt_execute($stmt)) {
		$result = (mysqli_stmt_get_result($stmt));
		while($row = mysqli_fetch_array($result)) {
			echo "---<br>
			{$row['name']} \${$row['price']} <b>Comment:</b> {$row['comment']} <b>Calories Per Cup:</b> {$row['cal_per_cup']} ";
			if($_SESSION['username']) {
				echo "
				<a href='update.php?id={$row['id']}&csrf_token={$_SESSION['csrf_token']}'>update</a>
				<a href='delete.php?id={$row['id']}&csrf_token={$_SESSION['csrf_token']}'>delete</a>";
			}
			echo <<<EOT
				<form action='/cart/index.php' method="POST">
					<input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'/>
					<input type='hidden' name='id' value='{$row['id']}'/>
					<input type="number" id="quantity" name="quantity" min="1" max="5" value='1'>
					<button type='submit'>Add to cart</button>
				</form>
				EOT;
		}
		echo "---";

	} else {
		echo "Error T-T";
	}
}

?>

</body>
</html>
