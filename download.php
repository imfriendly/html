<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

try {
    $con = require 'database.php';
} catch (Throwable $th) {
    $_SESSION['message'] = "Server is down";
    header("Location: home.php");
    exit();
}

// Single query to check date_expired
$stmt = $con->prepare("SELECT date_expired FROM users WHERE username = ? AND date_expired > NOW()");
$stmt->execute([$_SESSION['username']]);

// If no valid subscription found, redirect to payment
if ($stmt->rowCount() == 0) {
    header("Location: payment.php");
    exit();
}

// Continue with download logic...

$file = 'release.7z';

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit();
} else {
    echo "File not found.";
}

?>