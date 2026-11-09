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

$title= mysqli_real_escape_string($link,$_POST['name']);
$department= mysqli_real_escape_string($link,$_POST['department']);
$onroll= mysqli_real_escape_string($link,$_POST['onroll']);
$description= mysqli_real_escape_string($link,$_POST['desc']);


$filename = $_FILES["uploadfile"]["name"];
$tempname = $_FILES["uploadfile"]["tmp_name"];
$folder = "image/" . $filename;
if (move_uploaded_file($tempname, $folder)) {
        
    } else {
        
    }

$sql = "INSERT INTO teaching (name, department,onroll,description,image)
VALUES ('$title','$department','$onroll' ,'$description', '$filename')";

if ($link->query($sql) === TRUE) {
  header('Location:teaching.php?success=300');
} else {
    header('Location:teaching.php?error=300');

}


?>






    