<?php
session_start();
include("connection.php");




if (isset($_COOKIE['uuid'])) {

  $u_uname = mysqli_real_escape_string($link, $_COOKIE['uuid']);

} else {

  $u_uname = '';

}



 $sql4 = "SELECT * FROM ods WHERE uuid='$u_uname' AND status='verified'";

$result4 = mysqli_query($link, $sql4);

if ($result4->num_rows > 0) {

  while ($row4 = $result4->fetch_assoc()) {

    $u_uname = mysqli_real_escape_string($link, $row4['user_id']);

    $br_id = mysqli_real_escape_string($link, $row4['user_id']);

  }

} else {

  header("location:logout.php");

  exit;

}

$c_name= $_POST['c_name'];
$day= $_POST['day'];
$date= $_POST['date'];
$time= $_POST['time'];
$mode= $_POST['mode'];


$sql = "INSERT INTO `session`(`technology`, `day`, `time`, `mode`,`date`)
VALUES ('$c_name','$day','$time','$mode','$date')";

if ($link->query($sql) === TRUE) {
   $p=41;
             header("Location:free_demo.php?success=$p");
} else {
  $p=41;
             header("Location:free_demo.php?error=$p");
}

    