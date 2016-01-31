<?php session_start();?>
<?php
include "connectdb.php";
date_default_timezone_set("America/New_York");


$stmt4 = $mysqli->prepare("select max(mid) from message");
  $stmt4->execute();
   $stmt4->bind_result($mid);
   $stmt4->fetch();
  $stmt4->close();
$mid=$mid +1;



if(!empty($_SESSION["username"])) {
  $username = $_SESSION["username"];
 }

if(!empty($_POST["title"])) {
  $title = $_POST["title"];
 }

 if(!empty($_POST["subject"])) {
  $subject = $_POST["subject"];
 }

 if(!empty($_POST["mbody"])) {
  $body = $_POST["mbody"];
 }

 if(!empty($_POST["refcombo"])) {
  $refid = $_POST["refcombo"];
 }
 if(!empty($_POST["visibilitycombo"])) {
  $visibility = $_POST["visibilitycombo"];
 }

 if($visibility == 'Block'){
 	$refid = $_SESSION["nblockid"];
 	$visibility = "B";
 	$stmt = $mysqli->prepare("insert into message values(?,?,?,?,?,?,?,?)");
	$stmt->bind_param("ssssssss", $mid,$title,$subject,$body,$username, date('Y-m-d H:i:s'),$visibility,$refid);
	$stmt->execute();
	$stmt->close();
	$mysqli->close();
	header("Location:homepage.php");
    exit();
  }
  elseif($visibility == 'Neighbourhood'){

  	$refid = $_SESSION["hoodid"];
  	$visibility = "H";

 	$stmt1 = $mysqli->prepare("insert into message values(?,?,?,?,?,?,?,?)");
	$stmt1->bind_param("ssssssss", $mid,$title,$subject,$body,$username, date('Y-m-d H:i:s'),$visibility,$refid);
	$stmt1->execute();
	$stmt1->close();
	$mysqli->close();
	header("Location:homepage.php");
    exit();
  }
  elseif($visibility == 'Friend'){

  	$visibility="F";

 	$stmt2 = $mysqli->prepare("insert into message values(?,?,?,?,?,?,?,?)");
	$stmt2->bind_param("ssssssss", $mid,$title,$subject,$body,$username, date('Y-m-d H:i:s'),$visibility,$refid);
	$stmt2->execute();
	$stmt2->close();
	$mysqli->close();
	header("Location:homepage.php");
    exit();
  }
  else{

  	$visibility="N";

  	$stmt3 = $mysqli->prepare("insert into message values(?,?,?,?,?,?,?,?)");
	$stmt3->bind_param("ssssssss", $mid,$title,$subject,$body,$username, date('Y-m-d H:i:s'),$visibility,$refid);
	$stmt3->execute();
	$stmt3->close();
	$mysqli->close();
	header("Location:homepage.php");
    exit();
  }
?>