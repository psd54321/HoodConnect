<?php session_start();?>

<?php

include "connectdb.php";

if(isset($_POST["username"])) {
    $username = $_POST["username"];
}

$stmt = $mysqli->prepare("SELECT longi,lati from customer where username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($longi,$lati);
$stmt->fetch();
$stmt->close();

$stmt = $mysqli->prepare("select cb.nblockid,nlongi,nlati,slongi,slati from nblock b , customerblock cb where cb.username = ? and b.nblockid=cb.nblockid and cb.enddate is null");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($blockid,$nlongi,$nlati,$slongi,$slati);
$stmt->fetch();
$stmt->close();


$mysqli->close();
echo "abc,$longi,$lati,$nlongi,$nlati,$slongi,$slati";
?>