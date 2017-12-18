<?php session_start();
/*
Filename: cart.php
Author: Matthew Raw
Date Created: 10/5/17
Last Updated: 10/5/17
Description: cart page, utilises printCart.php

Requires: Delete shopping subnav and add different sidebar if necessary 
*/

//set the page title
$pageTitle = "Cart";

include "includes/head.php";

//if checkout has been attempted but not logged in, open modal window to prompt login
if (isset($_POST['hiddenCheckout']) && $_POST['hiddenCheckout'] == 'invalid') {
    ?>
        <script type="text/javascript">
                $(window).on('load',function(){
                    $('#loginModal').modal('show');
                });
        </script>
    <?php
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

                    <!--display cart-->

                    <?php
                        //if cart is empty, display message that cart is empty
                        if(!isset($_SESSION['cart'])) {
                            
                            echo "<div class='row searchNoResults' style='text-align: center;'><br>";
                            echo "<h3>Your cart is currently empty</h3>";
                            echo "<a href='store.php'>Click here to continue shopping</a><br><br>";
                            echo "</div>";
                        } // end if statement
                        else //display cart 
                        {
                            ?>
                            
                                <!-- title row for desktop sizes -->
                            <div class="row cartHeader">
                                <div class="hidden-xs col-sm-6 col-md-6 col-lg-6">
                                    <div class="hidden-xs col-sm-6 col-md-6 col-lg-6">
                                        <h4>Product</h4>
                                    </div>
                                    <div class="hidden-xs col-sm-6 col-md-6 col-lg-6">
                                    </div>
                                </div>
                                <div class="hidden-xs col-sm-6 col-md-6 col-lg-6">
                                    <div class="hidden-xs col-sm-6 col-md-6 col-lg-6">
                                        <h4>Quantity</h4>
                                    </div>
                                    <div class="hidden-xs col-sm-6 col-md-6 col-lg-6">
                                        <h4>Total</h4>
                                    </div>
                                </div>
                            </div>

                                <?php
                                    //loop through cart to display items
                                    foreach ($_SESSION['cart'] as $prodId => $value) {
                                        $thumbNail = $_SESSION['cart'][$prodId]['thumbNail'];
                                        $prodName = $_SESSION['cart'][$prodId]['prodName'];
                                        $qtyOrdered = $_SESSION['cart'][$prodId]['qtyOrdered'];
                                        $qtyOnHand = $_SESSION['cart'][$prodId]['qtyOnHand'];
                                        $listPrice = $_SESSION['cart'][$prodId]['listPrice'];
                                        $total = $_SESSION['cart'][$prodId]['listPrice'] * $qtyOrdered;

                                        
                                        ?>
                                        <div class="row cartRow"><!-- cart item bootstrap row-->
            
                                            <!-- Left half of cart contains picture, title, price -->
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 cartLeft">
                                                
                                                <!--Inner Left half - image column -->
                                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                    <img class="img-responsive cartImg" src="<?php echo $thumbNail; ?>" alt="<?php echo $prodName; ?>">
                                                </div>
                                                
                                                <!-- Inner right half - Title, Price, remove from cart-->
                                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                    <h4><?php echo $prodName; ?></h4>
                                                    <h5>$<?php echo $listPrice; ?> ea</h5>
                                                    <!-- delete item from cart -->
                                                    <form action='updateCart.php' method='POST'>
                                                        <input type='hidden' name='deleteItem' value="<?php echo $prodId; ?>">
                                                        <button class="cartRemoveBtn" type='button' name='delete' onclick='this.form.submit()'>X Remove from cart</button>
                                                    </form><!-- End delete item from cart -->
                                                </div>

                                                

                                            </div>

                                            <!-- Right half of cart contains qty ordered,  -->
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 cartRight"> 

                                                <!-- Inner left half -->
                                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

                                                    <!-- update cart form -->
                                                    <h5 class="hidden-lg hidden-md cartMobQty">Qty:</h5>
                                                    <form class="updateQtyForm" action='updateCart.php' method='POST'>
                                                            <input type='hidden' name='prodId' value="<?php echo $prodId; ?>">
                                                            <input class="cartUpdateQty" type='number' name='qtyOrdered' value="<?php echo $qtyOrdered; ?>">
                                                            <button class="cartUpdateQtyBtn" type='submit' name='updateSubmit'>Update</button>
                                                    </form>

                                                    

                                                </div> <!-- close inner left half-->

                                                <!-- Inner right half -->
                                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

                                                    <!-- display item total -->
                                                    <h5 class="cartMobTotal">Sub-Total:</h5>
                                                    <h4>$<?php echo number_format($total, 2); ?></h4>

                                                </div> <!-- close inner right half -->

                                            </div>

                                        </div><!-- end of cart item bootsrap row-->


                                        <?php

                                            if ($qtyOrdered >= $qtyOnHand) {
                                                echo "<div class='row cartError'>";
                                                        echo "<h5>Maximum available stock selected</h5>";
                                                echo "</div>";
                                            }

                                        
                                    } // end for each loop
                                
                                //display grand total
                                echo "<div class='row'>";
                                    echo "<div class='hidden-xs col-sm-6 col-md-6 col-lg-6'></div>";
                                    echo "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 checkoutTotals'><h3 class='totalDue'>Total Due: $".number_format($_SESSION['orderNetValue'], 2)."</h3></div>";
                                echo "</div>"
                                ?>

                                <div class="row">
                                    <div class='hidden-xs col-sm-6 col-md-6 col-lg-6'></div>
                                    
                                    <!-- form action changes depending on login. If not logged in re-call this page. If logged in, go forward-->
                                    <form action="<?php if (!isset($_SESSION['login']) || $_SESSION['login'] == 'invalid') {echo htmlspecialchars($_SERVER['PHP_SELF']);} else {echo 'checkout1.php';} ?>" method="post">
                                        <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 checkoutButtons'>
                                            <a href="store.php" class="back">Continue Shopping</a>
                                                <!-- if not logged in, change hiddenCheckout value to invalid -->
                                                <input type='hidden' name='hiddenCheckout' value="<?php if (!isset($_SESSION['login']) || $_SESSION['login'] == 'invalid') {echo 'invalid';} else {echo 'hiddenCheckout';}?>"> 
                                                <button class="checkoutSubmit" type="submit" name="submitShowCart">Checkout</button> 
                                        </div>
                                    </form>
                                </div>

                            <?php  

                        }//end else statement
                    ?> <!--end display cart-->

        </main><!-- end of main -->
        
        <!-- footer -->
        <?php include 'includes/footer.php'; ?>
        <!-- end of footer -->
        
        
    </div> <!-- end of bootstrap container -->
    
</body>
    
</html>