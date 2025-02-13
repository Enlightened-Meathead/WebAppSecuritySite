<?php require_once "db.inc.php"; ?>
<html>
<body>
<?php include("./header.inc.php");?>
<h1>Products</h1>

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
