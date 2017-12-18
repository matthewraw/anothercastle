<?php
/*
Filename: nav.php
Author: Matthew Raw
Date Created: 22/3/17
Last Updated: 22/3/17
Description: navbar section for all pages in dynamic website video game store assignment
*/
?>        

        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span> 
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                  <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="store.php">Store</a></li>
                    <li><a onclick="simulationWarning();" style="cursor: pointer;">About Us</a></li> 
                    <li><a onclick="simulationWarning();" style="cursor: pointer;">Contact Us</a></li> 
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                    <!-- start cart-->
                    <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> 
                        <?php
                            //if no cart has been created, echo empty
                            if (!isset($_SESSION['cart'])){
                                echo "Your cart is currently empty";
                            }
                            //else tell them how many items and total cost
                            else {
                                echo "Cart: ".$_SESSION['totalOrderedItems']." items - $".number_format($_SESSION['orderNetValue'], 2);
                            }
                        ?>
                    </a></li> <!-- end of cart icon -->
                  </ul>
                </div>
            </div>
        </nav>