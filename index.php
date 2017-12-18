<?php session_start();
/*
Filename: template.php
Author: Matthew Raw
Date Created: 22/3/17
Last Updated: 22/3/17
Description: template for all pages in dynamic website video game store assignment
*/

//set the page title
$pageTitle = "Home";

include "includes/head.php";
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
                    <div class="logo logoHomepageAnimation">
                        <img src="images/logo.svg" alt="Another Castle Background">
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
            
        <!-- carousel -->
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
              <!-- Indicators -->
              <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
                <li data-target="#myCarousel" data-slide-to="3"></li>
              </ol>

              <!-- Wrapper for slides -->
              <div class="carousel-inner" role="listbox">
                <div class="item active">
                  <a href="store.php"><img src="images/yooka_lg.jpg" alt="Yooka Laylee"></a>
                </div>

                <div class="item">
                  <a href="store.php"><img src="images/masseffect_lg.jpg" alt="Mass Effect Andromeda"></a>
                </div>

                <div class="item">
                  <a href="store.php"><img src="images/zelda_lg.jpg" alt="Legend of Zelda"></a>
                </div>

                <div class="item">
                  <a href="store.php"><img src="images/reddead_lg.jpg" alt="Red Dead Redemption 2"></a>
                </div>

              </div>

              <!-- Left and right controls -->
              <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>

            <!-- Category cards -->
            <div class="row maincardrow">
                <!-- card 1 -->    
                <div class="col-sm-4 col-md-4 col-lg-4 cardcontainer">
                    <div class="card">
                        <a class='block font_lg' href="store.php?category=1">   
                           <img class="child" src="images/thumbnails/middleearth_th.jpg" alt="shadow of mordor"> 
                           <br>
                           Games
                        </a>
                    </div>
                </div>
                
                <!-- card 2 -->
                <div class="col-sm-4 col-md-4 col-lg-4 cardcontainer">
                    <div class="card">
                        <a class='block font_lg' href="store.php?category=2">
                            <img class="child" src="images/thumbnails/switch_th.jpg" alt="nintendo switch"> 
                            <br>
                            Consoles
                        </a>
                    </div>
                </div>
                
                <!-- card 3 -->
                <div class="col-sm-4 col-md-4 col-lg-4 cardcontainer">
                    <div class="card">
                        <a class='block font_lg' href="store.php?category=3">
                           <img src="images/thumbnails/xboxonecontroller_th.jpg" alt="xbox one controller" class="child"> 
                           <br>
                           Accessories
                        </a>
                    </div>
                </div>

            </div> <!-- end of bootstrap row -->

        </main><!-- end of main -->
        
        <!-- footer -->
        <?php include 'includes/footer.php'; ?>
        <!-- end of footer -->
        
        
    </div> <!-- end of bootstrap container -->
    
</body>
    
</html>