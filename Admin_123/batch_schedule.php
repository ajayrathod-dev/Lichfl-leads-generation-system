<?php
session_start();
include("connection.php");




if (isset($_COOKIE['uuid'])) {

  $u_uname = mysqli_real_escape_string($link, $_COOKIE['uuid']);

} else {

  $u_uname = '';

}



 $sql4 = "SELECT * FROM ods WHERE uuid='$u_uname' AND status='verified'";

$result4 = mysqli_query($link, $sql4);

if ($result4->num_rows > 0) {

  while ($row4 = $result4->fetch_assoc()) {

    $u_uname = mysqli_real_escape_string($link, $row4['user_id']);

    $br_id = mysqli_real_escape_string($link, $row4['user_id']);

  }

} else {

  header("location:logout.php");

  exit;

}
   include('header.php');
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

function myfunction1(str1,str2)

{

  if (window.XMLHttpRequest)

    {// code for IE7+, Firefox, Chrome, Opera, Safari

        xmlhttp=new XMLHttpRequest();

    }

  else

    {// code for IE6, IE5

       xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

    }

   xmlhttp.onreadystatechange=function()

    {

      if (xmlhttp.readyState==4 && xmlhttp.status==200)

      {

      document.getElementById("prog_show").innerHTML=xmlhttp.responseText;

         location.reload();

 

      }

      }

  xmlhttp.open("GET","trans_status.php?usd="+str1+"&id="+str2,true);

  xmlhttp.send();

}

</script>
<div class="page-wrapper">
   <div class="page-content">
    


     		<div class="col-xl-12 mx-auto">  
						
						<div class="card border-top border-0 border-4 border-info">
							<div class="card-body">
								<div class="border p-4 rounded">
									<form action="batch_schedule1.php" method="post"> 
									<div class="card-title d-flex align-items-center">
										<div><i class="bx bxs-user me-1 font-22 text-info"></i>
										</div>
										<h5 class="mb-0 text-info">Add Schedule Batch</h5>
									</div>
									<hr/>
									<div class="row mb-3">
										<label for="inputEnterYourName" class="col-sm-3 col-form-label">Course Name</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="inputEnterYourName" name="c_name" required>
										</div>
									</div>
									<div class="row mb-3">
										<label for="inputPhoneNo2" class="col-sm-3 col-form-label">Start Date</label>
										<div class="col-sm-9">
											<input type="date" class="form-control" name="start_date">
										</div>
									</div>
										<div class="row mb-3">
										<label for="inputPhoneNo2" class="col-sm-3 col-form-label">Day</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="day">
										</div>
									</div>
								
										<div class="row mb-3">
										<label for="inputPhoneNo2" class="col-sm-3 col-form-label">Time</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="time">
										</div>
									</div>
										
									<div class="row">
										<label class="col-sm-3 col-form-label"></label>
										<div class="col-sm-9">
											<button type="submit" class="btn btn-outline-primary px-5 radius-30">Add Shedule Batch</button>
										</div>
									</div>
								</form>
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
                     <th>Sr.No</th>
                     <th>Course Name</th>
                     <th>Start Date</th>
                     <th>Days</th>
                     <th>Time</th>
                                     
                     
                    
                     

                   </tr>
                 </thead>
                 <tbody>
                   <?php
                    $sr = 1;
                    $sql1 = "SELECT * FROM batch_shedule order by id DESC";
                    $result1 = mysqli_query($link, $sql1);
                    if ($result1->num_rows > 0) {
                      while ($usr1 = mysqli_fetch_array($result1)) {
                    ?>
                       <tr>
                         <td><?php echo $sr; ?></td>
                       
                         <td><?php echo $usr1['technology']; ?></td>
                         <td><?php echo $usr1['start_date']; ?></td>
                         <td><?php echo $usr1['days']; ?></td>
                         <td><?php echo $usr1['timing']; ?></td>
                         
                       
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