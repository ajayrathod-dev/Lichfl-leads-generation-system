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
            <div class="breadcrumb-title pe-3">Loan Management</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Loan Status</li>
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
                                <p class="mb-0 text-secondary">Total Loans</p>
                                <h4 class="my-1 text-primary"><?php echo getTotalLoans(); ?></h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                <i class='bx bx-file'></i>
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
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                <i class='bx bx-check-circle'></i>
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
                                <p class="mb-0 text-secondary">Pending Loans</p>
                                <h4 class="my-1 text-warning"><?php echo getPendingLoans(); ?></h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                                <i class='bx bx-time'></i>
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
                                <p class="mb-0 text-secondary">Rejected Loans</p>
                                <h4 class="my-1 text-danger"><?php echo getRejectedLoans(); ?></h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                <i class='bx bx-x-circle'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary bg-gradient text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-white">All Loan Applications Status</h6>
                            <div>
                                <span class="badge bg-light text-dark" id="totalLoans">Loading...</span>
                                <button class="btn btn-light btn-sm ms-2" onclick="refreshLoans()">
                                    <i class="bx bx-refresh"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="filterStatus" class="form-label">Filter by Status</label>
                                <select class="form-select" id="filterStatus">
                                    <option value="">All Status</option>
                                    <option value="0">Pending</option>
                                    <option value="1">In Progress</option>
                                    <option value="2">Approved</option>
                                    <option value="3">Rejected</option>
                                    <option value="4">Disbursed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filterStaff" class="form-label">Filter by Staff</label>
                                <select class="form-select" id="filterStaff">
                                    <option value="">All Staff</option>
                                    <?php echo getStaffOptions(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filterDate" class="form-label">Filter by Date</label>
                                <input type="date" class="form-control" id="filterDate">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button class="btn btn-secondary w-100" onclick="clearFilters()">
                                    <i class="bx bx-reset"></i> Clear Filters
                                </button>
                            </div>
                        </div>

                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="loansTable" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="10%">Complaint ID</th>
                                        <th width="15%">Customer Name</th>
                                        <th width="10%">Mobile No</th>
                                        <th width="10%">Loan Amount</th>
                                        <th width="10%">Assigned To</th>
                                        <th width="10%">Current Status</th>
                                        <th width="15%">Last Updated</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo getLoansList(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Loan Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm">
                    <input type="hidden" id="update_complaint_id" name="complaint_id">
                    <div class="mb-3">
                        <label for="current_status" class="form-label">Current Status</label>
                        <input type="text" class="form-control" id="current_status" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">New Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="0">Pending</option>
                            <option value="1">In Progress</option>
                            <option value="2">Approved</option>
                            <option value="3">Rejected</option>
                            <option value="4">Disbursed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3" 
                                  placeholder="Enter remarks for this status change..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateLoanStatus()">Update Status</button>
            </div>
        </div>
    </div>
</div>

<!-- Loan Details Modal -->
<div class="modal fade" id="loanDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Loan Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="loanDetailsContent">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#loansTable').DataTable({
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "order": [[0, 'desc']],
        "responsive": true,
        "autoWidth": false,
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "paginate": {
                "previous": "← Previous",
                "next": "Next →"
            }
        },
        "initComplete": function(settings, json) {
            $('#totalLoans').text(this.api().data().length + ' loans');
        }
    });

    // Filter by status
    $('#filterStatus').on('change', function() {
        table.column(6).search(this.value).draw();
        updateLoanCount();
    });

    // Filter by staff
    $('#filterStaff').on('change', function() {
        table.column(5).search(this.value).draw();
        updateLoanCount();
    });

    // Filter by date
    $('#filterDate').on('change', function() {
        if(this.value) {
            table.column(7).search(this.value).draw();
        } else {
            table.column(7).search('').draw();
        }
        updateLoanCount();
    });

    // Update loan count
    function updateLoanCount() {
        $('#totalLoans').text(table.rows({ filter: 'applied' }).count() + ' loans');
    }

    table.on('draw', function() {
        updateLoanCount();
    });
});

function refreshLoans() {
    const table = $('#loansTable').DataTable();
    table.ajax.reload();
    Lobibox.notify('info', {
        pauseDelayOnHover: true,
        continueDelayOnInactiveTab: false,
        position: 'top right',
        icon: 'bx bx-refresh',
        msg: 'Loans data refreshed successfully'
    });
}

