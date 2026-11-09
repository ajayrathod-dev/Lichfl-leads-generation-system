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
            <div class="breadcrumb-title pe-3">Customer Management</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Assign Customers To Staff</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary bg-gradient text-white">
                        <h6 class="mb-0 text-white">Assign Contacts to Staff</h6>
                    </div>
                    <div class="card-body">
                        <form id="assignForm">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="agent_id" class="form-label">Select Staff Member</label>
                                    <select class="form-select" id="agent_id" name="agent_id" required>
                                        <option value="">Choose Staff Member...</option>
                                        <?php echo getStaffMembers(); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="start_serial" class="form-label">Start Contact ID</label>
                                    <input type="number" class="form-control" id="start_serial" name="start_serial" 
                                           min="1" required placeholder="From ID">
                                    <div class="form-text">Minimum available ID: <span id="minId">1</span></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_serial" class="form-label">End Contact ID</label>
                                    <input type="number" class="form-control" id="end_serial" name="end_serial" 
                                           min="1" required placeholder="To ID">
                                    <div class="form-text">Maximum available ID: <span id="maxId"><?php echo getMaxComplaintId(); ?></span></div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">Assignment Summary</h6>
                                            <div id="assignmentSummary" class="text-muted">
                                                Select staff member and contact range to see summary
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bx bx-user-check"></i> Assign Contacts
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-success bg-gradient text-white">
                        <h6 class="mb-0 text-white">Assignment Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 col-md-3 mb-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body py-3">
                                        <h4 class="mb-0"><?php echo getTotalComplaints(); ?></h4>
                                        <small>Total Contacts</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body py-3">
                                        <h4 class="mb-0"><?php echo getAssignedComplaints(); ?></h4>
                                        <small>Assigned</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body py-3">
                                        <h4 class="mb-0"><?php echo getUnassignedComplaints(); ?></h4>
                                        <small>Unassigned</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body py-3">
                                        <h4 class="mb-0"><?php echo getTotalAgents(); ?></h4>
                                        <small>Active Staff</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h6>Recent Assignments</h6>
                            <div class="list-group">
                                <?php echo getRecentAssignments(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info bg-gradient text-white">
                        <h6 class="mb-0 text-white">Current Assignments Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="assignmentsTable" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th width="10%">Staff Member</th>
                                        <th width="10%">Assigned Contacts</th>
                                        <th width="15%">Contact Range</th>
                                        <th width="15%">Last Assignment</th>
                                        <th width="30%">Recent Contacts</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo getAssignmentsOverview(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assignment Details Modal -->
<div class="modal fade" id="assignmentDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assignment Details - <span id="modalStaffName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="assignmentDetailsContent">
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
    $('#assignmentsTable').DataTable({
        "pageLength": 10,
        "order": [[3, 'desc']],
        "responsive": true,
        "autoWidth": false
    });

    // Update assignment summary
    function updateAssignmentSummary() {
        const agent = $('#agent_id').val();
        const start = parseInt($('#start_serial').val()) || 0;
        const end = parseInt($('#end_serial').val()) || 0;
        
        if (agent && start > 0 && end > 0) {
            if (start > end) {
                $('#assignmentSummary').html('<div class="text-danger"><i class="bx bx-error"></i> Start ID cannot be greater than End ID</div>');
                return;
            }
            
            const count = end - start + 1;
            $('#assignmentSummary').html(`
                <div class="text-success">
                    <i class="bx bx-check-circle"></i> Ready to assign <strong>${count}</strong> contacts 
                    (ID: ${start} to ${end}) to <strong>${agent}</strong>
                </div>
            `);
        } else {
            $('#assignmentSummary').html('<div class="text-muted">Select staff member and contact range to see summary</div>');
        }
    }

    // Event listeners for form changes
    $('#agent_id, #start_serial, #end_serial').on('change keyup', updateAssignmentSummary);

    // Form submission
    $('#assignForm').on('submit', function(e) {
        e.preventDefault();
        
        const start = parseInt($('#start_serial').val());
        const end = parseInt($('#end_serial').val());
        
        if (start > end) {
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                position: 'top right',
                icon: 'bx bx-error',
                msg: 'Start ID cannot be greater than End ID'
            });
            return;
        }

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="bx bx-loader bx-spin"></i> Assigning...').prop('disabled', true);

        $.ajax({
            url: 'ajax_actions.php',
            type: 'POST',
            data: $(this).serialize() + '&action=assign_contacts',
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
                        $('#assignForm')[0].reset();
                        $('#assignmentSummary').html('<div class="text-muted">Select staff member and contact range to see summary</div>');
                        setTimeout(() => location.reload(), 2000);
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
    });

    // Load available ID range
    updateIdRange();
});

