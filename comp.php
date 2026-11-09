<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();
session_start();
include("connection.php");

// Set UTF-8 encoding for proper character handling
header('Content-Type: text/html; charset=utf-8');
mysqli_set_charset($link, "utf8mb4"); // Changed to utf8mb4 for full UTF-8 support

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
    ob_end_flush();
    exit;
}

// FIXED: Preserve Marathi characters by not using sanitize_input on text fields
$mobile = sanitize_input($_POST['mobile']);
$reg = sanitize_input($_POST['reg']);
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$village = isset($_POST['village']) ? trim($_POST['village']) : '';
$taluka = isset($_POST['taluka']) ? trim($_POST['taluka']) : '';
$district = isset($_POST['destrict']) ? trim($_POST['destrict']) : '';
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$assign_to = sanitize_input($_POST['assign_to']);
$loan_amount = isset($_POST['loan_amount']) ? (float) $_POST['loan_amount'] : 0.0; // Removed sanitize_input
$loan_interest = isset($_POST['loan_interest']) ? sanitize_input($_POST['loan_interest']) : 'Pending';

// Loan type handling - FIXED: Remove sanitize_input to preserve Marathi text
$loan_type = isset($_POST['loan_type']) ? $_POST['loan_type'] : '';
$other_loan_type = isset($_POST['other_loan_type']) ? $_POST['other_loan_type'] : '';

// If agent selected Other, use the provided text
if (strtolower($loan_type) === 'other' && !empty($other_loan_type)) {
    $loan_type = $other_loan_type;
}

// FIXED: Escape text fields properly for SQL while preserving UTF-8
$name_escaped = mysqli_real_escape_string($link, $name);
$village_escaped = mysqli_real_escape_string($link, $village);
$taluka_escaped = mysqli_real_escape_string($link, $taluka);
$district_escaped = mysqli_real_escape_string($link, $district);
$title_escaped = mysqli_real_escape_string($link, $title);
$loan_type_escaped = mysqli_real_escape_string($link, $loan_type);

// Generate unique complaint ID
$complaint_id = 'COMP' . date('YmdHis') . rand(100, 999);

// Get current timestamp
$current_datetime = date('Y-m-d H:i:s');

