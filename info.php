<?php
session_start();
include("connection.php");

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
$mobile = isset($_GET['mobile']) ? sanitize_input($_GET['mobile']) : '';
$cat_id = isset($_GET['cat_id']) ? sanitize_input($_GET['cat_id']) : '';

include('header.php');
?>

<style>
/* Mobile-First Responsive Design */
.dashboard-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-left: 4px solid;
    margin-bottom: 15px;
}

.dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.stats-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    margin-right: 12px;
}

.complaint-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    transition: all 0.3s ease;
    border-left: 4px solid;
    overflow: hidden;
}

.complaint-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.loan-type-badge {
    background: rgba(255,255,255,0.9);
    color: #333;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.7rem;
    display: inline-block;
    margin: 5px 0;
}

/* Mobile Progress Indicator */
.mobile-progress {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 15px 0;
    padding: 0 10px;
    position: relative;
}

.mobile-progress::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 20px;
    right: 20px;
    height: 3px;
    background: #e9ecef;
    transform: translateY(-50%);
    z-index: 1;
}

.mobile-progress-step {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    background: white;
    border: 3px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    font-weight: bold;
    position: relative;
    z-index: 2;
    flex-shrink: 0;
}

.mobile-progress-step.active {
    border-color: #007bff;
    background: #007bff;
    color: white;
}

.mobile-progress-step.completed {
    border-color: #28a745;
    background: #28a745;
    color: white;
}

/* Status History Table */
.status-history-table {
    width: 100%;
    font-size: 0.8rem;
    margin: 10px 0;
}

