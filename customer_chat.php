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
    header("location:logout.php");
    exit;
}

// Check if mobile parameter is set
if (!isset($_GET['mobile'])) {
    header("location:start_chat.php");
    exit;
}

$mobile = sanitize_input($_GET['mobile']);

// Get customer details - first try from user table
$customerName = 'Customer';
$customerDesc = '';
$loanType = 'Not specified';
$loanDate = 'N/A';
$complaintId = 'N/A';

// Try to get basic info from user table first
$sqlUser = "SELECT * FROM user WHERE mobile_no = ?";
$stmtUser = $link->prepare($sqlUser);
$stmtUser->bind_param("s", $mobile);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

if ($resultUser->num_rows > 0) {
    $userData = $resultUser->fetch_assoc();
    $customerName = $userData['name'] ?: 'Customer';
    $customerDesc = $userData['description'] ?: '';
}

$stmtUser->close();

// Then try to get latest complaint details
$sqlCustomer = "SELECT c.*, u.name, u.description 
                FROM complaint c 
                LEFT JOIN user u ON c.mobile_no = u.mobile_no 
                WHERE c.mobile_no = ? 
                ORDER BY c.date DESC 
                LIMIT 1";
$stmtCustomer = $link->prepare($sqlCustomer);
$stmtCustomer->bind_param("s", $mobile);
$stmtCustomer->execute();
$resultCustomer = $stmtCustomer->get_result();

if ($resultCustomer->num_rows > 0) {
    $customerData = $resultCustomer->fetch_assoc();
    $customerName = $customerData['name'] ?: $customerName;
    $customerDesc = $customerData['description'] ?: $customerDesc;
    $loanType = $customerData['loan_type'] ?: 'Not specified';
    $loanDate = $customerData['date'] ? date('d-m-Y', strtotime($customerData['date'])) : 'N/A';
    $complaintId = $customerData['complaint_id'] ?: 'N/A';
}

include('header.php');
?>

