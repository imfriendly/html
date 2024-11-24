<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Payment Page</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <main>
        <h1>Pay for FriendlyHack</h1>
        <p>Please read and accept our terms of service before making a payment.</p>
        <form action="checkout.php" method="post">
        <label>
                <input type="checkbox" id="terms" name="terms" Required>
                I accept the terms of service
        </label>
            <button>Pay</button>
        </form>
    </main>
    

    <footer>
        <a href="logout.php">Logout</a>
    </footer>
</body>
</html>