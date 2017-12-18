<?php session_start();
/*
Filename: checkout1.php
Author: Matthew Raw
Date Created: 25/5/17
Last Updated: 11/6/17
Description: first page in checkout process. Gets delivery details from client and displays shipping charges.
*/

//set the page title
$pageTitle = "Checkout - Confirmation";

include "includes/head.php";
include "includes/connect.php";

//test if file has been accessed correctly- (from checkout 2) & user is logged in
if(isset($_POST['checkout2Submit']) && $_SESSION['login'] == 'valid') {

//insert data into order table
try {
        //create SQL statement 
            $sql= "INSERT INTO tbl_order SET custNbr = :custNbr, orderDate = :orderDate, orderNetValue = :orderNetValue, deliveryCharge = :deliveryCharge, deliveryTo = :deliveryTo, deliveryAddress = :deliveryAddress, deliverySuburb = :deliverySuburb, deliveryStateId = :deliveryStateId, deliveryPostCode = :deliveryPostCode, deliveryInstructions = :deliveryInstructions;";

            //prepare statement
            $statement = $pdo->prepare($sql);

            //bind the values to the sql placeholders
            $statement->bindValue(':custNbr', $_SESSION['custNbr']);
            $statement->bindValue(':orderDate', date('Y-m-d'));
            $statement->bindValue(':orderNetValue', $_SESSION['orderNetValue']);
            $statement->bindValue(':deliveryCharge', $_SESSION['deliveryCharge']);
            $statement->bindValue(':deliveryTo', $_SESSION['deliveryTo']);
            $statement->bindValue(':deliveryAddress', $_SESSION['deliveryAddress']);
            $statement->bindValue(':deliverySuburb', $_SESSION['deliverySuburb']);
            $statement->bindValue(':deliveryStateId', $_SESSION['deliveryStateId']);
            $statement->bindValue(':deliveryPostCode', $_SESSION['deliveryPostCode']);
            $statement->bindValue(':deliveryInstructions', $_SESSION['deliveryInstructions']);

            //execute the sql statement
            $statement->execute();

            //fetch order number
            $orderNbr = $pdo->lastInsertId();

    } //end of try block

    catch (PDOException $e) {
        //create an error message
        echo "Error submitting order: ".$e->getMessage();

        //stop script continuing
        exit();

    } // end of catch block


    try {
            

            //insert each product into tbl_ordered, update quantity of tbl_product
            foreach ($_SESSION['cart'] as $prodId => $value) {
                //insert product into tbl_ordered
                $qtyOrdered = $_SESSION['cart'][$prodId]['qtyOrdered'];
                $listPrice = $_SESSION['cart'][$prodId]['listPrice'];

                //if price is discounted, add it, otherwise just use listPrice
                if (isset($_SESSION['cart'][$prodId]['discountedPrice'])) {
                    $discountedPrice = $_SESSION['cart'][$prodId]['discountedPrice'];
                }
                else {
                    $discountedPrice = $_SESSION['cart'][$prodId]['listPrice'];
                }


                $sql= "INSERT INTO tbl_orderedProduct SET orderNbr = :orderNbr, prodId = :prodId, qtyOrdered = :qtyOrdered, listPrice = :listPrice, discountedPrice = :discountedPrice;";

                //prepare statement
                $statement = $pdo->prepare($sql);

                //bind the values to the sql placeholders
                $statement->bindValue(':orderNbr', $orderNbr);
                $statement->bindValue(':prodId', $prodId);
                $statement->bindValue(':qtyOrdered', $qtyOrdered);
                $statement->bindValue(':listPrice', $listPrice);
                $statement->bindValue(':discountedPrice', $discountedPrice);

                $statement->execute();

                //subtract qty ordered from available stock
                $sql = "UPDATE tbl_product SET qtyOnHand = qtyOnHand - :qtyOrdered WHERE prodId = :prodId;";

                $statement = $pdo->prepare($sql);

                $statement->bindValue(':prodId', $prodId);
                $statement->bindValue(':qtyOrdered', $qtyOrdered);

                $statement->execute();

                //if purchased item in wishlist, remove it

                if (in_array($prodId, $_SESSION['wishlist'])) {
                    //create SQL statement 
                    $sql= "DELETE FROM tbl_wishlist WHERE custNbr = :custNbr AND prodId = :prodId;";

                    //prepare statement
                    $statement = $pdo->prepare($sql);

                    //bind the values to the sql placeholders
                    $statement->bindValue(':custNbr', $_SESSION['custNbr']);
                    $statement->bindValue(':prodId', $prodId);

                    //execute the sql statement
                    $statement->execute();

                    //remove item from session wishlist array
                    $key = array_search($prodId, $_SESSION['wishlist']);
                    unset($_SESSION['wishlist'][$key]);
                }


            } // end foreach loop

    } //end of try block


    catch (PDOException $e) {
        //create an error message
        echo "Error submitting order items: ".$e->getMessage();

        //stop script continuing
        exit();

    } // end of catch block

    //unset relevant session variables
    unset($_SESSION['cart']);
    unset($_SESSION['orderNetValue']);
    unset($_SESSION['totalOrderedItems']);

    $_SESSION['deliveryTo'] = $_SESSION['firstName'].' '.$_SESSION['lastName'];
    $_SESSION['deliveryAddress'] = $_SESSION['address'];
    $_SESSION['deliverySuburb'] = $_SESSION['suburb'];
    $_SESSION['deliveryStateId'] = $_SESSION['stateId'];
    $_SESSION['deliveryPostCode'] = $_SESSION['postCode'];
    $_SESSION['deliveryInstructions'] = "";


    //re-fetch order details for rendering of reciept
    try {
        //create SQL statement
        $sql= "SELECT orderNbr, custNbr, DATE_FORMAT(orderDate,'%d %b, %Y') AS 'orderDate', orderNetValue, deliveryCharge, deliveryTo, deliveryAddress, deliverySuburb, deliveryStateId, deliveryPostCode, deliveryInstructions FROM tbl_order WHERE orderNbr = :orderNbr;";

        //prepare statement
        $statement = $pdo->prepare($sql);

        //bind values
        $statement->bindValue(':orderNbr', $orderNbr);

        //execute
        $statement->execute();

        //fetch results
        $resultSet = $statement->fetchAll();


        } //end of try block

        //if 
        catch (PDOException $e) {
            //create an error message
            echo "Error fetching previous orders: ".$e->getMessage();

            //stop script continuing
            exit();

        } // end of catch block

        //capture results as variables
        foreach ($resultSet as $row) {
            $orderNbr = $row['orderNbr'];
            $custNbr = $_SESSION['custNbr'];
            $orderDate = $row['orderDate'];
            $orderNetValue = $row['orderNetValue'];
            $deliveryCharge = $row['deliveryCharge'];
            $deliveryTo = $row['deliveryTo'];
            $deliveryAddress = $row['deliveryAddress'];
            $deliverySuburb = $row['deliverySuburb'];
            $deliveryStateId = $row['deliveryStateId'];
            $deliveryPostCode = $row['deliveryPostCode'];
            $deliveryInstructions = $row['deliveryInstructions'];
        }

    try {
        //create SQL statement
        $sql= "SELECT prodName, thumbNail, tbl_orderedProduct.listPrice, qtyOrdered FROM tbl_orderedProduct, tbl_product WHERE orderNbr = :orderNbr AND tbl_orderedProduct.prodId = tbl_product.prodId;";

        //prepare statement
        $statement = $pdo->prepare($sql);

        //bind values
        $statement->bindValue(':orderNbr', $orderNbr);

        //execute
        $statement->execute();

        //fetch results
        $itemSet = $statement->fetchAll();


    } //end of try block

        
    catch (PDOException $e) {
            //create an error message
            echo "Error fetching previous orders: ".$e->getMessage();

            //stop script continuing
            exit();

    } // end of catch block
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
        <main class="main paddingBottom">

            <div class="row cartHeader">
                <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
                        <h4>Order Confirmation</h4>
                </div>
                <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
                        <div class="checkoutNav">
                        <h5 class="inline">Your Details</h5>
                        <h5 class="inline">></h5>
                        <h5 class="inline">Payment Details</h5>
                        <h5 class="inline">></h5>
                        <h5 class="inline checkoutActive">Confirmation</h5>
                    </div>
                </div>
            </div>
            <div class="innerPageContainer">
            <h5>Congratulations, your order has been submitted.</h5>

            <div class="row">
                <!--left half order details-->
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <table class="prevOrdersDetailsTbl">
                        <tr>
                            <td><h5 class="prevOrderH5">Order Number:</h5></td>
                            <td><?php echo $orderNbr; ?></td>
                        </tr>
                        <tr>
                            <td><h5 class="prevOrderH5">Order Date:</h5></td>
                            <td><?php echo $orderDate; ?></td>
                        </tr>
                        <tr>
                            <td><h5 class="prevOrderH5">Delivering To:</h5></td>
                            <td><?php echo $deliveryTo.'<br>'.$deliveryAddress.'<br>'.$deliverySuburb.'<br>'.$deliveryStateId.', '.$deliveryPostCode; ?></td>
                        </tr>
                        <tr>
                            <td><h5 class="prevOrderH5 prevOrderTxt">Delivery Instructions:</h5></td>
                            <td><?php echo $deliveryInstructions; ?></td>
                        </tr>
                    </table>
                </div><!-- end left half -->

                <!-- right half order details-->
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <table class="prevOrdersItemsTbl">
                        
                        <?php // loop through items in order and print
                        foreach ($itemSet as $row) {
                            $thumbNail = $row['thumbNail'];
                            $prodName = $row['prodName'];
                            $listPrice = $row['listPrice'];
                            $qtyOrdered = $row['qtyOrdered'];

                        echo "<tr>";
                            echo "<td><img class='orderSummaryThumb' src='$thumbNail' alt='$prodName'></td>";
                            echo "<td><h5 class='orderSummaryText prevOrderTxt'>".$qtyOrdered."x $prodName</h5></td>";
                            echo "<td><h5 class='orderSummaryPrice'>$".number_format($listPrice, 2)."ea</h5></td>";
                        echo "</tr>";
                    } // end for each 
                    ?>
                    <tr class="spacerRow">
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="tdBorderTop"><h5 class="orderSummaryText">Sub-Total:</h5></td>
                        <td class="tdBorderTop">$<?php echo $orderNetValue; ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><h5 class="orderSummaryText">Delivery Fee:</h5></td>
                        <td>$<?php echo $deliveryCharge; ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="tdBorderTop"><h4 class="orderSummaryText">Total:</h4></td>
                        <td class="tdBorderTop"><h4>$<?php echo number_format($orderNetValue + $deliveryCharge, 2); ?></h4></td>
                    </tr>

                    </table>
                </div><!-- end right half -->
            </div> <!-- end row -->
                <div class="continueShopping">
                    <a href="store.php" class="back finalCartButton">Continue Shopping</a>
                    <a class="back printButton" onclick="window.print();">Print</a>
                </div>
            </div>



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