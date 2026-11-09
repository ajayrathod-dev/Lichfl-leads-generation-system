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

include('header.php');
?>

<style>
    .chat-container {
        height: calc(100vh - 200px);
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
    .user-info-card {
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
    
    /* Customer List Styles */
    .customer-list {
        max-height: 400px;
        overflow-y: auto;
    }
    .customer-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 10px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .customer-item:hover {
        background-color: #f8f9fa;
        border-color: #007bff;
    }
    .customer-item.active {
        background-color: #e7f3ff;
        border-color: #007bff;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .chat-container {
            height: calc(100vh - 180px);
            padding: 10px;
        }
        .chat-bubble {
            max-width: 90%;
        }
        .customer-list {
            max-height: 300px;
        }
        .row {
            margin: 0 -5px;
        }
        .col-12, .col-lg-4, .col-lg-8 {
            padding: 0 5px;
        }
    }
    
    @media (max-width: 576px) {
        .chat-container {
            height: calc(100vh - 160px);
            padding: 8px;
        }
        .customer-item {
            padding: 8px;
        }
        .message-content {
            font-size: 0.9rem;
        }
    }
</style>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Mobile Header -->
        <div class="mobile-only mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0">Chat Dashboard</h5>
                    <small class="text-muted">Communicate with customers</small>
                </div>
                <a href="info.php" class="btn btn-outline-secondary btn-sm">
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
                            <li class="breadcrumb-item active" aria-current="page">View Chat</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Customer List Column -->
            <div class="col-12 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class='bx bx-user-circle me-2'></i>Customers
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="customer-list">
                            <?php
                            // FIXED: Correct SQL query - using recieved_by instead of mobile_no
                            $sqlCustomers = "SELECT DISTINCT c.recieved_by as mobile_no, u.name, u.description 
                                           FROM chat c 
                                           LEFT JOIN user u ON c.recieved_by = u.mobile_no 
                                           WHERE c.sent_by = ? 
                                           ORDER BY c.id DESC";
                            $stmtCustomers = $link->prepare($sqlCustomers);
                            $stmtCustomers->bind_param("s", $u_uname);
                            $stmtCustomers->execute();
                            $resultCustomers = $stmtCustomers->get_result();

                            if ($resultCustomers->num_rows > 0) {
                                while ($customer = $resultCustomers->fetch_assoc()) {
                                    $mobile = $customer['mobile_no'];
                                    $name = $customer['name'] ?: 'Customer';
                                    $desc = $customer['description'];
                                    ?>
                                    <div class="customer-item <?php echo (isset($_GET['mobile']) && $_GET['mobile'] == $mobile) ? 'active' : ''; ?>" 
                                         onclick="loadChat('<?php echo $mobile; ?>')">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle p-2 me-3 flex-shrink-0">
                                                <i class='bx bx-user text-white'></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-truncate"><?php echo htmlspecialchars($name); ?></h6>
                                                <p class="text-muted mb-0 small">
                                                    <i class='bx bx-phone'></i> <?php echo $mobile; ?>
                                                </p>
                                                <?php if($desc): ?>
                                                <small class="text-muted text-truncate d-block"><?php echo htmlspecialchars($desc); ?></small>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-end flex-shrink-0">
                                                <i class='bx bx-chevron-right text-muted'></i>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<div class="text-center text-muted py-4">No customers found. Start by creating a new loan.</div>';
                            }
                            $stmtCustomers->close();
                            ?>
                        </div>
                        
                        <!-- Quick Action Button -->
                        <div class="mt-3">
                            <a href="complaint.php" class="btn btn-outline-primary w-100">
                                <i class='bx bx-plus-circle me-1'></i> New Loan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Column -->
            <div class="col-12 col-lg-8">
                <?php if (isset($_GET['mobile'])): ?>
                    <!-- Show specific customer chat -->
                    <?php
                    $mobile = sanitize_input($_GET['mobile']);
                    
                    // Get customer details
                    $sqlCustomer = "SELECT * FROM user WHERE mobile_no = ?";
                    $stmtCustomer = $link->prepare($sqlCustomer);
                    $stmtCustomer->bind_param("s", $mobile);
                    $stmtCustomer->execute();
                    $resultCustomer = $stmtCustomer->get_result();
                    
                    $customerName = 'Customer';
                    $customerDesc = '';
                    if ($resultCustomer->num_rows > 0) {
                        $customerData = $resultCustomer->fetch_assoc();
                        $customerName = $customerData['name'] ?: 'Customer';
                        $customerDesc = $customerData['description'];
                    }
                    ?>
                    
                    <div class="card">
                        <div class="card-header bg-transparent">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle p-2 me-3 flex-shrink-0">
                                    <i class='bx bx-user text-white'></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0"><?php echo htmlspecialchars($customerName); ?></h5>
                                    <p class="text-muted mb-0">
                                        <i class='bx bx-phone'></i> <?php echo $mobile; ?>
                                        <?php if($customerDesc): ?>
                                        <span class="badge bg-light text-dark ms-2"><?php echo htmlspecialchars($customerDesc); ?></span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="complaint.php?mobile=<?php echo $mobile; ?>" class="btn btn-primary btn-sm me-2">
                                        <i class='bx bx-plus-circle me-1'></i> New Loan
                                    </a>
                                    <a href="chat_dashboard.php" class="btn btn-outline-secondary btn-sm">
                                        <i class='bx bx-x'></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="chat-container" id="chatContainer">
                                <?php 
                                // Get messages for this customer
                                $sqlMessages = "SELECT * FROM chat WHERE recieved_by = ? ORDER BY id ASC";
                                $stmtMessages = $link->prepare($sqlMessages);
                                $stmtMessages->bind_param("s", $mobile);
                                $stmtMessages->execute();
                                $resultMessages = $stmtMessages->get_result();

                                if ($resultMessages->num_rows > 0) {
                                    while ($message = $resultMessages->fetch_assoc()) {
                                        $sent_by = sanitize_input($message['sent_by']);
                                        $msg_content = htmlspecialchars_decode($message['msg'], ENT_QUOTES);
                                        $datetimeFromDB = sanitize_input($message['modified_datetime']);
                                        $timestamp = $datetimeFromDB ? strtotime($datetimeFromDB) : strtotime($message['date']);
                                        $formattedTime = date('d-m-Y g:i a', $timestamp);
                                        ?>
                                        <div class="d-flex mb-3 <?php echo ($sent_by == $u_uname) ? 'justify-content-end' : 'justify-content-start'; ?>">
                                            <div class="chat-bubble <?php echo ($sent_by == $u_uname) ? 'sent-message' : 'received-message'; ?> rounded-3 p-3">
                                                <?php if($sent_by != $u_uname): ?>
                                                <div class="message-header">
                                                    <?php  
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
                                    echo '<div class="text-center text-muted py-5">No messages yet. Start a conversation!</div>';
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
                                           placeholder="Type your message here..." aria-describedby="button-addon2" required>
                                    <button type="submit" class="btn btn-primary" id="button-addon2">
                                        <i class='bx bx-send'></i> Send
                                    </button>
                                </div>
                            </form>
                            
                            <a href="complaint.php?mobile=<?php echo $mobile; ?>" 
                               class="btn btn-success w-100 d-flex align-items-center justify-content-center">
                                <i class='bx bx-plus-circle me-2'></i> Register New Loan
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Show welcome message when no customer selected -->
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class='bx bx-chat fs-1 text-primary'></i>
                            </div>
                            <h4>Welcome to Chat</h4>
                            <p class="text-muted mb-4">Select a customer from the list to start chatting or view conversation history</p>
                            <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                                <a href="info.php" class="btn btn-outline-primary">
                                    <i class='bx bx-arrow-back me-1'></i> Back to Dashboard
                                </a>
                                <a href="complaint.php" class="btn btn-primary">
                                    <i class='bx bx-plus-circle me-1'></i> Create New Loan
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function loadChat(mobile) {
    window.location.href = 'chat_dashboard.php?mobile=' + mobile;
}

// Auto-scroll to bottom of chat when page loads
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.getElementById('chatContainer');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
    
    // Mobile-specific enhancements
    if (window.innerWidth <= 768) {
        // Add touch improvements for mobile
        const customerItems = document.querySelectorAll('.customer-item');
        customerItems.forEach(item => {
            item.style.cursor = 'pointer';
        });
    }
});

// Handle responsive behavior
window.addEventListener('resize', function() {
    // Adjust layout for mobile
    if (window.innerWidth <= 768) {
        document.querySelector('.customer-list').style.maxHeight = '300px';
    } else {
        document.querySelector('.customer-list').style.maxHeight = '400px';
    }
});
</script>

<?php include('footer.php'); ?>