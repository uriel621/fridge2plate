<?php session_start(); ?>
<html>
<?php require_once("braintree_head.php"); ?>
<body>
<?php

if (isset($_SESSION['user'])) {
    $f_name = $_SESSION['user']['f_name'];
    $l_name = $_SESSION['user']['l_name'];
    $email = $_SESSION['user']['email'];
}

require_once("braintree_init.php");
    require_once("braintree_header.php");
//    $name = $_SESSION['fullName'];
    if (isset($_GET["id"])) {
        $transaction = Braintree\Transaction::find($_GET["id"]);
//        print("<pre>");
//        print_r($transaction);
//        print("</pre>");
        $transactionSuccessStatuses = [
            Braintree\Transaction::AUTHORIZED,
            Braintree\Transaction::AUTHORIZING,
            Braintree\Transaction::SETTLED,
            Braintree\Transaction::SETTLING,
            Braintree\Transaction::SETTLEMENT_CONFIRMED,
            Braintree\Transaction::SETTLEMENT_PENDING,
            Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT
        ];
        if (in_array($transaction->status, $transactionSuccessStatuses)) {
            $header = "Sweet Success!";
            $icon = "success";
            $message = "Your transaction has been successfully processed. Please click the button below to add your recipe.";
        } else {
            $header = "Transaction Failed";
            $icon = "fail";
            $message = "Your test transaction has a status of " . $transaction->status . ". See the Braintree API response and try again.";
        }
    }
?>
<div class="wrapper">
    <div class="response container">
        <div class="content">
            <div class="icon">
                <img src="<?php echo($icon)?>.svg" alt="">
            </div>

            <h1><?php echo($header)?></h1>
            <section>
                <p><?php echo($message)?></p>
            </section>
            <section>
                <a class="button primary back" href="../db/feature_recipe/index.php">
                    <span>Add Recipe</span>
                </a>
            </section>
        </div>
    </div>
</div>

<aside class="drawer dark" id="print-out">
    <header class="PrintArea">
        <div class="content compact">
            <a href="https://developers.braintreepayments.com" class="braintree" target="_blank">Braintree</a>
            <img src="../images/fridge2plate.png">
            <h3>Receipt</h3>
        </div>
    </header>

    <article class="content compact">
        <section class="PrintArea">
            <h5>Transaction Details</h5>
            <table cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td>Order Number</td>
                    <td><?php echo($transaction->id)?></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><?php echo($f_name ." " .$transaction->customerDetails->lastName)?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo($transaction->customerDetails->email)?></td>
                </tr>
                <tr>
                    <td>Product</td>
                    <td>Feature a Recipe</td>
                </tr>
                <tr>
                    <td>Order Total</td>
                    <td><?php echo("$".$transaction->amount)?></td>
                </tr>
                <tr>
                    <td>Time</td>
                    <td><?php echo($transaction->createdAt->format('Y-m-d H:i:s'))?></td>
                </tr>
<!--                <tr>-->
<!--                    <td>updated_at</td>-->
<!--                    <td>--><?php //echo($transaction->updatedAt->format('Y-m-d H:i:s'))?><!--</td>-->
<!--                </tr>-->
                </tbody>
            </table>
        </section>

<!--        <section>-->
<!--            <h5>Payment</h5>-->
<!---->
<!--            <table cellpadding="0" cellspacing="0">-->
<!--                <tbody>-->
<!--                <tr>-->
<!--                    <td>token</td>-->
<!--                    <td>--><?php //echo($transaction->creditCardDetails->token)?><!--</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>bin</td>-->
<!--                    <td>--><?php //echo($transaction->creditCardDetails->bin)?><!--</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>last_4</td>-->
<!--                    <td>--><?php //echo($transaction->creditCardDetails->last4)?><!--</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>card_type</td>-->
<!--                    <td>--><?php //echo($transaction->creditCardDetails->cardType)?><!--</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>expiration_date</td>-->
<!--                    <td>--><?php //echo($transaction->creditCardDetails->expirationDate)?><!--</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>cardholder_name</td>-->
<!--                    <td>--><?php //echo($transaction->customerDetails->firstName ." " .$transaction->customerDetails->lastName)?><!--</td>-->
<!--                </tr>-->
<!--                </tbody>-->
<!--            </table>-->
<!--        </section>-->

<!--        --><?php //if (!is_null($transaction->customerDetails->id)) : ?>
<!--            <section>-->
<!--                <h5>Customer Details</h5>-->
<!--                <table cellpadding="0" cellspacing="0">-->
<!--                    <tbody>-->
<!--                    <tr>-->
<!--                        <td>id</td>-->
<!--                        <td>--><?php //echo($transaction->customerDetails->id)?><!--</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>first_name</td>-->
<!--                        <td>--><?php //echo($transaction->customerDetails->firstName)?><!--</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>last_name</td>-->
<!--                        <td>--><?php //echo($transaction->customerDetails->lastName)?><!--</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>email</td>-->
<!--                        <td>--><?php //echo($transaction->customerDetails->email)?><!--</td>-->
<!--                    </tr>-->
<!--                    </tbody>-->
<!--                </table>-->
<!--            </section>i-->
<!--        --><?php //endif; ?>

<!--        <section>-->
<!--            <p class="center small">Integrate with the Braintree SDK for a secure and seamless checkout</p>-->
<!--        </section>-->

        <section>
            <button id="print_button" class="button secondary full" type="button">
                <span>Print</span>
            </button>
        </section>
    </article>
</aside>


</body>
</html>
