<?php session_start();?>
<?php
include "connectdb.php";
if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
}

if(!empty($_POST['check_list'])) {
	foreach($_POST['check_list'] as $check) {
		$stmt4 = $mysqli->prepare("insert into memberauth values(?,?)");
		$stmt4->bind_param("ss",$check, $username);
		$stmt4->execute();
		$stmt4->close();
	}
}
$mysqli->close();
header("Location:homepage.php");
exit();
?>