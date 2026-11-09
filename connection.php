<?php
$link = new mysqli("localhost", "call_center", "call_center@878889", "call_center");

// Check connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

// Set charset to utf8mb4 to support all characters and prevent question marks
$link->set_charset("utf8mb4");

function sanitize_input($data) {
    global $link;
    if (empty($data)) return $data;
    $data = trim($data);
    $data = stripslashes($data);
    // Use ENT_QUOTES to handle both single and double quotes
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    $data = $link->real_escape_string($data);
    return $data;
}
?>