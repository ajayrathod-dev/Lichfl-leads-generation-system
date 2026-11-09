<?php
session_start();
include("connection.php");

// Sanitize cookie input
$u_uname = isset($_COOKIE['uuid']) ? sanitize_input($_COOKIE['uuid']) : '';

// Prepare and execute query to verify user
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
    header("location:logout.php");
    exit;
}

// Sanitize POST input
$send = sanitize_input($_POST['send_by']);
$recieved = sanitize_input($_POST['recieved_by']);
$msg = sanitize_input($_POST['msg']);

// Prepare and execute query to insert chat message
$sql = "INSERT INTO chat (sent_by, recieved_by, msg) VALUES (?, ?, ?)";
$stmt = $link->prepare($sql);
$stmt->bind_param("sss", $send, $recieved, $msg);

if ($stmt->execute()) {
    header('Location: api.php?mobile=' . urlencode($recieved));
} else {
    header('Location: error.php?error=1');
}
?>