function clearFilters() {
    $('#filterStatus').val('');
    $('#filterStaff').val('');
    $('#filterDate').val('');
    
    const table = $('#loansTable').DataTable();
    table.columns().search('').draw();
    $('#totalLoans').text(table.data().length + ' loans');
}

function updateStatusModal(complaintId, currentStatus, customerName) {
    $('#update_complaint_id').val(complaintId);
    $('#current_status').val(currentStatus);
    
    // Set modal title with customer name
    $('#updateStatusModal .modal-title').html('Update Loan Status - <strong>' + customerName + '</strong>');
    
    $('#updateStatusModal').modal('show');
}

function updateLoanStatus() {
    const complaintId = $('#update_complaint_id').val();
    const status = $('#status').val();
    const remarks = $('#remarks').val();

    if (!status) {
        Lobibox.notify('warning', {
            pauseDelayOnHover: true,
            position: 'top right',
            icon: 'bx bx-error',
            msg: 'Please select a status'
        });
        return;
    }

    const submitBtn = $('#updateStatusModal').find('.btn-primary');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="bx bx-loader bx-spin"></i> Updating...').prop('disabled', true);

    $.ajax({
        url: 'ajax_actions.php',
        type: 'POST',
        data: {
            action: 'update_loan_status',
            complaint_id: complaintId,
            status: status,
            remarks: remarks
        },
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if(result.success) {
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: result.message
                    });
                    $('#updateStatusModal').modal('hide');
                    $('#updateStatusForm')[0].reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Lobibox.notify('error', {
                        pauseDelayOnHover: true,
                        position: 'top right',
                        icon: 'bx bx-error',
                        msg: result.message
                    });
                }
            } catch (e) {
                Lobibox.notify('error', {
                    pauseDelayOnHover: true,
                    position: 'top right',
                    icon: 'bx bx-error',
                    msg: 'Error processing response'
                });
            }
        },
        error: function() {
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                position: 'top right',
                icon: 'bx bx-error',
                msg: 'Network error occurred'
            });
        },
        complete: function() {
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
}

function viewLoanDetails(complaintId) {
    $('#loanDetailsContent').html(`
        <div class="text-center">
            <i class="bx bx-loader bx-spin display-4"></i>
            <p>Loading loan details...</p>
        </div>
    `);
    
    $('#loanDetailsModal').modal('show');
    
    $.ajax({
        url: 'ajax_actions.php',
        type: 'POST',
        data: { action: 'get_loan_details', complaint_id: complaintId },
        success: function(response) {
            $('#loanDetailsContent').html(response);
        },
        error: function() {
            $('#loanDetailsContent').html(`
                <div class="alert alert-danger">
                    <i class="bx bx-error"></i> Error loading loan details
                </div>
            `);
        }
    });
}

function viewStatusHistory(complaintId, customerName) {
    Lobibox.alert('info', {
        title: 'Status History - ' + customerName,
        msg: 'Loading status history...',
        callback: function() {
            // This would typically load a modal with the full history
            // For now, we'll show a notification
            Lobibox.notify('info', {
                pauseDelayOnHover: true,
                position: 'top right',
                icon: 'bx bx-history',
                msg: 'Status history feature coming soon'
            });
        }
    });
}
</script>

<style>
.table-container {
    overflow-x: auto;
    background: white;
    border-radius: 0.375rem;
}

#loansTable th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 12px 8px;
}

#loansTable td {
    padding: 10px 8px;
    border-color: #dee2e6;
    vertical-align: middle;
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

.btn-update {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
    padding: 4px 8px;
    font-size: 0.75rem;
}

.btn-details {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
    padding: 4px 8px;
    font-size: 0.75rem;
}

.btn-history {
    background-color: #fd7e14;
    border-color: #fd7e14;
    color: white;
    padding: 4px 8px;
    font-size: 0.75rem;
}

.btn-update:hover, .btn-details:hover, .btn-history:hover {
    opacity: 0.8;
    color: white;
}

.loan-amount {
    font-weight: 600;
    color: #28a745;
}

.customer-name {
    font-weight: 500;
}
</style>

