<?php
session_start();
include("connection.php");

// Sanitize GET input
$mobile = sanitize_input($_GET['mobile']);

// Initialize variables
$u_uname = '';

// Check if UUID cookie is set
if (isset($_COOKIE['uuid'])) {
    $u_uname = sanitize_input($_COOKIE['uuid']);
}

// Prepare and execute query to get user data
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

// Prepare and execute query to get user details
$sql45 = "SELECT * FROM user WHERE mobile_no=?";
$stmt45 = $link->prepare($sql45);
$stmt45->bind_param("s", $mobile);
$stmt45->execute();
$result45 = $stmt45->get_result();

if ($result45->num_rows > 0) {
    while ($row45 = $result45->fetch_assoc()) {
        $mobile_no = sanitize_input($row45['mobile_no']);
        $name = sanitize_input($row45['name']);
        $village = sanitize_input($row45['village']);
        $taluka = sanitize_input($row45['taluka']);
        $district = sanitize_input($row45['district']);
    } 
}
include('header.php');
?>

<div class="page-wrapper">
   <div class="page-content">
    

         <div class="col-lg-12">
<div class="alert alert-dark border-0 bg-dark alert-dismissible fade show" style="height:60px;">
                           <div class="text-white"><h5 class="text-white">Collect Fees</h5></div>
                        </div>
                     </div>
                    
      <div class="card">
         <div class="card-body">
            <form class="row g-3" action="fees_log_1.php" method="post" enctype="multipart/form-data">
                                  
                  <div class="col-md-6">
                    <label for="inputFirstName" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="inputFirstName" name="amount" value="" required>
                      <input type="hidden" class="form-control" id="inputFirstName" name="stud_mob" value="<?php echo $mobile; ?>" required>
                  </div>
                   
                   <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Description</label>
                     <input type="text" class="form-control" id="inputFirstName" name="desc" value="" required>

                  </div>
              
                 
                  
                  <div class="col-12" align="center">
                    <button type="submit" class="btn btn-outline-primary px-5">Submit</button>
                  </div>
                </form>
         </div>
      </div>
    <div class="card">
         <div class="card-body">   
       <div class="table-responsive">
                   <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Student Mobile No</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Date</th>
                                </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sr = 1;
                            // Prepared statement to select from complaint table
                            $sql1 = "SELECT * FROM fees_log WHERE std_mobile_no='$mobile' ORDER BY id DESC";
                            $stmt1 = $link->prepare($sql1);
                            $stmt1->execute();
                            $result1 = $stmt1->get_result();
                            if ($result1->num_rows > 0) {
                                while ($usr1 = $result1->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $sr; ?></td>
                                        <td><?php echo sanitize_input($usr1['std_mobile_no']); ?></td>
                                        <td><?php echo sanitize_input($usr1['amount']); ?></td>
                                        <td><?php echo sanitize_input($usr1['desc']); ?></td>
                                       
                                       
                                        <td><?php echo sanitize_input($usr1['date']); ?></td>
                                    </tr>
                                    <?php
                                    
                                    $sr++;
                                }
                            }
                                
                            $stmt1->close();
                            ?>
                        </tbody>
                    </table>
                </div>
                </div>
                </div>
   </div>
</div>
<?php include('footer.php'); ?>