<?php session_start(); ?>
<?php
include "connectdb.php";
date_default_timezone_set("America/New_York");

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
}

$stmt = $mysqli->prepare("update customer set lastacessed = ? where username=?");
$stmt->bind_param("ss",date('Y-m-d H:i:s'), $username);
$stmt->execute();
$stmt->fetch();
$stmt->close();
$mysqli->close();

session_destroy();
header('Location: login.php');
exit();
?>