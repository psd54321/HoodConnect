<?php session_start();?>
<?php
include "connectdb.php";

if(isset($_SESSION["username"])) {
    $originalusername = $_SESSION["username"];
}

$isfriend=0;

$username = $_POST["username"];

$stmt1 = $mysqli->prepare("insert into friend values(?,?,?)");
	$stmt1->bind_param("sss", $originalusername,$username,$isfriend);
	$stmt1->execute();
	$stmt1->fetch();
	$stmt1->close();
	$mysqli->close();

	header("Location:showprofile.php?username=".$username);
	exit();
?>