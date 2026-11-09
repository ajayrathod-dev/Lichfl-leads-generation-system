<?php
ob_start(); // Start output buffering
session_start();
include("connection.php");

// Check if UUID cookie is set
$u_uname = '';
if (isset($_COOKIE['uuid'])) {
    $u_uname = sanitize_input($_COOKIE['uuid']);
}

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
    ob_end_flush(); // Ensure the buffer is flushed
    exit;
}

// Sanitize POST input
$mobile = sanitize_input($_POST['mobile']);
$reg = sanitize_input($_POST['reg']);
$name = sanitize_input($_POST['name']);
$village = sanitize_input($_POST['village']);
$tq = sanitize_input($_POST['tq']);
$district = sanitize_input($_POST['district']);
$title = sanitize_input($_POST['title']);
$assign_to = sanitize_input($_POST['assign_to']);

// Generate unique complaint ID
$compaint_id = uniqid();

// Check if the std_mobile_no already exists in the log table
$sqlCheck = "SELECT COUNT(*) AS count FROM log WHERE std_mobile_no = ?";
$stmtCheck = $link->prepare($sqlCheck);
$stmtCheck->bind_param("s", $mobile);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
$rowCheck = $resultCheck->fetch_assoc();

if ($rowCheck['count'] > 0) {
    // Redirect to an error page or handle the duplicate entry scenario
    header('Location: complaint.php?error=3434');
    ob_end_flush();
    exit;
}

$tot = mt_rand(150, 250) / 1000;

// Prepare and execute query to insert into log table
$sqlLog = "INSERT INTO log (reg_mobile_no, std_mobile_no,amount) VALUES (?, ?, ?)";
$stmtLog = $link->prepare($sqlLog);
$stmtLog->bind_param("ssd", $reg, $mobile,$tot);
$stmtLog->execute();

// Insert complaint
$sql = "INSERT INTO complaint (mobile_no, registred_by, name, village, taluka, destrict, complaint_title, assign_to, complaint_id, category) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $link->prepare($sql);
$category = 1; // Set the default category
$stmt->bind_param("ssssssssss", $mobile, $reg, $name, $village, $tq, $district, $title, $assign_to, $compaint_id, $category);

if ($stmt->execute()) {
    // Insert chat record
    $sqlChat = "INSERT INTO chat (sent_by, recieved_by, msg, category, complaint_id) VALUES (?, ?, ?, ?, ?)";
    $stmtChat = $link->prepare($sqlChat);
    $stmtChat->bind_param("sssss", $reg, $mobile, $title, $category, $compaint_id);
    $stmtChat->execute();

    // Update user details
    $sqlUserUpdate = "UPDATE user SET name=?, village=?, taluka=?, district=? WHERE mobile_no=?";
    $stmtUserUpdate = $link->prepare($sqlUserUpdate);
    $stmtUserUpdate->bind_param("sssss", $name, $village, $tq, $district, $mobile);
    $stmtUserUpdate->execute();

    // Redirect to success page
    header('Location: api.php?success=1002&mobile=' . $mobile);
    ob_end_flush();
    exit;
} else {
    // Redirect to error page if insertion fails
    header('Location: api.php?error=1002');
    ob_end_flush();
    exit;
}
?>
