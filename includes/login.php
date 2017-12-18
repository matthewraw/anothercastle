<?php
/*
Filename: login.php
Author: Matthew Raw
Date Created: 22/3/17
Last Updated: 22/3/17
Description: login section for all pages in dynamic website video game store assignment. Login form reception occurs in head.php, utilizing checkCustomer function in myFunctions.php.
*/

//if page has not already connected to database, do so
if (!(isset($pdo))) { include "includes/connect.php"; }

?>
<div class="row">        
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-8">
    <!-- Login links for navbar -->


            <?php
            //if login is set and valid, show welcome message
            if (isset($_SESSION['login']) && $_SESSION['login'] == 'valid') {
            ?>
                <nav class="login">
                    <p class="inline">Welcome, <?php echo $_SESSION['firstName']; ?></p>
                    <p class="inline">&nbsp;|&nbsp;</p>
                    <div class="dropdown inline">
                        <button class="btn btn-primary dropdown-toggle myAccountBtn" type="button" data-toggle="dropdown">My Account
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <li><a href="myaccount.php">Update Details</a></li>
                          <li><a href="wishlist.php">Wishlist</a></li>
                          <li><a href="myorders.php">Previous Orders</a></li>
                        </ul>
                    </div>
                    <p class="inline">&nbsp;|&nbsp;</p>
                    <a href="logout.php" class="inline">Log Out</a>
                </nav> 
            <?php
            } 
            else { // if login not valid or unset, show login and register
            ?>
                <nav class="login">
                    <a data-toggle="modal" data-target="#loginModal" class="inline" style="cursor: pointer;">Log In</a>
                    <p class="inline">&nbsp;|&nbsp;</p>
                    <a data-toggle="modal" data-target="#registerModal" class="inline" style="cursor: pointer;">Register</a>

                </nav> 
            <?php
            } //end else statement
            ?>
    </div><!-- end left bootstrap column -->
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4">
        <div class="searchAlignDiv">
            <form action="search.php" method="GET" onsubmit="return checkSearch(this);">
                <div class="searchBoxContainer">
                    <input class="searchBox" name="search" maxlength="50" type="text">
                    <button class="searchBoxSubmit" type="submit" name="searchSubmit"><img class="searchBoxIcon" src="images/searchicon.png" alt="search icon"></button>
                </div>
            </form>
        </div>
    </div>
</div><!-- end bootstrap row -->

    <?php

        //if login attempted but invalid, show modal. Error will appear in modal, below
        if (isset($_SESSION['login']) && $_SESSION['login'] == 'invalid') {
        ?>
            <!-- jquery to display modal on launch-->
            <script type="text/javascript">
                $(window).on('load',function(){
                    $('#loginModal').modal('show');
                });
            </script>
        <?php
        }

        ?>
        
<!-- Modal for login-->

