<?php
session_start();

$redirect_uri_logged_in = 'https://' . $_SERVER['HTTP_HOST'] . '/braintree/index.php';

if (isset($_SESSION['user'])) {
    $f_name = $_SESSION['user']['f_name'];
    $l_name = $_SESSION['user']['l_name'];
    $email = $_SESSION['user']['email'];
    header('Location: ' . filter_var($redirect_uri_logged_in, FILTER_SANITIZE_URL));
}
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "g_login_head.php"; ?>
</head>
<body>
    <div class="container">
        <img src="../images/fridge2plate.png" id="logo" width="100%"><br>
        <div class="omb_login">
            <h3 class="omb_authTitle">Please Login</h3>
            <h3 class="omb_authTitle">or</h3>
            <h3 class="omb_authTitle"><a href="../index.php"> Return Home</a></h3>
            <div class="row omb_row-sm-offset-3 omb_socialButtons">
                <div id="my-signin2" data-onsuccess="onSignIn"></div>
<!--                <div class="col-xs-4 col-sm-2">-->
<!--                    <button class="btn btn-lg btn-block omb_btn-facebook fb-login-button" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="true"></button>-->
<!--                </div>-->
<!--                <div class="my-signin2" data-onsuccess="onSignIn">-->
<!--                        <i class="fa fa-google-plus visible-xs"></i>-->
<!--                        <span class="hidden-xs">Google</span>-->
<!--                </div>-->
            </div>
        </div>
    </div>
</body>
</html>
