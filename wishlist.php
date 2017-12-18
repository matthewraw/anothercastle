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
    $pageTitle = "Wishlist";

    include "includes/head.php";

    //connect to database
    include "includes/connect.php";

    //count number of items user has added to their wishlist
    try {
        //create SQL statement
        $sql= "SELECT COUNT(*) FROM tbl_product, tbl_wishlist WHERE custNbr = :custNbr AND tbl_product.prodId = tbl_wishlist.prodId;";
        

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
        echo "Error fetching wishlisted products: ".$e->getMessage();

        //stop script continuing
        exit();

    } // end of catch block

    //if user has items in a wishlist, fetch them
    if ($nbrOfRows > 0) {
        try {
        //create SQL statement
        $sql= "SELECT tbl_product.prodId, prodName, qtyOnHand, listPrice, thumbNail, image, description, DATE_FORMAT(dateAdded,'%d %b, %Y') AS 'dateAdded' FROM tbl_product, tbl_wishlist WHERE custNbr = :custNbr AND tbl_product.prodId = tbl_wishlist.prodId ORDER BY itemNbr ASC;";
        

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
            echo "Error fetching wishlisted products: ".$e->getMessage();

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
                                    <h4 class="singleTitle">Wishlist</h4>
                                </div>
                            </div>
                        </div> <!-- title row -->                 

                <!--display wishlist-->

                        <?php
                        
                            // if wishlist is empty, display message that wishlist is empty
                            if($nbrOfRows == 0) {
                                
                                echo "<div class='row searchNoResults' style='text-align: center;'><br>";
                                echo "<h3>Your wishlist is currently empty</h3>";
                                echo "<a href='store.php'>Click here to continue shopping</a><br><br>";
                                echo "</div>";
                            } // end if statement
                            else //display wishlist 
                            {
                                ?>

                                    <?php
                                        //loop through wishlist to display items
                                        foreach ($resultSet as $row) {
                                            $custNbr = $_SESSION['custNbr'];
                                            $prodId = $row['prodId'];
                                            $prodName = $row['prodName'];
                                            $qtyOnHand = $row['qtyOnHand'];
                                            $listPrice = $row['listPrice'];
                                            $thumbNail = $row['thumbNail'];
                                            $image = $row['image'];
                                            $description = $row['description'];
                                            $dateAdded = $row['dateAdded'];

                                            
                                            ?>
                                            <div class="row cartRow"><!-- wishlist item bootstrap row-->
                
                                                <!-- Left half of wishlist contains picture, title, price -->
                                                <div class="col-xs-12 col-sm-8 col-md-7 col-lg-7 cartLeft">
                                                    
                                                    <!--Inner Left half - image column -->
                                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                        <img class="img-responsive cartImg" src="<?php echo $thumbNail; ?>" alt="<?php echo $prodName; ?>">
                                                    </div>
                                                    
                                                    <!-- Inner right half - Title, Price, remove from wishlist-->
                                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                        <h4><?php echo $prodName; ?></h4>
                                                        <h5>$<?php echo $listPrice; ?> ea</h5>
                                                        <h5>Added on <?php echo $dateAdded; ?></h5>
                                                        <!-- delete item from wishlist -->
                                                        <form action="updatewishlist.php" method='POST'>
                                                            <input type="hidden" name="wishlistSubmitHidden" value="submit">
                                                            <input type="hidden" name="prodId" value="<?php echo $prodId; ?>">
                                                            <button class="wishlistRemoveBtn" type='submit' name='wishlistSubmit'>X Remove</button>
                                                        </form><!-- End delete item from wishlist -->
                                                    </div>

                                                    

                                                </div>

                                                <!-- Right half of wishlist -->
                                                <div class="col-xs-12 col-sm-4 col-md-5 col-lg-5 cartRight wishlistRight"> 
                                                        <!-- add to cart button -->
                                                        <?php
                                                         //test to see if item is in stock
                                                        if ($qtyOnHand <= 0) {
                                                            //if out of stock, display message
                                                            echo "<div class='outofstock wishlistOutOfStock'><p>Out of Stock</p></div>";
                                                        } 
                                                        //test for no more stock
                                                        else if (isset($_SESSION['cart'][$prodId]) && $qtyOnHand <= $_SESSION['cart'][$prodId]['qtyOrdered']) {
                                                            echo "<div class='outofstock'><p>All stock selected</p></div>";
                                                        }
                                                        else {
                                                                ?> <!-- exit php to create button-->
                                                                     <form action="addToCart.php" method="post">
                                                                        <input type="hidden" name="prodId" value="<?php echo $prodId; ?>">
                                                                        <input type="hidden" name="prodName" value="<?php echo $prodName; ?>">
                                                                        <input type="hidden" name="qtyOnHand" value="<?php echo $qtyOnHand; ?>">
                                                                        <input type="hidden" name="listPrice" value="<?php echo $listPrice; ?>">
                                                                        <input type="hidden" name="thumbNail" value="<?php echo $thumbNail; ?>">
                                                                        <!-- submit button -->
                                                                        <button name="submit" type="submit" class="addtocart wishlistAddToCart">
                                                                            <img class="cartimg" src="images/cart.svg" alt="cart" width="33" height="23">
                                                                            <span class="carttxt">Add to Cart</span>
                                                                        </button>
                                                                    </form>
                                                                <?php // re-enter php
                                                            } // end else statement
                                                            ?>

                                                </div>

                                            </div><!-- end of wishlist item bootsrap row-->


                                            <?php
                                            
                                        } // end for each loop

                            }//end else display wishlist statement
                        ?> <!--end display wishlist-->

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