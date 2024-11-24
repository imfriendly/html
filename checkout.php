<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    \Stripe\Stripe::setApiKey('sk_test_51QCliyCrqKubrvhKZzLGdQLkJI0wq4SNORlfje28aXCLMIxKxnLOt1V7KtbDR1awPpjwwplHhuFHv4Jii3yf61Op00AOQMGgjr');

    // Set up a "FriendlyHack" Subscription item that costs £10.00 and is recurring
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card', 'paypal'],
        'line_items' => [ [
            'price_data' => [
                'currency' => 'gbp',
                'product_data' => [
                    'name' => 'FriendlyHack',
                ],
                'unit_amount' => 1000,
            ],
            'quantity' => 1,
        ]
    ],
        'mode' => 'payment',
        'success_url' => 'http://localhostsuccess.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhostpayment.php',
    ]);

    http_response_code(303);
    header("Location: " . $checkout_session->url);
    exit();
}
?>
