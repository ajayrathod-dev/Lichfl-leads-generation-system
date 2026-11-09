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
            <div class="breadcrumb-title pe-3">Staff Management</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Staff All Chat's</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary bg-gradient text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-white">All Staff Chats</h6>
                            <div>
                                <span class="badge bg-light text-dark" id="totalChats">Loading...</span>
                                <button class="btn btn-light btn-sm ms-2" onclick="refreshChats()">
                                    <i class="bx bx-refresh"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="filterStaff" class="form-label">Filter by Staff</label>
                                <select class="form-select" id="filterStaff">
                                    <option value="">All Staff</option>
                                    <?php echo getStaffOptions(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filterCategory" class="form-label">Filter by Category</label>
                                <select class="form-select" id="filterCategory">
                                    <option value="">All Categories</option>
                                    <option value="complaint">Complaint</option>
                                    <option value="general">General</option>
                                    <option value="update">Update</option>
                                    <option value="query">Query</option>
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
                            <table class="table table-striped table-bordered table-hover" id="chatsTable" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="10%">Sender</th>
                                        <th width="10%">Receiver</th>
                                        <th width="10%">Category</th>
                                        <th width="30%">Message</th>
                                        <th width="15%">Complaint ID</th>
                                        <th width="15%">Date & Time</th>
                                        <th width="5%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo getChatsList(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message Details Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Sender:</strong> <span id="modalSender"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Receiver:</strong> <span id="modalReceiver"></span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <strong>Category:</strong> <span id="modalCategory"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Complaint ID:</strong> <span id="modalComplaintId"></span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <strong>Date & Time:</strong> <span id="modalDateTime"></span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <strong>Message:</strong>
                        <div class="card mt-2">
                            <div class="card-body bg-light">
                                <p id="modalMessage" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
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
    var table = $('#chatsTable').DataTable({
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
            $('#totalChats').text(this.api().data().length + ' chats');
        }
    });

    // Filter by staff
    $('#filterStaff').on('change', function() {
        table.column(1).search(this.value).draw();
        updateChatCount();
    });

    // Filter by category
    $('#filterCategory').on('change', function() {
        table.column(3).search(this.value).draw();
        updateChatCount();
    });

    // Filter by date
    $('#filterDate').on('change', function() {
        if(this.value) {
            table.column(6).search(this.value).draw();
        } else {
            table.column(6).search('').draw();
        }
        updateChatCount();
    });

    // Update chat count
    function updateChatCount() {
        $('#totalChats').text(table.rows({ filter: 'applied' }).count() + ' chats');
    }

    table.on('draw', function() {
        updateChatCount();
    });
});

function refreshChats() {
    const table = $('#chatsTable').DataTable();
    table.ajax.reload();
    Lobibox.notify('info', {
        pauseDelayOnHover: true,
        continueDelayOnInactiveTab: false,
        position: 'top right',
        icon: 'bx bx-refresh',
        msg: 'Chats refreshed successfully'
    });
}

function clearFilters() {
    $('#filterStaff').val('');
    $('#filterCategory').val('');
    $('#filterDate').val('');
    
    const table = $('#chatsTable').DataTable();
    table.columns().search('').draw();
    $('#totalChats').text(table.data().length + ' chats');
}

function viewMessage(id, sender, receiver, category, message, complaintId, datetime) {
    $('#modalSender').text(sender);
    $('#modalReceiver').text(receiver);
    $('#modalCategory').text(category || 'N/A');
    $('#modalComplaintId').text(complaintId || 'N/A');
    $('#modalDateTime').text(datetime);
    $('#modalMessage').text(message);
    
    $('#messageModal').modal('show');
}

