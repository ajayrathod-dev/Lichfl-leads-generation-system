<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sent_by = sanitize_input($_POST['send_by']);
    $received_by = sanitize_input($_POST['recieved_by']);
    $msg = sanitize_input($_POST['msg']);
    
    // Insert into chat table
    $sql = "INSERT INTO chat (sent_by, recieved_by, msg, date) VALUES (?, ?, ?, NOW())";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("sss", $sent_by, $received_by, $msg);
    
    if ($stmt->execute()) {
        echo "Message sent successfully";
    } else {
        echo "Error sending message";
    }
    
    $stmt->close();
    $link->close();
} else {
    echo "Invalid request method";
}
?>