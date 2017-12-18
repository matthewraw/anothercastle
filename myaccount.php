<?php session_start();
/*
Filename: template.php
Author: Matthew Raw
Date Created: 22/3/17
Last Updated: 11/6/17
Description: template for all pages in dynamic website video game store assignment
*/

//before taking action, test user is logged in
if(isset($_SESSION['login']) || $_SESSION['login'] == 'valid') {

    //set the page title
    $pageTitle = "My Account";


    include "includes/head.php";
    include 'includes/connect.php';

    //update details sql
    if (isset($_POST['updateSubmit'])) {

        //duplicate email check
        if ($_SESSION['email'] != cleanInput($_POST['email'])) {
            try {
                //create sql statement
                $sql = "SELECT COUNT(*) FROM tbl_customer WHERE email = :email;";

                //prepare statement
                $statement = $pdo->prepare($sql);

                //bind values
                $statement->bindValue(':email', cleanInput($_POST['email']));

                //execute the SQL statement
                $statement->execute();

            }//end of try block

            catch(PDOException $e) {
                // Creating a suitable error message including exception details
                echo "Error for checking duplicate email " .$e->getMessage();
        
                // Stops the script continuing
                exit();
            } //end of catch block

            //check number of matching rows. If there is a matching row, email is already in use. Throw error
            $nbrOfRows = $statement->fetchColumn();

            if ($nbrOfRows > 0) {
                //display message to use that there is a duplicate email
                echo "<script type='text/javascript'>alert('The E-mail address has already been taken');</script>";
                //stop the script
                exit();
            }

        }// end of duplicate email check


        //success, ready to update details clean input from user
        $email = cleanInput($_POST['email']);
        $firstName = cleanInput($_POST['firstName']);
        $lastName = cleanInput($_POST['lastName']);
        $address = cleanInput($_POST['address']);
        $suburb = cleanInput($_POST['suburb']);
        $stateId = cleanInput($_POST['stateId']);
        $postCode = cleanInput($_POST['postCode']);

        //sql update records
        try {
            //create statement
            $sql = "UPDATE tbl_customer SET email = :email, firstName = :firstName, lastName = :lastName, address = :address, suburb = :suburb, stateId = :stateId, postCode = :postCode WHERE custNbr = :custNbr;";

            //prepare statement
            $statement = $pdo->prepare($sql);

            //bind values
            $statement->bindValue(':email', $email);
            $statement->bindValue(':firstName', $firstName);
            $statement->bindValue(':lastName', $lastName);
            $statement->bindValue(':address', $address);
            $statement->bindValue(':suburb', $suburb);
            $statement->bindValue(':stateId', $stateId);
            $statement->bindValue(':postCode', $postCode);
            $statement->bindValue('custNbr', $_SESSION['custNbr']);

            $statement->execute();

        } // end of try block
        catch(PDOException $e) {
            // Creating a suitable error message including exception details
            echo "Error updating data: " .$e->getMessage();
        
            // Stops the script continuing
            exit();
        } //end of catch block

        // Update session variables relating to account
        $_SESSION['email'] = cleanInput($_POST['email']);
        $_SESSION['firstName'] = cleanInput($_POST['firstName']);
        $_SESSION['lastName'] = cleanInput($_POST['lastName']);
        $_SESSION['address'] = cleanInput($_POST['address']);
        $_SESSION['suburb'] = cleanInput($_POST['suburb']);
        $_SESSION['stateId'] = cleanInput($_POST['stateId']);
        $_SESSION['postCode'] = cleanInput($_POST['postCode']);
        
        $_SESSION['deliveryName'] = cleanInput($_POST['firstName']) ." " .cleanInput($_POST['lastName']);
        $_SESSION['deliveryAddress'] = cleanInput($_POST['address']);
        $_SESSION['deliverySuburb'] = cleanInput($_POST['suburb']);
        $_SESSION['deliveryStateId'] = cleanInput($_POST['stateId']);
        $_SESSION['deliveryPostCode'] = cleanInput($_POST['postCode']);
        
        echo "<script type='text/javascript'>alert('Account has been successfuly updated');</script>";
    } //end of update details sql block


    //update password SQL
    if(isset($_POST['passwordSubmit'])) {
        
        //confirm current password before updating new one
        try {
            $sql = "SELECT COUNT(*) FROM tbl_customer WHERE custNbr = :custNbr AND passWord = :passWord;";

            $statement = $pdo->prepare($sql);

            $currentPassWord = cleanInput($_POST['currentPassWord']);

            //bind values
            $statement->bindValue(':custNbr', $_SESSION['custNbr']);
            $statement->bindValue(':passWord', sha1($currentPassWord));   

            $statement->execute();     
        }//end of try block
        catch(PDOException $e) {
            // Creating a suitable error message including exception details
            echo "Error updating password: " .$e->getMessage();
        
            // Stops the script continuing
            exit();
        } //end of catch block

        $currentPasswordCheck = $statement->fetchColumn();

        //if correct match, update details. If incorrect, customer is informed below in HTML doc
        if ($currentPasswordCheck == 1) {
            
            try {
                //create our SQL statement
                $sql = "UPDATE tbl_customer SET passWord = :passWord WHERE custNbr = :custNbr;";

                //prepare statement
                $statement = $pdo->prepare($sql);

                //bind values
                $statement->bindValue(':custNbr', $_SESSION['custNbr']);
                $statement->bindValue(':passWord', sha1(cleanInput($_POST['newPassWord'])));

                $statement->execute();

            }//end of try block
            catch(PDOException $e) {
                // Creating a suitable error message including exception details
                echo "Error updating password: " .$e->getMessage();
            
                // Stops the script continuing
                exit();
            } //end of catch block

            echo "<script type='text/javascript'>alert('Password has been successfuly updated');</script>";
        }
        
    }//end of update password sql block


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

                <div class="row"> <!-- title row -->
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 titleContainer">
                        <div class="titleGradient">
                            <h4 class="singleTitle">Account Settings</h4>
                        </div>
                    </div>
                </div> <!-- title row -->
                
            <div class="row"><!-- main bootstrap row-->

                <!--left bootstrap column, customer update content container-->
                <div class="updatecontainer col-xs-12 col-sm-7 col-md-7 col-lg-7">
                    <div class="formcard accountLeft">
                        <h4>Edit my details:</h4>
                        
                            
                        <!--customer update up form-->
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" onsubmit="return checkUpdateCustomer(this);">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <label for="email">* Email:</label><br>
                                    <input type="text" name="email" maxlength="50" value="<?php echo $_SESSION['email']; ?>"><br>
                                    <label for="firstName">First Name:</label><br>
                                    <input type="text" name="firstName" maxlength="20" value="<?php echo $_SESSION['firstName']; ?>"><br>
                                    <label for="lastName">* Last Name:</label><br>
                                    <input type="text" name="lastName" maxlength="45" value="<?php echo $_SESSION['lastName']; ?>"><br>
                                    <label for="address">* Address:</label><br>
                                    <input type="text" name="address" maxlength="45" value="<?php echo $_SESSION['address']; ?>"><br>
                                    <label for="suburb">* Suburb:</label><br>
                                    <input type="text" name="suburb" maxlength="45" value="<?php echo $_SESSION['suburb']; ?>"><br>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <label for="stateId">* State:</label><br>
                                        <select name="stateId">
                                            <option value="">Select State:</option>
                                            <?php
                                                getStateList();
                                            ?>
                                        </select><br>
                                    <label for="postCode">* Postcode:</label><br>
                                    <input type="text" name="postCode" maxlength="10" value="<?php echo $_SESSION['postCode']; ?>"><br>
                                </div>
                            </div><!-- end form bootstrap row -->
                            <div class="row formcardButtons">
                                <input class="btn btn-default accountBtn" type="submit" name="updateSubmit">
                                <input class="btn btn-default accountBtn" type="reset" name="reset">
                            </div>
                        </form>
                        
                        

                    </div> <!-- end form card div-->
                </div> <!-- end left bootstrap column -->


                <!--right bootstrap column, update password content container-->
                <div class="updatecontainer col-xs-12 col-sm-5 col-md-5 col-lg-5">

                    <div id="updatepassword" class="formcard accountRight">
                        <h4>Update Password:</h4>
                        
                        <?php 
                        // if no match for current password, echo error for user
                        if (isset($currentPasswordCheck) && $currentPasswordCheck == 0) {
                            
                            echo '<script type="text/javascript">
                                    $(document).ready(function () {
                                        // Handler for .ready() called.
                                        $("html, body").animate({
                                            scrollTop: $("#updatepassword").offset().top
                                        }, 0);
                                    });
                                 </script>';
                            echo '<p class="error" style="color: #f44842;">Current password does not match password on file</p>';

                            }
                        ?>    
                        <!--update password form-->
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" onsubmit="return checkUpdatePassword(this);">
                                    <label for="currentPassWord">Current Password:</label><br>
                                    <input id="currentPassWord" type="password" name="currentPassWord" maxlength="12"><br>
                                    <br>

                                    <label for="newPassword">New Password:</label><br>
                                    <input id="newPassword" type="password" name="newPassWord" maxlength="12"><br>
                                    <label for="newPassWord2">Confirm New Password:</label><br>
                                    <input id="newPassWord2" type="password" name="newPassWord2" maxlength="12"><br><br>
                            <div class="row formcardButtons">
                                <input class="btn btn-default accountBtn" type="submit" name="passwordSubmit">
                                <input class="btn btn-default accountBtn" type="reset" name="reset">
                            </div>
                        </form>
                        
                        

                    </div> <!-- end form card div-->
                </div> <!-- end right bootstrap column -->

            </div><!-- end of main bootsrap row-->

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
?>