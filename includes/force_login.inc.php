<?php
session_start();

if(!strip_tags($_SESSION['username'])) {
	header("Location: login.php?redirect={$_SERVER['REQUEST_URI']}");
	exit();
}
?>
