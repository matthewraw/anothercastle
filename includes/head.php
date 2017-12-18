<?php if (session_id() == '') { session_start(); }
/*
Filename: head.php
Author: Matthew Raw
Date Created: 22/3/17
Last Updated: 27/4/17
Description: head section for all pages in dynamic website video game store assignment
*/

    //set the page title
    if (!isset($pageTitle)) {
        $pageTitle = "<<page title not set>>";
    }

    //include functions
    include "includes/myFunctions.php";

    //if login details have been submitted
    if (isset($_POST['loginsubmit'])) {

        //capture and clean the data
        $loginEmail = cleanInput($_POST['loginEmail']);
        $loginPassword = sha1(cleanInput($_POST['loginPassword']));

        checkCustomerLogin($loginEmail, $loginPassword);
    }

?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Another Castle, an online video game store, Australia">
    <meta name="keywords" content="Video Games, Store, Another Castle">
    <meta name="author" content="Matthew Raw">
    
    <title>Another Castle - <?php echo $pageTitle; ?></title>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
    
    <!-- bootstrap CDN-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    
    <!--jQuery-->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    
    <!-- bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
    <!-- Import font from fonts.google.com -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:500,700" rel="stylesheet">
    
    <!--site CSS -->
	<link rel="stylesheet" type="text/css" href="css/customstyles.css">

    <?php
        if ($pageTitle == "Checkout - Confirmation") {
        //if page is the final checkout confirmation, include print css file
        ?>
        <link rel="stylesheet" type="text/css" href="css/printconfirmation.css">
        <?php
        }
    ?>
    
    <!-- site js file -->
    <script type="text/javascript" src="js/scripts.js"></script>
</head>