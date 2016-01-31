<?php session_start();?>
<?php
include "header1.php";
include "connectdb.php";?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="myjs.js"></script>
<?php

$username = $_GET["username"];

if(isset($_SESSION["username"])) {
    $originalusername = $_SESSION["username"];
}

echo "<div class='container'>";//main div
	echo "<h3>Welcome $fname!</h3>";
	echo "<div class='row'>";//row div
	echo "<div id='navigation-side' role='navigation' class='span navigation-column overthrow'>";
	echo "<ul 'class='nav nav-pills nav-stacked' style='background=;'><li style='width:100%;'><a href='homepage.php'>Home</a></li><li><a href='mapview.php'>Map View</a></li><li><a href ='showprofile.php?username=".$username." ' > My Profile </a> </li><li><a href ='ChangeBlock.php?username1=".$username." ' > Change Block </a> </li></ul>";
	echo "</div>";//sidenav div end
	//Post Message
	echo "<div class='span single-column' role='main' >";
	echo "<div id='userdiv1'style='display:none;'><input type ='hidden' id ='userdiv' value='$username'></div>";

	$stmt = $mysqli->prepare("SELECT * from customer where username=?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$stmt->bind_result($uname,$fname,$lname,$bno,$street,$zip,$authflag,$lastaccessed,$userprofile,$longi,$lati);
	$stmt->fetch();
	$stmt->close();

	echo "<div class='rich-profile'>";// rich profile div
	echo "<div class='page-header'>";
	echo "<div class='profile-portrait' >";
	echo "<div class='profile-meta-information'>";
	echo "<div class='name-line'><h1>$fname&nbsp;$lname</h1></div>";
	echo "<div class='address-line'>$bno,$street Street,$zip</div>";

	echo "</div>";
	echo "</div>";
	echo "</div>";

	
	
	if($originalusername != $username)	{
	$stmt1 = $mysqli->prepare("SELECT 1 from friend where (user=? and friendof=?) or (user=? and friendof=?) and isfriend=1");
	$stmt1->bind_param("ssss", $originalusername,$username,$username,$originalusername);
	$stmt1->execute();
	$stmt1->bind_result($friendstatus);
	$stmt1->fetch();
	$stmt1->close();


	$stmt6 = $mysqli->prepare("SELECT 1 from friend where user=? and friendof=? and isfriend=0");
	$stmt6->bind_param("ss", $originalusername,$username);
	$stmt6->execute();
	$stmt6->bind_result($pendingrequest);
	$stmt6->fetch();
	$stmt6->close();

	if($friendstatus == 1 and !$pendingrequest == 1){
		echo "<br/>You are friends with ".$username;
	}
	if ($pendingrequest==1) {
		echo "<br/>Your friend request is yet to be accepted by ".$username;
	}
	if(!$friendstatus ==1 and !$pendingrequest == 1){
		echo "<form action='addfriend.php' method='post'><br/><input type='hidden' name='username' value='$username'><input type='submit' value='Add as Friend'> </form>";
	}

	$stmt2 = $mysqli->prepare("SELECT 1 from neighbour where user=? and neighbourof=?");
	$stmt2->bind_param("ss", $originalusername,$username);
	$stmt2->execute();
	$stmt2->bind_result($neighbourstatus);
	$stmt2->fetch();
	$stmt2->close();

	if(!empty($neighbourstatus)){
		echo "<br/>You are neighbours with ".$username;
	}
	else{
		echo "<form action='addneighbour.php' method='post'><br/><input type='hidden' name='username' value='$username'><input type='submit' value='Add as Neighbour'> </form>";
	}


	}

	echo "<br/><div class ='section-heading'>About</div>";
	echo "<div class=''>$userprofile</div>";
	echo "<br/><div class ='section-heading'>Location</div>";
	echo "<div id='usermap'> </div>";

	echo "</div>";//rich profile div end
	echo "</div>";//single column content div end
    echo "</div>";//row div end
    echo "</div>";//main div end
    $mysqli->close();
    echo "</script><script type='text/javascript'>fetchuserlocation();</script>";
?>