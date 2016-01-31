<?php session_start();?>
<?php
include "connectdb.php";

if(isset($_SESSION["username"])) {
    $originalusername = $_SESSION["username"];
}


$username = $_POST["username"];

$stmt1 = $mysqli->prepare("insert into neighbour values(?,?)");
	$stmt1->bind_param("ss", $originalusername,$username);
	$stmt1->execute();
	$stmt1->fetch();
	$stmt1->close();
	$mysqli->close();

	header("Location:showprofile.php?username=".$username);
	exit();
?>