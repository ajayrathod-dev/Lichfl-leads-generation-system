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
$start_date= $_POST['start_date'];
$day= $_POST['day'];
$time= $_POST['time'];


$sql = "INSERT INTO `batch_shedule`(`technology`, `start_date`, `days`, `timing`) 
VALUES ('$c_name','$start_date','$day','$time')";

if ($link->query($sql) === TRUE) {
   $p=42;
             header("Location:batch_schedule.php?success=$p");
} else {
  $p=42;
             header("Location:batch_schedule.php?error=$p");
}

    