<?php session_start();
/*
Filename: updateCart.php
Author: Matthew Raw
Date Created: 10/5/17
Last Updated: 10/5/17
Description: update cart page
*/

include "includes/myFunctions.php";

if(isset($_POST['updateSubmit'])) {
	//clean input from form
	$prodId = cleanInput($_POST['prodId']);
	$qtyOrdered = cleanInput($_POST['qtyOrdered']);

	if (is_numeric($qtyOrdered)) {
		
		//convert to whole number
		$qtyOrdered = (int)$qtyOrdered;

		//update cart, as long as qty ordered is positive and less than or equal to qtyOnHand
		if ($qtyOrdered >= 0 && $qtyOrdered <= $_SESSION['cart'][$prodId]['qtyOnHand']) {
			$_SESSION['cart'][$prodId]['qtyOrdered'] = $qtyOrdered;
		} 
		//else offer remaining stock if they request more than available
		else if ($qtyOrdered >= 0 && $qtyOrdered > $_SESSION['cart'][$prodId]['qtyOnHand']) {
			$_SESSION['cart'][$prodId]['qtyOrdered'] = $_SESSION['cart'][$prodId]['qtyOnHand'];
		}

		//if item updated to zero, unset item
		if ($_SESSION['cart'][$prodId]['qtyOrdered'] == 0) {
			unset($_SESSION['cart'][$prodId]);
		}

		//if cart is now empty, unset it
		if (count($_SESSION['cart']) == 0) {
			unset($_SESSION['cart']);	
		}

		//reset totals
		$_SESSION['orderNetValue'] = 0;
		$_SESSION['totalOrderedItems'] = 0;

		//update totals
		foreach($_SESSION['cart'] as $item => $value) {
			$_SESSION['orderNetValue'] = $_SESSION['orderNetValue'] + ($_SESSION['cart'][$item]['listPrice'] *  $_SESSION['cart'][$item]['qtyOrdered']);
			$_SESSION['totalOrderedItems'] = $_SESSION['totalOrderedItems'] + $_SESSION['cart'][$item]['qtyOrdered'];
		}

		
	}

}

//if user has clicked delete button
if (isset($_POST['deleteItem'])) {
	
	$deleteItem = $_POST['deleteItem'];

	unset($_SESSION['cart'][$deleteItem]);

	//reset totals
	$_SESSION['orderNetValue'] = 0;
	$_SESSION['totalOrderedItems'] = 0;

	//update totals
	foreach($_SESSION['cart'] as $item => $value) {
		$_SESSION['orderNetValue'] = $_SESSION['orderNetValue'] + ($_SESSION['cart'][$item]['listPrice'] *  $_SESSION['cart'][$item]['qtyOrdered']);
		$_SESSION['totalOrderedItems'] = $_SESSION['totalOrderedItems'] + $_SESSION['cart'][$item]['qtyOrdered'];
	}

	//if cart is now empty, unset
	if (count($_SESSION['cart']) == 0) {
		unset($_SESSION['cart']);	
	}

}

header("Location: ".htmlspecialchars($_SERVER['HTTP_REFERER']));

?>