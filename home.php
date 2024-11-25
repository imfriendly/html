<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

try {
    $con = include 'database.php';
} catch (Throwable $th) {
    exit();
}

// Get the username from the session
$user = $_SESSION['username'];

// Query to get the date_expired from the database
$stmt = $con->prepare("SELECT date_expired FROM users WHERE username = ?");
$stmt->execute([$user]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$date_expired = $result ? $result['date_expired'] : null;
$_SESSION['date_expired'] = $date_expired;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Home</title>
</head>
<body>
    <header>
        <h3>Hello <?php echo htmlspecialchars($_SESSION['username']); ?></h3>

        <div style="position:absolute;top:10px;right:10px;color:paleturquoise">
            <?php
            // Assuming you have the date_expired stored in the session
            if (isset($_SESSION['date_expired'])) {
                echo "Expires on: " . htmlspecialchars($_SESSION['date_expired']);
            } else {
                echo "No expiration date set.";
            }
            ?>
        </div>

        <h1>Friendlyhack</h1>
    </header>

    <main>
        <div class="button-container" style="display:flex;justify-content:center;flex-direction:column">
            <button onclick="location.href='download.php'">Download</button> 
            <button onclick="location.href='payment.php'">Payment</button>
            <button onclick="location.href='information.php'">Information</button>
        </div>
    </main>

    <footer>
        <a href="logout.php">Logout</a>
    </footer>

</body>
</html>