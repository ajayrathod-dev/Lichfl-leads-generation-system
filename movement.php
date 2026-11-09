<?php

include('header.php');


         if($designation_if=='BM' || $designation_if=='A BM' || $designation_if=='Reception'){
            
            
        }
        else
        {
            header('Location:dashboard.php');
        }
        
    
    date_default_timezone_set('Asia/Kolkata'); // Set to your timezone
$currentDateTime = date("Y-m-d H:i:s"); // Format: 2025-06-09 11:45:00
  

?>

<style>
  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
  }

  .switch input {
    display: none;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked+.slider {
    background-color: #2196F3;
  }

  input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked+.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
</style>
<script>
  function myfunction(str, str1) {
    

    alert("DO YOU WANT TO CHANGE STAUTS");

    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("prog_show").innerHTML = xmlhttp.responseText;
        location.reload();

      }
    }
    xmlhttp.open("GET", "show_program.php?uid=" + str + "&status=" + str1, true);
    xmlhttp.send();
  }
</script>
<div class="page-wrapper">
   <div class="page-content">

                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">+ Movement</button>
                     <div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Add Movement</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <form class="row g-3" action="movement1.php" method="post" enctype="multipart/form-data">
                                  <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">कर्मचाऱ्याचे नाव</label>
                                       <select  class="form-control select2" id="workAssignTo" name="c_name" required>
                                           <option>--Select--</option>
                                         <?php 
                                            $sql45 = "SELECT * FROM admin WHERE status=0";
                                            $result45 = mysqli_query($link, $sql45);
                                            if ($result45->num_rows > 0) {
 
                                            while ($row45 = $result45->fetch_assoc()) {
                                        ?>
                                        
                                        <option value="<?php echo $row45['user_id']; ?>"><?php echo $row45['emp_name']; ?> (<?php echo $row45['designation']; ?>)(<?php echo $row45['branch']; ?>)</option>
                                      <?php 
                                           }
                                         }
                                         
                                         
                                         ?>
                                         
                                     </select>
                                     
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">इन/आउट</label>
                                     <select class="form-control" id="inputFirstName" name="inout" required>
                                         
                                       <option>In</option>
                                       <option>Out</option>
                                    
                                     </select>
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">कारण</label>
                                     <input type="text" class="form-control" id="inputFirstName" name="res" required>
                                     <input type="hidden" class="form-control" id="inputFirstName" name="branch" required value="<?php echo $mybranch; ?>">

                                  </div>
                                
                                   <div class="col-md-6">
                                    <label for="inputFirstName" class="form-label">दिनांक</label>
                                     <input type="datetime-local" class="form-control" value="<?php echo $currentDateTime; ?>" name="time" required step="1">
                                  </div>
                               
                                  <div class="col-12" align="center">
                                    <button type="submit" class="btn btn-outline-primary px-5" onclick="return confirm('Do you want to submit?')">जतन करा </button>
                                  </div>
                                </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          
                          </div>
                        </div>
                      </div>
                    </div>
                    

      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="example2" class="table table-striped table-bordered">
                   <thead>
                   <tr>
                     <th>अ.क्र </th>
                     <th>कर्मचाऱ्याचे नाव</th>
                     <th>इन/आउट</th>
                     <th>कारण</th>
                     <th>द्वारे</th>
                     <th>दिनांक</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php 
                   $sr=1;
                    $sql451 = "SELECT * FROM movement WHERE `by`='$u_uname' OR `branch`='$mybranch' order by id desc limit 100";
                    $result451 = mysqli_query($link, $sql451);
                    if ($result451->num_rows > 0) {
                    while ($row451 = $result451->fetch_assoc()) {
                         $emp_id=$row451['emp_id'];
                        
                    ?>
                          <tr>
                              <td><?php echo $sr;  ?></td>
                             
                              <td><?php
                              
                                    $sql45 = "SELECT * FROM admin WHERE user_id='$emp_id'";
                                            $result45 = mysqli_query($link, $sql45);
                                            if ($result45->num_rows > 0) {
 
                                            while ($row45 = $result45->fetch_assoc()) {
                                        
                                         echo $row45['emp_name']."(".$row45['designation'].") (".$row45['branch'].")";    

                                           }
                                         }
                              
                              
                              
                              
                              ?></td>
                               <td><?php echo $row451['inout']; ?></td>
                              <td><?php echo $row451['reasion']; ?></td>
                              <td><?php
                              $byid = $row451['by'];
                                    $sql45 = "SELECT * FROM admin WHERE user_id='$byid'";
                                            $result45 = mysqli_query($link, $sql45);
                                            if ($result45->num_rows > 0) {
 
                                          	while ($row45 = $result45->fetch_assoc()) {
                                     		
                                     		 echo $row45['emp_name'];                        
                                           }
                                         }
                              
                              
                              
                              
                              ?></td>
                              <td><?php 
$datetime = $row451['time']; // 2:30 PM
$formatted = date("d-m-Y h:i A", strtotime($datetime));
echo $formatted;
                               ?></td>
                              
                          </tr>               
                                      
                    <?php 
                    $sr++;
                     }
                      }
                                         
                ?>
                 </tbody>

               </table>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<?php
   include('footer.php');
?>