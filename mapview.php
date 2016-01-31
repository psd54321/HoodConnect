<?php session_start();?>
<?php
include "header1.php";?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="myjs.js"></script>
<?php

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
}

echo "<div class='container'>";//main div
	echo "<h3>Welcome $fname!</h3>";
	echo "<div class='row'>";//row div
	echo "<div id='navigation-side' role='navigation' class='span navigation-column overthrow'>";
	echo "<ul 'class='nav nav-pills nav-stacked' style='background=;'><li style='width:100%;'><a href='homepage.php'>Home</a></li><li><a href='mapview.php'>Map View</a></li><li><a href ='showprofile.php?username=".$username." ' > My Profile </a> </li><li><a href ='ChangeBlock.php?username1=".$username." ' > Change Block </a> </li></ul>";
	echo "</div>";//sidenav div end
	//Post Message
	echo "<div class='span single-column' role='main' >";

	echo "<div>Please click on the marker to view info</div>";

	echo "<div id='messagemap'> </div>";


	echo "</div>";//single column content div end
    echo "</div>";//row div end
    echo "</div>";//main div end

    echo "</script><script type='text/javascript'>
   fetchmessagelocation();
</script>";
?>