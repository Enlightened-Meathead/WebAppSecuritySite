<?php require_once "./includes/db.inc.php"; ?>
<html>
<body>

<?php include("./includes/force_login.inc.php");?>
<?php include("./includes/header.inc.php");?><br>
<?php

if($_SESSION['csrf_token']===$_GET['csrf_token']){
	if(strip_tags($_SESSION['username'])) {

		$myid = strip_tags($_GET['id']);
		$sql = "DELETE FROM products WHERE id=?";

		//Create a prepared statement
		$stmt = mysqli_stmt_init($mysqli);

		//Prepare the statement
		if (!mysqli_stmt_prepare($stmt, $sql)){
			echo "Error T-T";
		} else {
			// Bind the parameters to the placeholder values in the sql variable query
			mysqli_stmt_bind_param($stmt, "s", $myid);
			
			// Execute the statement
			if (mysqli_stmt_execute($stmt)) {
				echo "Product $myname deleted.";
			} else {
				echo "Error T-T";
			}
		}
	} else{
		echo "Slow down there hacker... You aren't allowed to do that without logging in <.<";
	}
} else {
	echo "Error T-T";
}


?>

</body>
</html>
