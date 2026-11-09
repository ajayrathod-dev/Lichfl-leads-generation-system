<?php
session_start();
include("connection.php");
$dt=date("Y-m-d h:i:sa");
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
    header("Location: logout.php");
    exit;
}

// Sanitize GET input
$comp_id = isset($_GET['comp_id']) ? sanitize_input($_GET['comp_id']) : '';
$status = isset($_GET['status']) ? sanitize_input($_GET['status']) : '';
$abhi = isset($_GET['abhi']) ? sanitize_input($_GET['abhi']) : '';
$mob = isset($_GET['mob']) ? sanitize_input($_GET['mob']) : '';
// Prepare and execute query to update complaint status
$sqlUpdateComplaint = "UPDATE complaint SET category=?,dates=? WHERE complaint_id=?";
$stmtUpdateComplaint = $link->prepare($sqlUpdateComplaint);
$stmtUpdateComplaint->bind_param("sss", $status, $dt, $comp_id);
 
if ($stmtUpdateComplaint->execute()) {
    // Prepare and execute query to update chat status
    $sqlUpdateChat = "UPDATE chat SET category=? WHERE complaint_id=?";
    $stmtUpdateChat = $link->prepare($sqlUpdateChat);
    $stmtUpdateChat->bind_param("ss", $status, $comp_id);

    if ($stmtUpdateChat->execute()) {
        mysqli_query($link,"INSERT INTO chat (sent_by, recieved_by, msg) VALUES ('$u_uname','$mob','$abhi')");
        header('Location: info.php?success=1717');
        exit;
    } else {
        header('Location: error.php?error=3');
        exit;
    }
} else {
    header('Location: error.php?error=3');
    exit;
}
?>
