<?php
include "connectdb.php";


if(!empty($_POST["username"])) {
  $uname = $_POST["username"];
  $stmt = $mysqli->prepare("SELECT 1 from customer where username=?");
  $stmt->bind_param("s", $uname);
  $stmt->execute();
  if($stmt->fetch()) {
      echo "<span class='status-not-available'> Username Not Available.</span>";
  }else{
      echo "<span class='status-available'> Username Available.</span>";
  }
  $stmt->close();
  $mysqli->close();
}
?>