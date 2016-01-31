<?php
include "connectdb.php";


if(!empty($_POST["username"])) {
  $uname = $_POST["username"];
}
if(!empty($_POST["visibility"])) {
  $visibility = $_POST["visibility"];
}

$output="";

if($visibility == 'Friend'){
  $stmt = $mysqli->prepare("SELECT user from friend where friendof=? and isfriend=1");
  $stmt->bind_param("s", $uname);
  $stmt->execute();
  $stmt->bind_result($user);
  while($stmt->fetch()){
    $output = $output."<option value='".$user."'>".$user."</option>";
  }
  $stmt->close();

  $stmt1 = $mysqli->prepare("SELECT friendof from friend where user=? and isfriend=1");
  $stmt1->bind_param("s", $uname);
  $stmt1->execute();
  $stmt1->bind_result($user);
  while($stmt1->fetch()){
    $output = $output."<option value='".$user."'>".$user."</option>";
  }
  $stmt1->close();
  $mysqli->close();
  echo $output;
 }

 else{
    $stmt = $mysqli->prepare("SELECT neighbourof from neighbour where user=?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $stmt->bind_result($neighbour);
    while($stmt->fetch()){
      $output = $output."<option value='".$neighbour."'>".$neighbour."</option>";
    }
    $stmt->close();
    $mysqli->close();
    echo $output;
 }