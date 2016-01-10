<?php session_start();?>
<?php
include "connectdb.php";
if(isset($_POST["username"])) {
    $uname = $_POST["username"];
}
 if(isset($_POST["password"])) {
    $pwd = $_POST["password"];
}

$errors = [];



	$stmt = $mysqli->prepare("SELECT * from credential where username=? and pwd=?");
    $stmt->bind_param("ss", $uname,$pwd);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
    	$stmt->close();
    	$mysqli->close();
        $_SESSION['username']=$uname;
    	header("Location:homepage.php");
    	exit();
    }
    else{
    	$stmt->close();
    	$mysqli->close();
    	$errors[] = "Invalid username or password !!";
    	$_SESSION['errors'] = $errors;
    	header("Location:login.php");
    	exit();
    }
?>