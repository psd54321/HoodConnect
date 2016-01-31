<?php session_start();?>

<?php

include "connectdb.php";

$mid=102;
$output="";

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
}

$stmt = $mysqli->prepare("SELECT * from message where mid=?");
$stmt->bind_param("s", $mid);
$stmt->execute();
$stmt->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
$stmt->fetch();
$stmt->close();

$stmt1 = $mysqli->prepare("select c.username,c.cfname,c.clname,c.longi,c.lati from customerblock cb, customer c, nblock b where cb.username = c.username and cb.nblockid = b.nblockid and b.hoodid=? and cb.enddate is null");
$stmt1->bind_param("s", $_SESSION["hoodid"]);
$stmt1->execute();
$stmt1->bind_result($uname,$fname,$lname,$longi,$lati);
while($stmt1->fetch()){
	$output = $output."{$uname},{$fname},{$lname},{$longi},{$lati}";
	$output = $output.":";
}
$stmt1->close();
$output = $output.";";
$mysqli->close();
$output = $output.$mid.",".$title.",".$subject.",".$body.",".$author.",".$dateposted.",".$visibility.",".$refid.",".$longi.",".$lati."?";
echo $output;
?>