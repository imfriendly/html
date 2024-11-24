<?php
function getDatabaseConnection() {
    $dsn = "pgsql:host=localhost;port=5432;dbname=friendlyhack;";
    $username = "azeem";
    $password = "ILoveComputers-=[]2000";

    try {
        $conn = new PDO($dsn, $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // make persistent connection
        $conn->setAttribute(PDO::ATTR_PERSISTENT, true);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    return $conn;
}

return getDatabaseConnection();
?>
