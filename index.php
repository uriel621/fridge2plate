<?php
session_start();
//$redirect_uri = "https://" . $_SERVER['HTTP_HOST'] . "/google_login/g_login.php";
?>
<!DOCTYPE html>
<html>
<head>
    <!--Google Login Meta Tags-->
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="802478348342-ovn07tr2ulnqnqk06j94cga951pufnib.apps.googleusercontent.com">

    <meta name="title" content="Fridge 2 Plate">
    <meta name="description" content="Take your ingredients from the fridge to your plate with awesome recipes.">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.js"></script>

    <!-- Bootstrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500" rel="stylesheet">

    <!-- AddThis -->
    <script type="text/javascript" src="https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-582e2e9ffdf9c863"></script>

    <!--jQuery UI-->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="./style.css">
    <link rel="icon" href="./images/fridge2plate.png">

    <script src="./script.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid nav-menu">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" style="color: white" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar" style="color: white"></span>
                <span class="icon-bar" style="color: white"></span>
                <span class="icon-bar" style="color: white"></span>
            </button>
            <a class="navbar-brand toggle-nav" href="#" style="color: white"><i class="fa fa-bars"></i> Menu</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
              <!-- Google Login/Feature Recipe -->
                <li><a href="./google_login/g_login.php">Feature Recipe</a></li>
                <li class="signIn"><a id="my-signin2" data-onsuccess="onSignIn"></a></li>
                <!--<li class="signOut"><a href="#" onclick="signOut();">Sign out</a></li>-->
                <script src="https://apis.google.com/js/platform.js" defer></script>
                <script src="./google_login/g_login_root.js" defer></script>
            </ul>
        </div>
    </div>
</nav>

<div id="site-wrapper">
    <div id="site-canvas">
      <!-- Side Nav -->
        <div id="site-menu" data-spy="affix" data-offset-top="205" >
            <a href="#" class="toggle-nav" style="color: pink; font-size: 20px;"><i class="fa fa-times"></i></a>
            <img src="./images/fridge2plate.png" id="logo" width="100%"><br> <br>
            <form class="form-inline">
                <input type="text" class="form-control ingredientInput"  size="26" placeholder="Enter your ingredients">
            </form>
            <br>
            <div class="panel-group" id="accordion" >
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="fa fa-shopping-basket"></i>&nbsp;&nbsp; <a data-toggle="collapse" data-parent="#accordion" href="#collapse1"> Common Ingredients</a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse in">
                        <div class="panel-body" id="ingredientButtons">
                        </div>
                    </div>
                </div>
            </div>
            <ul class="nav nav-pills nav-stacked" >
                <li><a href="./about.html">About</a></li>
                <li><a href=" http://devjournal.fridge2plate.com/" target="_blank">Our Blog</a></li>
            </ul>
        </div>
        <!-- End Side Nav -->

        <!-- Recipe Section -->
        <div class="container-fluid" id="main-container">
            <div class="row-container">
                <div class="col-sm-12">
                    <div class="jumbotron text-center ">
                        <img src="./images/fridge2plate-jumbo.png" width="40%">
                        <p id="tagline">Making meals with ingredients you have</p>
                        <form class="form-inline">
                            <input type="text" class="form-control ingredientInput"  size="50" placeholder="What's in your fridge?">
                            <!-- <button type="button" id="placeholder-button" class="btn btn-danger">Go</button> -->
                        </form>
                        <img src="./images/loading-food-animation.gif" id="loading">
                    </div>
                    <div class="container-fluid fridge"></div>    <!--fridge container-->
                    <div class="row-container">
                        <div id="stuff"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Recipe Section -->
    </div>
    <!-- End Site Canvas -->
</div>
<!-- End Site Wrapper -->

<!-- Recipe Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <!--Note:Modal cannot be placed inside nested divs, so move it outside of the main div-->
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">My Recipe</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                <img class=" .col-sm-4  pull-right showImage img-responsive" src="#">
                <div class=".col-sm-5  ingContainer"></div>
                    <div class="addthis_inline_share_toolbox_co79"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Recipe Modal -->
<footer class="container-fluid footer-style text-center">
    <p> Website designed by Fridge2Plate. Recipe data provided by Spoonacular.  </p>
</footer>
</body>
</html>
