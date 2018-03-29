<?php
//session_start();
require_once("../vendor/autoload.php");

if(file_exists(__DIR__ . "../.env")) {
    $dotenv = new Dotenv\Dotenv(__DIR__ . "/../");
    $dotenv->load();
}
Braintree\Configuration::environment('sandbox');
Braintree\Configuration::merchantId('9pbmh9skjwrmy4t8');
Braintree\Configuration::publicKey('gqvjqscn5rzj5t3k');
Braintree\Configuration::privateKey('78c9f31216005c7ed79ed9197862c7a8');
//Braintree\Configuration::merchantAccountId('learningfuze');