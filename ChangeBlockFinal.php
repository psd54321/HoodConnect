<?php
include "connectdb.php" ;
include "header2.php";
date_default_timezone_set("America/New_York");
$blockname = $_GET["radio1"];
$username  = $_GET["username"];
$enddate = NULL;

  $stmt2 = $mysqli->prepare("select nblockid from nblock where nblockname = ?");
  $stmt2->bind_param("s",$blockname);
  $stmt2->execute();
  $stmt2->bind_result($blockid);
  $stmt2->fetch();
  $stmt2->close();

  $stmt3 = $mysqli->prepare("update  customerblock set enddate =? where enddate is null and username = ?");
  $stmt3->bind_param("ss",date('Y-m-d H:i:s'),$username);
  $stmt3->execute();
  $stmt3->close();
  
  $stmt3 = $mysqli->prepare("insert into customerblock values(?,?,?,?)");
  $stmt3->bind_param("ssss",$username,$blockid,date('Y-m-d H:i:s'),$enddate);
  $stmt3->execute();
  $stmt3->close();
  
  $stmt1 = $mysqli->prepare("update customer set authflag =0 where username = ?");
    $stmt1->bind_param("s",$username);
    $stmt1->execute();
    $stmt1->close();
	
	$stmt6 = $mysqli->prepare("delete from memberauth where requester = ?");
    $stmt6->bind_param("s",$username);
    $stmt6->execute();
    $stmt6->close();

  $stmt4 = $mysqli->prepare("select count(*) from customer c1,customer c2,customerblock cb1,customerblock cb2
 where c1.username =cb1.username and cb1.nblockid =cb2.nblockid 
    and c1.username =? and cb2.username =c2.username and c2.authflag =1 and cb2.enddate is not null");
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
  else
  {
 
  }
 
  echo "Applied to a block Successfully!";
  $mysqli->close();
?>
