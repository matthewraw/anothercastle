<?php session_start();
/*
Filename: checkout3.php
Author: Matthew Raw
Date Created: 30/5/17
Last Updated: 11/6/17
Description: Checkout 3- confirmation. Submits order to database and echoes out tax invoice
*/

//set the page title
$pageTitle = "Checkout - Payment";

include "includes/head.php";

//test if file has been accessed correctly- (from checkout1 || checkout 3)
if((isset($_POST['checkout1Submit']) || $_SERVER['HTTP_REFERER'] == 'https://mercury.swin.edu.au/ictprg425/s100925048/assignment/checkout2.php
')) {

//capture delivery details from checkout 1
if(isset($_POST['checkout1Submit'])) {
    $_SESSION['deliveryTo'] = cleanInput($_POST['deliveryTo']);
    $_SESSION['deliveryAddress'] = cleanInput($_POST['deliveryAddress']);
    $_SESSION['deliverySuburb'] = cleanInput($_POST['deliverySuburb']);
    $_SESSION['deliveryStateId'] = cleanInput($_POST['deliveryStateId']);
    $_SESSION['deliveryPostCode'] = cleanInput($_POST['deliveryPostCode']);
    $_SESSION['deliveryInstructions'] = cleanInput($_POST['deliveryInstructions']);

    //get the cost of delivery
    $_SESSION['deliveryCharge'] = getDeliveryCharge($_SESSION['deliveryStateId']);
}
?>

<body>
    <!-- background image container -->
    <div class="headerbackground">
        <img src="images/background.svg" alt="Another Castle Background">
    </div>
    
    <!-- bootstrap container -->
    <div class="container"> 
        
        <!-- header -->
        <header class="header">
                <div class="logoContainer">
                    <div class="logo">
                        <img src="images/logo.svg" alt="Another Castle Logo">
                    </div>
                </div>
        </header> 
        <!-- end of header -->
        
        <!-- search and login -->
        <div class="gradient">
          <?php include 'includes/login.php'; ?>
        </div>
        
        <!-- navigation -->
        <?php include 'includes/nav.php'; ?>
        <!-- end of navigation -->
        
        <!-- main content -->
        <main class="main">

            <div class="row cartHeader">
                <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
                        <h4>Payment Details</h4>
                </div>
                <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
                    <div class="checkoutNav">
                        <h5 class="inline"><a class="checkoutBack" href='checkout1.php'>Your Details</a></h5>
                        <h5 class="inline">></h5>
                        <h5 class="inline checkoutActive">Payment Details</h5>
                        <h5 class="inline">></h5>
                        <h5 class="inline checkoutNext">Confirmation</h5>
                    </div>
                </div>
            </div>
            
        <div class="row"><!-- main bootstrap row-->

            <!--main content container-->
            <div class="maincontainer col-xs-12 col-sm-8 col-md-8 col-lg-8">
                <div class="maincontent">
                    <div class="formcard">
                    <h4>Payment Details:</h4>
                    
                        
                    <!--customer update up form-->
                    <form action="checkout3.php" method="POST" onsubmit="return checkCheckout2(this);">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label for="cardName">Name on Card:</label><br>
                                <input type="text" id="cardName" name="cardName" maxlength="20"><br>
                                <label for="cardNumber">Card Number:</label><br>
                                <input type="text" name="cardNumber" id="cardNumber" maxlength="45"><br>
                                <label for="cvv">CVV:</label><br>
                                <input type="text" id="cvv" name="cvv" maxlength="5"><br>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            </div>
                        </div><!-- end form bootstrap row -->
                        <div class="row formcardButtons">
                            <div class="hidden-xs hidden-sm col-md-5 col-lg-5">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                <a class="back" href="checkout1.php">Back to Delivery Details</a>
                                <input class="checkoutSubmit" type="submit" name="checkout2Submit" value="Submit Order" onClick="return confirmCheckout();">
                            </div>
                        </div>
                    </form>
                    
                    

                </div> <!-- end form card div-->
                </div> <!-- end main content -->
            </div> <!-- end bootstrap column -->

            <!-- main sidebar container-->
            <div class="mainsidebar_container xs-hide col-sm-4 col-md-4 col-lg-4">
                <div class="orderSummary">
                    <h4>Delivery Details:</h4>
                        <p><?php echo $_SESSION['deliveryTo']; ?>
                        <br><?php echo $_SESSION['deliveryAddress']; ?>
                        <br><?php echo $_SESSION['deliverySuburb']; ?>
                        <br><?php echo $_SESSION['deliveryStateId']; ?>
                        <?php echo $_SESSION['deliveryPostCode']; ?>
                        </p>
                    
                    <h4>Instructions:</h4>
                        <p><?php echo $_SESSION['deliveryInstructions']; ?></p>
                        <br>
                        




                        <h4>Order Summary:</h4>
                    <table class="orderSummaryTable">
                        <?php 
                            foreach ($_SESSION['cart'] as $prodId => $value) {
                                $thumbNail = $_SESSION['cart'][$prodId]['thumbNail'];
                                $prodName = $_SESSION['cart'][$prodId]['prodName'];
                                $qtyOrdered = $_SESSION['cart'][$prodId]['qtyOrdered'];
                                $total = $_SESSION['cart'][$prodId]['listPrice'] * $qtyOrdered;
                                    echo "<tr>";
                                        echo "<td class='orderSummaryThumbTd'>";
                                            echo "<img class='orderSummaryThumb' src='$thumbNail' alt='$prodName'>";
                                        echo "</td>";
                                        echo "<td>";
                                            echo "<h5 class='orderSummaryText'>".$qtyOrdered."x $prodName</h5>";
                                        echo "</td>";
                                        echo "<td>";
                                            echo "<h5 class='orderSummaryPrice'>$".number_format($total, 2)."</h5>";
                                        echo "</td>";
                                    echo "</tr>";
                            }//end foreach 
                        ?>
                        <tr>
                            <td>
                            </td>
                            <td>
                                <h5 class='orderSummaryText'>Delivery to <?php echo $_SESSION['deliveryStateId']; ?></h5> 
                            </td>
                            <td>
                                <h5 class="orderSummaryPrice"> <?php echo '$'.number_format(getDeliveryCharge($_SESSION['deliveryStateId']), 2); ?> </h5>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            </td>
                            <td>
                                <h5 class='orderSummaryText'>Total</h5> 
                            </td>
                            <td>
                                <h5 class="orderSummaryPrice"><?php echo '$'.number_format($_SESSION['orderNetValue'] + getDeliveryCharge($_SESSION['deliveryStateId']), 2); ?></h5>
                            </td>
                        </tr>
                    </table>
                </div>
            </div> <!-- end bootstrap column -->

        </div><!-- end of main bootsrap row-->

        </main><!-- end of main -->
        
        <!-- footer -->
        <?php include 'includes/footer.php'; ?>
        <!-- end of footer -->
        
        
    </div> <!-- end of bootstrap container -->
    
</body>
    
</html>
<?php
    } // end test to see if file accessed correctly
    else {
        header("Location: cart.php");
        exit;
    } // end else statement 
?>