<div id="loginModal" class="modal <?php if (!isset($_SESSION['login'])) {echo 'fade';} ?>" role="dialog" data-focus="true">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Login</h4>
      </div>
      

      <div class="modal-body">
        

        <!-- Login Form starts here -->
        <?php
        //if user has clicked forgot password, show create new password form
        if (isset($_POST['forgotPassword'])) {
            ?>
            <!-- call script to keep modal open -->
            <script type="text/javascript">
                $(window).on('load',function(){
                    $('#loginModal').modal('show');
                });
            </script>

            <!-- output form to apply for new password -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" onsubmit="return checkResetPass(this);">
                <p>Please enter your email, and a new password will be generated and sent to you</p>
                <label for="newPassEmail">Email:</label><br>
                <input id="newPassEmail" type="text" name="newPassEmail" maxlength="50"><br><br>
                <button type="submit" class="btn btn-default" name="newPasswordSubmit">Submit</button>
                </form> <!-- end new password form --> 

            <?php
        }   
        //else display regular login form 
        else {
            //if user login has failed, display error
            if (isset($_SESSION['login']) && $_SESSION['login'] == 'invalid') {
                echo '<p class="error" style="color: #f44842;">The username and password do not match an account on file, please try again.</p>';
                unset($_SESSION['login']);
            }

            //if user has tried to check out and is not logged in, prompt them to do so
            if (isset($_POST['hiddenCheckout']) && $_POST['hiddenCheckout'] == 'invalid') {
                echo '<p class="error" style="color: #f44842;">Please log in to your account before continuing.</p>';
            }

            ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" onsubmit="return checkLogin(this);">
                <label for="loginEmail">Email:</label><br>
                <input id="loginEmail" type="text" name="loginEmail" maxlength="50"><br>

                <label for="loginPassword">Password:</label><br>
                <input id="loginPassword" type="password" name="loginPassword" maxlength="12"><br><br>
                <button type="submit" class="btn btn-default" name="loginsubmit">Submit</button>
                </form> <!-- end login form -->

                <!-- forgotten password form to bring up forgot password modal -->
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <button type="submit" class="resetPassBtn" name="forgotPassword">Forgotten Password?</button>
                </form>

            <?php } // end else statement ?>   
      </div>        
     
</div>

  </div>
</div>


<!-- Modal for register -->

<div id="registerModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Register a new account</h4>
      </div>
      <div class="modal-body formcard formcardregister">

                    <!--customer sign up form-->
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" onsubmit="return checkAddCustomer(this);">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label for="email">* Email:</label><br>
                                <input id="email" type="text" name="email" maxlength="50"><br>
                                <label for="firstName">First Name:</label><br>
                                <input id="firstName" type="text" name="firstName" maxlength="20"><br>
                                <label for="lastName">* Last Name:</label><br>
                                <input id="lastName" type="text" name="lastName" maxlength="45"><br>
                                <label for="address">* Address:</label><br>
                                <input id="address" type="text" name="address" maxlength="45"><br>
                                <label for="suburb">* Suburb:</label><br>
                                <input id="suburb" type="text" name="suburb" maxlength="45"><br>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label for="stateId">* State:</label><br>
                                    <select id="stateId" name="stateId">
                                        <option value="">Select State:</option>
                                        <?php
                                            getStateList();
                                        ?>
                                    </select><br>
                                <label for="postCode">* Postcode:</label><br>
                                <input id="postCode" type="text" name="postCode" maxlength="10"><br>
                                <label for="passWord">* Password:</label><br>
                                <input id="passWord" type="password" name="passWord" maxlength="12"><br>
                                <label for="passWord2">* Confirm Password:</label><br>
                                <input id="passWord2" type="password" name="passWord2" maxlength="12"><br><br>
                            </div>
                        </div><!-- end form bootstrap row -->
                        <br><br>
                        <input class="btn btn-default formcardsubmit" type="submit" name="registersubmit">
                        <input class="btn btn-default formcardsubmit" type="reset" name="reset">
        </form>

      </div>
</div>

  </div>
</div>


