<?php
session_start();
if(strip_tags($_SESSION["username"])) {
	include("./includes/header.inc.php");
	echo "<p>Welcome {$_SESSION['username']}</p>";
	echo '<button onclick="window.location.href=\'logout.php\'">Logout</button>';
	
	
} else {

	require_once "./includes/db.inc.php";
	include("./includes/header.inc.php");

	$myusername = strip_tags($_POST['username']);
	$mypassword = strip_tags($_POST['password']);

	$sql = "SELECT * FROM users WHERE username=? AND password=SHA2(?, 256)";

	//Create a prepared statement
	$stmt = mysqli_stmt_init($mysqli);
	//Prepare the statement
	if (!mysqli_stmt_prepare($stmt, $sql)){
		echo "Error T-T";
	} else {
		// Bind the parameters to the placeholder values in the sql variable query
		mysqli_stmt_bind_param($stmt, "ss", $myusername, $mypassword,);
		
		// Execute the statement
		if (mysqli_stmt_execute($stmt)) {
			$result = mysqli_stmt_get_result($stmt);
			$row = mysqli_fetch_array($result);
			// This is what happens when a user successfully authenticates
			if(!empty($row)) {
				// Delete any data in the current session to start new
				session_destroy();
				session_start();

				$_SESSION['username'] = $row['username'];
			// This is what happens when the username and/or password doesn't match
			} else if (!session_start()){
				echo "<p>Incorrect username OR password</p>";
			} else {
				echo "<br>Please login:";
			}
		} else {
			echo "Error T-T";
		}
	}

	if($_SESSION['username']) {
		// Let's redirect instead of saying "Welcome" here
		echo "<p>Welcome {$_SESSION['username']}</p>";

		//header("Location: {$_GET['redirect']}");
		//<input type="hidden" name="redirect" value="<?= $_GET['redirect'] \?\>" />
		header("Location: login.php");
		exit();

	} else {
	?>
	<html>
	<body>

	<form method="POST">
		<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">


		<label>Username:</label>
		<input type="text" name="username" />

		<label>Password:</label>
		<input type="password" name="password" />

		<input type="submit" value="Log In" />
	</form>

	<?php
	}
	}
?>

</body>
</html>
