<?php
session_start();
include("connection.php");

// Check if user is logged in for POST actions
if($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

switch($action) {
    case 'add_agent':
        addAgent();
        break;
    case 'update_agent':
        updateAgent();
        break;
    case 'delete_agent':
        deleteAgent();
        break;
    case 'assign_contacts':
        assignContacts();
        break;
    case 'update_loan_status':
        updateLoanStatus();
        break;
    case 'get_assignment_details':
        getAssignmentDetails();
        break;
    case 'reassign_contact':
        reassignContact();
        break;
    case 'get_id_range':
        getIdRange();
        break;
    case 'delete_chat':
        deleteChat();
        break;
    case 'get_loan_details':
        getLoanDetails();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function addAgent() {
    global $link;
    
    $user_id = sanitize_input($_POST['user_id']);
    $password = sanitize_input($_POST['password']);
    $name = sanitize_input($_POST['name']);
    
    // Check if user already exists
    $check_sql = "SELECT id FROM admin WHERE user_id = ?";
    $stmt = $link->prepare($check_sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'User ID already exists']);
        return;
    }
    
    // Insert new agent
    $sql = "INSERT INTO admin (user_id, password, name, present_staff) VALUES (?, ?, ?, 0)";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("sss", $user_id, $password, $name);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Agent added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding agent: ' . $stmt->error]);
    }
}

function updateAgent() {
    global $link;
    
    $id = intval($_POST['id']);
    $user_id = sanitize_input($_POST['user_id']);
    $name = sanitize_input($_POST['name']);
    $password = $_POST['password'];
    
    // Check if new user_id already exists (excluding current agent)
    $check_sql = "SELECT id FROM admin WHERE user_id = ? AND id != ?";
    $check_stmt = $link->prepare($check_sql);
    $check_stmt->bind_param("si", $user_id, $id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if($check_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'User ID already exists']);
        return;
    }
    
    if(!empty($password)) {
        $sql = "UPDATE admin SET user_id = ?, password = ?, name = ? WHERE id = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("sssi", $user_id, $password, $name, $id);
    } else {
        $sql = "UPDATE admin SET user_id = ?, name = ? WHERE id = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("ssi", $user_id, $name, $id);
    }
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Agent updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating agent: ' . $stmt->error]);
    }
}

function deleteAgent() {
    global $link;
    
    $id = intval($_POST['id']);
    
    // Check if agent has assigned loans
    $check_sql = "SELECT user_id FROM admin WHERE id = ?";
    $check_stmt = $link->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if($check_result->num_rows > 0) {
        $agent = $check_result->fetch_assoc();
        $user_id = $agent['user_id'];
        
        // Check assigned loans
        $loan_sql = "SELECT COUNT(*) as loan_count FROM complaint WHERE assign_to = ?";
        $loan_stmt = $link->prepare($loan_sql);
        $loan_stmt->bind_param("s", $user_id);
        $loan_stmt->execute();
        $loan_result = $loan_stmt->get_result();
        $loan_count = $loan_result->fetch_assoc()['loan_count'];
        
        if($loan_count > 0) {
            echo json_encode(['success' => false, 'message' => 'Cannot delete agent. They have ' . $loan_count . ' assigned loans.']);
            return;
        }
    }
    
    // Delete agent
    $sql = "DELETE FROM admin WHERE id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Agent deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting agent: ' . $stmt->error]);
    }
}

