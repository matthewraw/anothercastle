<?php session_start();

	unset($_SESSION['cart']);
	unset($_SESSION['orderNetValue']);
	unset($_SESSION['totalOrderedItems']);

	echo"<script type='text/javascript'>alert('cart emptied');</script>";
	header("Location: ".htmlspecialchars($_SERVER['HTTP_REFERER']));
?>