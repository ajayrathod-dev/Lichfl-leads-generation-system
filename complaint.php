<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
include("connection.php");

$mobile = isset($_GET['mobile']) ? sanitize_input($_GET['mobile']) : '';
$u_uname = '';

if (isset($_COOKIE['uuid'])) {
    $u_uname = sanitize_input($_COOKIE['uuid']);
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

$mobile_no = $name = $village = $taluka = $destrict = '';

$sql45 = "SELECT * FROM user WHERE mobile_no=?";
$stmt45 = $link->prepare($sql45);
$stmt45->bind_param("s", $mobile);
$stmt45->execute();
$result45 = $stmt45->get_result();

if ($result45->num_rows > 0) {
    while ($row45 = $result45->fetch_assoc()) {
        $mobile_no = sanitize_input($row45['mobile_no']);
        $name = $row45['name']; // FIXED: Remove sanitize_input to preserve UTF-8
        $village = $row45['village']; // FIXED: Remove sanitize_input to preserve UTF-8
        $taluka = $row45['taluka']; // FIXED: Remove sanitize_input to preserve UTF-8
        $destrict = $row45['destrict']; // FIXED: Remove sanitize_input to preserve UTF-8
    }
}
include('header.php');
?>

<style>
/* Mobile-First Responsive Design */
.mobile-header {
    display: none;
}

.form-control-mobile {
    padding: 12px;
    font-size: 16px; /* Prevents zoom on iOS */
    border-radius: 8px;
    width: 100%;
}

.btn-mobile {
    padding: 12px 20px;
    font-size: 0.9rem;
    border-radius: 10px;
    width: 100%;
}

.section-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 15px;
    border-radius: 8px;
    margin: 20px 0 15px 0;
    font-size: 0.95rem;
}

.section-header i {
    font-size: 1.1rem;
}

/* Mobile-only elements */
.mobile-only {
    display: none;
}

.desktop-only {
    display: block;
}

