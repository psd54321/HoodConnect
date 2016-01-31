<?php session_start();?>
<html>
<body>
<?php

include "connectdb.php" ;
include "header1.php";
date_default_timezone_set("America/New_York");
$authflag = 0;
$block = array();

if(isset($_GET["username1"])) 
{
    	$username = $_SESSION["username"];
  
}
 
  $stmt = $mysqli->prepare("select n2.nblockname from customerblock c, nblock n1, nblock n2 where c.username =? and c.nblockid=n1.nblockid and n1.hoodid = n2.hoodid and c.enddate is null and c.nblockid!=n2.nblockid ");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->bind_result($blockname);
  while($stmt->fetch()){
  		$block[] = $blockname;
  }
  $stmt->close();

  echo" <form action = 'ChangeBlockFinal.php' method ='GET'>";
  echo "<table>";
  foreach ($block as $singleblock) {
  	echo "<tr>";
  		echo "<td><input type='radio' name = 'radio1' id='radio1' value='$singleblock'>";
  	
  	echo "</td>";
  	echo "<td>$singleblock</td>";
  	echo "</tr>";
  }
echo "</table>";
echo "<input type='hidden' name='username' value='$username'>";
echo "<input type='submit'  value='Submit'>";


?>

</form>
</body>
</html>