function updateIdRange() {
    $.get('ajax_actions.php?action=get_id_range', function(response) {
        try {
            const result = JSON.parse(response);
            if(result.success) {
                $('#minId').text(result.min_id);
                $('#maxId').text(result.max_id);
            }
        } catch (e) {
            console.error('Error updating ID range:', e);
        }
    });
}

function viewAssignmentDetails(agentId) {
    $('#modalStaffName').text(agentId);
    $('#assignmentDetailsContent').html(`
        <div class="text-center">
            <i class="bx bx-loader bx-spin display-4"></i>
            <p>Loading assignment details...</p>
        </div>
    `);
    
    $('#assignmentDetailsModal').modal('show');
    
    $.ajax({
        url: 'ajax_actions.php',
        type: 'POST',
        data: { action: 'get_assignment_details', agent_id: agentId },
        success: function(response) {
            $('#assignmentDetailsContent').html(response);
        },
        error: function() {
            $('#assignmentDetailsContent').html(`
                <div class="alert alert-danger">
                    <i class="bx bx-error"></i> Error loading assignment details
                </div>
            `);
        }
    });
}

function reassignContact(contactId, currentAgent) {
    Lobibox.prompt('text', {
        title: `Reassign Contact #${contactId}`,
        msg: `Currently assigned to: ${currentAgent}<br>Enter new staff member ID:`,
        callback: function(newAgent) {
            if(newAgent && newAgent !== currentAgent) {
                $.ajax({
                    url: 'ajax_actions.php',
                    type: 'POST',
                    data: { 
                        action: 'reassign_contact',
                        contact_id: contactId,
                        new_agent: newAgent
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
                    }
                });
            }
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

#assignmentsTable th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 12px 8px;
}

#assignmentsTable td {
    padding: 10px 8px;
    border-color: #dee2e6;
    vertical-align: middle;
}

.assignment-card {
    transition: transform 0.2s;
}

.assignment-card:hover {
    transform: translateY(-2px);
}

.badge-assigned { background-color: #28a745; }
.badge-pending { background-color: #ffc107; color: #000; }
.badge-unassigned { background-color: #6c757d; }

.btn-view {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
    padding: 4px 8px;
    font-size: 0.75rem;
}

.btn-reassign {
    background-color: #fd7e14;
    border-color: #fd7e14;
    color: white;
    padding: 4px 8px;
    font-size: 0.75rem;
}

.btn-view:hover, .btn-reassign:hover {
    opacity: 0.8;
    color: white;
}

.list-group-item {
    border-left: none;
    border-right: none;
    padding: 0.75rem 1rem;
}

.list-group-item:first-child {
    border-top: none;
}
</style>

<?php
function getStaffMembers() {
    global $link;
    $output = '';
    
    $sql = "SELECT user_id, name FROM admin WHERE user_id != 'admin' ORDER BY name";
    $result = $link->query($sql);
    
    while($row = $result->fetch_assoc()) {
        $output .= '<option value="' . $row['user_id'] . '">' . $row['name'] . ' (' . $row['user_id'] . ')</option>';
    }
    
    return $output;
}

function getTotalComplaints() {
    global $link;
    $sql = "SELECT COUNT(*) as total FROM complaint";
    $result = $link->query($sql);
    return $result->fetch_assoc()['total'];
}

function getAssignedComplaints() {
    global $link;
    $sql = "SELECT COUNT(*) as assigned FROM complaint WHERE assign_to != ''";
    $result = $link->query($sql);
    return $result->fetch_assoc()['assigned'];
}

function getUnassignedComplaints() {
    global $link;
    $sql = "SELECT COUNT(*) as unassigned FROM complaint WHERE assign_to = '' OR assign_to IS NULL";
    $result = $link->query($sql);
    return $result->fetch_assoc()['unassigned'];
}

function getTotalAgents() {
    global $link;
    $sql = "SELECT COUNT(*) as total FROM admin WHERE user_id != 'admin'";
    $result = $link->query($sql);
    return $result->fetch_assoc()['total'];
}

function getMaxComplaintId() {
    global $link;
    $sql = "SELECT MAX(id) as max_id FROM complaint";
    $result = $link->query($sql);
    return $result->fetch_assoc()['max_id'] ?: 1;
}

function getRecentAssignments() {
    global $link;
    $output = '';
    
    $sql = "SELECT assign_to, COUNT(*) as count, MAX(assignment_date) as last_date 
            FROM complaint 
            WHERE assign_to != '' 
            GROUP BY assign_to 
            ORDER BY last_date DESC 
            LIMIT 5";
    $result = $link->query($sql);
    
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $date = $row['last_date'] ? date('M d, h:i A', strtotime($row['last_date'])) : 'N/A';
            $output .= '
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>' . $row['assign_to'] . '</strong>
                    <br><small class="text-muted">' . $date . '</small>
                </div>
                <span class="badge bg-primary rounded-pill">' . $row['count'] . '</span>
            </div>';
        }
    } else {
        $output = '<div class="list-group-item text-center text-muted">No assignments yet</div>';
    }
    
    return $output;
}

function getAssignmentsOverview() {
    global $link;
    $output = '';
    
    $sql = "SELECT assign_to, 
                   COUNT(*) as total_count,
                   MIN(id) as min_id,
                   MAX(id) as max_id,
                   MAX(assignment_date) as last_assignment,
                   GROUP_CONCAT(id ORDER BY id DESC LIMIT 3) as recent_ids
            FROM complaint 
            WHERE assign_to != '' 
            GROUP BY assign_to 
            ORDER BY last_assignment DESC";
    $result = $link->query($sql);
    
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $recent_ids = $row['recent_ids'] ? explode(',', $row['recent_ids']) : [];
            $recent_display = implode(', ', $recent_ids);
            
            $date = $row['last_assignment'] ? date('M d, Y h:i A', strtotime($row['last_assignment'])) : 'N/A';
            $range = $row['min_id'] . ' - ' . $row['max_id'];
            
            $output .= '<tr>
                <td><strong>' . $row['assign_to'] . '</strong></td>
                <td><span class="badge bg-primary">' . $row['total_count'] . '</span></td>
                <td><code>' . $range . '</code></td>
                <td><small>' . $date . '</small></td>
                <td><small class="text-muted">' . $recent_display . '</small></td>
                <td><span class="badge badge-assigned">Active</span></td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-view" onclick="viewAssignmentDetails(\'' . $row['assign_to'] . '\')" title="View Details">
                            <i class="bx bx-show"></i>
                        </button>
                    </div>
                </td>
            </tr>';
        }
    } else {
        $output = '<tr>
            <td colspan="7" class="text-center py-4">
                <div class="text-muted">
                    <i class="bx bx-user-check display-4 d-block mb-2"></i>
                    <h5>No assignments found</h5>
                    <p>Start assigning contacts to staff members to see overview here.</p>
                </div>
            </td>
        </tr>';
    }
    
    return $output;
}
?>