<?php
// Start the user's session
session_start();

// Required our database connection
require_once $_SERVER['DOCUMENT_ROOT']."/includes/db.inc.php";
include("../includes/header.inc.php");

// Form variables
$myproduct_id = strip_tags($_POST['id']);
$myquantity = strip_tags($_POST['quantity'] ?: 1);
$myremove_product_id = strip_tags($_GET['remove_product_id']);

// Price
// Prepare the statement
$stmt = mysqli_prepare($mysqli, "SELECT price FROM products WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $myproduct_id);

// Run the query
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
// Get the results of a single row
$row = mysqli_fetch_array($result);
$myprice = $row['price'];

// If the user requested an item to be removed, remove it
if(!empty($myremove_product_id)) {
	unset($_SESSION['cart'][$myremove_product_id]);
}

// If the user sent a product_id, add the quantity to the existing cart quantity
if(!empty($myproduct_id)) {
	if ($_POST['quantity'] > 1) {
		$myquantity = $_POST['quantity'];
	} else {
		$myquantity = 1;
	}
	$_SESSION['cart'][$myproduct_id][$myprice] += $myquantity;
}

// Select all of the product details from the database
$sql = "SELECT * FROM products";
$results = mysqli_query($mysqli, $sql);
while($row = mysqli_fetch_array($results)) {
	// This will produce an array where the product id shows the name; for example:
	// $product_name[1] = 'Apple Juice'
	// $product_name[2] = 'Orange Juice'
	$product_name[$row['id']] = $row['name'];
}

?>
<!DOCTYPE HTML>
<html lang=en>

<head>
	<title>Disco Juice - Shopping Cart</title>
	<style>
		table {
			border-collapse: collapse;
			width: 50rem;
		}
		td, th {
			border: 1px solid black;
			padding: .5rem;
		}
		th {
			text-align: left;
		}
		td.price, th.price {
			text-align: right;
		}
		.remove {
			text-decoration: none;
		}
	</style>
</head>

<body>
<h1>Shopping Cart</h1>

<?php
// BEGIN: If-else shopping cart check
// If the shopping cart is empty, tell the user
if(empty($_SESSION['cart'])) {
	echo "<p>Your shopping cart is empty.</p>";

// Else show the cart contents
} else {
?>

<p>You've picked out some great products! Ready to check out?</p>
<table>
	<thead>
		<tr><th>Product</th><th class="price">Quantity @ Price</th><th class="price">Subtotal</th></tr>
	</thead>
	<tbody>

<?php

// Loop through the items in the shopping cart
foreach($_SESSION['cart'] as $item_product_id => $item) {
	foreach($item as $item_price => $item_quantity) {
		// Find the item name based on our previous database query
		$item_name = $product_name[$item_product_id];
		$item_subtotal = $item_quantity * $item_price;
		$shopping_cart_total += $item_subtotal;

		// Display the table row with a subtotal
		echo "<tr><td>$item_name <a class='remove' href='?remove_product_id=$item_product_id' onclick='return confirm(\"Remove from cart?\");'>&#x1f5d1;</a></td><td class='price'>$item_quantity @ $".number_format($item_price,2)."</td><td class='price'>$".number_format($item_subtotal,2)."</td></tr>";

	}
}
?>

	</tbody>
	<tfoot>
		<tr><th colspan="2" class="price">TOTAL</th><td class="price">$<?= number_format($shopping_cart_total,2) ?></td></tr>
	</tfoot>
</table>

<?php
//END: If-else shopping cart check
}
?>

<p><a href="<?= $_SERVER['HTTP_REFERER'] && (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) != parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) ? $_SERVER['HTTP_REFERER'] : "/" ?>">Continue Shopping</a>

<?php
// Only show the "Checkout" button if there are contents in the cart
if(!empty($_SESSION['cart'])) {
?>

or <button onclick="document.location='checkout.php'">Checkout</button>

<?php
}
?>

</p>
</body>
</html>
