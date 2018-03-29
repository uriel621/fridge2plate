<?php
ob_start();
//always_populate_raw_post_data = -1
require_once("braintree_init.php");
$amount = $_POST["amount"];
$nonce = $_POST["payment_method_nonce"];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
//$company = $_POST['company'];
$email = $_POST['email'];
//$phone = $_POST['user_phone'];
//$fullName = $_POST['full_name'];
//$maskedNumber = $_POST['card_number'];

$result = Braintree_Transaction::sale([
    'amount' => $amount,
    'paymentMethodNonce' => $nonce,
    'customer' => [
        'firstName' => $first_name,
        'lastName' => $last_name,
        'email' => $email,
    ],
    'options' => [
        'submitForSettlement' => true
    ]
]);
if ($result->success || !is_null($result->transaction)) {
    $transaction = $result->transaction;
    header("Location: transaction.php?id=" . $transaction->id);
} else {
    $errorString = "";

    foreach($result->errors->deepAll() as $error) {
        $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
    }
    $_SESSION["errors"] = $errorString;
    header("Location: index.php");
}
?>