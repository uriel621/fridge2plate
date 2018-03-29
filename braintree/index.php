<?php
session_start();
$redirect_uri = "https://" . $_SERVER['HTTP_HOST'] . "/google_login/g_login.php";

if (isset($_SESSION['user'])) {
    $f_name = $_SESSION['user']['f_name'];
    $l_name = $_SESSION['user']['l_name'];
    $email = $_SESSION['user']['email'];
} else {
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
require_once("braintree_init.php");
?>

<html>

<?php require_once("braintree_head.php"); ?>

<body>
<div class="wrapper">
    <?php require_once("braintree_header.php"); ?>
    <div class="checkout container">
        <h1 id="payment-header">Payment</h1>
        <form method="post" id="payment-form" action="checkout.php">
            <section id="top-section">
                <label for="first_name">
                    <span class="input-label">First Name</span>
                    <div class="input-wrapper first-name-wrapper">
                        <input type="text" name="first_name" value="<?= $f_name; ?>" placeholder="First Name"
                               id="first_name" autocomplete="off"/>
                    </div>
                </label>
                <label for="l_name" class="middle_border_fix">
                    <span class="input-label">Last Name</span>
                    <div class="input-wrapper last-name-wrapper">
                        <input type="text" name="last_name" value="<?= $l_name; ?>" placeholder="Last Name"
                               id="last_name"/>
                    </div>
                </label>
                <label for="email">
                    <span class="input-label">Email</span>
                    <div class="input-wrapper email-wrapper">
                        <input type="text" name="email" value="<?= $email; ?>" placeholder="Email" id="email"/>
                    </div>
                </label>
            </section>
            <h3>Choose a payment method</h3>
            <section id="bottom-section">
                <div class="bt-drop-in-wrapper">
                    <div id="dropin-container"></div>
                </div>
                <label for="amount">
                    <span class="input-label">Amount</span>
                    <div class="input-wrapper amount-wrapper">
                        <input id="amount" name="amount" type="tel" min="10" placeholder="Amount" value="10">
                    </div>
                </label>
            </section>

            <button id="submit_button" class="button" type="submit" onclick=""><span>Submit</span></button>
        </form>
    </div>
</div>
<script src="https://js.braintreegateway.com/js/braintree-2.27.0.min.js"></script>
<script>
    var checkout = new Demo({
        formID: 'payment-form'
    });
    var client_token = "<?php echo(Braintree\ClientToken::generate([
    ])); ?>";
    braintree.setup(client_token, 'dropin', {
        container: 'dropin-container',
        paypal: {
            button: {
                type: 'checkout'
            }
        }
    });
</script>

</body>
</html>