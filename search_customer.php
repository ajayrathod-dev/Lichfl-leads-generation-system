<?php
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
$stmt4->bind_param("s", $u_uname);
$stmt4->execute();
$result4 = $stmt4->get_result();

if ($result4->num_rows > 0) {
    while ($row4 = $result4->fetch_assoc()) {
        $u_uname = sanitize_input($row4['user_id']);
    }
} else {
    die("Unauthorized access");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mobile'])) {
    $mobile = sanitize_input($_POST['mobile']);
    
    // Search in complaint table
    $sql = "SELECT c.*, u.name, u.description 
            FROM complaint c 
            LEFT JOIN user u ON c.mobile_no = u.mobile_no 
            WHERE c.mobile_no = ? 
            ORDER BY c.date DESC 
            LIMIT 1";
    
    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
        
        // Format the date
        $loanDate = date('d-m-Y', strtotime($customer['date']));
        $loanType = $customer['loan_type'] ?: 'Not specified';
        $customerName = $customer['name'] ?: 'Customer';
        $description = $customer['description'] ?: 'No description';
        
        echo "
        <div class='customer-result'>
            <div class='customer-info'>
                <div class='customer-details'>
                    <h6><i class='bx bx-user me-2'></i>{$customerName}</h6>
                    <div class='customer-meta'>
                        <div><i class='bx bx-phone me-1'></i> {$mobile}</div>
                        <div><i class='bx bx-calendar me-1'></i> Loan Created: {$loanDate}</div>
                        <div><i class='bx bx-category me-1'></i> Loan Type: {$loanType}</div>
                        <div><i class='bx bx-file me-1'></i> {$description}</div>
                    </div>
                </div>
                <div>
                    <a href='customer_chat.php?mobile={$mobile}' class='btn chat-btn'>
                        <i class='bx bx-chat me-1'></i> Start Chat
                    </a>
                </div>
            </div>
        </div>";
    } else {
        echo "
        <div class='no-results'>
            <i class='bx bx-user-x fs-1 text-muted mb-3'></i>
            <h5>No Customer Found</h5>
            <p class='text-muted'>No loan application found for mobile number: {$mobile}</p>
            <a href='complaint.php?mobile={$mobile}' class='btn btn-primary mt-2'>
                <i class='bx bx-plus-circle me-1'></i> Create New Loan
            </a>
        </div>";
    }
    
    $stmt->close();
} else {
    echo "<div class='alert alert-warning'>Invalid request</div>";
}

$link->close();
?>