function deleteChat(id) {
    Lobibox.confirm({
        title: 'Confirm Delete',
        msg: 'Are you sure you want to delete this chat message? This action cannot be undone.',
        callback: function(result) {
            if(result) {
                $.ajax({
                    url: 'ajax_actions.php',
                    type: 'POST',
                    data: { 
                        action: 'delete_chat',
                        id: id
                    },
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
                                // Remove row from table
                                const table = $('#chatsTable').DataTable();
                                table.row('#chat-' + id).remove().draw();
                                updateChatCount();
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

<style>
.chat-message {
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.badge-category {
    font-size: 0.75em;
    padding: 4px 8px;
}

.badge-complaint { background-color: #dc3545; }
.badge-general { background-color: #6c757d; }
.badge-update { background-color: #17a2b8; }
.badge-query { background-color: #28a745; }

.table-container {
    overflow-x: auto;
    background: white;
    border-radius: 0.375rem;
}

#chatsTable th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 12px 8px;
}

#chatsTable td {
    padding: 10px 8px;
    border-color: #dee2e6;
    vertical-align: middle;
}

.btn-view {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
    padding: 4px 8px;
    font-size: 0.75rem;
}

.btn-delete {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
    padding: 4px 8px;
    font-size: 0.75rem;
}

.btn-view:hover, .btn-delete:hover {
    opacity: 0.8;
    color: white;
}
</style>

<?php
function getStaffOptions() {
    global $link;
    $output = '';
    
    $sql = "SELECT DISTINCT user_id FROM admin UNION SELECT DISTINCT sent_by FROM chat UNION SELECT DISTINCT recieved_by FROM chat";
    $result = $link->query($sql);
    
    while($row = $result->fetch_assoc()) {
        if(!empty($row['user_id'])) {
            $output .= '<option value="' . $row['user_id'] . '">' . $row['user_id'] . '</option>';
        }
    }
    
    return $output;
}

function getChatsList() {
    global $link;
    $output = '';
    
    $sql = "SELECT * FROM chat ORDER BY date DESC";
    $result = $link->query($sql);
    
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Format category badge
            $category = $row['category'] ?: 'general';
            $category_badge = '<span class="badge badge-' . $category . '">' . ucfirst($category) . '</span>';
            
            // Truncate message
            $message = $row['msg'];
            $truncated_message = strlen($message) > 100 ? substr($message, 0, 100) . '...' : $message;
            
            // Format datetime
            $datetime = date('M d, Y h:i A', strtotime($row['date']));
            
            // Complaint ID
            $complaint_id = $row['complaint_id'] ?: 'N/A';
            
            $output .= '<tr id="chat-' . $row['id'] . '">
                <td><strong>' . $row['id'] . '</strong></td>
                <td><strong>' . $row['sent_by'] . '</strong></td>
                <td><strong>' . $row['recieved_by'] . '</strong></td>
                <td>' . $category_badge . '</td>
                <td>
                    <span class="chat-message" title="' . htmlspecialchars($message) . '">
                        ' . htmlspecialchars($truncated_message) . '
                    </span>
                </td>
                <td><code>' . $complaint_id . '</code></td>
                <td><small>' . $datetime . '</small></td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-view" 
                                onclick="viewMessage(' . $row['id'] . ', \'' . $row['sent_by'] . '\', \'' . $row['recieved_by'] . '\', \'' . $row['category'] . '\', \'' . addslashes($row['msg']) . '\', \'' . $row['complaint_id'] . '\', \'' . $datetime . '\')"
                                title="View Message">
                            <i class="bx bx-show"></i>
                        </button>
                        <button class="btn btn-delete" 
                                onclick="deleteChat(' . $row['id'] . ')"
                                title="Delete Message">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>';
        }
    } else {
        $output = '<tr>
            <td colspan="8" class="text-center py-5">
                <div class="text-muted">
                    <i class="bx bx-chat display-4 d-block mb-3"></i>
                    <h5>No chat messages found</h5>
                    <p>Staff chat messages will appear here once they start communicating.</p>
                </div>
            </td>
        </tr>';
    }
    
    return $output;
}
?>