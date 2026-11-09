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

$title= mysqli_real_escape_string($link,$_POST['title']);
$description= mysqli_real_escape_string($link,$_POST['description']);


$filename = $_FILES["uploadfile"]["name"];
$tempname = $_FILES["uploadfile"]["tmp_name"];
$folder = "image/" . $filename;
if (move_uploaded_file($tempname, $folder)) {
        
    } else {
        
    }
$filename2 = $_FILES["uploadfile2"]["name"];
$tempname2 = $_FILES["uploadfile2"]["tmp_name"];
$folder2 = "image/" . $filename2;
if (move_uploaded_file($tempname2, $folder2)) {
      
    } else {
       
    }
$filename3 = $_FILES["uploadfile3"]["name"];
$tempname3 = $_FILES["uploadfile3"]["tmp_name"];
$folder3 = "image/" . $filename3;
if (move_uploaded_file($tempname3, $folder3)) {
        
    } else {
        
    }
$filename4 = $_FILES["uploadfile4"]["name"];
$tempname4 = $_FILES["uploadfile4"]["tmp_name"];
$folder4 = "image/" . $filename4;
if (move_uploaded_file($tempname4, $folder4)) {
        
    } else {
        
    }
$filename5 = $_FILES["uploadfile5"]["name"];
$tempname5 = $_FILES["uploadfile5"]["tmp_name"];
$folder5 = "image/" . $filename5;
if (move_uploaded_file($tempname5, $folder5)) {
       
    } else {
        
    }
$filename6 = $_FILES["uploadfile6"]["name"];
$tempname6 = $_FILES["uploadfile6"]["tmp_name"];
$folder6 = "image/" . $filename6;
if (move_uploaded_file($tempname6, $folder6)) {
       
    } else {
        
    }
$filename7 = $_FILES["uploadfile7"]["name"];
$tempname7 = $_FILES["uploadfile7"]["tmp_name"];
$folder7 = "image/" . $filename7;
if (move_uploaded_file($tempname7, $folder7)) {
        echo "<h3>  Image uploaded successfully!</h3>";
    } else {
        echo "<h3>  Failed to upload image!</h3>";
    }
$filename8 = $_FILES["uploadfile8"]["name"];
$tempname8 = $_FILES["uploadfile8"]["tmp_name"];
$folder8 = "image/" . $filename8;
if (move_uploaded_file($tempname8, $folder8)) {
       
    } else {
        
    }
$filename9 = $_FILES["uploadfile9"]["name"];
$tempname9 = $_FILES["uploadfile9"]["tmp_name"];
$folder9 = "image/" . $filename9;
if (move_uploaded_file($tempname9, $folder9)) {
       
    } else {
        
    }
$filename10 = $_FILES["uploadfile10"]["name"];
$tempname10 = $_FILES["uploadfile10"]["tmp_name"];
$folder10 = "image/" . $filename10;
if (move_uploaded_file($tempname10, $folder10)) {
       
    } else {
        
    }
$sql = "INSERT INTO gallery (title, description,image1,image2,image3,image4,image5,image6,image7,image8,image9,image10)
VALUES ('$title', '$description', '$filename','$filename2','$filename3','$filename4','$filename5','$filename6','$filename7','$filename8','$filename9','$filename10')";

if ($link->query($sql) === TRUE) {
  header('Location:photo.php?success=200');
} else {
    header('Location:photo.php?error=200');

}


?>






    