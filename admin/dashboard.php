<?php
session_start();
include("connection.php");

// Check if user is logged in
if(!isset($_SESSION['admin_logged_in'])) {
    if (isset($_COOKIE['uuid'])) {
        $u_uname = sanitize_input($_COOKIE['uuid']);
        $sql4 = "SELECT * FROM ods WHERE uuid=? AND status='verified'";
        $stmt4 = $link->prepare($sql4);
        $stmt4->bind_param("s", $u_uname);
        $stmt4->execute();
        $result4 = $stmt4->get_result();

        if ($result4->num_rows > 0) {
            while ($row4 = $result4->fetch_assoc()) {
                $u_uname = sanitize_input($row4['user_id']);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user_id'] = $u_uname;
            }
        } else {
            header("location:logout.php");
            exit;
        }
    } else {
        header("location:login.php");
        exit;
    }
}

include('header.php');
?>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dashboard</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- Statistics Cards -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mb-4">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Agents</p>
                                <h4 class="my-1 text-primary"><?php echo getTotalAgents(); ?></h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                <i class='bx bx-user'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Loans</p>
                                <h4 class="my-1 text-success"><?php echo getTotalLoans(); ?></h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                <i class='bx bx-file'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Assigned Loans</p>
                                <h4 class="my-1 text-warning"><?php echo getAssignedLoans(); ?></h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                                <i class='bx bx-check-circle'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Pending Loans</p>
                                <h4 class="my-1 text-danger"><?php echo getPendingLoans(); ?></h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                <i class='bx bx-time'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics Row -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mb-4">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Today's Selfies</p>
                                <h4 class="my-1 text-info"><?php echo getTodaysSelfies(); ?></h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-cosmic text-white ms-auto">
                                <i class='bx bx-camera'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-secondary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Today's Chats</p>
                                <h4 class="my-1 text-secondary"><?php echo getTodaysChats(); ?></h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                                <i class='bx bx-chat'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Approved Loans</p>
                                <h4 class="my-1 text-success"><?php echo getApprovedLoans(); ?></h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-forest text-white ms-auto">
                                <i class='bx bx-like'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Today's Applications</p>
                                <h4 class="my-1 text-warning"><?php echo getTodaysApplications(); ?></h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-kyoto text-white ms-auto">
                                <i class='bx bx-credit-card'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary bg-gradient text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-white">Recent Loan Applications</h6>
                            <a href="all_loans_status.php" class="btn btn-light btn-sm">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Loan ID</th>
                                        <th>Customer Name</th>
                                        <th>Mobile No</th>
                                        <th>Loan Amount</th>
                                        <th>Assigned To</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo getRecentComplaints(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header bg-success bg-gradient text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-white">Recent Agent Activities</h6>
                            <a href="staff_selfie_log.php" class="btn btn-light btn-sm">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php echo getRecentAgentActivities(); ?>
                        </div>
                    </div>
                </div>

                <!-- Loan Status Distribution -->
                <div class="card mt-4">
                    <div class="card-header bg-info bg-gradient text-white">
                        <h6 class="mb-0 text-white">Loan Status Distribution</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <?php echo getLoanStatusStats(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Performance Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning bg-gradient text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-white">Staff Performance Overview</h6>
                            <a href="all_users.php" class="btn btn-light btn-sm">Manage Staff</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Assigned Loans</th>
                                        <th>Completed</th>
                                        <th>Pending</th>
                                        <th>Success Rate</th>
                                        <th>Last Activity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo getStaffPerformance(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(document).ready(function() {
    // Initialize any DataTables if needed
    $('table').DataTable({
        "pageLength": 5,
        "searching": false,
        "lengthChange": false,
        "info": false,
        "paging": true
    });

    // Auto refresh dashboard every 2 minutes
    setInterval(function() {
        // You can add AJAX refresh here if needed
        console.log('Dashboard auto-refresh check');
    }, 120000);
});

function refreshDashboard() {
    location.reload();
}
</script>

<style>
.card {
    transition: transform 0.2s;
    margin-bottom: 1rem;
}

.card:hover {
    transform: translateY(-2px);
}

.widgets-icons-2 {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.list-group-item {
    border: none;
    border-bottom: 1px solid #e9ecef;
    padding: 0.75rem 0;
}

.list-group-item:last-child {
    border-bottom: none;
}

.status-badge {
    font-size: 0.75em;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 500;
}

.badge-pending { background-color: #ffc107; color: #000; }
.badge-inprogress { background-color: #17a2b8; color: #fff; }
.badge-approved { background-color: #28a745; color: #fff; }
.badge-rejected { background-color: #dc3545; color: #fff; }
.badge-disbursed { background-color: #6f42c1; color: #fff; }

.progress {
    height: 8px;
}

.performance-card {
    text-align: center;
    padding: 1rem;
}

.performance-card h4 {
    margin-bottom: 0.5rem;
}

.performance-card small {
    color: #6c757d;
}
</style>

<?php
function getTotalAgents() {
    global $link;
    $sql = "SELECT COUNT(*) as total FROM admin WHERE user_id != 'admin'";
    $result = $link->query($sql);
    return $result->fetch_assoc()['total'];
}

function getTotalLoans() {
    global $link;
    $sql = "SELECT COUNT(*) as total FROM complaint";
    $result = $link->query($sql);
    return $result->fetch_assoc()['total'];
}

function getAssignedLoans() {
    global $link;
    $sql = "SELECT COUNT(*) as assigned FROM complaint WHERE assign_to != ''";
    $result = $link->query($sql);
    return $result->fetch_assoc()['assigned'];
}

function getPendingLoans() {
    global $link;
    $sql = "SELECT COUNT(DISTINCT c.id) as pending 
            FROM complaint c 
            LEFT JOIN loan_status_history lsh ON c.id = lsh.complaint_id 
            WHERE lsh.status IS NULL OR lsh.status = 0";
    $result = $link->query($sql);
    return $result->fetch_assoc()['pending'];
}

function getApprovedLoans() {
    global $link;
    $sql = "SELECT COUNT(DISTINCT c.id) as approved 
            FROM complaint c 
            INNER JOIN loan_status_history lsh ON c.id = lsh.complaint_id 
            WHERE lsh.status = 2";
    $result = $link->query($sql);
    return $result->fetch_assoc()['approved'];
}

function getTodaysSelfies() {
    global $link;
    $sql = "SELECT COUNT(*) as total FROM selfie_log WHERE DATE(datetime) = CURDATE()";
    $result = $link->query($sql);
    return $result->fetch_assoc()['total'];
}

function getTodaysChats() {
    global $link;
    $sql = "SELECT COUNT(*) as total FROM chat WHERE DATE(date) = CURDATE()";
    $result = $link->query($sql);
    return $result->fetch_assoc()['total'];
}

function getTodaysApplications() {
    global $link;
    $sql = "SELECT COUNT(*) as total FROM complaint WHERE DATE(date) = CURDATE()";
    $result = $link->query($sql);
    return $result->fetch_assoc()['total'];
}

function getRecentComplaints() {
    global $link;
    $output = '';
    
    $sql = "SELECT c.*, 
                   (SELECT status FROM loan_status_history 
                    WHERE complaint_id = c.id 
                    ORDER BY changed_at DESC LIMIT 1) as current_status
            FROM complaint c 
            ORDER BY c.date DESC 
            LIMIT 8";
    $result = $link->query($sql);
    
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $status = $row['current_status'] ?? 0;
            $status_text = getStatusText($status);
            $status_class = getStatusClass($status);
            
            $complaint_id = $row['complaint_id'] ?: 'CMP-' . $row['id'];
            $assigned_to = $row['assign_to'] ?: '<span class="text-muted">Not Assigned</span>';
            $date = date('M d, Y', strtotime($row['date']));
            
            $output .= '<tr>
                <td><code>' . $complaint_id . '</code></td>
                <td><strong>' . $row['name'] . '</strong></td>
                <td>' . $row['mobile_no'] . '</td>
                <td>₹' . number_format($row['loan_amount'], 2) . '</td>
                <td>' . $assigned_to . '</td>
                <td><span class="status-badge badge-' . $status_class . '">' . $status_text . '</span></td>
                <td><small>' . $date . '</small></td>
            </tr>';
        }
    } else {
        $output = '<tr>
            <td colspan="7" class="text-center text-muted py-3">
                <i class="bx bx-file-blank display-4 d-block mb-2"></i>
                No loan applications found
            </td>
        </tr>';
    }
    
    return $output;
}

function getRecentAgentActivities() {
    global $link;
    $output = '';
    
    $sql = "SELECT * FROM selfie_log 
            ORDER BY datetime DESC 
            LIMIT 6";
    $result = $link->query($sql);
    
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $time_ago = timeAgo($row['datetime']);
            $badge_class = strtolower($row['description']) == 'in' ? 'bg-success' : 'bg-danger';
            
            $output .= '<div class="list-group-item">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="badge ' . $badge_class . ' me-2">' . $row['description'] . '</span>
                        <strong>' . $row['by'] . '</strong>
                    </div>
                    <small class="text-muted">' . $time_ago . '</small>
                </div>
                <p class="mb-1 text-muted small">' . $row['address'] . '</p>
            </div>';
        }
    } else {
        $output = '<div class="list-group-item text-center text-muted">
            <i class="bx bx-camera-off display-4 d-block mb-2"></i>
            No recent activities
        </div>';
    }
    
    return $output;
}

function getLoanStatusStats() {
    global $link;
    $output = '';
    
    $status_counts = [
        'Pending' => 0,
        'In Progress' => 0, 
        'Approved' => 0,
        'Rejected' => 0,
        'Disbursed' => 0
    ];
    
    // Get latest status for each complaint
    $sql = "SELECT c.id, 
                   (SELECT status FROM loan_status_history lsh 
                    WHERE lsh.complaint_id = c.id 
                    ORDER BY lsh.changed_at DESC LIMIT 1) as latest_status
            FROM complaint c";
    $result = $link->query($sql);
    
    while($row = $result->fetch_assoc()) {
        $status_index = $row['latest_status'] ?? 0;
        $status_text = ['Pending', 'In Progress', 'Approved', 'Rejected', 'Disbursed'][$status_index] ?? 'Pending';
        $status_counts[$status_text]++;
    }
    
    $total_loans = array_sum($status_counts);
    $colors = ['warning', 'info', 'success', 'danger', 'primary'];
    $icons = ['bx-time', 'bx-refresh', 'bx-check-circle', 'bx-x-circle', 'bx-credit-card'];
    
    $i = 0;
    foreach($status_counts as $status => $count) {
        $color = $colors[$i] ?? 'primary';
        $icon = $icons[$i] ?? 'bx-file';
        $percentage = $total_loans > 0 ? round(($count / $total_loans) * 100) : 0;
        
        $output .= '<div class="col-6 mb-3">
            <div class="performance-card">
                <i class="bx ' . $icon . ' fs-1 text-' . $color . ' mb-2"></i>
                <h4 class="text-' . $color . '">' . $count . '</h4>
                <small>' . $status . '</small>
                <div class="progress mt-2">
                    <div class="progress-bar bg-' . $color . '" role="progressbar" 
                         style="width: ' . $percentage . '%" 
                         aria-valuenow="' . $percentage . '" 
                         aria-valuemin="0" 
                         aria-valuemax="100"></div>
                </div>
                <small class="text-muted">' . $percentage . '%</small>
            </div>
        </div>';
        $i++;
    }
    
    return $output;
}

function getStaffPerformance() {
    global $link;
    $output = '';
    
    $sql = "SELECT a.user_id, a.name,
                   COUNT(c.id) as total_assigned,
                   SUM(CASE WHEN lsh.status = 2 THEN 1 ELSE 0 END) as completed,
                   SUM(CASE WHEN lsh.status IS NULL OR lsh.status = 0 THEN 1 ELSE 0 END) as pending,
                   MAX(sl.datetime) as last_activity
            FROM admin a
            LEFT JOIN complaint c ON a.user_id = c.assign_to
            LEFT JOIN loan_status_history lsh ON c.id = lsh.complaint_id
            LEFT JOIN selfie_log sl ON a.user_id = sl.by
            WHERE a.user_id != 'admin'
            GROUP BY a.user_id, a.name
            ORDER BY total_assigned DESC
            LIMIT 8";
    
    $result = $link->query($sql);
    
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $success_rate = $row['total_assigned'] > 0 ? 
                round(($row['completed'] / $row['total_assigned']) * 100) : 0;
            
            $last_activity = $row['last_activity'] ? 
                timeAgo($row['last_activity']) : 'No activity';
            
            $output .= '<tr>
                <td><strong>' . $row['name'] . '</strong><br><small class="text-muted">' . $row['user_id'] . '</small></td>
                <td><span class="badge bg-primary">' . $row['total_assigned'] . '</span></td>
                <td><span class="badge bg-success">' . $row['completed'] . '</span></td>
                <td><span class="badge bg-warning">' . $row['pending'] . '</span></td>
                <td>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: ' . $success_rate . '%" 
                             title="' . $success_rate . '% Success Rate"></div>
                    </div>
                    <small>' . $success_rate . '%</small>
                </td>
                <td><small class="text-muted">' . $last_activity . '</small></td>
            </tr>';
        }
    } else {
        $output = '<tr>
            <td colspan="6" class="text-center text-muted py-3">
                <i class="bx bx-user display-4 d-block mb-2"></i>
                No staff performance data available
            </td>
        </tr>';
    }
    
    return $output;
}

// Helper functions
function getStatusText($status) {
    $statuses = ['Pending', 'In Progress', 'Approved', 'Rejected', 'Disbursed'];
    return $statuses[$status] ?? 'Pending';
}

function getStatusClass($status) {
    $classes = ['pending', 'inprogress', 'approved', 'rejected', 'disbursed'];
    return $classes[$status] ?? 'pending';
}

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $current = time();
    $diff = $current - $time;
    
    if($diff < 60) return 'Just now';
    if($diff < 3600) return floor($diff/60) . ' min ago';
    if($diff < 86400) return floor($diff/3600) . ' hours ago';
    if($diff < 2592000) return floor($diff/86400) . ' days ago';
    return date('M d, Y', $time);
}
?>