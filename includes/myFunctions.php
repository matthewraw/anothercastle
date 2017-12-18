<?php 
/* 
Filename: myFunctions.php
Author: Matthew Raw
Date Created: 2/3/17
Last Updated: 6/4/17
Description: External file containing user defined functions
*/

//cleans input recieved from an HTML form
function cleanInput($data) {
	
	$data = trim($data); // removes spaces, tabs and new lines
	$data = stripslashes($data); // removes any back slashes
	$data = htmlspecialchars($data); //convert special characters to entity codes
	
	return $data;

} // end of function

//----------------------------------------------------------------------------

function getStateList() {
	//open database connection
	include 'includes/connect.php';

	try {
		//create SQL statement
	    $sql= "SELECT stateId, stateName FROM tbl_state";

	    //execute the sql statement and store the output
	    $resultSet = $pdo->query($sql);
	} //end of try block

	//if 
	catch (PDOException $e) {
		//create an error message
		echo "Error fetching state list: ".$e->getMessage();

		//stop script continuing
		exit();

	} // end of catch block

	foreach ($resultSet as $row) {
		$stateName = $row['stateName'];
		$stateId = $row['stateId'];

		echo '<option value="'.$stateId.'"';
		
		//if function used in update account page, select that option
		if (isset($_SESSION['stateId']) && $stateId == $_SESSION['stateId']) {
			echo ' selected="selected"';
		}
		
		echo'>'.$stateName.'</option>';
	}

} //end of get state list function

//----------------------------------------------------------------------------

function checkCustomerLogin($em, $pw) {
	//connect to database if not already connected	
	if (!(isset($pdo))) { 
			include "includes/connect.php"; 
		}

	try {
	
		//create sql statement
		$sql = "SELECT * FROM tbl_customer WHERE email = :email AND passWord = :passWord;";

		//prepare the statement
		$statement = $pdo->prepare($sql);

		//bind values to statement
		$statement->bindValue(':email', $em);
		$statement->bindValue(':passWord', $pw);

		//execute sql statement
		$statement->execute();

	} // end of try block

	catch (PDOException $e) {
		//create an error message
		echo "Error testing user login: ".$e->getMessage();

		//stop script continuing
		exit();

	} // end of catch block

	//count number of rows, if 1, customer has valid login
	$nbrOfRecords = $statement->rowCount();

	if ($nbrOfRecords == 1) {

		//set up session variable
		$_SESSION['login'] = "valid";

		//fetch customer data from $statement
		$row = $statement->fetch();

		//save customer data to session variables
		$_SESSION['custNbr'] = $row['custNbr'];
		$_SESSION['email'] = $row['email'];
		$_SESSION['firstName'] = $row['firstName'];
		$_SESSION['lastName'] = $row['lastName'];
		$_SESSION['discountRate'] = $row['discountRate'];
		$_SESSION['address'] = $row['address'];
		$_SESSION['suburb'] = $row['suburb'];
		$_SESSION['postCode'] = $row['postCode'];
		$_SESSION['stateId'] = $row['stateId']; 

		$_SESSION['deliveryTo'] = $row['firstName'].' '.$row['lastName'];
		$_SESSION['deliveryAddress'] = $row['address'];
		$_SESSION['deliverySuburb'] = $row['suburb'];
		$_SESSION['deliveryStateId'] = $row['stateId'];
		$_SESSION['deliveryPostCode'] = $row['postCode'];
		$_SESSION['deliveryInstructions'] = "";
		$_SESSION['wishlist'] = array();

		//fetch customer wishlist data
		try {
			//create sql statement
			$sql = "SELECT prodId FROM tbl_wishlist WHERE custNbr = :custNbr;";

			//prepare the statement
			$statement = $pdo->prepare($sql);

			//bind values to statement
			$statement->bindValue(':custNbr', $_SESSION['custNbr']);

			//execute sql statement
			$statement->execute();
			$nbrOfRows = $statement->rowCount();

			if ($nbrOfRows > 0) {

				//store wishlist results
				$wishlist = array();
				$wishlist = $statement->fetchAll();

				$i = 0;
				foreach ($wishlist as $key => $value) {
					$_SESSION['wishlist'][$i] = $value['prodId'];

					$i++;
				}
			}// end if nbrOfRows > 0
		} //end try block

		catch (PDOException $e) {
			//create an error message
			echo "Error fetching user wishlist: ".$e->getMessage();

			//stop script continuing
			exit();

		} // end of catch block


	} // end of if block
	else {
		$_SESSION['login'] = "invalid";
	}
} // end of check customer login function

