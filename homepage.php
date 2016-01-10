<?php session_start();?>
<?php 
include "connectdb.php";
include "header1.php";?>
<script type="text/javascript" src="myjs.js"></script>
<?php

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    echo "<div style='display:none;'><input type ='hidden'id ='userdiv' value='$username'></div>";
}


//$mysqli = new mysqli("localhost", "root", "Hmq2V0eG", "hoodconnect");
$stmt = $mysqli->prepare("SELECT authflag,cfname from customer where username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($authflag,$fname);
$stmt->fetch();
$stmt->close();
if($authflag == 0){
	echo"Your request to join the block has not yet been approved. Please check again later.";
}
else{
	$stmt6 = $mysqli->prepare("select cb.nblockid,b.hoodid from customerblock cb,nblock b where cb.username=? and cb.nblockid=b.nblockid");
	$stmt6->bind_param("s", $username);
	$stmt6->execute();
	$stmt6->bind_result($nblockid,$hoodid);
	$stmt6->fetch();
	$stmt6->close();
	$_SESSION["nblockid"] = $nblockid;
	$_SESSION["hoodid"] = $hoodid;
	//$mysqli1 = new mysqli("localhost", "root", "Hmq2V0eG", "hoodconnect");

	echo "<div class='container'>";//main div
	echo "<h3>Welcome $fname!</h3>";
	echo "<div class='row'>";//row div
	echo "<div id='navigation-side' role='navigation' class='span navigation-column overthrow'>";
	echo "<ul 'class='nav nav-pills nav-stacked' style='background=;'><li style='width:100%;'><a href='homepage.php'>Home</a></li><li><a href='mapview.php'>Map View</a></li></ul>";
	echo "</div>";//sidenav div end
	//Post Message
	echo "<div class='span single-column' role='main' >";//single column div with content start
	echo "<div id='messagecontainer' >";
	echo "<form action='postmessage.php' method='POST'><div id='errordiv' style='display:none;'></div>";
	echo "<div><input style='width:173px;' type='text' name='title' id='title' placeholder='Enter title' required>";
	echo "<input style='margin-left: 50px;width: 67%;' type='text' name='subject' id='subject' placeholder='Enter Subject' required></div>";
	echo "<div><textarea style='width:100%;' name='mbody' id='mbody' rows='3' cols='50' placeholder='Write your message here.' required></textarea>";
	echo "Choose the group or people to post to<select required style='margin-left:15px;width:100px; margin-top:10px;'id='visibilitycombo' name='visibilitycombo' onchange='fetchcombodata(this.value)'> <option value='Block'>Block</option><option value='Neighbourhood'>Neighbourhood</option><option value='Friend'>Friend</option><option value='Neighbour'>Neighbour</option></select>";
	echo "<select style='width:100px; margin-left:15px;color:#1e1c1c;' id='refcombo' name='refcombo' disabled='true'></select>";
	echo "<input style='margin-left:20px;' type='submit' value='Post' onclick='return validatemessagecombo();'></div>";
	echo "</form>";
	echo "</div>";

	//Approve New Users
	$stmt1 = $mysqli->prepare("select c2.username from customer c1 , customer c2, customerblock cb1,  customerblock cb2 where c1.username = cb1.username and cb1.nblockid = cb2.nblockid and cb2.username = c2.username and c2.authflag = 0 and c1.username=? and c2.username not in(select requester from memberauth where approver = c1.username)");
	$stmt1->bind_param("s", $username);
	$stmt1->execute();
	$stmt1->bind_result($pendingusers);
	$stmt1->store_result();
	if($stmt1->num_rows > 0){
		echo "<form action='approve.php' method='POST'";
		echo "<table>";
		echo "<div class='section-heading'>Following members have requested to join the block</div>";
		while($stmt1->fetch()){
			echo "<tr>";
			echo "<td><input type='checkbox' name='check_list[$pendingusers]' value='$pendingusers'</td> <td>$pendingusers</td>";
			echo "</tr>";
		}
		echo "<tr>";
		echo "<input style='float:right;' type='submit' value='Approve'>";
		echo "</tr></table>";
		echo "</form>";
	}
	$stmt1->close();

    

    //Approve friend request
    $stmt1 = $mysqli->prepare("select user from friend where friendof=? and isfriend=0");
	$stmt1->bind_param("s", $username);
	$stmt1->execute();
	$stmt1->bind_result($pendingfriend);
	$stmt1->store_result();
	if($stmt1->num_rows > 0){
		echo "<form action='approvefriend.php' method='POST'";
		echo "<table>";
		while($stmt1->fetch()){
			echo "<div class='section-heading'>Following members have requested to be friends with you</div><tr>";
			echo "<td><input type='checkbox' name='check_list[$pendingfriend]' value='$pendingfriend'</td> <td>$pendingfriend</td>";
			echo "</tr>";
		}
		echo "<tr>";
		echo "<input style='float:right;' type='submit' value='Accept'>";
		echo "</tr></table>";
		echo "</form>";
	}
	$stmt1->close();

    


    $stmt2 = $mysqli->prepare("select * from message where author=?");
	$stmt2->bind_param("s", $username);
	$stmt2->execute();
	$stmt2->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
	while($stmt2->fetch()){
		echo "<div class='media' data-class='whole-story'>";
		echo "<div style='margin-top:10px;'class='media-object'><a href='showprofile.php?username=".$author."'><img class='avatar circular notranslate_alt' alt='No Photo' src='/avatar-default-80.png'></img></a></div>";
		echo "<div class='media-body' >";
		echo "<div data-class='main-content-area'><div>";
			echo "<h4 class='media-heading'><span class='subject'><a href='showmessage.php?mid=".$mid." '>".$title."</a> </span><span class='timestamp'>".$dateposted."</span></h4>";
			echo "<h5 class='media-author'>".$author."</h5>";
			echo "<p data-class='post-content'>".$body."</p></div><div class='metadata'></div>";
		echo "<div class='media-scope' style='color:#9b9999;'>Shared with ".$refid."</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
	}
	$stmt2->close();


    echo "</div>";//single column content div end
    echo "</div>";//row div end
    echo "</div>";//main div end
}
$mysqli->close();
?>