<?php
function getTotalLoans() {
    global $link;
    $sql = "SELECT COUNT(*) as total FROM complaint";
    $result = $link->query($sql);
    return $result->fetch_assoc()['total'];
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

function getPendingLoans() {
    global $link;
    $sql = "SELECT COUNT(DISTINCT c.id) as pending 
            FROM complaint c 
            LEFT JOIN loan_status_history lsh ON c.id = lsh.complaint_id 
            WHERE lsh.status IS NULL OR lsh.status = 0";
    $result = $link->query($sql);
    return $result->fetch_assoc()['pending'];
}

function getRejectedLoans() {
    global $link;
    $sql = "SELECT COUNT(DISTINCT c.id) as rejected 
            FROM complaint c 
            INNER JOIN loan_status_history lsh ON c.id = lsh.complaint_id 
            WHERE lsh.status = 3";
    $result = $link->query($sql);
    return $result->fetch_assoc()['rejected'];
}

function getStaffOptions() {
    global $link;
    $output = '';
    
    $sql = "SELECT DISTINCT assign_to FROM complaint WHERE assign_to != '' ORDER BY assign_to";
    $result = $link->query($sql);
    
    while($row = $result->fetch_assoc()) {
        $output .= '<option value="' . $row['assign_to'] . '">' . $row['assign_to'] . '</option>';
    }
    
    return $output;
}

function getLoansList() {
    global $link;
    $output = '';
    
    $sql = "SELECT c.*, 
                   (SELECT status FROM loan_status_history 
                    WHERE complaint_id = c.id 
                    ORDER BY changed_at DESC LIMIT 1) as current_status,
                   (SELECT changed_at FROM loan_status_history 
                    WHERE complaint_id = c.id 
                    ORDER BY changed_at DESC LIMIT 1) as last_updated
            FROM complaint c 
            ORDER BY c.id DESC";
    $result = $link->query($sql);
    
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $status = $row['current_status'] ?? 0;
            $status_text = getStatusText($status);
            $status_class = getStatusClass($status);
            
            $complaint_id = $row['complaint_id'] ?: 'CMP-' . $row['id'];
            $assigned_to = $row['assign_to'] ?: '<span class="text-muted">Not Assigned</span>';
            
            $last_updated = $row['last_updated'] ? date('M d, Y h:i A', strtotime($row['last_updated'])) : 'N/A';
            
            $output .= '<tr>
                <td><strong>' . $row['id'] . '</strong></td>
                <td><code>' . $complaint_id . '</code></td>
                <td><span class="customer-name">' . $row['name'] . '</span></td>
                <td>' . $row['mobile_no'] . '</td>
                <td><span class="loan-amount">₹' . number_format($row['loan_amount'], 2) . '</span></td>
                <td>' . $assigned_to . '</td>
                <td><span class="status-badge badge-' . $status_class . '">' . $status_text . '</span></td>
                <td><small>' . $last_updated . '</small></td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-update" 
                                onclick="updateStatusModal(' . $row['id'] . ', \'' . $status_text . '\', \'' . addslashes($row['name']) . '\')"
                                title="Update Status">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-details" 
                                onclick="viewLoanDetails(' . $row['id'] . ')"
                                title="View Details">
                            <i class="bx bx-show"></i>
                        </button>
                        <button class="btn btn-history" 
                                onclick="viewStatusHistory(' . $row['id'] . ', \'' . addslashes($row['name']) . '\')"
                                title="Status History">
                            <i class="bx bx-history"></i>
                        </button>
                    </div>
                </td>
            </tr>';
        }
    } else {
        $output = '<tr>
            <td colspan="9" class="text-center py-5">
                <div class="text-muted">
                    <i class="bx bx-file-blank display-4 d-block mb-3"></i>
                    <h5>No loan applications found</h5>
                    <p>Loan applications will appear here once they are submitted.</p>
                </div>
            </td>
        </tr>';
    }
    
    return $output;
}

function getStatusText($status) {
    $statuses = ['Pending', 'In Progress', 'Approved', 'Rejected', 'Disbursed'];
    return $statuses[$status] ?? 'Pending';
}

function getStatusClass($status) {
    $classes = ['pending', 'inprogress', 'approved', 'rejected', 'disbursed'];
    return $classes[$status] ?? 'pending';
}
?>