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
$pdf_file = $_FILES["pdf_file"];

    // Check if the file is a PDF
    $file_type = pathinfo($pdf_file["name"], PATHINFO_EXTENSION);
    if ($file_type != "pdf") {
        echo "Only PDF files are allowed.";
        exit();
    }

    // Generate a unique filename
    $file_name = uniqid() . ".pdf";
    $file_path = "uploads/" . $file_name;

    // Move the uploaded file to the server
    if (move_uploaded_file($pdf_file["tmp_name"], $file_path)) {
        // Insert file information into the database
        $sql = "INSERT INTO nmc (title,file_name, file_path) VALUES ('$title','$file_name', '$file_path')";
        if ($link->query($sql) === TRUE) {
             header('Location:nmc.php?success=11');
        } else {
            header('Location:nmc.php?error=11');
        }
    } else {
         header('Location:nmc.php?error=11');
    }



?>






    