.status-history-table th {
    background: #f8f9fa;
    font-weight: 600;
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.status-history-table td {
    padding: 8px;
    border-bottom: 1px solid #dee2e6;
    vertical-align: top;
}

.status-history-table tr:last-child td {
    border-bottom: none;
}

/* Mobile Action Buttons */
.mobile-actions {
    position: sticky;
    bottom: 0;
    background: white;
    padding: 15px;
    border-top: 1px solid #e9ecef;
    display: none;
}

.action-btn {
    flex: 1;
    margin: 0 5px;
    padding: 12px;
    border-radius: 10px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.action-btn i {
    margin-right: 5px;
}

/* Filter Section for Mobile */
.mobile-filters {
    background: white;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.filter-scroll {
    display: flex;
    overflow-x: auto;
    padding-bottom: 10px;
    -webkit-overflow-scrolling: touch;
}

.filter-scroll::-webkit-scrollbar {
    display: none;
}

.filter-btn {
    flex-shrink: 0;
    margin-right: 8px;
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 0.8rem;
    white-space: nowrap;
}

/* Responsive Typography */
@media (max-width: 576px) {
    .page-title {
        font-size: 1.3rem;
    }
    
    .card-title {
        font-size: 1.1rem;
    }
    
    .complaint-name {
        font-size: 1rem;
    }
    
    .complaint-detail {
        font-size: 0.85rem;
    }
    
    .status-history-table {
        font-size: 0.75rem;
    }
    
    .status-history-table th,
    .status-history-table td {
        padding: 6px 4px;
    }
}

/* Touch-friendly elements */
.btn-mobile {
    padding: 12px 20px;
    font-size: 0.9rem;
    border-radius: 10px;
}

.form-control-mobile {
    padding: 12px;
    font-size: 16px; /* Prevents zoom on iOS */
    border-radius: 8px;
    width: 100%;
    margin-bottom: 10px;
}

/* Mobile-only elements */
.desktop-only {
    display: block;
}

.mobile-only {
    display: none;
}

/* Status Update Form */
.status-update-form {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
}

/* Responsive utilities */
@media (max-width: 768px) {
    .desktop-only {
        display: none !important;
    }
    
    .mobile-only {
        display: block !important;
    }
    
    .stats-icon {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
        margin-right: 10px;
    }
    
    .dashboard-card .card-body {
        padding: 15px;
    }
    
    .complaint-card .card-body {
        padding: 15px;
    }
    
    .mobile-actions {
        display: flex;
    }
    
    .status-history-table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
}

/* Extra small devices */
@media (max-width: 576px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .page-breadcrumb {
        margin-bottom: 15px !important;
    }
    
    .row-cols-md-5 > * {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .mobile-progress-step {
        width: 22px;
        height: 22px;
        font-size: 0.6rem;
    }
    
    .status-history-table {
        font-size: 0.7rem;
    }
}

/* Debug styles */
.debug-info {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 15px;
    font-size: 0.9rem;
}

.debug-info h6 {
    color: #495057;
    margin-bottom: 8px;
}
</style>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Debug Information -->
        <div class="debug-info">
            <h6>User Information:</h6>
            <div><strong>Logged in as:</strong> <?php echo htmlspecialchars($u_uname); ?></div>
            
        </div>

        <!-- Mobile Header -->
        <div class="mobile-only">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="page-title mb-0">Loan Dashboard</h5>
                    <small class="text-muted">Your assigned loans</small>
                </div>
                <a href="complaint.php" class="btn btn-primary btn-sm">
                    <i class='bx bx-plus'></i>
                </a>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="desktop-only">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Loan Management</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="info.php"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <a href="complaint.php" class="btn btn-primary">
                        <i class='bx bx-plus-circle me-1'></i> New Loan
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Filters -->
        <div class="mobile-only">
            <div class="mobile-filters">
                <div class="filter-scroll">
                    <a href="info.php" class="filter-btn btn btn-outline-primary <?php echo empty($cat_id) ? 'active' : ''; ?>">
                        All
                    </a>
                    <?php
                    $statuses = [
                        1 => ['label' => 'Enquiry', 'color' => '#f1c40f'],
                        2 => ['label' => 'Documents', 'color' => '#e67e22'],
                        3 => ['label' => 'Bank', 'color' => '#3498db'],
                        4 => ['label' => 'Sanction', 'color' => '#9b59b6'],
                        5 => ['label' => 'Disbursed', 'color' => '#2ecc71']
                    ];
                    
                    foreach ($statuses as $sId => $sMeta): 
                    ?>
                    <a href="info.php?cat_id=<?php echo $sId; ?>" 
                       class="filter-btn btn btn-outline-primary <?php echo $cat_id == $sId ? 'active' : ''; ?>"
                       style="border-left-color: <?php echo $sMeta['color']; ?>; border-left-width: 3px;">
                        <?php echo $sMeta['label']; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Desktop Filters -->
        <div class="desktop-only">
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title mb-3">Filter Loans</h6>
                    <div class="filter-buttons">
                        <a href="info.php" class="btn btn-outline-primary <?php echo empty($cat_id) ? 'active' : ''; ?>">
                            All Loans
                        </a>
                        <?php foreach ($statuses as $sId => $sMeta): ?>
                        <a href="info.php?cat_id=<?php echo $sId; ?>" 
                           class="btn btn-outline-primary <?php echo $cat_id == $sId ? 'active' : ''; ?>"
                           style="border-left-color: <?php echo $sMeta['color']; ?>; border-left-width: 3px;">
                            <?php echo $sMeta['label']; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Overview Cards -->
        <div class="row row-cols-2 row-cols-md-5 g-2 g-md-3 mb-4">
            <?php
            foreach ($statuses as $sId => $sMeta) {
                // FIXED: Using registred_by instead of assign_to
                $sqlCount = "SELECT COUNT(*) as cnt FROM complaint WHERE category=? AND registred_by=?";
                $stmtCount = $link->prepare($sqlCount);
                $stmtCount->bind_param("is", $sId, $u_uname);
                $stmtCount->execute();
                $resCount = $stmtCount->get_result();
                $count = 0;
                if ($resCount && $rowC = $resCount->fetch_assoc()) {
                    $count = (int)$rowC['cnt'];
                }
                
                $icons = [
                    1 => 'bx-search-alt',
                    2 => 'bx-folder',
                    3 => 'bx-building-house',
                    4 => 'bx-check-shield',
                    5 => 'bx-credit-card'
                ];
            ?>
            <div class="col">
                <div class="card dashboard-card h-100" style="border-left-color: <?php echo $sMeta['color']; ?>">
                    <div class="card-body p-2 p-md-3">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon" style="background: <?php echo $sMeta['color']; ?>20; color: <?php echo $sMeta['color']; ?>">
                                <i class='bx <?php echo $icons[$sId]; ?>'></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0" style="color: #333; font-weight: 600; font-size: 0.9rem"><?php echo $count; ?></h6>
                                <small class="text-muted" style="font-size: 0.7rem"><?php echo $sMeta['label']; ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- Complaints List -->
        <div class="card">
            <div class="card-header bg-transparent desktop-only">
                <h5 class="card-title mb-0">
                    <i class='bx bx-list-ul me-2'></i>Recent Loans
                    <?php
                    // FIXED: Using registred_by instead of assign_to
                    $sqlTotal = "SELECT COUNT(*) as total FROM complaint WHERE registred_by=?";
                    $stmtTotal = $link->prepare($sqlTotal);
                    $stmtTotal->bind_param("s", $u_uname);
                    $stmtTotal->execute();
                    $resultTotal = $stmtTotal->get_result();
                    $totalCount = 0;
                    if ($resultTotal && $rowTotal = $resultTotal->fetch_assoc()) {
                        $totalCount = (int)$rowTotal['total'];
                    }
                    ?>
                    <span class="badge bg-primary ms-2"><?php echo $totalCount; ?></span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php
                    // FIXED: Using registred_by instead of assign_to
                    if (!empty($cat_id)) {
                        $sql = "SELECT * FROM complaint WHERE registred_by=? AND category=? ORDER BY id DESC LIMIT 50";
                        $stmt = $link->prepare($sql);
                        $stmt->bind_param("is", $u_uname, $cat_id);
                    } else {
                        $sql = "SELECT * FROM complaint WHERE registred_by=? ORDER BY id DESC LIMIT 50";
                        $stmt = $link->prepare($sql);
                        $stmt->bind_param("s", $u_uname);
                    }
                    
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $complaintCount = 0;

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $complaintCount++;
                            $mobile_no = sanitize_input($row['mobile_no']);
                            $registred_by = sanitize_input($row['registred_by']);
                            $name = sanitize_input($row['name']);
                            $complaint_title = sanitize_input($row['complaint_title']);
                            $loan_type = isset($row['loan_type']) ? sanitize_input($row['loan_type']) : '';
                            $loan_amount = isset($row['loan_amount']) ? sanitize_input($row['loan_amount']) : '0';
                            $loan_interest = isset($row['loan_interest']) ? sanitize_input($row['loan_interest']) : 'Pending';
                            
                            if (!empty($loan_type)) {
                                $prefix = $loan_type . ' - ';
                                if (strpos($complaint_title, $prefix) === 0) {
                                    $complaint_title = substr($complaint_title, strlen($prefix));
                                }
                            }
                            
                            $complaint_id = sanitize_input($row['complaint_id']);
                            $date = sanitize_input($row['date']);
                            $category = sanitize_input($row['category']);
                            
                            $statusColors = [
                                1 => ['bg' => '#FFF9C4', 'border' => '#f1c40f', 'text' => '#8B7500'],
                                2 => ['bg' => '#FFE0B2', 'border' => '#e67e22', 'text' => '#B35A00'],
                                3 => ['bg' => '#E3F2FD', 'border' => '#3498db', 'text' => '#0B5ED7'],
                                4 => ['bg' => '#F3E5F5', 'border' => '#9b59b6', 'text' => '#6A1B9A'],
                                5 => ['bg' => '#E8F5E9', 'border' => '#2ecc71', 'text' => '#1B5E20'],
                                6 => ['bg' => '#FFEBEE', 'border' => '#e74c3c', 'text' => '#B71C1C']
                            ];
                            
                            $statusInfo = $statusColors[$category] ?? $statusColors[1];
                    ?>
                    <div class="list-group-item p-0 border-0">
                        <div class="complaint-card" style="background: <?php echo $statusInfo['bg']; ?>; border-left-color: <?php echo $statusInfo['border']; ?>">
                            <div class="card-body">
                                <!-- Customer Header -->
                                <div class="d-flex align-items-start mb-2">
                                    <div class="bg-primary rounded-circle p-2 me-3 flex-shrink-0">
                                        <i class='bx bx-user text-white'></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="complaint-name mb-1"><?php echo "$name"; ?></h6>
                                        <p class="complaint-detail text-muted mb-1">
                                            <i class='bx bx-phone me-1'></i><?php echo $mobile_no; ?>
                                        </p>
                                        <?php if (!empty($loan_type)): ?>
                                        <span class="loan-type-badge"><?php echo htmlspecialchars($loan_type); ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($loan_amount) && $loan_amount != '0'): ?>
                                        <span class="loan-type-badge" style="background: #e3f2fd; color: #1976d2;">
                                            ₹<?php echo htmlspecialchars($loan_amount); ?>
                                        </span>
                                        <?php endif; ?>
                                        <?php if (!empty($loan_interest) && $loan_interest != 'Pending'): ?>
                                        <span class="loan-type-badge" style="background: <?php echo $loan_interest == 'Interested' ? '#e8f5e9' : '#ffebee'; ?>; color: <?php echo $loan_interest == 'Interested' ? '#2e7d32' : '#c62828'; ?>;">
                                            <?php echo htmlspecialchars($loan_interest); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-shrink-0 text-end">
                                        <small class="text-muted d-block">
                                            <?php 
                                            $timestamp = strtotime($date);
                                            echo date('d M', $timestamp);
                                            ?>
                                        </small>
                                        <small class="text-muted">
                                            <?php echo date('g:i a', $timestamp); ?>
                                        </small>
                                    </div>
                                </div>
                                
                                <!-- Complaint Details -->
                                <div class="mb-3">
                                    <p class="mb-2"><strong><?php echo $complaint_title; ?></strong></p>
                                    <small class="text-muted">
                                        ID: <?php echo $complaint_id; ?>
                                    </small>
                                </div>
                                
                                <!-- Mobile Progress Indicator -->
                                <div class="mobile-only">
                                    <div class="mobile-progress">
                                        <?php 
                                        $steps = [1, 2, 3, 4, 5];
                                        foreach ($steps as $stepNum) {
                                            $stepClass = '';
                                            if ($stepNum == $category) {
                                                $stepClass = 'active';
                                            } elseif ($stepNum < $category) {
                                                $stepClass = 'completed';
                                            }
                                        ?>
                                        <div class="mobile-progress-step <?php echo $stepClass; ?>">
                                            <?php echo $stepNum; ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Status History Table -->
                                <div class="status-history-section mt-3">
                                    <h6 class="mb-2" style="font-size: 0.9rem;">
                                        <i class='bx bx-history me-1'></i>Status History
                                    </h6>
                                    <?php
                                    $sqlHistory = "SELECT status, remarks, changed_by, changed_at FROM loan_status_history WHERE complaint_id=? ORDER BY changed_at DESC";
                                    $stmtHistory = $link->prepare($sqlHistory);
                                    $stmtHistory->bind_param("s", $complaint_id);
                                    $stmtHistory->execute();
                                    $resHistory = $stmtHistory->get_result();
                                    if ($resHistory && $resHistory->num_rows > 0) {
                                    ?>
                                    <div class="status-history-collapse">
                                        <table class="status-history-table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 25%">Status</th>
                                                    <th style="width: 40%">Remarks</th>
                                                    <th style="width: 20%">Date</th>
                                                    <th style="width: 15%">By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($h = $resHistory->fetch_assoc()) { 
                                                    $sLbl = '';
                                                    switch($h['status']) {
                                                        case '1': $sLbl='Enquiry'; break;
                                                        case '2': $sLbl='Documents'; break;
                                                        case '3': $sLbl='Bank'; break;
                                                        case '4': $sLbl='Sanction'; break;
                                                        case '5': $sLbl='Disbursed'; break;
                                                        case '6': $sLbl='Canceled'; break;
                                                    }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge 
                                                            <?php 
                                                            if($h['status'] == 1) echo 'bg-warning text-dark';
                                                            elseif($h['status'] == 2) echo 'bg-warning';
                                                            elseif($h['status'] == 3) echo 'bg-info';
                                                            elseif($h['status'] == 4) echo 'bg-primary';
                                                            elseif($h['status'] == 5) echo 'bg-success';
                                                            elseif($h['status'] == 6) echo 'bg-danger';
                                                            else echo 'bg-secondary';
                                                            ?>" style="font-size: 0.7rem;">
                                                            <?php echo $sLbl; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small><?php echo htmlspecialchars($h['remarks']); ?></small>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted"><?php echo date('d/m/y', strtotime($h['changed_at'])); ?></small>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted"><?php echo htmlspecialchars($h['changed_by']); ?></small>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php } else { ?>
                                    <div class="text-center text-muted py-3">
                                        <small>No status history available</small>
                                    </div>
                                    <?php } ?>
                                </div>

                                <!-- Status Update Form (Simple Form - No AJAX) -->
                                <?php if ($category != 5 && $category != 6): ?>
                                <div class="status-update-form">
                                    <h6 class="mb-2" style="font-size: 0.9rem;">
                                        <i class='bx bx-edit me-1'></i>Update Status
                                    </h6>
                                    <form action="change_status.php" method="get" class="row g-2" onsubmit="postStatusUpdateToChat(this, '<?php echo $mobile_no; ?>', '<?php echo $u_uname; ?>')">
                                        <input type="hidden" name="id" value="<?php echo $complaint_id; ?>">
                                        
                                        <div class="col-12">
                                            <select name="status" class="form-control form-control-mobile" required>
                                                <option value="">Select New Status</option>
                                                <option value="1" <?php echo ($category == 1) ? 'selected' : ''; ?>>Enquiry</option>
                                                <option value="2" <?php echo ($category == 2) ? 'selected' : ''; ?>>Document Collection</option>
                                                <option value="3" <?php echo ($category == 3) ? 'selected' : ''; ?>>Bank Submission</option>
                                                <option value="4" <?php echo ($category == 4) ? 'selected' : ''; ?>>Sanction</option>
                                                <option value="5">Disbursed</option>
                                                <option value="6">Canceled</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-12">
                                            <textarea name="remarks" class="form-control form-control-mobile" 
                                                      placeholder="Add remarks about status change" 
                                                      rows="2" required></textarea>
                                        </div>
                                        
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary w-100 btn-mobile">
                                                <i class='bx bx-check me-1'></i> Update Status
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <?php else: ?>
                                    <?php
                                    $statusLabel = ($category == 5) ? 'Disbursed' : 'Canceled';
                                    $alertClass = ($category == 5) ? 'alert-success' : 'alert-danger';
                                    ?>
                                    <div class="alert <?php echo $alertClass; ?> mb-0 mt-3 text-center">
                                        <strong><?php echo $statusLabel; ?></strong><br>
                                        <small>Status updates are disabled for completed loans.</small>
                                    </div>
                                <?php endif; ?>

                                <!-- Action Buttons -->
                                <!-- In the Action Buttons section of info.php, update the button text -->
<div class="row g-2 mt-3">
    <div class="col-12">
        <a href="chat_dashboard.php?mobile=<?php echo $mobile_no; ?>" 
           class="btn btn-outline-primary w-100 btn-mobile d-flex align-items-center justify-content-center">
            <i class='bx bx-chat me-1'></i> View Chat
        </a>
    </div>
</div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        }
                    } else {
                    ?>
                    <div class="list-group-item">
                        <div class="text-center py-5">
                            <i class='bx bx-inbox fs-1 text-muted mb-3'></i>
                            <h5 class="text-muted">No loans found</h5>
                            <p class="text-muted mb-3">Get started by registering a new loan</p>
                            <a href="complaint.php" class="btn btn-primary">
                                <i class='bx bx-plus-circle me-1'></i> Register New Loan
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Mobile Floating Action Button -->
        <div class="mobile-only">
            <div class="mobile-actions">
                <a href="complaint.php" class="action-btn btn btn-primary">
                    <i class='bx bx-plus'></i> New
                </a>
                <a href="info.php" class="action-btn btn btn-outline-primary">
                    <i class='bx bx-refresh'></i> Refresh
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function postStatusUpdateToChat(form, mobileNo, userName) {
    const status = form.querySelector('select[name="status"]').value;
    const remarks = form.querySelector('textarea[name="remarks"]').value;
    const complaintId = form.querySelector('input[name="id"]').value;
    
    // Get status label
    const statusLabels = {
        '1': 'Enquiry',
        '2': 'Document Collection', 
        '3': 'Bank Submission',
        '4': 'Sanction',
        '5': 'Disbursed',
        '6': 'Canceled'
    };
    
    const statusLabel = statusLabels[status] || 'Unknown';
    
    // Create status update message
    const statusMessage = "📊 *Loan Status Updated!*\n\n" +
                         "📋 *Loan ID:* " + complaintId + "\n" +
                         "🔄 *New Status:* " + statusLabel + "\n" +
                         "💬 *Remarks:* " + remarks + "\n" +
                         "👨‍💼 *Updated by:* " + userName;
    
    // Send to chat via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'send_chat.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('send_by=' + encodeURIComponent(userName) + '&recieved_by=' + encodeURIComponent(mobileNo) + '&msg=' + encodeURIComponent(statusMessage));
}

// Update complaint count and debug info
document.addEventListener('DOMContentLoaded', function() {
    console.log('Total loans found: <?php echo $complaintCount; ?>');
    console.log('Logged in user: <?php echo $u_uname; ?>');
    console.log('Filter category: <?php echo $cat_id ?: "All"; ?>');
    
    // Mobile touch improvements
    const complaintCards = document.querySelectorAll('.complaint-card');
    complaintCards.forEach(card => {
        card.style.cursor = 'pointer';
    });
    
    // Simple form submission - no AJAX needed
    const statusForms = document.querySelectorAll('.status-update-form form');
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Basic validation
            const status = this.querySelector('select[name="status"]');
            const remarks = this.querySelector('textarea[name="remarks"]');
            
            if (!status.value || !remarks.value.trim()) {
                e.preventDefault();
                alert('Please select a status and add remarks before updating.');
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Updating...';
            submitBtn.disabled = true;
            
            // Form will submit normally
        });
    });
});

// Notification handling
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
    1002 => 'Loan Not Registered.',
    42 => 'Batch Not Scheduled.',
    3 => 'Status Not Changed.',
    66 => 'Bill Already Paid.',
    33 => 'Password Mismatched.',
    300 => 'Staff Not Added.',
    200 => 'Photo Not Added.',
    21 => 'Product Not Added.',
    210 => 'Category Not Added.',
    41 => 'Demo Session Not Added.',
    6 => 'Bill Not Paid.'
];

if(isset($_GET['success']) && isset($successMessages[$_GET['success']])) {
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
</script>

<?php include('footer.php'); ?>