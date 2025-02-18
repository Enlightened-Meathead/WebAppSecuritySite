<?php require_once "./includes/db.inc.php"; ?>
<html>
<body>
<?php include("./includes/header.inc.php");?>
<h1>Products</h1>
<a href="/create.php">Add a Product</a><br>

<?php

$sql = "SELECT * FROM products";

// This is the procedural style to query the database
$result = mysqli_query($mysqli, $sql);

while($row = mysqli_fetch_array($result)) {
	echo "{$row['name']} \${$row['price']} <b>Comment:</b> {$row['comment']} <b>Calories Per Cup:</b> {$row['cal_per_cup']}
		<a href='update.php?id={$row['id']}'>update</a> 
		<a href='delete.php?id={$row['id']}'>delete</a>
		<br />";
}

?>

</body>
</html>
