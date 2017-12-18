<?php
/*
Filename: subnav.php
Author: Matthew Raw
Date Created: 22/3/17
Last Updated: 22/3/17
Description: subnav section for all pages in dynamic website video game store assignment. 
Will connect to database if page has not previously connected to fetch catgegories.

Selected categories stored in array $_POST['catFilter']

catFilter utilised in fetching of products at top of store.php
*/

//if page has not already connected to database, do so
if (!(isset($pdo))) { include "includes/connect.php"; }

try {
    //create SQL statement
    $sql_categories= "SELECT catName, catNbr FROM tbl_category;";

    //execute the sql statement and store the output
    $result_categories = $pdo->query($sql_categories);

} //end of try block

//if errors- catch
catch (PDOException $e) {
    //create an error message
    echo "Error fetching categories: ".$e->getMessage();

    //stop script continuing
    exit();

} // end of catch block

?>

<nav class="mainsidebar">
    <div class="subnavFilters">
        <h4>Filter By:</h4>
        <!-- create link to all products page-->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <?php
                //loop through sql category results and print out links to categories
                foreach($result_categories as $row) {
                    $catName = $row['catName'];
                    $catNbr = $row['catNbr'];

                echo '<input type="checkbox" id="'.$catName.'" name="catFilter[]" value="'.$catNbr.'" onclick="this.form.submit()"';
                
                //if user has previously selected this checkbox, keep it checked on form submit
                if(isset($_SESSION['catFilter']) && in_array($catNbr, $_SESSION['catFilter'])){ 
                    echo "checked='checked'";
                }

                echo '><label for="'.$catName.'">&nbsp;'.$catName.' </label><br>';
                    
            } //end foreach
        ?>
            <!--If user empties all checkboxes, no data is sent from 
            final empty checkbox into POST, therefore SESSION is not updated.
            Hidden field used to determine if form submitted without checkboxes-->
            <input type='hidden' name="hidden" value="hidden">
        </form>        
    </div>
    <br>
    <ul class="subnav testing">
        <li><a href="displaySessionVariables.php">Display Session Variables</a></li>
        <li><a href="killSessionVariables.php">Kill Session Variables</a></li>
        <li><a href="emptyCart.php">Empty Cart</a></li>
    </ul>
</nav>



    
