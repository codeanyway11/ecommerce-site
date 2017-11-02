<?php
require_once 'core/init.php';

$token = $_POST['stripeToken'];
$full_name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$state = sanitize($_POST['state']);
$zip_code = sanitize($_POST['zip_code']);
$country = sanitize($_POST['country']);
$tax = sanitize($_POST['tax']);
$sub_total = sanitize($_POST['sub_total']);
$grand_total = sanitize($_POST['grand_total']);
$cart_id = sanitize($_POST['cart_id']);
$description = sanitize($_POST['description']);
$charge_amount = number_format($grand_total, 2) * 100;
$metadata = array(
    'cart_id' => $cart_id,
    'tax' => $tax,
    'sub_total' => $sub_total,

);


/**
* Check stripe information.
*/
\Stripe\Stripe::setApiKey('sk_test_uIGZfV43XXol1FOSzi7xrLlx');

try {
    $charge = \Stripe\Charge::create([
        'amount'  => $charge_amount,
        'source' => $token,
        'currency'   => CURRENCY,
        'description' => $description,
        'reciept_email' => $email,
        'metadata' => $metadata
    ]);
    $db->query("UPDATE cart SET paid =1 WHERE id ='{$cart_id}'");
    $db->query("INSERT INTO transactions (charge_id, cart_id, full_name, email, street, street2, city, state, zip_code, country, sub_total, tax, grand_total, description, txn_type) VALUES ('$charge->id', '$cart_id', '$full_name', '$email', '$street', '$street2', '$city', '$state', '$zip_code', '$country', '$sub_total', '$tax', '$grand_total', '$description', '$charge->object')");

    $domain= ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST'] :false;
    setcookie(CART_COOKIE, '', 1,'/', $domain, false);
    include 'includes/head.php';
    include 'includes/navigation.php';
    include 'includes/headerpartial.php';
    ?>
    <h1 class="text-center text-success">Thank You!</h1>
    <p>Your card has been successfully charged <?=money($grand_total);?></p>
    <p>Your reciept number is <strong><?=$cart_id;?></strong></p>
    <?php


}
catch(\Stripe\Error\Card $e) {
    echo $e;
}
?>