<style>
    .chat-container {
        height: calc(100vh - 250px);
        overflow-y: auto;
        padding: 15px;
    }
    .message-card {
        border-radius: 12px;
        margin-bottom: 15px;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .message-header {
        font-size: 0.8rem;
        opacity: 0.8;
        margin-bottom: 5px;
    }
    .message-time {
        font-size: 0.7rem;
        opacity: 0.6;
    }
    .footer-form {
        background: white;
        padding: 15px;
        border-top: 1px solid #dee2e6;
        position: sticky;
        bottom: 0;
    }
    .customer-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .chat-bubble {
        max-width: 80%;
        position: relative;
    }
    .sent-message {
        margin-left: auto;
        background: #007bff;
        color: white;
    }
    .received-message {
        background: #f8f9fa;
        color: #333;
    }
    
    /* Floating Call Button */
    .floating-call-btn {
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        box-shadow: 0 4px 20px rgba(40, 167, 69, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        z-index: 1000;
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
    }
    .floating-call-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(40, 167, 69, 0.6);
    }
    @keyframes pulse {
        0% {
            box-shadow: 0 4px 20px rgba(40, 167, 69, 0.4);
        }
        50% {
            box-shadow: 0 4px 30px rgba(40, 167, 69, 0.8);
        }
        100% {
            box-shadow: 0 4px 20px rgba(40, 167, 69, 0.4);
        }
    }
    
    /* Call tooltip */
    .call-tooltip {
        position: absolute;
        bottom: 70px;
        right: 0;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    .floating-call-btn:hover .call-tooltip {
        opacity: 1;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .chat-container {
            height: calc(100vh - 220px);
            padding: 10px;
        }
        .chat-bubble {
            max-width: 90%;
        }
        .floating-call-btn {
            bottom: 80px;
            right: 20px;
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
    }
    
    @media (max-width: 576px) {
        .floating-call-btn {
            bottom: 70px;
            right: 15px;
            width: 45px;
            height: 45px;
            font-size: 18px;
        }
    }
</style>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Mobile Header -->
        <div class="mobile-only mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0">Customer Chat</h5>
                    <small class="text-muted">Communicate with <?php echo htmlspecialchars($customerName); ?></small>
                </div>
                <a href="start_chat.php" class="btn btn-outline-secondary btn-sm">
                    <i class='bx bx-arrow-back'></i>
                </a>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="desktop-only">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Communication</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="info.php"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="start_chat.php">Start Chat</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Chat</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Customer Info Card -->
                <div class="customer-info-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2"><?php echo htmlspecialchars($customerName); ?></h5>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-1"><i class='bx bx-phone'></i> <?php echo $mobile; ?></p>
                                    <p class="mb-1"><i class='bx bx-category'></i> <?php echo $loanType; ?></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1"><i class='bx bx-calendar'></i> <?php echo $loanDate; ?></p>
                                    <p class="mb-0"><i class='bx bx-id-card'></i> <?php echo $complaintId; ?></p>
                                </div>
                            </div>
                            <?php if($customerDesc): ?>
                            <p class="mb-0 mt-2"><i class='bx bx-info-circle'></i> <?php echo htmlspecialchars($customerDesc); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="complaint.php?mobile=<?php echo $mobile; ?>" class="btn btn-light me-2">
                                <i class='bx bx-plus-circle me-1'></i> New Loan
                            </a>
                            <a href="start_chat.php" class="btn btn-outline-light">
                                <i class='bx bx-arrow-back me-1'></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Chat Container -->
                <div class="card">
                    <div class="card-body p-0">
                        <div class="chat-container" id="chatContainer">
                            <?php 
                            // Get ALL chat history for this mobile number (GLOBAL CHAT)
                            $sqlMessages = "SELECT * FROM chat 
                                            WHERE recieved_by = ? OR sent_by = ?
                                            ORDER BY id ASC";
                            $stmtMessages = $link->prepare($sqlMessages);
                            $stmtMessages->bind_param("ss", $mobile, $mobile);
                            $stmtMessages->execute();
                            $resultMessages = $stmtMessages->get_result();

                            if ($resultMessages->num_rows > 0) {
                                while ($message = $resultMessages->fetch_assoc()) {
                                    $sent_by = sanitize_input($message['sent_by']);
                                    $received_by = sanitize_input($message['recieved_by']);
                                    $msg_content = htmlspecialchars_decode($message['msg'], ENT_QUOTES);
                                    $datetimeFromDB = sanitize_input($message['modified_datetime']);
                                    $timestamp = $datetimeFromDB ? strtotime($datetimeFromDB) : strtotime($message['date']);
                                    $formattedTime = date('d-m-Y g:i a', $timestamp);
                                    
                                    // Determine if message was sent by current user or received from customer
                                    $isSentByCurrentUser = ($sent_by == $u_uname);
                                    $isCustomerMessage = ($sent_by == $mobile);
                                    ?>
                                    <div class="d-flex mb-3 <?php echo $isSentByCurrentUser ? 'justify-content-end' : 'justify-content-start'; ?>">
                                        <div class="chat-bubble <?php echo $isSentByCurrentUser ? 'sent-message' : 'received-message'; ?> rounded-3 p-3">
                                            <?php if(!$isSentByCurrentUser): ?>
                                            <div class="message-header">
                                                <?php  
                                                if ($isCustomerMessage) {
                                                    // Message from customer
                                                    echo htmlspecialchars($customerName) . " (Customer)";
                                                } else {
                                                    // Message from another user/admin
                                                    $sqlSender = "SELECT * FROM admin WHERE user_id=?";
                                                    $stmtSender = $link->prepare($sqlSender);
                                                    $stmtSender->bind_param("s", $sent_by);
                                                    $stmtSender->execute();
                                                    $resultSender = $stmtSender->get_result();
                                                    if ($resultSender->num_rows > 0) {
                                                        $sender = $resultSender->fetch_assoc();
                                                        echo htmlspecialchars($sender['name']) . " (" . $sent_by . ")";
                                                    } else {
                                                        echo $sent_by;
                                                    }
                                                    $stmtSender->close();
                                                }
                                                ?>
                                            </div>
                                            <?php endif; ?>
                                            <div class="message-content"><?php echo nl2br($msg_content); ?></div>
                                            <div class="message-time text-end mt-2"><?php echo $formattedTime; ?></div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<div class="text-center text-muted py-5">
                                        <i class="bx bx-message-rounded-detail fs-1 mb-3"></i>
                                        <h5>No Messages Yet</h5>
                                        <p>Start the conversation by sending a message below.</p>
                                      </div>';
                            }
                            $stmtMessages->close();
                            ?>
                        </div>
                    </div>
                    
                    <!-- Message Form -->
                    <div class="footer-form">
                        <form action="send.php" method="post" class="mb-3">
                            <input type="hidden" name="send_by" value="<?php echo $u_uname; ?>">
                            <input type="hidden" name="recieved_by" value="<?php echo $mobile; ?>">
                            
                            <div class="input-group">
                                <input type="text" class="form-control border-primary" name="msg" 
                                       placeholder="Type your message or remark about loan status..." 
                                       aria-describedby="button-addon2" required>
                                <button type="submit" class="btn btn-primary" id="button-addon2">
                                    <i class='bx bx-send'></i> Send
                                </button>
                            </div>
                        </form>
                        
                        <div class="d-flex gap-2">
                            <a href="complaint.php?mobile=<?php echo $mobile; ?>" 
                               class="btn btn-success flex-fill d-flex align-items-center justify-content-center">
                                <i class='bx bx-plus-circle me-2'></i> Register New Loan
                            </a>
                            <a href="start_chat.php" 
                               class="btn btn-outline-secondary d-flex align-items-center justify-content-center">
                                <i class='bx bx-search me-2'></i> Search Another
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Call Button -->
<button class="floating-call-btn" onclick="callCustomer('<?php echo $mobile; ?>')" title="Call <?php echo htmlspecialchars($customerName); ?>">
    <i class='bx bx-phone'></i>
    <div class="call-tooltip">Call <?php echo htmlspecialchars($customerName); ?></div>
</button>

<script>
// Auto-scroll to bottom of chat when page loads
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.getElementById('chatContainer');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
});

// Call customer function
function callCustomer(mobileNumber) {
    if (confirm('Call ' + mobileNumber + '?')) {
        window.open('tel:' + mobileNumber, '_self');
    }
}

// Auto-refresh chat every 10 seconds (optional)
setInterval(function() {
    const chatContainer = document.getElementById('chatContainer');
    const currentScroll = chatContainer.scrollTop;
    const isScrolledToBottom = chatContainer.scrollHeight - chatContainer.clientHeight <= currentScroll + 10;
    
    // Reload the chat messages (you might want to implement AJAX for better performance)
    if (isScrolledToBottom) {
        location.reload();
    }
}, 10000);

// Add click sound effect for call button (optional)
document.querySelector('.floating-call-btn').addEventListener('click', function() {
    // You can add a click sound here if desired
    console.log('Calling customer: <?php echo $mobile; ?>');
});
</script>

<?php 
$stmtCustomer->close();
$link->close();
include('footer.php'); 
?>