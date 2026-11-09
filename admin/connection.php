<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");

$servername = "localhost";
$username = "call_center";
$password = "call_center@878889";
$dbname = "call_center";

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

