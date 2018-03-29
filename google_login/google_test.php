<!--<!DOCTYPE html>-->
<!--<html>-->
<!--<head>-->
<!--    <title>Google Login Test</title>-->
<!--    <meta charset="UTF-8">-->
<!--    <meta name="google-signin-client_id" content="802478348342-ovn07tr2ulnqnqk06j94cga951pufnib.apps.googleusercontent.com">-->
<!--    <script src="https://apis.google.com/js/platform.js" async defer>-->
<!--    </script>-->
<!--</head>-->
<!--<body>-->
<!--<div class="g-signin2" data-longtitle="true" data-onsuccess="Google_signIn" data-theme="light" data-width="200"></div>-->
<!--    <script>-->
<!--        /* After you have signed in a user with Google using the default scopes, you can access the user's Google ID, name, profile URL, and email address.-->
<!---->
<!--        To retrieve profile information for a user, use the getBasicProfile() method.-->
<!---->
<!--        *** Note: To use the getBasicProfile() method, the fetch_basic_profile parameter of gapi.auth2.init() must be set to true (the default value).-->
<!--        */-->
<!---->
<!--        function onSignIn(googleUser) {-->
<!--            var profile = googleUser.getBasicProfile();-->
<!--            console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.-->
<!--            console.log('Name: ' + profile.getName());-->
<!--            console.log('Image URL: ' + profile.getImageUrl());-->
<!--            console.log('Email: ' + profile.getEmail());-->
<!--        }-->
<!---->
<!---->
<!--        /* *** Important: Do not use the Google IDs returned by getId() or the user's profile information to communicate the currently signed in user to your backend server. Instead, send ID tokens, which can be securely validated on the server. */-->
<!--        if (auth2.isSignedIn.get()) {-->
<!--            var profile = auth2.currentUser.get().getBasicProfile();-->
<!--            console.log('ID: ' + profile.getId());-->
<!--            console.log('Full Name: ' + profile.getName());-->
<!--            console.log('Given Name: ' + profile.getGivenName());-->
<!--            console.log('Family Name: ' + profile.getFamilyName());-->
<!--            console.log('Image URL: ' + profile.getImageUrl());-->
<!--            console.log('Email: ' + profile.getEmail());-->
<!--        }-->
<!---->
<!--       /* If you want to only sign in the user and do not need to get details other than the user ID, you can disable retrieving basic profile information and request only the profile scope. */-->
<!---->
<!--        gapi.load('auth2', function() {-->
<!--            auth2 = gapi.auth2.init({-->
<!--                client_id: '802478348342-ovn07tr2ulnqnqk06j94cga951pufnib.apps.googleusercontent.com',-->
<!--                fetch_basic_profile: true,-->
<!--                scope: 'profile'-->
<!--            });-->
<!---->
<!--            // Sign the user in, and then retrieve their ID.-->
<!--            auth2.signIn().then(function() {-->
<!--                console.log(auth2.currentUser.get().getId());-->
<!--            });-->
<!--        });-->
<!--    </script>-->
<!---->
<!--<!--    You can enable users to sign out of your app without signing out of Google by adding a sign-out button or link to your site. To create a sign-out link, attach a function that calls the GoogleAuth.signOut() method to the link's onclick event. -->
<!---->
<!--    <script>-->
<!--        function signOut() {-->
<!--            var auth2 = gapi.auth2.getAuthInstance();-->
<!--            auth2.signOut().then(function () {-->
<!--                console.log('User signed out.');-->
<!--            });-->
<!--        }-->
<!--    </script>-->
<!--    <a href="#" onclick="signOut();">Sign out</a>-->
<!--</body>-->

<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
    <script src="https://apis.google.com/js/api:client.js"></script>
    <script>
        var googleUser = {};
        var startApp = function() {
            gapi.load('auth2', function(){
                // Retrieve the singleton for the GoogleAuth library and set up the client.
                auth2 = gapi.auth2.init({
                    client_id: '802478348342-ovn07tr2ulnqnqk06j94cga951pufnib.apps.googleusercontent.com',
                    cookiepolicy: 'single_host_origin'
                    // Request scopes in addition to 'profile' and 'email'
//                    scope: 'profile email'
                });
                attachSignin(document.getElementById('customBtn'));
            });
        };

        function attachSignin(element) {
            console.log(element.id);
            auth2.attachClickHandler(element, {},
                function(googleUser) {
                    document.getElementById('name').innerText = "Signed in: " +
                        googleUser.getBasicProfile().getName();
                }, function(error) {
                    alert(JSON.stringify(error, undefined, 2));
                });
        }
    </script>
    <style type="text/css">
        #customBtn {
            display: inline-block;
            background: white;
            color: #444;
            width: 190px;
            border-radius: 5px;
            border: thin solid #888;
            box-shadow: 1px 1px 1px grey;
            white-space: nowrap;
        }
        #customBtn:hover {
            cursor: pointer;
        }
        span.label {
            font-family: serif;
            font-weight: normal;
        }
        span.icon {
            background: url('signin_button.png') transparent 5px 50% no-repeat;
            display: inline-block;
            vertical-align: middle;
            width: 42px;
            height: 42px;
        }
        span.buttonText {
            display: inline-block;
            vertical-align: middle;
            padding-left: 42px;
            padding-right: 42px;
            font-size: 14px;
            font-weight: bold;
            /* Use the Roboto font that is loaded in the <head> */
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body>
<!-- In the callback, you would hide the gSignInWrapper element on a
successful sign in -->
<div id="gSignInWrapper">
    <span class="label">Sign in with:</span>
    <div id="customBtn" class="customGPlusSignIn">
        <span class="icon"></span>
        <span class="buttonText">Google</span>
    </div>
</div>
<div id="name"></div>
<script>startApp();</script>
</body>
</html>