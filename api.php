<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
include("connection.php");
include("header.php");
$mobile = sanitize_input($_GET['mobile']);

if (isset($_COOKIE['uuid'])) {
    $u_uname = sanitize_input($_COOKIE['uuid']);
} else {
    $u_uname = '';
}
 
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

$sql4444 = "SELECT * FROM user WHERE mobile_no=?";
$stmt4444 = $link->prepare($sql4444);
$stmt4444->bind_param("s", $mobile);
$stmt4444->execute();
$result4444 = $stmt4444->get_result();

if ($result4444->num_rows == 0) {
    $sql = "INSERT INTO user (mobile_no) VALUES (?)";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
}

$stmt4444->execute();
$result4444 = $stmt4444->get_result();

if ($result4444->num_rows > 0) {
    while ($row4444 = $result4444->fetch_assoc()) {
        $name = sanitize_input($row4444['name']);
        $mobile_no = sanitize_input($row4444['mobile_no']);
        $desc = sanitize_input($row4444['description']);
    }
}

$sqlqqq = "UPDATE user SET modified_datetime = `date` + INTERVAL 12 HOUR + INTERVAL 30 MINUTE WHERE modified_datetime IS NULL";
$stmtqqq = $link->prepare($sqlqqq);
$stmtqqq->execute();
$sqlaaa = "UPDATE complaint SET modified_datetime = `date` + INTERVAL 12 HOUR + INTERVAL 30 MINUTE WHERE modified_datetime IS NULL";
$stmtaaa = $link->prepare($sqlaaa);
$stmtaaa->execute();
$sqlsss = "UPDATE chat SET modified_datetime = `date` + INTERVAL 12 HOUR + INTERVAL 30 MINUTE WHERE modified_datetime IS NULL";
$stmtsss = $link->prepare($sqlsss);
$stmtsss->execute();

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="" type="image/png" />
    <link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/plugins/notifications/css/lobibox.min.css" />
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dark-theme.css" />
    <link rel="stylesheet" href="assets/css/semi-dark.css" />
    <link rel="stylesheet" href="assets/css/header-colors.css" />
    <title></title>
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
        .status-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }
        .category-1 { background-color: #dc3545; }
        .category-2 { background-color: #ffc107; }
        .category-3 { background-color: #198754; }
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
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .chat-container {
                height: calc(100vh - 180px);
                padding: 10px;
            }
            .chat-bubble {
                max-width: 90%;
            }
            .user-info-card {
                margin-bottom: 15px;
                padding: 12px;
            }
            .footer-form {
                padding: 12px;
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
            .chat-container {
                height: calc(100vh - 160px);
                padding: 8px;
            }
            .chat-bubble {
                max-width: 95%;
            }
            .message-content {
                font-size: 0.9rem;
            }
            .floating-call-btn {
                bottom: 70px;
                right: 15px;
                width: 45px;
                height: 45px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <!--<div class="sidebar-wrapper" data-simplebar="true">-->
        <!--    <div class="sidebar-header">-->
        <!--        <div>-->
        <!--            <h4 class="logo-text">Loan</h4>-->
        <!--            <small class="text-muted">Customer Portal</small>-->
        <!--        </div>-->
        <!--        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i></div>-->
        <!--    </div>-->
            
        <!--    <ul class="metismenu" id="menu">-->
        <!--        <li>-->
        <!--            <a href="info.php">-->
        <!--                <div class="parent-icon"><i class='bx bx-home-circle'></i></div>-->
        <!--                <div class="menu-title">Dashboard</div>-->
        <!--            </a>-->
        <!--        </li>-->
        <!--        <li>-->
        <!--            <a href="complaint.php">-->
        <!--                <div class="parent-icon"><i class='bx bx-message-square-add'></i></div>-->
        <!--                <div class="menu-title">New Loan</div>-->
        <!--            </a>-->
        <!--        </li>-->
        <!--        <li>-->
        <!--            <a href="api.php">-->
        <!--                <div class="parent-icon"><i class='bx bx-chat'></i></div>-->
        <!--                <div class="menu-title">View Chat</div>-->
        <!--            </a>-->
        <!--        </li>-->
        <!--        <li>-->
        <!--            <a href="logout.php">-->
        <!--                <div class="parent-icon"><i class='bx bx-log-out-circle'></i></div>-->
        <!--                <div class="menu-title">Logout</div>-->
        <!--            </a>-->
        <!--        </li>-->
        <!--    </ul>-->
        <!--</div>-->

         Main Content 
        <div class="page-wrapper">
             Header 
            <header>
                <div class="topbar d-flex align-items-center">
                    <nav class="navbar navbar-expand w-100">
                        <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
                        
                        <div class="user-profile ms-3">
                            <div class="d-flex align-items-center">
                                <div class="user-info">
                                    <h6 class="mb-0"><?php echo $name ?: 'Customer'; ?></h6>
                                    <small class="text-muted">
                                        <a href="tel:<?php echo $mobile; ?>" class="text-decoration-none">
                                            <i class='bx bx-phone'></i> <?php echo $mobile; ?>
                                        </a>
                                        <a href="edit.php?mob=<?php echo $mobile; ?>" class="ms-2 text-decoration-none">
                                            <i class='bx bx-edit'></i> Edit
                                        </a>
                                        <a href="chat_dashboard.php" class="ms-2 text-decoration-none">
                                            <i class='bx bx-arrow-back'></i> Back to Chats
                                        </a>
                                    </small>
                                    <?php if($desc): ?>
                                    <div class="user-description mt-1">
                                        <span class="badge bg-light text-dark"><?php echo $desc; ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </header>

            <!-- Chat Content -->
            <div class="page-content">
                <div class="container-fluid">
                    <!-- User Info Card -->
                    <div class="user-info-card">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-1">Welcome, <?php echo $name ?: 'Customer'; ?></h5>
                                <p class="mb-0">We're here to help you with your loan queries</p>
                            </div>
                            <div class="col-auto">
                                <div class="bg-white rounded-circle p-2">
                                    <i class='bx bx-chat text-primary fs-4'></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="chat-container" id="chatContainer">
                                <?php 
                                // FIXED: Show only messages for this specific customer
                                $sql44 = "SELECT * FROM chat WHERE recieved_by = ? ORDER BY id ASC";
                                $stmt44 = $link->prepare($sql44);
                                $stmt44->bind_param("s", $mobile);
                                $stmt44->execute();
                                $result44 = $stmt44->get_result();

                                if ($result44->num_rows > 0) {
                                    while ($row44 = $result44->fetch_assoc()) {
                                        $cat = sanitize_input($row44['category']);
                                        $sent_by = sanitize_input($row44['sent_by']);
                                        // FIXED: Use raw message without decoding
                                        $message = $row44['msg'];
                                        $datetimeFromDB = sanitize_input($row44['modified_datetime']);
                                        $timestamp = $datetimeFromDB ? strtotime($datetimeFromDB) : strtotime($row44['date']);
                                        $formattedTime = date('d-m-Y g:i a', $timestamp);
                                ?>
                                <div class="d-flex mb-3 <?php echo ($sent_by == $u_uname) ? 'justify-content-end' : 'justify-content-start'; ?>">
                                    <div class="chat-bubble <?php echo ($sent_by == $u_uname) ? 'sent-message' : 'received-message'; ?> rounded-3 p-3">
                                        <?php if($sent_by != $u_uname): ?>
                                        <div class="message-header">
                                            <?php  
                                            $sql444 = "SELECT * FROM admin WHERE user_id=?";
                                            $stmt444 = $link->prepare($sql444);
                                            $stmt444->bind_param("s", $sent_by);
                                            $stmt444->execute();
                                            $result444 = $stmt444->get_result();
                                            if ($result444->num_rows > 0) {
                                                while ($row444 = $result444->fetch_assoc()) {
                                                    echo sanitize_input($row444['name']) . " (" . $sent_by . ")";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php endif; ?>
                                        <!-- FIXED: Remove htmlspecialchars_decode to preserve UTF-8 encoding -->
                                        <div class="message-content"><?php echo nl2br($message); ?></div>
                                        <div class="message-time text-end mt-2"><?php echo $formattedTime; ?></div>
                                    </div>
                                </div>
                                <?php 
                                    }
                                } else {
                                    echo '<div class="text-center text-muted py-5">No messages yet. Start a conversation!</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer with Message Form -->
            <footer class="footer-form">
                <div class="container-fluid">
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
                </div>
            </footer>
        </div>
    </div>

    <!-- Floating Call Button -->
    <button class="floating-call-btn" onclick="callCustomer('<?php echo $mobile; ?>')" title="Call <?php echo htmlspecialchars($name ?: 'Customer'); ?>">
        <i class='bx bx-phone'></i>
    </button>

    <!-- Scripts -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="assets/plugins/notifications/js/lobibox.min.js"></script>
    <script src="assets/plugins/notifications/js/notifications.min.js"></script>
    <script src="assets/js/app.js"></script>

    <script>
        // Auto-scroll to bottom of chat
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

        // Notification handling
        <?php if(isset($_GET['success'])): ?>
        window.onload = function() {
            <?php
            $successMessages = [
                1 => 'User Login Successfully.',
                11 => 'Notice Uploaded Successfully.',
                1717 => 'Status Changed Successfully.',
                42 => 'Batch Scheduled Successfully.',
                333 => 'Record Deleted Successfully.',
                300 => 'Teaching Staff Added Successfully.',
                200 => 'Photo Added Successfully.',
                21 => 'Product Details Added Successfully.',
                210 => 'Category Added Successfully.',
                41 => 'Free Demo Session Added Successfully.',
                6 => 'Bill Paid Successfully.',
                1002 => 'Loan Registered Successfully.'
            ];
            
            $errorMessages = [
                11 => 'Notice Not Uploaded.',
                17170 => 'UTR Already Exists.',
                42 => 'Batch Not Scheduled.',
                3 => 'Status Not Changed.',
                66 => 'Bill Already Paid.',
                33 => 'Password Mismatched.',
                300 => 'Staff Not Added.',
                200 => 'Photo Not Added.',
                21 => 'Product Not Added.',
                210 => 'Category Not Added.',
                41 => 'Demo Session Not Added.',
                6 => 'Bill Not Paid.',
                1002 => 'Failed to Register Loan.'
            ];
            
            if($_GET['success'] && isset($successMessages[$_GET['success']])) {
                echo "Lobibox.notify('success', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-check-circle',
                    msg: '{$successMessages[$_GET['success']]}'
                });";
            }
            
            if(isset($_GET['error']) && isset($errorMessages[$_GET['error']])) {
                echo "Lobibox.notify('error', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-x-circle',
                    msg: '{$errorMessages[$_GET['error']]}'
                });";
            }
            ?>
        };
        <?php endif; ?>
    </script>
</body>
</html>