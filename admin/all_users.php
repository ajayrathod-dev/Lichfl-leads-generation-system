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
            <div class="breadcrumb-title pe-3">User Management</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Users</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary bg-gradient text-white">
                        <h6 class="mb-0 text-white">Add New Agent</h6>
                    </div>
                    <div class="card-body">
                        <form id="addAgentForm">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary d-block">Add Agent</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success bg-gradient text-white">
                        <h6 class="mb-0 text-white">All Agents</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="agentsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Present Staff</th>
                                        <th>Assigned Loans</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo getAgentsList(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Agent Modal -->
<div class="modal fade" id="editAgentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Agent</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAgentForm">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_user_id" class="form-label">User ID</label>
                        <input type="text" class="form-control" id="edit_user_id" name="user_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                        <small class="text-muted">Leave blank to keep current password</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateAgent()">Update Agent</button>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#agentsTable').DataTable();

    // Add Agent Form Submission
    $('#addAgentForm').on('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="bx bx-loader bx-spin"></i> Adding...').prop('disabled', true);
        
        $.ajax({
            url: 'ajax_actions.php',
            type: 'POST',
            data: { action: 'add_agent', ...$(this).serialize() },
            success: function(response) {
                try {
                    var result = JSON.parse(response);
                    if(result.success) {
                        Lobibox.notify('success', {
                            pauseDelayOnHover: true,
                            continueDelayOnInactiveTab: false,
                            position: 'top right',
                            icon: 'bx bx-check-circle',
                            msg: result.message
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        Lobibox.notify('error', {
                            pauseDelayOnHover: true,
                            continueDelayOnInactiveTab: false,
                            position: 'top right',
                            icon: 'bx bx-x-circle',
                            msg: result.message
                        });
                    }
                } catch (e) {
                    Lobibox.notify('error', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-x-circle',
                        msg: 'Error processing response'
                    });
                }
            },
            error: function() {
                Lobibox.notify('error', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-x-circle',
                    msg: 'Network error occurred'
                });
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
});

function editAgent(id, user_id, name) {
    $('#edit_id').val(id);
    $('#edit_user_id').val(user_id);
    $('#edit_name').val(name);
    $('#edit_password').val('');
    $('#editAgentModal').modal('show');
}

function updateAgent() {
    const submitBtn = $('#editAgentModal').find('.btn-primary');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="bx bx-loader bx-spin"></i> Updating...').prop('disabled', true);
    
    $.ajax({
        url: 'ajax_actions.php',
        type: 'POST',
        data: { action: 'update_agent', ...$('#editAgentForm').serialize() },
        success: function(response) {
            try {
                var result = JSON.parse(response);
                if(result.success) {
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: result.message
                    });
                    $('#editAgentModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    Lobibox.notify('error', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-x-circle',
                        msg: result.message
                    });
                }
            } catch (e) {
                Lobibox.notify('error', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-x-circle',
                    msg: 'Error processing response'
                });
            }
        },
        error: function() {
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                icon: 'bx bx-x-circle',
                msg: 'Network error occurred'
            });
        },
        complete: function() {
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
}

function viewAssignments(user_id) {
    window.location.href = 'agent_assignments.php?agent_id=' + user_id;
}

function deleteAgent(id, user_id) {
    Lobibox.confirm({
        title: 'Confirm Delete',
        msg: 'Are you sure you want to delete agent: ' + user_id + '?',
        callback: function(result) {
            if(result) {
                $.ajax({
                    url: 'ajax_actions.php',
                    type: 'POST',
                    data: { action: 'delete_agent', id: id },
                    success: function(response) {
                        try {
                            var result = JSON.parse(response);
                            if(result.success) {
                                Lobibox.notify('success', {
                                    pauseDelayOnHover: true,
                                    continueDelayOnInactiveTab: false,
                                    position: 'top right',
                                    icon: 'bx bx-check-circle',
                                    msg: result.message
                                });
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            } else {
                                Lobibox.notify('error', {
                                    pauseDelayOnHover: true,
                                    continueDelayOnInactiveTab: false,
                                    position: 'top right',
                                    icon: 'bx bx-x-circle',
                                    msg: result.message
                                });
                            }
                        } catch (e) {
                            Lobibox.notify('error', {
                                pauseDelayOnHover: true,
                                continueDelayOnInactiveTab: false,
                                position: 'top right',
                                icon: 'bx bx-x-circle',
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

<?php
function getAgentsList() {
    global $link;
    $output = '';
    
    // Get all agents from admin table
    $sql = "SELECT * FROM admin ORDER BY id DESC";
    $result = $link->query($sql);
    
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Count assigned loans for this agent
            $count_sql = "SELECT COUNT(*) as assigned_count FROM complaint WHERE assign_to = ?";
            $count_stmt = $link->prepare($count_sql);
            $count_stmt->bind_param("s", $row['user_id']);
            $count_stmt->execute();
            $count_result = $count_stmt->get_result();
            $assigned_count = $count_result->fetch_assoc()['assigned_count'];
            $count_stmt->close();
            
            $output .= '<tr>
                <td>'.$row['id'].'</td>
                <td>'.$row['user_id'].'</td>
                <td>'.$row['name'].'</td>
                <td>'.($row['present_staff'] ? 'Yes' : 'No').'</td>
                <td><span class="badge bg-primary">'.$assigned_count.'</span></td>
                <td>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary" onclick="editAgent('.$row['id'].', \''.$row['user_id'].'\', \''.$row['name'].'\')">
                            <i class="bx bx-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-info" onclick="viewAssignments(\''.$row['user_id'].'\')">
                            <i class="bx bx-show"></i> View Assignments
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteAgent('.$row['id'].', \''.$row['user_id'].'\')">
                            <i class="bx bx-trash"></i> Delete
                        </button>
                    </div>
                </td>
            </tr>';
        }
    } else {
        $output = '<tr><td colspan="6" class="text-center">No agents found</td></tr>';
    }
    return $output;
}
?>