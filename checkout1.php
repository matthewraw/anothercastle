<?php session_start();
/*
Filename: checkout1.php
Author: Matthew Raw
Date Created: 25/5/17
Last Updated: 25/5/17
Description: first page in checkout process. Gets delivery details from client and displays shipping charges.
*/

//set the page title
$pageTitle = "Checkout - Your Details";

include "includes/head.php";

//test if file has been accessed correctly- (from cart || checkout 2) & user is logged in
if((isset($_POST['hiddenCheckout']) || $_SERVER['HTTP_REFERER'] == 'https://mercury.swin.edu.au/ictprg425/s100925048/assignment/checkout2.php') && $_SESSION['login'] == 'valid') {

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
                        <h4>Delivery Address</h4>
                </div>
                <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
                    <div class="checkoutNav">
                        <h5 class="inline checkoutActive">Your Details</h5>
                        <h5 class="inline">></h5>
                        <h5 class="inline checkoutNext">Payment Details</h5>
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
                    <h4>Delivery Address:</h4>
                    
                        
                    <!--customer delivery address form-->
                    <form action="checkout2.php" method="POST" onsubmit="return checkCheckout1(this);">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label for="firstName">Deliver To:</label><br>
                                <input type="text" id="deliveryTo" name="deliveryTo" maxlength="20" value="<?php echo $_SESSION['deliveryTo']; ?>"><br>
                                <label for="address">Address:</label><br>
                                <input type="text" id="deliveryAddress" name="deliveryAddress" maxlength="45" value="<?php echo $_SESSION['deliveryAddress']; ?>"><br>
                                <label for="suburb">Suburb:</label><br>
                                <input type="text" id="deliverySuburb" name="deliverySuburb" maxlength="45" value="<?php echo $_SESSION['deliverySuburb']; ?>"><br>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label for="deliveryStateId">* State:</label><br>
                                    <select name="deliveryStateId" id="deliveryStateId">
                                        <option value="">Select State:</option>
                                        <?php
                                            getStateList();
                                        ?>
                                    </select><br>
                                <label for="deliveryPostCode">* Postcode:</label><br>
                                <input type="text" name="deliveryPostCode" id="deliveryPostCode" maxlength="10" value="<?php echo $_SESSION['deliveryPostCode']; ?>"><br>
                                <label for="deliveryInstructions">Instructions</label><br>
                                <textarea name="deliveryInstructions" id="deliveryInstructions"><?php echo $_SESSION['deliveryInstructions']; ?></textarea>
                            </div>
                        </div><!-- end form bootstrap row -->
                        <div class="row formcardButtons">
                            <div class="hidden-xs hidden-sm col-md-5 col-lg-5">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                <a class="back" href="cart.php">Back to Cart</a>
                                <input class="checkoutSubmit" type="submit" name="checkout1Submit" value="Continue to Payment">
                            </div>
                        </div>
                    </form>
                    
                </div> <!-- end form card div-->
                </div> <!-- end main content -->
            </div> <!-- end bootstrap column -->

            <!-- main sidebar container-->
            <div class="mainsidebar_container xs-hide col-sm-4 col-md-4 col-lg-4">
                <div class="orderSummary">
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