/* Responsive utilities */
@media (max-width: 768px) {
    .mobile-header {
        display: block;
        background: white;
        padding: 15px;
        border-bottom: 1px solid #dee2e6;
        margin: -20px -20px 20px -20px;
    }
    
    .desktop-only {
        display: none !important;
    }
    
    .mobile-only {
        display: block !important;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .col-md-6 {
        margin-bottom: 15px;
    }
    
    .section-header {
        margin: 15px 0 10px 0;
        padding: 10px 12px;
        font-size: 0.9rem;
    }
    
    .form-control, .form-select {
        padding: 12px;
        font-size: 16px;
    }
    
    .btn-group-mobile {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 20px;
    }
    
    .btn-group-mobile .btn {
        width: 100%;
        margin: 0 !important;
    }
}

@media (max-width: 576px) {
    .page-content {
        padding: 10px;
    }
    
    .card {
        border-radius: 10px;
        margin-bottom: 15px;
    }
    
    .card-header {
        padding: 15px;
    }
    
    .card-header h5 {
        font-size: 1.1rem;
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }
    
    .row.g-3 {
        margin: 0 -8px;
    }
    
    .row.g-3 > [class*="col-"] {
        padding: 0 8px;
    }
}

/* Extra small devices */
@media (max-width: 375px) {
    .container-fluid {
        padding-left: 5px;
        padding-right: 5px;
    }
    
    .card-body {
        padding: 12px;
    }
    
    .section-header {
        font-size: 0.85rem;
        padding: 8px 10px;
    }
    
    .form-control, .form-select {
        padding: 10px;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .section-header {
        background: #495057;
    }
}

/* Focus states for better accessibility */
.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Loading animation */
.btn-loading {
    position: relative;
    color: transparent;
}

.btn-loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-left: -10px;
    margin-top: -10px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-right-color: transparent;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Mobile Header -->
        <div class="mobile-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0">Register New Loan</h5>
                    <small class="text-muted">Customer: <?php echo $name ?: $mobile; ?></small>
                </div>
                <a href="api.php?mobile=<?php echo $mobile; ?>" class="btn btn-outline-secondary btn-sm">
                    <i class='bx bx-arrow-back'></i>
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
                            <li class="breadcrumb-item active" aria-current="page">Register Loan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <!-- Loan Card -->
                <div class="card">
                    <div class="card-header bg-primary bg-gradient text-white">
                        <h5 class="card-title text-white mb-0">
                            <i class='bx bx-message-square-add me-2'></i>Register New Loan
                        </h5>
                    </div>
                    <div class="card-body">
                        <form class="row g-3" action="comp.php" method="post" enctype="multipart/form-data" id="loanForm" accept-charset="UTF-8">
                            <!-- Personal Information Section -->
                            <div class="col-12">
                                <div class="section-header">
                                    <i class='bx bx-user-circle me-2'></i>Personal Information
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label for="inputRegisteredBy" class="form-label">Registered By</label>
                                <input type="text" class="form-control form-control-mobile" id="inputRegisteredBy" name="reg" 
                                       value="<?php echo $u_uname; ?>" required readonly>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label for="inputMobile" class="form-label">Mobile No</label>
                                <input type="text" class="form-control form-control-mobile" id="inputMobile" name="mobile" 
                                       value="<?php echo $mobile; ?>" required>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label for="inputName" class="form-label">Full Name</label>
                                <input type="text" class="form-control form-control-mobile" id="inputName" name="name" 
                                       value="<?php echo $name; ?>" required>
                            </div>

                            <!-- Address Information Section -->
                            <div class="col-12">
                                <div class="section-header">
                                    <i class='bx bx-map me-2'></i>Address Information
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-4">
                                <label for="inputVillage" class="form-label">Village</label>
                                <input type="text" class="form-control form-control-mobile" id="inputVillage" name="village" 
                                       value="<?php echo $village; ?>" placeholder="Enter village">
                            </div>
                            
                            <div class="col-12 col-md-4">
                                <label for="inputTaluka" class="form-label">Taluka</label>
                                <input type="text" class="form-control form-control-mobile" id="inputTaluka" name="taluka" 
                                       value="<?php echo $taluka; ?>" placeholder="Enter taluka">
                            </div>
                            
                            <div class="col-12 col-md-4">
                                <label for="inputDistrict" class="form-label">District</label>
                                <input type="text" class="form-control form-control-mobile" id="inputDistrict" name="destrict" 
                                       value="<?php echo $destrict; ?>" placeholder="Enter district">
                            </div>
                            
                            <!-- Loan Information Section -->
                            <div class="col-12">
                                <div class="section-header">
                                    <i class='bx bx-credit-card me-2'></i>Loan Information
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label for="inputLoanReason" class="form-label">Loan Reason</label>
                                <input type="text" class="form-control form-control-mobile" id="inputLoanReason" name="title" 
                                       placeholder="Enter reason for loan" required>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label for="loanAmount" class="form-label">Loan Amount</label>
                                <input type="number" class="form-control form-control-mobile" id="loanAmount" name="loan_amount" 
                                       placeholder="Enter loan amount" required>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label for="loanInterest" class="form-label">Interest</label>
                                <select class="form-select form-control-mobile" id="loanInterest" name="loan_interest" required>
                                    <option value="">Select Interest</option>
                                    <option value="Interested">Interested</option>
                                    <option value="Not Interested">Not Interested</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label for="loanType" class="form-label">Loan Type</label>
                                <select class="form-select form-control-mobile" id="loanType" name="loan_type" onchange="checkOtherLoan()" required>
                                    <option value="">Select Loan Type</option>
                                    <option value="Home Loan">Home Loan</option>
                                    <option value="Property Loan">Property Loan</option>
                                    <option value="Business Loan">Business Loan</option>
                                    <option value="Personal Loan">Personal Loan</option>
                                    <option value="Education Loan">Education Loan</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div id="otherLoanDiv" class="mt-2" style="display: none;">
                                    <input type="text" class="form-control form-control-mobile" id="otherLoanType" name="other_loan_type" 
                                           placeholder="Please specify loan type">
                                </div>
                            </div>

                            <!-- Assignment Section -->
                            <div class="col-12">
                                <div class="section-header">
                                    <i class='bx bx-user-check me-2'></i>Assignment
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label for="assignTo" class="form-label">Assign To</label>
                                <select class="form-select form-control-mobile" id="assignTo" name="assign_to" required>
                                    <option value="">Select Agent</option>
                                    <?php
                                    $sql22 = "SELECT * FROM admin";
                                    $result22 = $link->query($sql22);
                                    if ($result22->num_rows > 0) {
                                        while($row22 = $result22->fetch_assoc()) {
                                            // FIXED: Remove htmlspecialchars to preserve UTF-8 in admin names
                                            echo '<option value="' . $row22['user_id'] . '">' . 
                                                 $row22['name'] .'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-4">
                                <!-- Desktop Buttons -->
                                <div class="desktop-only">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="api.php?mobile=<?php echo $mobile; ?>" class="btn btn-secondary me-md-2">
                                            <i class='bx bx-arrow-back me-1'></i> Back to Chat
                                        </a>
                                        <button type="submit" class="btn btn-primary px-5" id="submitBtn">
                                            <i class='bx bx-check-circle me-1'></i> Add Loan
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Mobile Buttons -->
                                <div class="mobile-only">
                                    <div class="btn-group-mobile">
                                        <button type="submit" class="btn btn-primary" id="submitBtnMobile">
                                            <i class='bx bx-check-circle me-1'></i> Add Loan
                                        </button>
                                        <a href="api.php?mobile=<?php echo $mobile; ?>" class="btn btn-outline-secondary">
                                            <i class='bx bx-arrow-back me-1'></i> Back to Chat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function checkOtherLoan() {
    var loanType = document.getElementById('loanType');
    var otherDiv = document.getElementById('otherLoanDiv');
    var otherInput = document.getElementById('otherLoanType');
    
    if (loanType.value === 'Other') {
        otherDiv.style.display = 'block';
        otherInput.required = true;
    } else {
        otherDiv.style.display = 'none';
        otherInput.required = false;
        otherInput.value = '';
    }
}

// Form validation and submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loanForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnMobile = document.getElementById('submitBtnMobile');
    
    form.addEventListener('submit', function(e) {
        const loanType = document.getElementById('loanType');
        const otherInput = document.getElementById('otherLoanType');
        
        // Validate other loan type
        if (loanType.value === 'Other' && !otherInput.value.trim()) {
            e.preventDefault();
            alert('Please specify the loan type');
            otherInput.focus();
            return false;
        }
        
        // Validate all required fields
        const requiredFields = form.querySelectorAll('[required]');
        let valid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Please fill in all required fields');
            return false;
        }
        
        // Show loading state
        if (submitBtn) {
            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;
        }
        if (submitBtnMobile) {
            submitBtnMobile.classList.add('btn-loading');
            submitBtnMobile.disabled = true;
        }
        
        // Form will submit normally
    });
    
    // Real-time validation
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
});

// Mobile-specific enhancements
if (window.innerWidth <= 768) {
    // Focus management for better mobile UX
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                // Scroll to input on focus for better mobile experience
                setTimeout(() => {
                    this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
            });
        });
    });
}
</script>

<?php include('footer.php'); ?>