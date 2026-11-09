<?php
session_start();
include("connection.php");

// Check if 'uuid' cookie is set and sanitize its value
if (isset($_COOKIE['uuid'])) {
    $u_uname = sanitize_input($_COOKIE['uuid']);
} else {
    $u_uname = '';
}

// Prepared statement for SELECT query
$sql4 = "SELECT * FROM ods WHERE uuid=? AND status='verified'";
$stmt4 = $link->prepare($sql4);
$stmt4->bind_param("s", $u_uname);
$stmt4->execute();
$result4 = $stmt4->get_result();

if ($result4->num_rows > 0) {
    while ($row4 = $result4->fetch_assoc()) {
        $u_uname = sanitize_input($row4['user_id']);
        $br_id = sanitize_input($row4['user_id']);
    }
} else {
   
   }
$stmt4->close();

// Prepared statement for UPDATE query
$sql = "UPDATE ods SET status='unverified' WHERE user_id=?";
$stmt = $link->prepare($sql);
$stmt->bind_param("s", $u_uname);

if ($stmt->execute()) {
    header('Location:login.php');
} else {
    header('Location:login.php');
}
$stmt->close();
?>