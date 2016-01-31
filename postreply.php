<?php session_start();?>
<?php
include "connectdb.php";

date_default_timezone_set("America/New_York");

if(isset($_SESSION["toid"])) {
   $toid = $_SESSION["toid"];
    }

  if(isset($_SESSION["username"])) {
   $username = $_SESSION["username"];
    }

   $url= $_SESSION['messageurl'];

  $reply = $_POST['replytext'];

  $stmt4 = $mysqli->prepare("select max(rid) from reply");
  $stmt4->execute();
   $stmt4->bind_result($rid);
   $stmt4->fetch();
  $stmt4->close();
   $rid=$rid +1;

   

  	$stmt = $mysqli->prepare("insert into reply values(?,?,?,?,?)");
	$stmt->bind_param("sssss", $rid,$toid,date('Y-m-d H:i:s'),$username,$reply);
	$stmt->execute();
	$stmt->close();
	$mysqli->close();
	header("Location:showmessage.php?mid=".$toid);
    exit();?>