<?php
error_reporting('0');
date_default_timezone_set("Asia/Kolkata"); 

$servername = "localhost";
$username = "classes";
$password = "Class@87888970";
$dbname = "classes";

// Function to sanitize input
function sanitize_input($data) {
  return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Create connection
$link = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($link->connect_error) {
  die("Connection failed: " . $link->connect_error);
}

?>

