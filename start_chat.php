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
    .search-container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .customer-result {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 15px;
        padding: 15px;
        background: white;
        transition: all 0.3s ease;
    }
    .customer-result:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-color: #007bff;
    }
    .customer-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .customer-details h6 {
        margin-bottom: 5px;
        color: #333;
    }
    .customer-meta {
        font-size: 0.85rem;
        color: #666;
    }
    .chat-btn {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        color: white;
        transition: all 0.3s ease;
    }
    .chat-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,123,255,0.3);
    }
    .no-results {
        text-align: center;
        padding: 40px;
        color: #666;
    }
    .loading-spinner {
        display: none;
        text-align: center;
        padding: 20px;
    }
</style>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Mobile Header -->
        <div class="mobile-only mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0">Start New Chat</h5>
                    <small class="text-muted">Search customer by mobile number</small>
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
                            <li class="breadcrumb-item active" aria-current="page">Start Chat</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Search Box -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class='bx bx-search me-2'></i>Search Customer
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="search-container">
                            <form id="searchForm">
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="mobileSearch" 
                                           placeholder="Enter mobile number to search..."
                                           pattern="[0-9]{10}" 
                                           maxlength="10"
                                           required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class='bx bx-search'></i> Search
                                    </button>
                                </div>
                                <small class="text-muted mt-2 d-block">Enter 10-digit mobile number to find customer details</small>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const mobileNumber = document.getElementById('mobileSearch').value.trim();
    
    if (mobileNumber.length !== 10) {
        alert('Please enter a valid 10-digit mobile number');
        return;
    }
    
    // Simply redirect to customer_chat.php with the mobile parameter
    window.location.href = 'customer_chat.php?mobile=' + mobileNumber;
});

// Auto-format mobile number input
document.getElementById('mobileSearch').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '');
});
</script>

<?php include('footer.php'); ?>