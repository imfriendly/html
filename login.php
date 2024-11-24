<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: home.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['last_request']) && time() - $_SESSION['last_request'] < 1) {
        $_SESSION['message'] = "Rate limit exceeded";
        header("Location: register.php");
        exit();
    }

    $_SESSION['last_request'] = time();    

    $input_username = isset($_POST['username']) ? $_POST['username'] : '';
    $input_password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($input_username) || empty($input_password)) {
        $_SESSION['message'] = "Empty username or password";
        header("Location: index.php");
        exit();
    }

    if (strlen($input_password) < 8) {
        $_SESSION['message'] = "Password must be at least 8 characters";
        header("Location: index.php");
        exit();
    }

    if (strlen($input_username) < 4) {
        $_SESSION['message'] = "Username must be at least 4 characters";
        header("Location: index.php");
        exit();
    }
    

    try {
        $con = include 'database.php';
    } catch (Throwable $th) {
        $_SESSION['message'] = "Server is down";
        header("Location: index.php");
        exit();
    }

    $stmt = $con->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$input_username]);

    if (!$stmt) {
        $_SESSION['message'] = "Username or password not found";
        header("Location: index.php");
        exit();
    }

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $_SESSION['message'] = "Username or password not found";
        header("Location: index.php");
        exit();
    }

    if (password_verify($input_password, $result['password'])) {
        session_regenerate_id();
        $_SESSION['username'] = $input_username;
        header("Location: home.php");
        exit();
    } else {
        $_SESSION['message'] = "Username or password not found";
        header("Location: index.php");
        exit();
    }
}
?>