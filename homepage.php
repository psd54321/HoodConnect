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
if (isset($_POST['search']))
{
		$search = "%{$_POST['search']}%";
		$search1 =$_POST['search'];
		}
else
{
	$search ='%%';
}	
//echo "<script type='text/javascript'> window.onload=doSearch($search); </script>";


//$mysqli = new mysqli("localhost", "root", "Hmq2V0eG", "hoodconnect");
$stmt = $mysqli->prepare("SELECT authflag,cfname,lastacessed from customer where username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($authflag,$fname,$lastlogin);
$stmt->fetch();
$stmt->close();
$_SESSION["fname"] = $fname;
if($authflag == 0){
	echo"Your request to join the block has not yet been approved. Please check again later.";
}
else{

	$countfreind = 0;
	$countneighbour = 0;
	$countblock = 0;
	$counthood = 0;
	$stmt6 = $mysqli->prepare("select cb.nblockid,b.hoodid from customerblock cb,nblock b where cb.username=? and cb.nblockid=b.nblockid and cb.enddate is null");
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
	echo "<div style='float:right'>Last Login ".$lastlogin."</div>";
	echo "<div class='row'>";//row div
	echo "<div id='navigation-side' role='navigation' class='span navigation-column overthrow'>";
	echo "<ul 'class='nav nav-pills nav-stacked' style='background=;'><li style='width:100%;'><a href='homepage.php'>Home</a></li><li><a href='mapview.php'>Map View</a></li><li><a href ='showprofile.php?username=".$username." ' > My Profile </a> </li><li><a href ='ChangeBlock.php?username1=".$username." ' > Change Block </a> </li></ul>";
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

    


    echo "<form action='homepage.php' method='POST'>";
	if (isset($_POST['search']))
		$search = "%{$_POST['search']}%";

	echo "  Search <input type ='text' name ='search' id ='search'/>";
	echo " <input type ='submit' value ='submit'>";

	//Friend Messages
	echo "<br/><div class ='section-heading'>Friend Messages</div>"; 
	if (isset($_POST['search'])){
	    $stmt2 = $mysqli->prepare("select * from message where ( refid=? or author =?) and visibility ='F' and (body like ? or msubject like ? or title like ? )");
		$stmt2->bind_param("sssss", $username,$username,$search,$search,$search);
		$stmt2->execute();
		$stmt2->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
		$stmt2->store_result();
		$countfreind = $stmt2->num_rows;
		
		while($stmt2->fetch()){
			if($visibility =='F')
			{
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
		}
		$stmt2->close();
	}
	else{
		$stmt9 = $mysqli->prepare("select m.mid,m.title,m.msubject,m.body,m.author,m.dateposted,m.visibility,m.refid from message m, readunread ru where  m.visibility ='F' and ru.username =? and ru.mid=m.mid and ru.readflag=0 order by updatedate desc");
		$stmt9->bind_param("s", $username);
		$stmt9->execute();
		$stmt9->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
		$stmt9->store_result();
		$countfreind = $stmt9->num_rows;
		
		while($stmt9->fetch()){
			if($visibility =='F')
			{
				echo "<div class='media' data-class='whole-story'>";
				echo "<div style='margin-top:10px;'class='media-object'><a href='showprofile.php?username=".$author."'><img class='avatar circular notranslate_alt' alt='No Photo' src='/avatar-default-80.png'></img></a></div>";
				echo "<div class='media-body' >";
				echo "<div data-class='main-content-area'><div>";
					
					echo "<h4 class='media-heading'><span class='subject' style='color:red;'><a href='showmessage.php?mid=".$mid." '>".$title."</a> </span><span class='timestamp'>".$dateposted."</span></h4>";
					echo "<h5 class='media-author'>".$author."</h5>";
					echo "<p data-class='post-content'>".$body."</p></div><div class='metadata'></div>";
				echo "<div class='media-scope' style='color:#9b9999;'>Shared with ".$refid."</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}
		}
		$stmt9->close();

		$stmt10 = $mysqli->prepare("select m.mid,m.title,m.msubject,m.body,m.author,m.dateposted,m.visibility,m.refid from message m, readunread ru where  m.visibility ='F' and ru.username =? and ru.mid=m.mid and ru.readflag=1 order by updatedate desc");
		$stmt10->bind_param("s", $username);
		$stmt10->execute();
		$stmt10->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
		$stmt10->store_result();
		$countfreind = $countfreind+$stmt10->num_rows;
		
		while($stmt10->fetch()){
			if($visibility =='F')
			{
				echo "<div class='media' data-class='whole-story'>";
				echo "<div style='margin-top:10px;'class='media-object'><a href='showprofile.php?username=".$author."'><img class='avatar circular notranslate_alt' alt='No Photo' src='/avatar-default-80.png'></img></a></div>";
				echo "<div class='media-body' >";
				echo "<div data-class='main-content-area'><div>";
					
					echo "<h4 class='media-heading'><span class='subject' ><a href='showmessage.php?mid=".$mid." '>".$title."</a> </span><span class='timestamp'>".$dateposted."</span></h4>";
					echo "<h5 class='media-author'>".$author."</h5>";
					echo "<p data-class='post-content'>".$body."</p></div><div class='metadata'></div>";
				echo "<div class='media-scope' style='color:#9b9999;'>Shared with ".$refid."</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}
		}
		$stmt10->close();
	}
	if($countfreind == 0){
		echo "No messages to display";
	}
	
	//Neighbour message
	echo "<br/><div class ='section-heading'>Neighbour Messages</div>"; 
	if (isset($_POST['search'])){
	$stmt3 = $mysqli->prepare("select * from message where (author=? or refid=?) and visibility ='N' and (body like ? or msubject like ? or title like ? ) ");
	$stmt3->bind_param("sssss", $username,$username,$search,$search,$search);
	$stmt3->execute();
	$stmt3->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
	$stmt3->store_result();
	$countneighbour = $stmt3->num_rows;
	
	while($stmt3->fetch()){
		if($visibility =='N')
		{
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
	}
	$stmt3->close();
	}
	else{
		$stmt11= $mysqli->prepare("select m.mid,m.title,m.msubject,m.body,m.author,m.dateposted,m.visibility,m.refid from message m, readunread ru where  m.visibility ='N' and ru.username =? and ru.mid=m.mid and ru.readflag=0 order by updatedate desc");
		$stmt11->bind_param("s", $username);
		$stmt11->execute();
		$stmt11->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
		$stmt11->store_result();
		$countneighbour = $stmt11->num_rows;
		
		while($stmt11->fetch()){
			if($visibility =='N')
			{
				echo "<div class='media' data-class='whole-story'>";
				echo "<div style='margin-top:10px;'class='media-object'><a href='showprofile.php?username=".$author."'><img class='avatar circular notranslate_alt' alt='No Photo' src='/avatar-default-80.png'></img></a></div>";
				echo "<div class='media-body' >";
				echo "<div data-class='main-content-area'><div>";
					
					echo "<h4 class='media-heading'><span class='subject' style='color:red;'><a href='showmessage.php?mid=".$mid." '>".$title."</a> </span><span class='timestamp'>".$dateposted."</span></h4>";
					echo "<h5 class='media-author'>".$author."</h5>";
					echo "<p data-class='post-content'>".$body."</p></div><div class='metadata'></div>";
				echo "<div class='media-scope' style='color:#9b9999;'>Shared with ".$refid."</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}
		}
		$stmt11->close();

		$stmt12= $mysqli->prepare("select m.mid,m.title,m.msubject,m.body,m.author,m.dateposted,m.visibility,m.refid from message m, readunread ru where  m.visibility ='N' and ru.username =? and ru.mid=m.mid and ru.readflag=1 order by updatedate desc");
		$stmt12->bind_param("s", $username);
		$stmt12->execute();
		$stmt12->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
		$stmt12->store_result();
		$countneighbour = $countneighbour+$stmt12->num_rows;
		
		while($stmt12->fetch()){
			if($visibility =='N')
			{
				echo "<div class='media' data-class='whole-story'>";
				echo "<div style='margin-top:10px;'class='media-object'><a href='showprofile.php?username=".$author."'><img class='avatar circular notranslate_alt' alt='No Photo' src='/avatar-default-80.png'></img></a></div>";
				echo "<div class='media-body' >";
				echo "<div data-class='main-content-area'><div>";
					
					echo "<h4 class='media-heading'><span class='subject' ><a href='showmessage.php?mid=".$mid." '>".$title."</a> </span><span class='timestamp'>".$dateposted."</span></h4>";
					echo "<h5 class='media-author'>".$author."</h5>";
					echo "<p data-class='post-content'>".$body."</p></div><div class='metadata'></div>";
				echo "<div class='media-scope' style='color:#9b9999;'>Shared with ".$refid."</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}
		}
		$stmt12->close();
	}
	if($countneighbour == 0){
		echo "There are no messages to display";
	}

	//Block Message
	echo "<br/><div class ='section-heading'>Block Messages</div>"; 
	if (isset($_POST['search'])){
	$stmt4 = $mysqli->prepare("select m.mid,m.title,m.msubject,m.body,m.author,m.dateposted,m.visibility,m.refid from message m, readunread ru where  m.visibility ='B' and ru.username =? and ru.mid=m.mid and(m.body like ? or m.msubject like ? or m.title like ? ) order by updatedate desc  ");
	$stmt4->bind_param("ssss",$username,$search,$search,$search);
	$stmt4->execute();
	$stmt4->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
	$stmt4->store_result();
	$countblock = $stmt4->num_rows;
	
	while($stmt4->fetch()){
		if($visibility =='B')
		{
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
	}
	$stmt4->close();
}
else{
		$stmt15= $mysqli->prepare("select m.mid,m.title,m.msubject,m.body,m.author,m.dateposted,m.visibility,m.refid from message m, readunread ru,customerblock cb where  m.visibility ='B' and ru.username =? and ru.mid=m.mid and ru.readflag=0 and cb.nblockid=m.refid and cb.enddate is null and cb.username = ru.username order by updatedate desc");
		$stmt15->bind_param("s", $username);
		$stmt15->execute();
		$stmt15->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
		$stmt15->store_result();
		$countblock = $stmt15->num_rows;
		
		while($stmt15->fetch()){
			if($visibility =='B')
			{
				echo "<div class='media' data-class='whole-story'>";
				echo "<div style='margin-top:10px;'class='media-object'><a href='showprofile.php?username=".$author."'><img class='avatar circular notranslate_alt' alt='No Photo' src='/avatar-default-80.png'></img></a></div>";
				echo "<div class='media-body' >";
				echo "<div data-class='main-content-area'><div>";
					
					echo "<h4 class='media-heading'><span class='subject' style='color:red;'><a href='showmessage.php?mid=".$mid." '>".$title."</a> </span><span class='timestamp'>".$dateposted."</span></h4>";
					echo "<h5 class='media-author'>".$author."</h5>";
					echo "<p data-class='post-content'>".$body."</p></div><div class='metadata'></div>";
				echo "<div class='media-scope' style='color:#9b9999;'>Shared with ".$refid."</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}
		}
		$stmt15->close();

		$stmt16= $mysqli->prepare("select m.mid,m.title,m.msubject,m.body,m.author,m.dateposted,m.visibility,m.refid from message m, readunread ru,customerblock cb where  m.visibility ='B' and ru.username =? and ru.mid=m.mid and ru.readflag=1 and cb.nblockid=m.refid and cb.enddate is null and cb.username = ru.username order by updatedate desc");
		$stmt16->bind_param("s", $username);
		$stmt16->execute();
		$stmt16->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
		$stmt16->store_result();
		$countblock = $countblock + $stmt16->num_rows;
		
		while($stmt16->fetch()){
			if($visibility =='B')
			{
				echo "<div class='media' data-class='whole-story'>";
				echo "<div style='margin-top:10px;'class='media-object'><a href='showprofile.php?username=".$author."'><img class='avatar circular notranslate_alt' alt='No Photo' src='/avatar-default-80.png'></img></a></div>";
				echo "<div class='media-body' >";
				echo "<div data-class='main-content-area'><div>";
					
					echo "<h4 class='media-heading'><span class='subject' ><a href='showmessage.php?mid=".$mid." '>".$title."</a> </span><span class='timestamp'>".$dateposted."</span></h4>";
					echo "<h5 class='media-author'>".$author."</h5>";
					echo "<p data-class='post-content'>".htmlspecialchars($body)."</p></div><div class='metadata'></div>";
				echo "<div class='media-scope' style='color:#9b9999;'>Shared with ".$refid."</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}
		}
		$stmt16->close();
	}
	if($countblock ==0){
		echo "There are no messages to display";
	}

	//Hood MEssages
	echo "<br/><div class ='section-heading'>Hood Messages</div>";
if (isset($_POST['search'])){
	$stmt5 = $mysqli->prepare(" select * from message where author in ( select cb2.username from customerblock cb1,customerblock cb2,nblock n1,nblock n2 where cb1.nblockid = n1.nblockid
 and n1.hoodid=n2.hoodid and n2.nblockid =cb2.nblockid and cb1.username = ?) and Visibility ='H' and (body like ? or msubject like ? or title like ? ) ");
	$stmt5->bind_param("ssss", $username,$search,$search,$search);
	$stmt5->execute();
	$stmt5->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
	$stmt5->store_result();
	$counthood = $stmt5->num_rows;

	while($stmt5->fetch()){
	if($visibility =='H')
	{
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
	}
	$stmt5->close();
}
else{
		$stmt13= $mysqli->prepare("select m.mid,m.title,m.msubject,m.body,m.author,m.dateposted,m.visibility,m.refid from message m, readunread ru where  m.visibility ='H' and ru.username =? and ru.mid=m.mid and ru.readflag=0 order by updatedate desc");
		$stmt13->bind_param("s", $username);
		$stmt13->execute();
		$stmt13->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
		$stmt13->store_result();
		$counthood = $stmt13->num_rows;
		
		while($stmt13->fetch()){
			if($visibility =='H')
			{
				echo "<div class='media' data-class='whole-story'>";
				echo "<div style='margin-top:10px;'class='media-object'><a href='showprofile.php?username=".$author."'><img class='avatar circular notranslate_alt' alt='No Photo' src='/avatar-default-80.png'></img></a></div>";
				echo "<div class='media-body' >";
				echo "<div data-class='main-content-area'><div>";
					
					echo "<h4 class='media-heading'><span class='subject' style='color:red;'><a href='showmessage.php?mid=".$mid." '>".$title."</a> </span><span class='timestamp'>".$dateposted."</span></h4>";
					echo "<h5 class='media-author'>".$author."</h5>";
					echo "<p data-class='post-content'>".$body."</p></div><div class='metadata'></div>";
				echo "<div class='media-scope' style='color:#9b9999;'>Shared with ".$refid."</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}
		}
		$stmt13->close();

		$stmt14= $mysqli->prepare("select m.mid,m.title,m.msubject,m.body,m.author,m.dateposted,m.visibility,m.refid from message m, readunread ru where  m.visibility ='H' and ru.username =? and ru.mid=m.mid and ru.readflag=1 order by updatedate desc");
		$stmt14->bind_param("s", $username);
		$stmt14->execute();
		$stmt14->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
		$stmt14->store_result();
		$counthood = $counthood + $stmt14->num_rows;
		
		while($stmt14->fetch()){
			if($visibility =='H')
			{
				echo "<div class='media' data-class='whole-story'>";
				echo "<div style='margin-top:10px;'class='media-object'><a href='showprofile.php?username=".$author."'><img class='avatar circular notranslate_alt' alt='No Photo' src='/avatar-default-80.png'></img></a></div>";
				echo "<div class='media-body' >";
				echo "<div data-class='main-content-area'><div>";
					
					echo "<h4 class='media-heading'><span class='subject' ><a href='showmessage.php?mid=".$mid." '>".$title."</a> </span><span class='timestamp'>".$dateposted."</span></h4>";
					echo "<h5 class='media-author'>".$author."</h5>";
					echo "<p data-class='post-content'>".$body."</p></div><div class='metadata'></div>";
				echo "<div class='media-scope' style='color:#9b9999;'>Shared with ".$refid."</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}
		}
		$stmt14->close();
	}
	if($counthood == 0 ){
		echo "There are no messages to display";
	}


	echo "</form>";


    echo "</div>";//single column content div end
    echo "</div>";//row div end
    echo "</div>";//main div end
}
$mysqli->close();
?>

