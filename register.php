<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: home.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // have 5 second rate limit
    if (isset($_SESSION['last_request']) && time() - $_SESSION['last_request'] < 1) {
        $_SESSION['message'] = "Rate limit exceeded";
        header("Location: register.php");
        exit();
    }

    $_SESSION['last_request'] = time();    

    $input_email = isset($_POST['email']) ? $_POST['email'] : '';
    $input_username = isset($_POST['username']) ? $_POST['username'] : '';
    $input_password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($input_username) || empty($input_password) || empty($input_email)) {
        $_SESSION['message'] = "Empty username, email or password";
        header("Location: register.php");
        exit();
    }

    if (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email";
        header("Location: register.php");
        exit();
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $input_username)) {
        $_SESSION['message'] = "Username can only contain letters, numbers, and underscores";
        header("Location: register.php");
        exit();
    }

    if (strlen($input_password) < 8) {
        $_SESSION['message'] = "Password must be at least 8 characters";
        header("Location: register.php");
        exit();
    }

    if (strlen($input_password) > 32) {
        $_SESSION['message'] = "Password must be at most 32 characters";
        header("Location: register.php");
        exit();
    }

    if (strlen($input_username) < 4) {
        $_SESSION['message'] = "Username must be at least 4 characters";
        header("Location: register.php");
        exit();
    } 

    try {
        $con = require_once 'database.php';
    } catch (Throwable $th) {
        $_SESSION['message'] = "Server is down";
        header("Location: register.php");
        exit();
    }

    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$input_username]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['message'] = "Username already exists";
        header("Location: register.php");
        exit();
    }

    $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$input_email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['message'] = "Email already in use";
        header("Location: register.php");
        exit();
    }

    $input_password = password_hash($input_password, PASSWORD_ARGON2ID);
    $stmt = $con->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
    $stmt->execute([$input_email, $input_username, $input_password]);

    $_SESSION['message'] = "User registered successfully";

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
    <!-- Auto refresh -->
    <!--<meta http-equiv="refresh" content="5">-->
    <title>Hello World</title>
</head>
<body>
    <header>
        <h1>Friendlyhack</h1>
    </header>

    <main>
        <?php
            if (isset($_SESSION['message'])) {
                echo '<p style="color:red;">' . htmlspecialchars($_SESSION['message']) . '</p>';
                unset($_SESSION['message']);
            }
        ?>

        <form action="register.php" method="post">
            <label for="l_email">Email:</label>
            <input type="email" name="email" id="l_email" Required>
            <label for="l_username">Username:</label>
            <input type="text" name="username" id="l_username" Required>
            <label for="l_password">Password:</label>
            <input type="password" name="password" id="l_password" Required>
            <button id="register">Register</button>
        </form>
        
    </main>

    <footer>

    </footer>
</body>
</html>