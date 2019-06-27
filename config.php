<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include('vendor/autoload.php');


$btSettings = [
    "test_mode"   => "on",
    "merchant_id" => "jszbnz6jgstn85rp",
    "merchant_account_id" => "cb-local-test",
    "public_key"  => "x8xhgh2t68zz83fp",
    "private_key" => "ce975729cbad5bf5ab882a289818668e",
];



if ($btSettings['test_mode'] == "on")
{
    Braintree_Configuration::environment('sandbox');
}
else
{
    Braintree_Configuration::environment('production');
}

Braintree_Configuration::merchantId($btSettings["merchant_id"]);
Braintree_Configuration::publicKey($btSettings["public_key"]);
Braintree_Configuration::privateKey($btSettings["private_key"]);