//----------------------------------------------------------------------------

//function to change title of store page depending on what filters are active.
function echoStoreTitle() {
	
	//connect to database if not already connected
	if (!(isset($pdo))) { 
		include "includes/connect.php"; 
	}

	if (isset($_SESSION['catFilter'])) {
		try {
	
			//create sql statement
			$sql = "SELECT catName FROM tbl_category WHERE";

			//initialise counter 
	        $i = 1;
	    	
	    	//count number of filters in array
        	$numFilters = count($_SESSION['catFilter']);

	        foreach ($_SESSION['catFilter'] as $value) {

	            $sql= $sql." catNbr = ".$value;

	            //if not final item in array append OR
	            if(!($i == $numFilters)) {
	                $sql = $sql." OR";
	            }

	            //iterate i
	            $i++;
	        }
	        
	        $resultSet = $pdo->query($sql);
		} // end of try block

		catch (PDOException $e) {
			//create an error message
			echo "Error fetching category names: ".$e->getMessage();

			//stop script continuing
			exit();

		} // end of catch block

		//initialise counter 
	    $i = 1;

		foreach ($resultSet as $row) {
			echo $row['catName'];

			//if not last item print comma. No comma needed if just 2 items as and is used instead.
			if($i != $numFilters && $numFilters != 2) {
				echo ", ";
			}
			//

			//if there are multiple items, right before final item add "and"
			if($i == ($numFilters - 1)) {
				echo " and ";
			}

		$i++;
		}

	}//end if statement
	else {

	echo 'Store';

	}
}

//----------------------------------------------------------------------------

function createNewPassword() {

	//create empty variable to store result
	$newPassword = '';
	//randomly choose a password length
	$passwordLength = mt_rand(7, 12);

	//create password one character at a time, up to $passwordLength
	for($i = 0; $i < $passwordLength; $i++) {

		//randomly choose between uppercase, lowercase or int for each character
		$rand = mt_rand(0, 2);

		//ASCII: uppercase - 65/90 lowercase - 97/122 
		//generate uppercase character
		if ($rand == 0) {
			$char = chr(mt_rand(65, 90));
		}
		//generate lowercase letter
		else if ($rand == 1) {
			$char = chr(mt_rand(97, 122));
		}
		//generate int
		else {
			$char = mt_rand(0, 9);
		}

		//add character to string
		$newPassword = $newPassword.$char;

	} // end for loop

	return $newPassword;

}

//----------------------------------------------------------------------------

function getDeliveryCharge($deliveryStateId) {

	//connect to database if not already connected
	if (!(isset($pdo))) { 
		include "includes/connect.php"; 
	}

	try {
	
		//create sql statement
		$sql = "SELECT deliveryCharge FROM tbl_state WHERE stateId = :deliveryStateId";

		$statement = $pdo->prepare($sql);

        //bind values
        $statement->bindValue(':deliveryStateId', $deliveryStateId);

        //run statement
        $statement->execute();

		
	} // end of try block

	catch (PDOException $e) {
		//create an error message
		echo "Error fetching delivery charges: ".$e->getMessage();

		//stop script continuing
		exit();

	} // end of catch block

	$deliveryCharge = $statement->fetchColumn();

	return $deliveryCharge;

}

//----------------------------------------------------------------------------

?>