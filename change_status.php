<?php
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
    exit;
}

// Get parameters
$complaint_id = sanitize_input($_GET['id']);
$status = sanitize_input($_GET['status']);
$remarks = sanitize_input($_GET['remarks']);

// Get complaint details
$sqlComplaint = "SELECT mobile_no, name FROM complaint WHERE complaint_id=?";
$stmtComplaint = $link->prepare($sqlComplaint);
$stmtComplaint->bind_param("s", $complaint_id);
$stmtComplaint->execute();
$resultComplaint = $stmtComplaint->get_result();

if ($resultComplaint->num_rows > 0) {
    $complaint = $resultComplaint->fetch_assoc();
    $mobile_no = $complaint['mobile_no'];
    $customer_name = $complaint['name'];
    
    // Update complaint status
    $sqlUpdate = "UPDATE complaint SET category=? WHERE complaint_id=?";
    $stmtUpdate = $link->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ss", $status, $complaint_id);
    
    if ($stmtUpdate->execute()) {
        // Insert status history
        $sqlHistory = "INSERT INTO loan_status_history (complaint_id, status, remarks, changed_by, changed_at) VALUES (?, ?, ?, ?, NOW())";
        $stmtHistory = $link->prepare($sqlHistory);
        $stmtHistory->bind_param("siss", $complaint_id, $status, $remarks, $u_uname);
        $stmtHistory->execute();
        
        // Get status label for chat message
        $statusLabels = [
            '1' => 'Enquiry',
            '2' => 'Document Collection',
            '3' => 'Bank Submission', 
            '4' => 'Sanction',
            '5' => 'Disbursed',
            '6' => 'Canceled'
        ];
        $statusLabel = $statusLabels[$status] ?? 'Unknown';
        
        // Post status update to chat
        $statusMessage = "📊 *Loan Status Updated!*\n\n" .
                        "📋 *Loan ID:* $complaint_id\n" .
                        "👤 *Customer:* $customer_name\n" .
                        "🔄 *New Status:* $statusLabel\n" .
                        "💬 *Remarks:* $remarks\n" .
                        "👨‍💼 *Updated by:* $u_uname\n\n" .
                        "We'll keep you updated on further progress.";
        
        $sqlChat = "INSERT INTO chat (sent_by, recieved_by, msg, category, complaint_id, date) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmtChat = $link->prepare($sqlChat);
        $stmtChat->bind_param("sssss", $u_uname, $mobile_no, $statusMessage, $status, $complaint_id);
        $stmtChat->execute();
        
        header('Location: info.php?success=1717');
        exit;
    } else {
        header('Location: info.php?error=3');
        exit;
    }
} else {
    header('Location: info.php?error=3');
    exit;
}

$link->close();
?>