<?php // Registration PHP functions
    
    if(isset($_POST['registersubmit'])) {
        
        include 'includes/connect.php';

        //test to see if email is already registered
        try {
            //create SQL statement 
            $sql= "SELECT COUNT(*) FROM tbl_customer WHERE email = :email;";

            //prepare statement
            $statement = $pdo->prepare($sql);

            //bind the values to the sql placeholders
            $statement->bindValue(':email', cleanInput($_POST['email']));

            //execute the sql statement
            $statement->execute();


        } //end of try block

        //if 
        catch (PDOException $e) {
            //create an error message
            echo "Error checking for duplicate email: ".$e->getMessage();

            //stop script continuing
        exit();

        } // end of catch block


        //get the value of count in our sql statement result set
        $nbrOfRows = $statement->fetchColumn();

        //test value of number of rows, exit script if email exists.
        if ($nbrOfRows) {
            //display message to user that there is a duplicate email
            echo "<script type='text/javascript'>alert('Email address is already in use, please use another address');</script>";
            exit;
        }

        //end duplicate email check


        //success, write user data to database
        $email = cleanInput($_POST['email']);
        $firstName = cleanInput($_POST['firstName']);
        $lastName = cleanInput($_POST['lastName']);
        $address = cleanInput($_POST['address']);
        $suburb = cleanInput($_POST['suburb']);
        $stateId = cleanInput($_POST['stateId']);
        $postCode = cleanInput($_POST['postCode']);
        $passWord = sha1(cleanInput($_POST['passWord']));

        try {
            //create SQL statement 
            $sql= "INSERT INTO tbl_customer SET email = :email, firstName = :firstName, lastName = :lastName, address = :address, suburb = :suburb, stateId = :stateId, postCode = :postCode, passWord = :passWord, dateJoined = :dateJoined, discountRate = :discountRate;";

            //prepare statement
            $statement = $pdo->prepare($sql);

            //bind the values to the sql placeholders
            $statement->bindValue(':email', $email);
            $statement->bindValue(':firstName', $firstName);
            $statement->bindValue(':lastName', $lastName);
            $statement->bindValue(':address', $address);
            $statement->bindValue(':suburb', $suburb);
            $statement->bindValue(':stateId', $stateId);
            $statement->bindValue(':postCode', $postCode);
            $statement->bindValue(':passWord', $passWord);
            $statement->bindValue(':dateJoined', date('Y-m-d'));
            $statement->bindValue(':discountRate', 0.00);

            //execute the sql statement
            $statement->execute();

        } //end of try block

        catch (PDOException $e) {
            //create an error message
            echo "Error registering user: ".$e->getMessage();

            //stop script continuing
            exit();

        } // end of catch block


        echo "<script type='text/javascript'>alert('Registration Successful');</script>";
        

    } // end registration PHP functions - end registersubmit if statement


    //forgotten password functions
    if(isset($_POST['newPassEmail'])) {

        //capture data
        $email = cleanInput($_POST['newPassEmail']);

        //test email against database
        //test to see if email is already registered
        try {
            //create SQL statement 
            $sql= "SELECT COUNT(*) FROM tbl_customer WHERE email = :email;";

            //prepare statement
            $statement = $pdo->prepare($sql);

            //bind the values to the sql placeholders
            $statement->bindValue(':email', $email);

            //execute the sql statement
            $statement->execute();


        } //end of try block

        //if 
        catch (PDOException $e) {
            //create an error message
            echo "Error checking for email: ".$e->getMessage();

            //stop script continuing
        exit();

        } // end of catch block

        //get the value of count in our sql statement result set
        $nbrOfRows = $statement->fetchColumn();

        //if email is found
        if ($nbrOfRows == 1) {
            
            //create new password
            $newPassWord = createNewPassword();

            //update database with new password
            
            try {
                //create statement
                $sql = "UPDATE tbl_customer SET passWord = :passWord WHERE email = :email;";

                //prepare statement
                $statement = $pdo->prepare($sql);

                //bind values
                $statement->bindValue(':passWord', sha1($newPassWord));
                $statement->bindValue(':email', $email);

                $statement->execute();

            }

            catch (PDOException $e) {
                //create an error message
                echo "Error creating new password: ".$e->getMessage();

                //stop script continuing
                exit();

            } // end of catch block


            //email user
            $to = $email;
            $subject = 'Another Castle password reset request';
            $message = 'Your Another Castle account has recieved a password reset request. 
Your new password is '.$newPassWord.'
Please login to your account with this temporary password and choose a new one.';
            $headers = 'From: do-not-reply';

            mail($to, $subject, $message, $headers);

            //success, message user
            echo "<script type='text/javascript'>alert('A new password has been emailed to the address provided');</script>";


        }
        else {
            echo "<script type='text/javascript'>alert('Email does not match any account on file, please try again');</script>";
        }
    }


?>