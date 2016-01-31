<?php session_start();?>
<html>
<body>
<?php
include "connectdb.php" ;
include "header2.php";
date_default_timezone_set("America/New_York");
$authflag = 0;
$block = [];
if(!empty($_POST["username"])) {
  $_SESSION["username"] = $_POST["username"];
  $uname = $_POST["username"];
 }

 if(!empty($_POST["pwd"])) {
  $_SESSION["pwd"] = $_POST["pwd"];
 }

 if(!empty($_POST["fname"])) {
  $_SESSION["fname"] = $_POST["fname"];
 }

 if(!empty($_POST["lname"])) {
  $_SESSION["lname"] = $_POST["lname"];
 }

 if(!empty($_POST["username"])) {
  $username = $_POST["username"];
 }

 if(!empty($_POST["profile"])) {
  $_SESSION["profile"] = $_POST["profile"];
 }

 if(!empty($_POST["bno"])) {
  $_SESSION["bno"] = $_POST["bno"];
 }

 if(!empty($_POST["street"])) {
  $_SESSION["street"] = $_POST["street"];
 }
 if(!empty($_POST["zip"])) {
  $_SESSION["zip"] = $_POST["zip"];
 }

 if(!empty($_POST["lat"])) {
  $_SESSION["lat"] = $_POST["lat"];
  $lat = $_POST["lat"];
 }

 if(!empty($_POST["lng"])) {
  $_SESSION["lng"] = $_POST["lng"];
  $lng = $_POST["lng"];
 }

  $stmt3 = $mysqli->prepare("select nblockname,hoodid,nblockid from nblock where nlongi >= ? and nlati >= ? and slongi <= ? and slati <= ? ");
  $stmt3->bind_param("ssss",$lat,$lng,$lat,$lng);
  $stmt3->execute();
  $stmt3->bind_result($blockname,$hoodid,$blockid);
  if($stmt3->fetch()){
  		$block[] = $blockname;
  }
  $stmt3->close();


  $stmt2 = $mysqli->prepare("select nblockname from nblock where hoodid = ? and not(nblockid = ?)");
  $stmt2->bind_param("ss",$hoodid,$blockid);
  $stmt2->execute();
  $stmt2->bind_result($blocknames);
  while($stmt2->fetch()){
  	$block[] = $blocknames;
  }
  $stmt2->close();
  echo" <form action = 'FinalRegPage.php' method ='GET'>";
  echo "<table>";
  foreach ($block as $singleblock) {
  	echo "<tr>";
  	if($blockname == $singleblock){
  		echo "<td><input type='radio' name = 'radio1' id='radio1' checked='checked' value='$singleblock'>&nbsp;&nbsp;<-Suggested Block";
  	}
  	else{
  		echo "<td><input type='radio' name = 'radio1' id='radio1' value='$singleblock'>";
  	}
  	echo "</td>";
  	echo "<td>$singleblock</td>";
  	echo "</tr>";
  }
echo "</table>";
echo "<input type='hidden' name='username' value='$uname'>";
echo "<input type='submit'  value='Submit'>";


?>

</form>
</body>
</html>