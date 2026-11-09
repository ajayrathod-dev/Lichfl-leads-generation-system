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
                           <div class="text-white"><h5 class="text-white">Register Enquiry</h5></div>
                        </div>
                     </div>
                    
      <div class="card">
         <div class="card-body">
            <form class="row g-3" action="comp.php" method="post" enctype="multipart/form-data">
                                  <div class="col-md-6">
                    <label for="inputFirstName" class="form-label">Registered By</label>
                    <input type="text" class="form-control" id="inputFirstName" name="reg" value="<?php echo htmlspecialchars($u_uname); ?>" required readonly>
                  </div>
                   
                     <div class="col-md-6">
                    <label for="inputPassword" class="form-label">Mobile No</label>
                      <input type="text" class="form-control" id="inputFirstName" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label for="inputFirstName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="inputFirstName" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                  </div>
                   
                   <div class="col-md-6"> 
                    <label for="inputLastName" class="form-label">Village</label>
                                        <input type="text" class="form-control" id="inputFirstName" name="village" value="<?php echo htmlspecialchars($village); ?>" required>

                  </div>
                    <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Taluka</label>
                                        <input type="text" class="form-control" id="inputFirstName" name="tq" value="<?php echo htmlspecialchars($taluka); ?>" required>

                  </div>
                  <div class="col-md-6">
                    <label for="inputLastName" class="form-label">District</label>
                                        <input type="text" class="form-control" id="inputFirstName"name="district" value="<?php echo htmlspecialchars($district); ?>" required>

                  </div>
                  <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Enquiry Title</label>
                                        <input type="text" class="form-control" id="inputFirstName" name="title" required>

                  </div>
                
                  <div class="col-md-6"> 
                    <label for="inputLastName" class="form-label">Assign Enquiry</label>
                    
                    <select class="form-control" id="inputLastName" name="assign_to">
                         <option value="">Select Option</option> 
                          
                        <?php
                        
                        $sql22 = "SELECT * FROM dropdown";
                        $result22 = $link->query($sql22);
                        if ($result22->num_rows > 0) 
                        {
 
                           while($row22 = $result22->fetch_assoc())
                           {
                               ?>
                               
                                <option value="<?php echo $row22['user_id']; ?>"><?php echo $row22['name']; ?> (<?php echo $row22['description']; ?>)</option>
                        <?php   }
                          }
                        
                        
                        ?>
                   

                    </select>
                  </div>
                 
                  
                  <div class="col-12" align="center">
                    <button type="submit" class="btn btn-outline-primary px-5">Register</button>
                  </div>
                </form>
         </div>
      </div>
   </div>
</div>
<?php include('footer.php'); ?>