function assignContacts() {
    global $link;
    
    $agent_id = sanitize_input($_POST['agent_id']);
    $start_serial = intval($_POST['start_serial']);
    $end_serial = intval($_POST['end_serial']);
    
    // Validate range
    if($start_serial > $end_serial) {
        echo json_encode(['success' => false, 'message' => 'Start ID cannot be greater than End ID']);
        return;
    }
    
    // Check if agent exists
    $agent_check = "SELECT user_id FROM admin WHERE user_id = ?";
    $stmt = $link->prepare($agent_check);
    $stmt->bind_param("s", $agent_id);
    $stmt->execute();
    $agent_result = $stmt->get_result();
    
    if($agent_result->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Staff member not found']);
        return;
    }
    
    // Check if any contact in range is already assigned
    $check_sql = "SELECT id, assign_to FROM complaint WHERE id BETWEEN ? AND ? AND assign_to != '' LIMIT 1";
    $stmt = $link->prepare($check_sql);
    $stmt->bind_param("ii", $start_serial, $end_serial);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'success' => false, 
            'message' => "Contact ID {$row['id']} is already assigned to {$row['assign_to']}"
        ]);
        return;
    }
    
    // Check if contacts exist in the range
    $count_sql = "SELECT COUNT(*) as total_count FROM complaint WHERE id BETWEEN ? AND ?";
    $stmt = $link->prepare($count_sql);
    $stmt->bind_param("ii", $start_serial, $end_serial);
    $stmt->execute();
    $count_result = $stmt->get_result();
    $total_count = $count_result->fetch_assoc()['total_count'];
    
    if($total_count == 0) {
        echo json_encode(['success' => false, 'message' => 'No contacts found in the specified ID range']);
        return;
    }
    
    // Assign contacts
    $sql = "UPDATE complaint SET assign_to = ?, assignment_date = NOW() WHERE id BETWEEN ? AND ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("sii", $agent_id, $start_serial, $end_serial);
    
    if($stmt->execute()) {
        $affected_rows = $stmt->affected_rows;
        echo json_encode([
            'success' => true, 
            'message' => "Successfully assigned $affected_rows contacts to $agent_id (ID: $start_serial to $end_serial)"
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error assigning contacts: ' . $stmt->error]);
    }
}

function updateLoanStatus() {
    global $link;
    
    $complaint_id = intval($_POST['complaint_id']);
    $status = intval($_POST['status']);
    $remarks = sanitize_input($_POST['remarks']);
    $changed_by = $_SESSION['admin_user_id'] ?? 'admin';
    
    // Validate complaint exists
    $check_sql = "SELECT id FROM complaint WHERE id = ?";
    $stmt = $link->prepare($check_sql);
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $check_result = $stmt->get_result();
    
    if($check_result->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Loan application not found']);
        return;
    }
    
    // Insert into loan status history
    $sql = "INSERT INTO loan_status_history (complaint_id, status, remarks, changed_by) VALUES (?, ?, ?, ?)";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("iiss", $complaint_id, $status, $remarks, $changed_by);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Loan status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating loan status: ' . $stmt->error]);
    }
}

function getAssignmentDetails() {
    global $link;
    
    $agent_id = sanitize_input($_POST['agent_id']);
    
    // Get assignment details
    $sql = "SELECT c.*, 
                   (SELECT status FROM loan_status_history 
                    WHERE complaint_id = c.id 
                    ORDER BY changed_at DESC LIMIT 1) as loan_status 
            FROM complaint c 
            WHERE c.assign_to = ? 
            ORDER BY c.id DESC 
            LIMIT 50";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $agent_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $output = '<div class="table-responsive">
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>Contact ID</th>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>Loan Amount</th>
                    <th>Status</th>
                    <th>Assigned Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';
    
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $status = getLoanStatusText($row['loan_status']);
            $assigned_date = $row['assignment_date'] ? date('M d, Y', strtotime($row['assignment_date'])) : 'N/A';
            
            $output .= '<tr>
                <td><strong>' . $row['id'] . '</strong></td>
                <td>' . $row['name'] . '</td>
                <td>' . $row['mobile_no'] . '</td>
                <td>₹' . number_format($row['loan_amount'], 2) . '</td>
                <td><span class="badge bg-primary">' . $status . '</span></td>
                <td><small>' . $assigned_date . '</small></td>
                <td>
                    <button class="btn btn-sm btn-outline-warning" 
                            onclick="reassignContact(' . $row['id'] . ', \'' . $agent_id . '\')">
                        <i class="bx bx-transfer"></i>
                    </button>
                </td>
            </tr>';
        }
    } else {
        $output .= '<tr><td colspan="7" class="text-center text-muted">No assignments found for this staff member</td></tr>';
    }
    
    $output .= '</tbody></table></div>';
    
    echo $output;
}

function reassignContact() {
    global $link;
    
    $contact_id = intval($_POST['contact_id']);
    $new_agent = sanitize_input($_POST['new_agent']);
    
    // Check if new agent exists
    $agent_check = "SELECT user_id FROM admin WHERE user_id = ?";
    $stmt = $link->prepare($agent_check);
    $stmt->bind_param("s", $new_agent);
    $stmt->execute();
    $agent_result = $stmt->get_result();
    
    if($agent_result->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Staff member not found']);
        return;
    }
    
    // Reassign contact
    $sql = "UPDATE complaint SET assign_to = ?, assignment_date = NOW() WHERE id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("si", $new_agent, $contact_id);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Contact #$contact_id reassigned to $new_agent successfully"]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error reassigning contact: ' . $stmt->error]);
    }
}

function deleteChat() {
    global $link;
    
    $id = intval($_POST['id']);
    
    // Delete chat message
    $sql = "DELETE FROM chat WHERE id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Chat message deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting chat message: ' . $stmt->error]);
    }
}

