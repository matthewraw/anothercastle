<?php session_start();
/*
Filename: updateWishlist.php
Author: Matthew Raw
Date Created: 7/6/17
Last Updated: 11/6/17
Description: update wishlist page
*/

include "includes/myFunctions.php";
include "includes/connect.php";

if (isset($_POST['wishlistSubmitHidden'])) {
	//capture user data
	$prodId = cleanInput($_POST['prodId']);


	//if item is not in wishlist array
	if(!in_array($prodId, $_SESSION['wishlist'])) {

		//add to database
		try {
		        //create SQL statement 
	            $sql= "INSERT INTO tbl_wishlist SET custNbr = :custNbr, prodId = :prodId, dateAdded = :dateAdded;";

	            //prepare statement
	            $statement = $pdo->prepare($sql);

	            //bind the values to the sql placeholders
	            $statement->bindValue(':custNbr', $_SESSION['custNbr']);
	            $statement->bindValue(':prodId', $prodId);
	            $statement->bindValue(':dateAdded', date('Y-m-d'));

	            //execute the sql statement
	            $statement->execute();

		    } //end of try block

		    catch (PDOException $e) {
		        //create an error message
		        echo "Error adding to wishlist: ".$e->getMessage();

		        //stop script continuing
		        exit();

		    } // end of catch block
		//update session[wishlist]
		$_SESSION['wishlist'][] = $prodId;

	}
	//else item is already in wishlist, remove it 
	else if (in_array($prodId, $_SESSION['wishlist'])){
		try {

		        //create SQL statement 
	            $sql= "DELETE FROM tbl_wishlist WHERE custNbr = :custNbr AND prodId = :prodId;";

	            //prepare statement
	            $statement = $pdo->prepare($sql);

	            //bind the values to the sql placeholders
	            $statement->bindValue(':custNbr', $_SESSION['custNbr']);
	            $statement->bindValue(':prodId', $prodId);

	            //execute the sql statement
	            $statement->execute();

		    } //end of try block

		    catch (PDOException $e) {
		        //create an error message
		        echo "Error removing from wishlist: ".$e->getMessage();

		        //stop script continuing
		        exit();

		    } // end of catch block
		
		//update session[wishlist]
	    $key = array_search($prodId, $_SESSION['wishlist']);
	    unset($_SESSION['wishlist'][$key]);
		
	}

}

	header("Location: ".htmlspecialchars($_SERVER['HTTP_REFERER']));