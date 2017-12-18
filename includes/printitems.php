<?php 
/*
Filename: printitems.php
Author: Matthew Raw
Date Created: 5/4/17
Last Updated: 5/4/17
Description: Includes file for store item printing. Uses bootstrap to print off columns of items. Requires SQL query stored in $resultSet above in pages where used.
*/

                    
    //initialise counter for printing items
    $i = 1;

    //loop through items in result set and create cards. Every 1st item, create new row. Every 3rd item or if final item, close row.
    foreach ($resultSet as $row) {
        // store row data in variable
        $prodId = $row['prodId'];
        $prodName = $row['prodName'];
        $qtyOnHand = $row['qtyOnHand'];
        $listPrice = $row['listPrice'];
        $thumbNail = $row['thumbNail'];
        $image = $row['image'];
        $description = $row['description'];

        //if first item, open new bootstrap row
        if ($i % 3 == 1) {
            echo '<div class="row itemcardrow">';
        }

        // -------------------- item cards ---------------------//
        //echo out HTML items adding in variables
        echo '<div class="col-sm-4 col-md-4 col-lg-4 cardcontainer">
                <div class="itemcard">
                    <a class="itemcard_a" onclick=\'displayImage("'.htmlspecialchars($image).'", "'.htmlspecialchars($description).'");\'>
                        <p class="prodName child">'.htmlspecialchars($prodName).'</p>
                        <p class="price child">$'.$listPrice.'</p>
                        <img class="child" src="'.$thumbNail.'" alt="'.htmlspecialchars($prodName).'"> 
                    </a>';
                    
                    //---------------Add to Cart Button-------------------//

                    //test to see if item is in stock
                    if ($qtyOnHand <= 0) {
                        //if out of stock, display message
                        echo "<div class='outofstock'><p>Out of Stock</p></div>";
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
                                    <button name="submit" type="submit" class="addtocart">
                                        <img class="cartimg" src="images/cart.svg" alt="cart" width="33" height="23">
                                        <span class="carttxt">Add to Cart</span>
                                    </button>
                                </form>
                            <?php // re-enter php
                        } // end else statement

                // ------------ End Add to Cart Button -------------------//

                // ------------ Add to Wishlist Button ------------------//
                if (isset($_SESSION['login']) && $_SESSION['login'] == 'valid') {
                    ?>
                    <form class="wishlistForm" action="updatewishlist.php" method="post">
                        <input type="hidden" name="wishlistSubmitHidden" value="submit">
                        <input type="hidden" name="prodId" value="<?php echo $prodId; ?>">
                        <!-- submit button -->
                        <button name="wishlistSubmit" type="submit" class="addToWishlist">
                            <?php
                                //test to see if product is already in wishlist. If so, echo out different button
                                if(in_array($prodId, $_SESSION['wishlist'])) {
                                    echo '<img class="wishlistimg" src="images/wishlistTrue.svg" alt="cart" width="33" height="23">';
                                }
                                else {
                                    echo '<img class="wishlistimg" src="images/wishlistFalse.svg" alt="cart" width="33" height="23">';
                                }
                            ?>
                        </button>
                    </form>


                    <?php
                }

                // ------------ End Add to Wishlist ------------------//
                echo '</div>
            </div>';

        // -------------------- end item cards ---------------------//

        //if 3rd item or last item, close bootstrap row
        if ($i % 3 == 0 || $i == $productCount) {
            echo '</div> <!-- end row -->';
        }

        $i++;

    }   // --------------- end for each loop ----------------------//

?>