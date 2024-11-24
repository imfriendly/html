<?php
    session_start();
    if (isset($_SESSION['username'])) {
        header('Location: home.php');
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

            // check if pdo server work
        ?>

        <form action="login.php" method="post">
            <label for="l_username">Username:</label>
            <input type="text" name="username" id="l_username" Required>
            <label for="l_password">Password:</label>
            <input type="password" name="password" id="l_password" Required>
            <button id="login">Login</button>
        </form>

        <br>
        
        <label for="register">Don't have an account?</label>
        <button id="register" onclick="location.href='register.php'">Register</button>

        
    </main>

    <footer>

    </footer>
</body>
</html>