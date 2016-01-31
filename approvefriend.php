<?php session_start();?>
<?php
include "connectdb.php";
if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
}

if(!empty($_POST['check_list'])) {
	foreach($_POST['check_list'] as $check) {
		$stmt4 = $mysqli->prepare("update friend set isfriend=1 where isfriend=0 and user=? and friendof=?");
		$stmt4->bind_param("ss",$check, $username);
		$stmt4->execute();
		$stmt4->close();
	}
}
$mysqli->close();
header("Location:homepage.php");
exit();
?>