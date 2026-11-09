<?php
session_start();
include("connection.php");

// Clear session
session_destroy();

// Clear cookie
setcookie("uuid", "", time() - 3600, "/");

// Clear ods table entry if exists
if(isset($_COOKIE['uuid'])) {
    $uuid = sanitize_input($_COOKIE['uuid']);
    $sql = "DELETE FROM ods WHERE uuid = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $uuid);
    $stmt->execute();
    $stmt->close();
}

header("Location: login.php?logout=true");
exit();
?>