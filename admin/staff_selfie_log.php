<?php
session_start();
include("connection.php");

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
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
                        <li class="breadcrumb-item active" aria-current="page">Staff Selfie Log</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary bg-gradient text-white">
                        <h6 class="mb-0 text-white">Staff Selfie Attendance Log</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="selfieLogTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Agent</th>
                                        <th>Description</th>
                                        <th>Location</th>
                                        <th>Address</th>
                                        <th>Date & Time</th>
                                        <th>Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo getSelfieLogs(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selfie Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Selfie Image">
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
function viewImage(imageData) {
    if(imageData) {
        // Convert blob data to base64 if needed
        $('#modalImage').attr('src', 'data:image/jpeg;base64,' + imageData);
        $('#imageModal').modal('show');
    } else {
        alert('No image available');
    }
}
</script>

<?php
function getSelfieLogs() {
    global $link;
    $output = '';
    $sql = "SELECT * FROM selfie_log ORDER BY datetime DESC";
    $result = $link->query($sql);
    
    while($row = $result->fetch_assoc()) {
        $imageData = base64_encode($row['image']);
        $output .= '<tr>
            <td>'.$row['id'].'</td>
            <td>'.$row['by'].'</td>
            <td>'.$row['description'].'</td>
            <td>'.$row['latitude'].', '.$row['longitude'].'</td>
            <td>'.$row['address'].'</td>
            <td>'.date('M d, Y h:i A', strtotime($row['datetime'])).'</td>
            <td>
                <button class="btn btn-sm btn-info" onclick="viewImage(\''.$imageData.'\')">View Image</button>
            </td>
        </tr>';
    }
    return $output;
}
?>