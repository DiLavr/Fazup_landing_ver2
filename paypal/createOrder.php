<?php
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

function getApiContext()
{
    $clientId = 'ARoQepQNWo7H_8l5OFX5M05HbnITn1pmvLx0KeFNfBNcdch9GBEKSXAOv0ppzo7iWtZL8aALQbs-DpFf';
    $clientSecret = 'ECdXvM-Asa98_Nm-ut6CwXMBFcpZ-M7YlnPpNaPb533VKuuz7ZF_xhsJASZ8omjiWcTov4SWYtSE0Cv_';

    $apiContext = new ApiContext(
        new OAuthTokenCredential($clientId, $clientSecret)
    );

    $apiContext->setConfig([
        'mode' => 'sandbox', // Или 'live', если вы готовы к продакшену
    ]);

    return $apiContext;
}

use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;

$apiContext = getApiContext();

list($product, $price) = explode('-', $_POST['product_option']);

if ($product === 'Fazup Silver Patch') {
    $price = 29.95;
} elseif ($product === 'Fazup Silver 2x Pack') {
    $price = 47.95;
} elseif ($product === 'Fazup Gold Patch') {
    $price = 33.95;
}

$payer = new Payer();
$payer->setPaymentMethod('paypal');

$item = new Item();
$item->setName($product)
    ->setCurrency('USD')
    ->setQuantity(1)
    ->setPrice($price);

$itemList = new ItemList();
$itemList->setItems([$item]);

$amount = new Amount();
$amount->setCurrency('USD')
    ->setTotal($price);

$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription("Payment for product: $product");

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl("http://example.com/success.php")
    ->setCancelUrl("http://example.com/cancel.php");

$payment = new Payment();
$payment->setIntent('sale')
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions([$transaction]);

try {
    $payment->create($apiContext);
    header("Location: " . $payment->getApprovalLink());
} catch (PayPal\Exception\PayPalConnectionException $ex) {
    echo "An error occurred: " . $ex->getMessage();
    exit(1);
}
