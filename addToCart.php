<?php session_start();
/*
Filename: addtocart.php
Author: Matthew Raw
Date Created: 4/5/17
Last Updated: 4/5/17
Description: add to cart page
*/

include "includes/myFunctions.php";

//if no cart exists yet, create one
if(!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = array();
	$_SESSION['orderNetValue'] = 0;
	$_SESSION['totalOrderedItems'] = 0;
}

//clean input from form
$prodId = cleanInput($_POST['prodId']);
$prodName = cleanInput($_POST['prodName']);
$qtyOnHand = cleanInput($_POST['qtyOnHand']);
$listPrice = cleanInput($_POST['listPrice']);
$thumbNail = cleanInput($_POST['thumbNail']);

//if item is not in cart, add it
if (!isset($_SESSION['cart'][$prodId])) {
	
	//add item to cart
	$_SESSION['cart'][$prodId] = array(
		'prodName'=> $prodName,
		'qtyOnHand'=> $qtyOnHand,
		'listPrice'=> $listPrice,
		'qtyOrdered' => 1,
		'thumbNail' => $thumbNail
		);

	//add value to total order cost
	$_SESSION['orderNetValue'] = $_SESSION['orderNetValue'] + $listPrice;
	$_SESSION['totalOrderedItems']++;
}
//else customer wants another item, increase qtyOrdered
else {
	$_SESSION['cart'][$prodId]['qtyOrdered']++;
	$_SESSION['orderNetValue'] = $_SESSION['orderNetValue'] + $listPrice;
	$_SESSION['totalOrderedItems']++;
}

header("Location: ".htmlspecialchars($_SERVER['HTTP_REFERER']));

?>