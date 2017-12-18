<?php session_start();
/*
Filename: wishlist.php
Author: Matthew Raw
Date Created: 10/5/17
Last Updated: 10/5/17
Description: Customer wishlist page

*/

//before taking action, test user is logged in
if(isset($_SESSION['login']) || $_SESSION['login'] == 'valid') {

    //set the page title
    $pageTitle = "Orders";

    include "includes/head.php";

    //connect to database
    include "includes/connect.php";

    //count number of items user has added to their wishlist
    try {
        //create SQL statement
        $sql= "SELECT COUNT(*) FROM tbl_order WHERE custNbr = :custNbr;";
        

        //prepare statement
        $countStatement = $pdo->prepare($sql);

        //bind values
        $countStatement->bindValue(':custNbr', $_SESSION['custNbr']);

        //execute
        $countStatement->execute();

        //count number of results
        $nbrOfRows = $countStatement->fetchColumn();

    } //end of try block


    catch (PDOException $e) {
        //create an error message
        echo "Error counting previous orders: ".$e->getMessage();

        //stop script continuing
        exit();

    } // end of catch block

//if user has previous orders, fetch them
    if ($nbrOfRows > 0) {
        try {
        //create SQL statement
        $sql= "SELECT orderNbr, custNbr, DATE_FORMAT(orderDate,'%d %b, %Y') AS 'orderDate', orderNetValue, deliveryCharge, deliveryTo, deliveryAddress, deliverySuburb, deliveryStateId, deliveryPostCode, deliveryInstructions FROM tbl_order WHERE custNbr = :custNbr ORDER BY orderNbr DESC;";

        //prepare statement
        $statement = $pdo->prepare($sql);

        //bind values
        $statement->bindValue(':custNbr', $_SESSION['custNbr']);

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
    } // end if statement



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
            <!-- title row for desktop sizes -->
                <div class="row"> <!-- title row -->
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 titleContainer">
                        <div class="titleGradient">
                            <h4 class="singleTitle">My Orders</h4>
                        </div>
                    </div>
                </div> <!-- title row -->                 

                        <!--display previous orders-->

                        <?php
                        
                            // if no previous orders, display message
                            if($nbrOfRows == 0) {
                                
                                echo "<div class='row searchNoResults' style='text-align: center;'><br>";
                                echo "<h3>You haven't submitted any orders yet</h3>";
                                echo "<a href='store.php'>Click here to get shopping!</a><br><br>";
                                echo "</div>";
                            } // end if statement
                            else //display prev orders 
                            {
                                ?>
                                <div class="prevOrdersContainer"><!-- formcard div-->

                                        <?php
                                            //loop through wishlist to display orders
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

                                                
                                                ?>
                                                <!-- accordion -->    
                                                <div class="accordion">
                                                    <div class="accordionHead">
                                                        <img class="arrowUp" src="images/arrow.png" alt="arrow">
                                                        <h4 class="accordionTitle">Order #<?php echo $orderNbr." - ".$orderDate; ?></h4>
                                                    </div>
                                                    <div class="contentContainer">
                                                        <div class="accordionContent">
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

                                                                <?php 
                                                                //fetch details for items in order
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

                                                                //if 
                                                                catch (PDOException $e) {
                                                                    //create an error message
                                                                    echo "Error fetching previous orders: ".$e->getMessage();

                                                                    //stop script continuing
                                                                    exit();

                                                                } // end of catch block

                                                                ?>

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
                                                        </div> <!-- end content -->
                                                    </div> <!-- end contentContainer -->
                                                </div>
                                    <!-- end accordion -->
                                                    
                                                   

                                                


                                                
                                            
                                        <?php } // end for each loop ?>

                                   

                            <?php }//end else display prev orders statement ?>
                         

                        </div><!-- end prevOrders container div-->
            </main><!-- end of main -->
            
            <!-- footer -->
            <?php include 'includes/footer.php'; ?>
            <!-- end of footer -->
            
            
        </div> <!-- end of bootstrap container -->
        
    </body>
        
    </html>
<?php

//end test if user is logged in from beginning of page
}
else {

    header("Location: index.php");
    exit;

} // end else statement 