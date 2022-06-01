<?php
session_start();

if (!isset($_SESSION['UserData']['Username'])) {
    header("location:login.php");
    exit;
}
?>
Congratulation! You have logged into password protected page.<a href="logout.php">Click here</a> to Logout.