// OPTION 1: Try with 'dist' (common abbreviation)
try {
    $sql = "INSERT INTO complaint (mobile_no, registred_by, name, village, taluka, dist, complaint_title, loan_type, assign_to, complaint_id, category, date, loan_amount, loan_interest) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $link->prepare($sql);
    $category = 1;
    // FIXED: Use original variables (not escaped) for prepared statements
    $stmt->bind_param("ssssssssssssds", $mobile, $reg, $name, $village, $taluka, $district, $title, $loan_type, $assign_to, $complaint_id, $category, $current_datetime, $loan_amount, $loan_interest);
    
    if ($stmt->execute()) {
        // Success - continue with the rest of the code
        $loanMessage = "Loan Application Registered Successfully!\n\n" .
                       "Loan ID: $complaint_id\n" .
                       "Customer: $name\n" .
                       "Mobile: $mobile\n";
        
        if (!empty($village) || !empty($taluka) || !empty($district)) {
            $loanMessage .= "Address: ";
            $addressParts = [];
            if (!empty($village)) $addressParts[] = $village;
            if (!empty($taluka)) $addressParts[] = $taluka;
            if (!empty($district)) $addressParts[] = $district;
            $loanMessage .= implode(", ", $addressParts) . "\n";
        }
        
        $loanMessage .= "Loan Type: $loan_type\n" .
                       "Loan Amount: ₹$loan_amount\n" .
                       "Interest Status: $loan_interest\n" .
                       "Loan Purpose: $title\n" .
                       "Assigned To: $assign_to\n\n" .
                       "We will update you on the progress shortly. Thank you!";
        
        $sqlChat = "INSERT INTO chat (sent_by, recieved_by, msg, category, complaint_id, date) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtChat = $link->prepare($sqlChat);
        $stmtChat->bind_param("ssssss", $reg, $mobile, $loanMessage, $category, $complaint_id, $current_datetime);
        $stmtChat->execute(); 

        // Also update user table with 'dist'
        $sqlUserUpdate = "UPDATE user SET name=?, village=?, taluka=?, dist=? WHERE mobile_no=?";
        $stmtUserUpdate = $link->prepare($sqlUserUpdate);
        $stmtUserUpdate->bind_param("sssss", $name, $village, $taluka, $district, $mobile);
        $stmtUserUpdate->execute();

        // Insert initial status history
        $sqlHistory = "INSERT INTO loan_status_history (complaint_id, status, remarks, changed_by, changed_at) VALUES (?, ?, ?, ?, ?)";
        $stmtHistory = $link->prepare($sqlHistory);
        $initialRemarks = "Loan application registered";
        $stmtHistory->bind_param("sisss", $complaint_id, $category, $initialRemarks, $reg, $current_datetime);
        $stmtHistory->execute();

        header('Location: api.php?success=1002&mobile=' . urlencode($mobile));
        ob_end_flush();
        exit;
    }
} catch (Exception $e) {
    // OPTION 2: If 'dist' fails, try without district column entirely
    try {
        $sql = "INSERT INTO complaint (mobile_no, registred_by, name, village, taluka, complaint_title, loan_type, assign_to, complaint_id, category, date, loan_amount, loan_interest) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $link->prepare($sql);
        $category = 1;
        $stmt->bind_param("sssssssssssds", $mobile, $reg, $name, $village, $taluka, $title, $loan_type, $assign_to, $complaint_id, $category, $current_datetime, $loan_amount, $loan_interest);
        
        if ($stmt->execute()) {
            // Success - continue with the rest of the code (without district in message)
            $loanMessage = "Loan Application Registered Successfully!\n\n" .
                           "Loan ID: $complaint_id\n" .
                           "Customer: $name\n" .
                           "Mobile: $mobile\n";
            
            if (!empty($village) || !empty($taluka)) {
                $loanMessage .= "Address: ";
                $addressParts = [];
                if (!empty($village)) $addressParts[] = $village;
                if (!empty($taluka)) $addressParts[] = $taluka;
                $loanMessage .= implode(", ", $addressParts) . "\n";
            }
            
            $loanMessage .= "Loan Type: $loan_type\n" .
                           "Loan Amount: ₹$loan_amount\n" .
                           "Interest Status: $loan_interest\n" .
                           "Loan Purpose: $title\n" .
                           "Assigned To: $assign_to\n\n" .
                           "We will update you on the progress shortly. Thank you!";
            
            $sqlChat = "INSERT INTO chat (sent_by, recieved_by, msg, category, complaint_id, date) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtChat = $link->prepare($sqlChat);
            $stmtChat->bind_param("ssssss", $reg, $mobile, $loanMessage, $category, $complaint_id, $current_datetime);
            $stmtChat->execute(); 

            // Update user table without district
            $sqlUserUpdate = "UPDATE user SET name=?, village=?, taluka=? WHERE mobile_no=?";
            $stmtUserUpdate = $link->prepare($sqlUserUpdate);
            $stmtUserUpdate->bind_param("ssss", $name, $village, $taluka, $mobile);
            $stmtUserUpdate->execute();

            // Insert initial status history
            $sqlHistory = "INSERT INTO loan_status_history (complaint_id, status, remarks, changed_by, changed_at) VALUES (?, ?, ?, ?, ?)";
            $stmtHistory = $link->prepare($sqlHistory);
            $initialRemarks = "Loan application registered";
            $stmtHistory->bind_param("sisss", $complaint_id, $category, $initialRemarks, $reg, $current_datetime);
            $stmtHistory->execute();

            header('Location: api.php?success=1002&mobile=' . urlencode($mobile));
            ob_end_flush();
            exit;
        }
    } catch (Exception $e2) {
        // Final fallback - log error and redirect
        error_log("Complaint registration failed for mobile: $mobile, Error: " . $e2->getMessage());
        header('Location: complaint.php?mobile=' . urlencode($mobile) . '&error=1002');
        ob_end_flush();
        exit;
    }
}

// Close connections
if (isset($stmt)) $stmt->close();
$link->close();
?>