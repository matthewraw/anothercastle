<?php session_start();
/*
Filename: search.php
Author: Matthew Raw
Date Created: 30/5/17
Last Updated: 7/6/17
Description: search page for products
*/

//set the page title
$pageTitle = "Search Results";

include "includes/head.php";

//connect to database
include "includes/connect.php";

if (isset($_GET['search'])) {

    try {
        //capture user data
        $searchValue = cleanInput($_GET['search']);

        $sql= "SELECT prodId, prodName, qtyOnHand, listPrice, thumbNail, image, description FROM tbl_product WHERE prodName LIKE :searchValue OR description LIKE :searchValue;";
        $count_sql= "SELECT COUNT(*) FROM tbl_product WHERE prodName LIKE :searchValue OR description LIKE :searchValue;";

        $statement = $pdo->prepare($sql);
        $count_statement = $pdo->prepare($count_sql);

        $statement->bindValue(':searchValue', '%'.$searchValue.'%');
        $count_statement->bindValue(':searchValue', '%'.$searchValue.'%');

        $statement->execute();
        $count_statement->execute();

        $resultSet = $statement->fetchAll();
        $productCount = $count_statement->fetchColumn();


    } // end of try block

    catch (PDOException $e) {
        //create an error message
        echo "Error fetching products: ".$e->getMessage();

        //stop script continuing
        exit();
    } // end of catch block
} // end if block

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
                        
                            // if wishlist is empty, display message that cart is empty
                            if(isset($productCount) && $productCount == 0) {
                                
                                echo "<div class='row searchNoResults' style='text-align: center;'><br>";
                                echo "<h3>Your search for ".$searchValue." returned 0 results</h3>";
                                echo "<a href='store.php'>Click here to continue shopping</a><br><br>";
                                echo "</div>";
                            } // end if statement
                            //if search box was empty redirect to store
                            else if ($_GET['search'] == '' || !isset($_GET['search'])) {
                                echo '<script type="text/javascript">location.assign("store.php");</script>';
                            }
                            else //display results
                            {
                                ?>
                                
                                        <div class="row"><!-- main bootstrap row-->

                                            <!--main content container-->
                                            <div class="maincontainer col-xs-12 col-sm-9 col-md-9 col-lg-9">
                                                <div class="maincontent">
                                                    <?php echo '<h5 class="searchResultTitle">Your search for \''.$searchValue.'\' returned '.$productCount.' results</h5>'; ?>

                                                    <!--Print off all items in SQL result set-->
                                                    <?php include 'includes/printitems.php' ?>


                                                </div> <!-- end main content -->
                                            </div> <!-- end bootstrap column -->

                                            <!-- main sidebar container-->
                                            <div class="mainsidebar_container xs-hide col-sm-3 col-md-3 col-lg-3">
                                                
                                            </div> <!-- end bootstrap column -->


                                        </div><!-- end of main bootsrap row-->

                                        <?php } // end display results if?> 
                               
        </main><!-- end of main -->
        
        <!-- footer -->
        <?php include 'includes/footer.php'; ?>
        <!-- end of footer -->
        
        
    </div> <!-- end of bootstrap container -->
    
</body>
    
</html>