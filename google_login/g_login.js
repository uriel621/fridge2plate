function onSignIn(googleUser) {
    // Useful data for your client-side scripts:
    var profile = googleUser.getBasicProfile();
    // console.log("ID: " + profile.getId()); // Don't send this directly to your server!
    // console.log('Full Name: ' + profile.getName());
    // console.log('Given Name: ' + profile.getGivenName());
    // console.log('Family Name: ' + profile.getFamilyName());
    // console.log("Image URL: " + profile.getImageUrl());
    // console.log("Email: " + profile.getEmail());

    // The ID token you need to pass to your backend:
    var id_token = googleUser.getAuthResponse().id_token;
    // console.log("ID Token: " + id_token);

    var sendData = {
        fName: profile.getGivenName(),
        lName: profile.getFamilyName(),
        email: profile.getEmail(),
        idToken: id_token,
        id: profile.getId()
    };

    $.ajax({
        url: 'save_user.php',
        data: sendData,
        method: 'POST',
        cache: false,
        dataType: 'json',
        success: function(resp){
            // console.log(resp);
            window.location.href = "../braintree/index.php";
        }, error: function(resp){
            // console.log(resp);
        }
    });
}

function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
        // console.log('User signed out.');
    });
}
function renderButton() {
    gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'width': 240,
        'height': 50,
        'longtitle': true,
        'onsuccess': onSignIn
    });
}

function applyClickHandlers(){
    renderButton();
    $('#my-signin2').click(onSignIn);
    $('.g-signout').click(signOut);
}

$(document).ready(applyClickHandlers);