function getLoanDetails() {
    global $link;
    
    $complaint_id = intval($_POST['complaint_id']);
    
    // Get loan details
    $sql = "SELECT c.*, 
                   (SELECT status FROM loan_status_history 
                    WHERE complaint_id = c.id 
                    ORDER BY changed_at DESC LIMIT 1) as current_status,
                   (SELECT changed_at FROM loan_status_history 
                    WHERE complaint_id = c.id 
                    ORDER BY changed_at DESC LIMIT 1) as last_updated
            FROM complaint c 
            WHERE c.id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $loan = $result->fetch_assoc();
        $status_text = getStatusText($loan['current_status'] ?? 0);
        $status_class = getStatusClass($loan['current_status'] ?? 0);
        
        $output = '
        <div class="row">
            <div class="col-md-6">
                <h6>Customer Information</h6>
                <table class="table table-sm table-bordered">
                    <tr><th width="40%">Name</th><td>' . $loan['name'] . '</td></tr>
                    <tr><th>Mobile No</th><td>' . $loan['mobile_no'] . '</td></tr>
                    <tr><th>Village</th><td>' . $loan['village'] . '</td></tr>
                    <tr><th>Taluka</th><td>' . $loan['taluka'] . '</td></tr>
                    <tr><th>District</th><td>' . $loan['destrict'] . '</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Loan Information</h6>
                <table class="table table-sm table-bordered">
                    <tr><th width="40%">Loan Amount</th><td>₹' . number_format($loan['loan_amount'], 2) . '</td></tr>
                    <tr><th>Loan Interest</th><td>' . $loan['loan_interest'] . '</td></tr>
                    <tr><th>Loan Type</th><td>' . ($loan['loan_type'] ?: 'N/A') . '</td></tr>
                    <tr><th>Current Status</th><td><span class="status-badge badge-' . $status_class . '">' . $status_text . '</span></td></tr>
                    <tr><th>Assigned To</th><td>' . ($loan['assign_to'] ?: 'Not Assigned') . '</td></tr>
                </table>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <h6>Complaint Details</h6>
                <div class="card">
                    <div class="card-body">
                        ' . nl2br(htmlspecialchars($loan['complaint_title'])) . '
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-6">
                <h6>Timeline</h6>
                <table class="table table-sm">
                    <tr><th>Registered Date</th><td>' . date('M d, Y h:i A', strtotime($loan['date'])) . '</td></tr>
                    <tr><th>Last Updated</th><td>' . ($loan['last_updated'] ? date('M d, Y h:i A', strtotime($loan['last_updated'])) : 'N/A') . '</td></tr>
                    <tr><th>Assignment Date</th><td>' . ($loan['assignment_date'] ? date('M d, Y h:i A', strtotime($loan['assignment_date'])) : 'N/A') . '</td></tr>
                </table>
            </div>
        </div>';
        
        // Get status history
        $history_sql = "SELECT * FROM loan_status_history WHERE complaint_id = ? ORDER BY changed_at DESC";
        $history_stmt = $link->prepare($history_sql);
        $history_stmt->bind_param("i", $complaint_id);
        $history_stmt->execute();
        $history_result = $history_stmt->get_result();
        
        if($history_result->num_rows > 0) {
            $output .= '
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Status History</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                    <th>Changed By</th>
                                    <th>Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>';
            
            while($history = $history_result->fetch_assoc()) {
                $history_status = getStatusText($history['status']);
                $history_class = getStatusClass($history['status']);
                $output .= '
                <tr>
                    <td><span class="status-badge badge-' . $history_class . '">' . $history_status . '</span></td>
                    <td>' . ($history['remarks'] ?: 'N/A') . '</td>
                    <td>' . $history['changed_by'] . '</td>
                    <td>' . date('M d, Y h:i A', strtotime($history['changed_at'])) . '</td>
                </tr>';
            }
            
            $output .= '</tbody></table></div></div></div>';
        }
        
    } else {
        $output = '<div class="alert alert-danger">Loan application not found</div>';
    }
    
    echo $output;
}

function getIdRange() {
    global $link;
    
    $min_sql = "SELECT MIN(id) as min_id FROM complaint";
    $max_sql = "SELECT MAX(id) as max_id FROM complaint";
    $min_result = $link->query($min_sql);
    $max_result = $link->query($max_sql);
    $min_id = $min_result->fetch_assoc()['min_id'] ?: 1;
    $max_id = $max_result->fetch_assoc()['max_id'] ?: 1;
    
    echo json_encode(['success' => true, 'min_id' => $min_id, 'max_id' => $max_id]);
}

// Helper functions
function getLoanStatusText($status) {
    $statuses = ['Pending', 'In Progress', 'Approved', 'Rejected', 'Disbursed'];
    return $statuses[$status] ?? 'Pending';
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