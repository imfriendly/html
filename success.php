<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// needs session_id
if (!isset($_GET['session_id'])) {
    header("Location: payment.php");
    exit();
}

require 'vendor/autoload.php';

$stripe = new \Stripe\StripeClient(
    'sk_test_51QCliyCrqKubrvhKZzLGdQLkJI0wq4SNORlfje28aXCLMIxKxnLOt1V7KtbDR1awPpjwwplHhuFHv4Jii3yf61Op00AOQMGgjr'
);

$checkout_session = $stripe->checkout->sessions->retrieve($_GET['session_id']);

if ($checkout_session->payment_status === 'paid') {
    try {
        $con = include 'database.php';
    } catch (Throwable $th) {
        $_SESSION['message'] = "Server is down";
        header("Location: payment.php");
        exit();
    }

    if ($con->connect_error) {
        $_SESSION['message'] = "Server is down";
        header("Location: payment.php");
        exit();
    }

    // get user_id 
    $stmt = $con->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_id = $result['id'];

    // insert payment record
    $stmt = $con->prepare("INSERT INTO payments (user_id, amount, date_paid) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $transformed_price, $formatted_date]);

    // get current date_expired and if it is in the future add 30 days to it else add 30 days to the current date
    $stmt = $con->prepare("UPDATE users SET date_expired = GREATEST(date_expired, CURRENT_TIMESTAMP) + INTERVAL '30 days' WHERE id = ?");
    $stmt->execute([$user_id]);

    $stmt->close();

    $con->close();

} else {
    // Payment was not successful
    // Redirect the user back to the payment page
    header("Location: payment.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - FriendlyHack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Payment Successful!</h1>
        <p>Thank you for your payment for FriendlyHack. Your transaction was completed successfully.</p>   
        <div class="button-container" style="display:flex;justify-content:center;">
            <button onclick="location.href='index.php'">Go back!</button>
        </div>
    </div>
</body>
</html>