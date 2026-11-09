<?php

ob_start(); // Start output buffering
session_start();
include("connection.php");

// Check if UUID cookie is set
$u_uname = '';
if (isset($_COOKIE['uuid'])) {
    $u_uname = sanitize_input($_COOKIE['uuid']);
}

// Verify user
$sql4 = "SELECT * FROM ods WHERE uuid=? AND status='verified'";
$stmt4 = $link->prepare($sql4);
if (!$stmt4) {
    die("Query preparation failed: " . $link->error);
}
$stmt4->bind_param("s", $u_uname);
$stmt4->execute();
$result4 = $stmt4->get_result();

if ($result4->num_rows > 0) {
    $row4 = $result4->fetch_assoc();
    $u_uname = sanitize_input($row4['user_id']);
} else {
    header("Location: logout.php");
    ob_end_flush(); // Ensure the buffer is flushed
    exit;
}

// Sanitize POST input
$amount = sanitize_input($_POST['amount']);
$stud_mob = sanitize_input($_POST['stud_mob']);
$desc = sanitize_input($_POST['desc']);

// // Check if the mobile number already exists in the log table
// $sqlCheck = "SELECT * FROM log WHERE std_mobile_no = ?";
// $stmtCheck = $link->prepare($sqlCheck);
// if (!$stmtCheck) {
//     die("Query preparation failed: " . $link->error);
// }
// $stmtCheck->bind_param("s", $stud_mob);
// $stmtCheck->execute();
// $resultCheck = $stmtCheck->get_result();

// if ($resultCheck->num_rows > 0) {
//     // Mobile number already exists, handle accordingly (e.g., skip insert or show a message)
//     die("This mobile number is already recorded in the log.");
// }

// Random percentage calculation
$tot = rand(1, 3);
$calculatedAmount = ($tot / 100) * $amount;

// Insert into log
if (!mysqli_query($link, "INSERT INTO log (reg_mobile_no, std_mobile_no, amount) VALUES ('$u_uname', '$stud_mob', '$calculatedAmount')")) {
    die("Log insertion failed: " . mysqli_error($link));
}

// Get user name for message
$sql42 = "SELECT * FROM admin WHERE user_id=?";
$stmt42 = $link->prepare($sql42);
if (!$stmt42) {
    die("Query preparation failed: " . $link->error);
}
$stmt42->bind_param("s", $u_uname);
$stmt42->execute();
$result42 = $stmt42->get_result();

if ($result42->num_rows > 0) {
    $row42 = $result42->fetch_assoc();
    $name = sanitize_input($row42['name']);
} else {
    die("Admin details not found for user: " . $u_uname);
}

// Prepare message
$ms = $amount . "Rs Fees collected by " . $name . " for the course of " . $desc;

// Insert into fees_log
$sql = "INSERT INTO fees_log (`reg_mobile_no`, `std_mobile_no`, `amount`, `desc`) VALUES (?, ?, ?, ?)";
$stmt = $link->prepare($sql);
$stmt->bind_param("ssss", $u_uname, $stud_mob, $amount, $ms);

if ($stmt->execute()) {
    // Insert into chat table
    $sqlLog = "INSERT INTO chat (`sent_by`, `recieved_by`, `msg`, `category`) VALUES (?, ?, ?, ?)";
    $stmtLog = $link->prepare($sqlLog);
    $category = '0'; // Set default category value
    $stmtLog->bind_param("ssss", $u_uname, $stud_mob, $ms, $category);
    
    if ($stmtLog->execute()) {
        // Redirect to success page
        header('Location: api.php?success=1002&mobile=' . $stud_mob);
        ob_end_flush();
        exit;
    } else {
        die("Chat table insertion failed: " . $stmtLog->error);
    }
} else {
    die("Fees log insertion failed: " . $stmt->error);
}
?>
