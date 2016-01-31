<?php
include "connectdb.php" ;
include "header2.php";

date_default_timezone_set("America/New_York");
$blockname = $_GET["radio1"];
$username  = $_GET["username"];
$authflag = 0;
$enddate=NULL;

if(!empty($_SESSION["username"])) {
  $uname = $_SESSION["username"];
 }

 if(!empty($_SESSION["pwd"])) {
  $pwd = $_SESSION["pwd"];
 }

 if(!empty($_SESSION["fname"])) {
  $fname = $_SESSION["fname"];
 }

 if(!empty($_SESSION["lname"])) {
  $lname = $_SESSION["lname"];
 }

 if(!empty($_SESSION["username"])) {
  $username = $_SESSION["username"];
 }

 if(!empty($_SESSION["profile"])) {
  $profile = $_SESSION["profile"];
 }

 if(!empty($_SESSION["bno"])) {
  $bno = $_SESSION["bno"];
 }
 if(!empty($_SESSION["street"])) {
  $street = $_SESSION["street"];
 }
 if(!empty($_SESSION["zip"])) {
  $zip = $_SESSION["zip"];
 }
 if(!empty($_SESSION["lat"])) {
  $lat = $_SESSION["lat"];
 }
 if(!empty($_SESSION["lng"])) {
  $lng = $_SESSION["lng"];
 }

  $stmt = $mysqli->prepare("insert into customer values(?,?,?,?,?,?,?,?,?,?,?)");
  $stmt->bind_param("sssssssssss", $uname,$fname,$lname,$bno,$street,$zip,$authflag,date('Y-m-d H:i:s'),$profile,$lat,$lng);
  $stmt->execute();
  $stmt->close();

  $stmt1 = $mysqli->prepare("insert into credential values(?,?)");
  $stmt1->bind_param("ss",$uname,$pwd);
  $stmt1->execute();
  $stmt1->close();


  $stmt2 = $mysqli->prepare("select nblockid from nblock where nblockname = ?");
  $stmt2->bind_param("s",$blockname);
  $stmt2->execute();
  $stmt2->bind_result($blockid);
  $stmt2->fetch();
  $stmt2->close();

  $stmt3 = $mysqli->prepare("insert into customerblock values(?,?,?,?)");
  $stmt3->bind_param("ssss",$username,$blockid,date('Y-m-d H:i:s'),$enddate);
  $stmt3->execute();
  $stmt3->close();

  $stmt4 = $mysqli->prepare("select count(*) from customer c1,customer c2,customerblock cb1,customerblock cb2
 where c1.username =cb1.username and cb1.nblockid =cb2.nblockid 
    and c1.username =? and cb2.username =c2.username and c2.authflag =1");
  $stmt4->bind_param("s",$username);
  $stmt4->execute();
  $stmt4->bind_result($numberusers);
  $stmt4->fetch();
  $stmt4->close();

  if($numberusers == 0){
    $stmt5 = $mysqli->prepare("update customer set authflag =1 where customer.username = ?");
    $stmt5->bind_param("s",$username);
    $stmt5->execute();
    $stmt5->close();
  }

  echo "Hurray! Sign up Complete!";
  $mysqli->close();
?>
