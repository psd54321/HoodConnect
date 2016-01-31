<?php session_start();?>

<?php
include "header1.php";
include "connectdb.php";

date_default_timezone_set("America/New_York");
$disablereply = "";
$getmid = $_GET['mid']; 
$fname = $_SESSION['fname'];
$_SESSION['toid'] = $getmid;
$_SESSION['messageurl'] = $_SERVER['REQUEST_URI'];
	echo "<div class='container'>";//main div
	echo "<h3>Welcome $fname!</h3>";
	echo "<div class='row'>";//row div
	echo "<div id='navigation-side' role='navigation' class='span navigation-column overthrow'>";
	echo "<ul 'class='nav nav-pills nav-stacked'><li><a href='homepage.php'>Home</a></li></ul>";
	echo "</div>";//sidenav div end
	//Post Message
	echo "<div class='span single-column' role='main' >";

	$stmt8 = $mysqli->prepare("update readunread set readflag = 1,updatedate=? where mid=? and username=?");
	$stmt8->bind_param("sss",date('Y-m-d H:i:s'),$getmid,$_SESSION["username"]);
	$stmt8->execute();
	$stmt8->close();


	$stmt6 = $mysqli->prepare("select * from message where mid=?");
	$stmt6->bind_param("s", $getmid);
	$stmt6->execute();
	$stmt6->bind_result($mid,$title,$subject,$body,$author,$dateposted,$visibility,$refid);
	while($stmt6->fetch()){
		echo "<div class='media' data-class='whole-story'>";
		echo "<div style='margin-top:10px;'class='media-object'><img class='avatar circular notranslate_alt' alt='No Photo' src='/avatar-default-80.png'></img></div>";
		echo "<div class='media-body' >";
		echo "<div data-class='main-content-area'><div>";
			echo "<h4 class='media-heading'><span class='subject'><a href='showmessage.php/?mid=$mid'>".$title."</a> </span><span class='timestamp'>".$dateposted."</span></h4>";
			echo "<h5 class='media-author'>".$author."</h5>";
			echo "<p data-class='post-content'>".$body."</p></div><div class='metadata'></div>";
		echo "<div class='media-scope' style='color:#9b9999;'>Shared with ".$refid."</div>";
		echo "</div>";
		
	}
	if($visibility == 'B' && $_SESSION['nblockid'] != $refid){
		$disablereply = "disabled";
	}

	echo "<div style='display:block;height:20px;' ></div>";

	$stmt6->close();

	$stmt7 = $mysqli->prepare("select * from reply where toid=?");
	$stmt7->bind_param("s", $getmid);
	$stmt7->execute();
	$stmt7->bind_result($rid,$toid,$rdateposted,$replyerid,$rbody);
	while($stmt7->fetch()){
		echo "<div class='media-comments' data-class='comment-like-container'>";
		echo "<div class='media-comment'>";
		echo "<div>";//1
		echo "<div class='media-object hidden-phone'><img class='avatar circular notranslate_alt' alt='No Photo' src='/avatar-default-80.png'></img></div>";
		echo "<div class='media-body'>";//2
		echo "<div >";//3
			echo "<h6 class='media-author'><span class='user-name'>".$replyerid."</span><span class='timestamp' style='float:right;'>".$rdateposted."</span></h6>";
			echo "<p data-class='post-content'>".$rbody."</p></div>";
		
		echo "</div>";	//2 closed
		echo "</div>"; //1 closed
		echo "</div>"; //media-comment closed
		
	}

	$stmt7->close();
	echo "<div class='media-comment'> ";
	echo "<form action='postreply.php' method='post'><div><textarea rows='2' cols='50' placeholder='Write a reply' name='replytext' style='overflow:hidden;resize:none;height:50px;' class='input-block-level' ".$disablereply."></textarea>";
	echo "<input style='margin-top:20px;'type='submit' value='Post' ".$disablereply."></div>";
	echo "</form>";
	echo "</div>";
	echo "</div>"; //media-comments closed
	echo "</div>";//media-body closed
	echo "</div>";//media-object closed

	 echo "</div>";//single column content div end
    echo "</div>";//row div end
    echo "</div>";//main div end

    $mysqli->close();
?>