<?php session_start();
/*
Filename: store.php
Author: Matthew Raw
Date Created: 5/4/17
Last Updated: 5/4/17
Description: Store home. Displays all items in database.
*/

//set the page title
$pageTitle = "Store";

include "includes/head.php";

//Get products from database

//connect to database
include "includes/connect.php";

try {
    //create default SQL statement
    $sql= "SELECT prodId, prodName, qtyOnHand, listPrice, thumbNail, image, description FROM tbl_product";
    $sql_prodCount= "SELECT COUNT(*) FROM tbl_product";


    //---------------------------- reset filters --------------------------------//
    //if reset filters button has been clicked, empty out filter arrays
    if(isset($_POST['resetFilter'])) {
        unset($_SESSION["catFilter"]);
        unset($_SESSION["sortselect"]);
    }

    //------------------ recieve get requests for categories ---------------------//
    //set session variables if GET recieves requests for categories
    if(isset($_GET["category"])) {
        $_SESSION["catFilter"] = array();
        $_SESSION["catFilter"][0] = $_GET["category"];
    }

    //------------ recieve any POST filter requests and alter SQL ---------------//
    //capture any _POST data if sorting form submitted and store it in user session
    if(isset($_POST["sortselect"])) {
        $_SESSION["sortselect"] = $_POST["sortselect"];
    }

    //if checkbox filter form submitted, update session data with selected filters
    if(isset($_POST["catFilter"])) {
        $_SESSION["catFilter"] = $_POST["catFilter"];
    }

    //if post does not recieve any data from catFilter, but recieves hidden form value, checkboxes must empty. Unset SESSION[catFilter] as empty.
    if(isset($_POST['hidden']) && !(isset($_POST["catFilter"]))) {
        unset($_SESSION["catFilter"]);
    }

    //if user has filtered items using catFilter checkboxes:
    if(isset($_SESSION['catFilter'])) {
        //add where clause to SQL + productCount
        $sql = $sql." WHERE";
        $sql_prodCount = $sql_prodCount." WHERE";

        //count number of filters in array
        $numFilters = count($_SESSION['catFilter']);
        //initialise counter 
        $i = 1;
    
        foreach ($_SESSION['catFilter'] as $key => $value) {

            $sql= $sql." catNbr = ".$value;
            $sql_prodCount = $sql_prodCount." catNbr = ".$value;

            //if not final item in array append OR
            if($i != $numFilters) {
                $sql = $sql." OR";
                $sql_prodCount = $sql_prodCount." OR";
            }

            //iterate i
            $i++;
        }
    }

    //if user has sorted products using sort select
    if(isset($_SESSION["sortselect"])) {

        //check which sorting choice made and append SQL to command. If default is selected by user, array unset.
        if ($_SESSION["sortselect"] == "nameasc") {
            $sql= $sql." ORDER BY prodName ASC";
        } 
        elseif  ($_SESSION["sortselect"] == "namedesc") {
            $sql= $sql." ORDER BY prodName DESC";
        }
        elseif ($_SESSION["sortselect"] == "pricedesc") {
            $sql= $sql." ORDER BY listPrice DESC";
        }
        elseif ($_SESSION["sortselect"] == "priceasc") {
            $sql= $sql." ORDER BY listPrice ASC";
        }
        elseif ($_SESSION["sortselect"] == "default") {
            unset($_SESSION["sortselect"]);
        }
    }

    
    //---------------------------- pagination --------------------------------//

    //get count of total items before creating pages
    $result_totalCount = $pdo->query($sql_prodCount);

    foreach ($result_totalCount as $row) {
        $totalCount = $row['COUNT(*)'];
    }

    //initialise default values.
    $pageNumber = 1;
    $offset = 0;

    //if there are more than 15 products, limit them unless user clicked View All button
    if ($totalCount > 15 && (!isset($_GET['page']) || $_GET['page'] != 'viewAll')) {
            
            $sql = $sql." LIMIT 15";
            $sql_prodCount = $sql_prodCount." LIMIT 15";

            if (isset($_GET['page'])) {

                $pageNumber = cleanInput($_GET['page']);
                
                //get requested page number
                if (!is_int($pageNumber)) {
                    exit();
                } 

                 //calculte the sql offset
                 $offset = ($pageNumber - 1) * 15;
                
                $sql = $sql." OFFSET $offset";
            }
        } 
        //otherwise user has clicked view all
        else if ($totalCount > 15) {
            $pageNumber = 'viewAll';
        }

    //---------------------------- finalise results --------------------------------//

    //teriminate SQL command with semicolon
    $sql = $sql.";";
    $sql_prodCount = $sql_prodCount.";";

    $statement = $pdo->prepare($sql);

    //execute the sql statement and store the output
    $statement->execute();

    $resultSet = $statement->fetchAll();
    $productCount = $statement->rowCount();

    //work out numbers for page to render pagination state for user
    $firstItem = $offset + 1;
    $lastItem = $offset + $productCount;

} //end of try block

//if 
catch (PDOException $e) {
    //create an error message
    echo "Error fetching products: ".$e->getMessage();

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
        <main class="main">
        
        <div class="row"> <!-- title row -->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 titleContainer">
                <div class="titleGradient">
                    <h4 class="pageTitle">
                        <?php 
                        //if products sorted, change page title to match items
                        echoStoreTitle();
                        ?>
                    </h4>
                <?php 
                    include "includes/sort.php";
                ?>
                </div>
            </div>
        </div> <!-- title row -->

        <div class="row"><!-- main bootstrap row-->

            <!--main content container-->
            <div class="maincontainer col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <div class="maincontent">

                    <?php 
                        if ($totalCount < 15 || (isset($_GET['page']) && $_GET['page'] == 'viewAll')) {
                            echo '<h5 class="searchResultTitle">Displaying '.$totalCount.' items<h5>';
                        }
                        else {
                            echo "<h5 class='searchResultTitle'>Displaying $firstItem - $lastItem of $totalCount</h5>"; 
                        }
                    ?>
                    <!--Print off all items in SQL result set-->
                    <?php include 'includes/printitems.php' ?>

                    <!--include page buttons for pagination-->
                    <?php include 'includes/pagebuttons.php' ?>                    


                </div> <!-- end main content -->
            </div> <!-- end bootstrap column -->

            <!-- main sidebar container-->
            <div class="mainsidebar_container xs-hide col-sm-3 col-md-3 col-lg-3">
                <?php include 'includes/subnav.php'; ?>
            </div> <!-- end bootstrap column -->


        </div><!-- end of main bootsrap row-->

        </main><!-- end of main -->
        
        <!-- footer -->
        <?php include 'includes/footer.php'; ?>
        <!-- end of footer -->
        
        
    </div> <!-- end of bootstrap container -->
    
</body>
    
</html>