<?php
session_start();
include("connection.php");

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
$stmt4->close();

include('header.php');
?> 

<div class="page-wrapper">
    <div class="page-content">
        <!-- Page Header -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Communication</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="info.php"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chat History</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="info.php" class="btn btn-outline-primary">
                        <i class='bx bx-arrow-back me-1'></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class='bx bx-chat me-2'></i>Chat History
                        </h5>
                        <p class="text-muted mb-0">Complete history of all communications</p>
                    </div>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-outline-secondary" onclick="exportTable()">
                            <i class='bx bx-export me-1'></i> Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search messages...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="categoryFilter">
                            <option value="">All Categories</option>
                            <option value="1">Category 1</option>
                            <option value="2">Category 2</option>
                            <option value="3">Category 3</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" id="dateFilter">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-outline-primary w-100" onclick="applyFilters()">
                            <i class='bx bx-filter me-1'></i> Apply Filters
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="chatHistoryTable" class="table table-striped table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Sent By</th>
                                <th>Received By</th>
                                <th>Message</th>
                                <th>Category</th>
                                <th>Complaint ID</th>
                                <th>Date & Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sr = 1;
                            $sql1 = "SELECT * FROM chat ORDER BY id DESC";
                            $stmt1 = $link->prepare($sql1);
                            $stmt1->execute();
                            $result1 = $stmt1->get_result();
                            if ($result1->num_rows > 0) {
                                while ($usr1 = $result1->fetch_assoc()) {
                                    $sent = $usr1['sent_by'];
                                    $received = $usr1['recieved_by'];
                                    $category = $usr1['category'];
                                    $complaint_id = $usr1['complaint_id'];
                                    $date = $usr1['date'];
                                    $message = $usr1['msg'];
                                    
                                    // Get sender name
                                    $sender_name = $sent;
                                    $sql20 = "SELECT * FROM admin where user_id='$sent'";
                                    $result20 = $link->query($sql20);
                                    if ($result20->num_rows > 0) {
                                        while($row20 = $result20->fetch_assoc()) {
                                            $sender_name = $sent . " (" . $row20["name"] . ")";
                                        }
                                    }
                                    
                                    // Get receiver name
                                    $receiver_name = $received;
                                    $sql200 = "SELECT * FROM user where mobile_no='$received'";
                                    $result200 = $link->query($sql200);
                                    if ($result200->num_rows > 0) {
                                        while($row200 = $result200->fetch_assoc()) {
                                            $receiver_name = $received . " (" . $row200["name"] . ")";
                                        }
                                    }
                            ?>
                            <tr>
                                <td><?php echo $sr; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle p-2 me-2">
                                            <i class='bx bx-user text-white'></i>
                                        </div>
                                        <span><?php echo $sender_name; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success rounded-circle p-2 me-2">
                                            <i class='bx bx-user text-white'></i>
                                        </div>
                                        <span><?php echo $receiver_name; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="message-preview">
                                        <?php echo strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message; ?>
                                        <?php if(strlen($message) > 50): ?>
                                        <a href="javascript:void(0)" onclick="showFullMessage('<?php echo addslashes($message); ?>')" 
                                           class="text-primary small ms-1">View more</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge 
                                        <?php 
                                        if($category == 1) echo 'bg-danger';
                                        elseif($category == 2) echo 'bg-warning';
                                        elseif($category == 3) echo 'bg-success';
                                        else echo 'bg-secondary';
                                        ?>">
                                        <?php echo $category; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($complaint_id): ?>
                                    <span class="badge bg-info"><?php echo $complaint_id; ?></span>
                                    <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d M Y, h:i A', strtotime($date)); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="viewMessageDetails(<?php echo $usr1['id']; ?>)">
                                            <i class='bx bx-show'></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteMessage(<?php echo $usr1['id']; ?>)">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php
                                    $sr++;
                                }
                            } else {
                                echo '<tr><td colspan="8" class="text-center text-muted py-4">No chat history found</td></tr>';
                            }
                            $stmt1->close();
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="text-muted">
                            Showing <strong><?php echo ($sr-1); ?></strong> entries
                        </div>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
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
            <div class="modal-body" id="messageDetails">
                <!-- Message details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function applyFilters() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const category = document.getElementById('categoryFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    const table = document.getElementById('chatHistoryTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let row of rows) {
        const message = row.cells[3].textContent.toLowerCase();
        const rowCategory = row.cells[4].textContent.trim();
        const rowDate = row.cells[6].textContent;
        
        let show = true;
        
        if (search && !message.includes(search)) show = false;
        if (category && rowCategory !== category) show = false;
        if (date && !rowDate.includes(date)) show = false;
        
        row.style.display = show ? '' : 'none';
    }
}

function showFullMessage(message) {
    document.getElementById('messageDetails').innerHTML = `
        <div class="alert alert-info">
            <h6>Full Message:</h6>
            <p class="mb-0">${message}</p>
        </div>
    `;
    new bootstrap.Modal(document.getElementById('messageModal')).show();
}

function viewMessageDetails(id) {
    // In a real application, you would fetch details via AJAX
    document.getElementById('messageDetails').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    new bootstrap.Modal(document.getElementById('messageModal')).show();
    
    // Simulate API call
    setTimeout(() => {
        document.getElementById('messageDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <strong>Message ID:</strong> ${id}
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong> <span class="badge bg-success">Delivered</span>
                </div>
            </div>
            <hr>
            <p><strong>Full message content would be displayed here with all details.</strong></p>
            <p>This feature would typically show complete message metadata and content.</p>
        `;
    }, 1000);
}

function deleteMessage(id) {
    if (confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
        // In a real application, you would make an AJAX call to delete the message
        alert('Message deletion feature would be implemented here. Message ID: ' + id);
    }
}

function exportTable() {
    // Simple table export functionality
    const table = document.getElementById('chatHistoryTable');
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let row of rows) {
        let rowData = [];
        const cells = row.querySelectorAll('th, td');
        for (let cell of cells) {
            rowData.push(cell.innerText);
        }
        csv.push(rowData.join(','));
    }
    
    const csvString = csv.join('\n');
    const blob = new Blob([csvString], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', 'chat_history.csv');
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

// Initialize DataTable functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('keyup', applyFilters);
});
</script>

<?php
include('footer.php');
?>