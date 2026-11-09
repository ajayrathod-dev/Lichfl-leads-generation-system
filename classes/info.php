<?php
session_start();
include("connection.php");


// Sanitize cookie input
$u_uname = isset($_COOKIE['uuid']) ? sanitize_input($_COOKIE['uuid']) : '';

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
    header("Location: logout.php");
    exit;
}

// Sanitize GET input
$mobile = isset($_GET['mobile']) ? sanitize_input($_GET['mobile']) : '';
$cat_id = isset($_GET['cat_id']) ? sanitize_input($_GET['cat_id']) : '';

include('header.php');
?>

<style>
    .blink_me {
        animation: blinker 1s linear infinite;
        color: white;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }

    .rainbow {
        text-align: center;
        text-decoration: none;
        font-size: 14px;
        font-family: monospace;
        letter-spacing: 0px;
    }
    .rainbow_text_animated {
        background: linear-gradient(to right, #6666ff, #0099ff , #00ff00, #ff3399, #6666ff);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        animation: rainbow_animation 6s ease-in-out infinite;
        background-size: 400% 100%;
    }

    @keyframes rainbow_animation {
        0%, 100% {
            background-position: 0 0;
        }
        50% {
            background-position: 100% 0;
        }
    }
    table {
    width: 100%; /* Optional: Make table fit within its container */
    table-layout: fixed; /* Ensure cells use the specified width */
}

td {
    word-wrap: break-word; /* Wrap long words */
    word-break: break-all; /* Break long words if necessary */
    white-space: normal; /* Allow text to wrap to the next line */
    overflow: hidden; /* Hide overflow content */
}
</style>

<div class="page-wrapper">
			<div class="page-content">
				<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                   <div class="col">
					 <a href="info.php?cat_id=1"><div class="card radius-10 bg-danger bg-gradient">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-white">Pending</p>
									<h4 class="my-1 text-white"><?php
            $sql1 = "SELECT * FROM complaint WHERE category=1 AND assign_to=?";
            $stmt1 = $link->prepare($sql1);
            $stmt1->bind_param("s", $u_uname);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            echo $result1->num_rows;
            ?>
									</h4>
									
								</div>
								<div class="widgets-icons-2 bg-white text-danger ms-auto"><i class='bx bxs-wallet'></i>
								</div>
							</div>
						</div>
					 </div></a>
				   </div>
				   <div class="col">
						  <a href="info.php?cat_id=2"><div class="card radius-10 bg-warning bg-gradient">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-white">Inprogress</p>
								   <h4 class="my-1 text-white">
								    <?php
            $sql2 = "SELECT * FROM complaint WHERE category=2 AND assign_to=?";
            $stmt2 = $link->prepare($sql2);
            $stmt2->bind_param("s", $u_uname);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            echo $result2->num_rows;
            ?>
								   </h4>
								 
							   </div>
							 	<div class="widgets-icons-2 bg-white text-warning ms-auto"><i class='bx bxs-wallet'></i>
							   </div>
						   </div>
					   </div>
					</div></a>
				  </div>
				  <div class="col">
					<a href="info.php?cat_id=3"> <div class="card radius-10 bg-success bg-gradient">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-white">Completed</p>
								   <h4 class="my-1 text-white">
								   <?php
            $sql3 = "SELECT * FROM complaint WHERE category=3 AND assign_to=?";
            $stmt3 = $link->prepare($sql3);
            $stmt3->bind_param("s", $u_uname);
            $stmt3->execute();
            $result3 = $stmt3->get_result();
            echo $result3->num_rows;
            ?>
								   </h4>
								  
							   </div>
							  <div class="widgets-icons-2 bg-white text-success ms-auto"><i class='bx bxs-wallet' ></i>
							   </div>
						   </div>
					   </div>
					</div></a>
				  </div>
				    <div class="col">
					 <a href="info.php?cat_id=1"><div class="card radius-10 bg-primary bg-gradient">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-white">Incentive </p>
									<h4 class="my-1 text-white"><?php
$sqlSum = "SELECT SUM(amount) AS total_amount FROM log WHERE reg_mobile_no = ?";
$stmtSum = $link->prepare($sqlSum); 
$stmtSum->bind_param("s", $u_uname);
$stmtSum->execute();
$resultSum = $stmtSum->get_result();

if ($rowSum = $resultSum->fetch_assoc()) {
    echo "RS ".$rowSum['total_amount']."/-";
} else {
    echo "0";
}
            ?>
									</h4>
									
								</div>
								<div class="widgets-icons-2 bg-white text-primary ms-auto"><i class='bx bxs-wallet'></i>
								</div>
							</div>
						</div>
					 </div></a>
				   </div>
				</div><!--end row-->
			
			
<div class="card">
<div class="card-body">	
<div class="table-responsive">
	<table id="customers" class="table mb-0">
  <tr>
    <th>Description</th>
  </tr>
  <?php
  $sql = "SELECT * FROM complaint WHERE assign_to=? AND category LIKE CONCAT('%', ?, '%') ORDER BY id DESC";
  $stmt = $link->prepare($sql);
  $stmt->bind_param("ss", $u_uname,$cat_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $mobile_no = sanitize_input($row['mobile_no']);
          $registred_by = sanitize_input($row['registred_by']);
          $name = sanitize_input($row['name']);
          $village = sanitize_input($row['village']);
          $taluka = sanitize_input($row['taluka']);
          $district = sanitize_input($row['destrict']);
          $complaint_title = sanitize_input($row['complaint_title']);
          $complaint_id = sanitize_input($row['complaint_id']);
          $date = sanitize_input($row['modified_datetime']);
          $category = sanitize_input($row['category']);
  ?>
  <tr style="background-color:<?php echo ($category == 1) ? "#f55f5f" : (($category == 2) ? "yellow" : "#7de57d"); ?>;">
    <td>
      <?php echo "$name ($mobile_no)"; ?> <br>
      <?php echo "$village, $taluka, $district"; ?> <br>
       <b><?php echo $complaint_title; ?></b> <br>
      <?php 
      $timestamp = strtotime($date);
      echo $formattedTime = date('d-m-Y g:i a', $timestamp);
       ?>
       <br>
      <a href="api.php?mobile=<?php echo $mobile_no; ?>" style="display: inline-block; padding: 10px 20px; width: 50%; font-size: 16px; color: white; background-color: #007bff; text-align: center; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; transition: background-color 0.3s ease; margin-bottom:10px;">View</a><br>
      <?php if ($category == 1 || $category == 2) { ?>
      <form action="change_status.php" method="get">
        <input type="hidden" name="comp_id" value="<?php echo $complaint_id; ?>">
        <select name="status" class="form-control" required>
         
          <option value="1">Pending</option>
          <option value="2">Inprogress</option>
          <option value="3">Complete</option>
        </select>
         <input type="hidden" name="mob" value="<?php echo $mobile_no; ?>">
         <input type="text" name="abhi" class="form-control mb-2 mt-2" required placeholder="अभिप्राय नोंदवा">
        <button type="submit" style="display: inline-block; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; border: none; border-radius: 5px; cursor: pointer; text-align: center; text-decoration: none; transition: background-color 0.3s ease, box-shadow 0.3s ease;">Submit</button>
      </form>
      <?php } ?>
       
    </td>
  </tr>
  <?php 
      }
  }
  ?>
</table>
		</div>
		</div>
		</div>
		</div>
		</div>

	<footer class="page-footer">
		 <a href="complaint.php?mobile=<?php echo $mobile; ?>" style="text-decoration:none;color:white;width: 100%; !important" class="btn btn-primary">Register New Enquiry</a>
		</footer>
	</div>
	<!--end wrapper-->
	<!--start switcher-->
	<div class="switcher-wrapper">
		<div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
		</div>
		<div class="switcher-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-uppercase">Theme Customizer</h5>
				<button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
			</div>
			<hr/>
			<h6 class="mb-0">Theme Styles</h6>
			<hr/>
			<div class="d-flex align-items-center justify-content-between">

				<script type="text/javascript">
				window.onload = function() {
					<?php
					$time = date("H");
					$timezone = date("e");
					if ($time < "7" || $time > "19") {
					?>
						document.getElementById("darkmode").click();
					<?php
					} else {
					?>
						document.getElementById("lightmode").click();
					<?php
					}
					?>
					<?php
					if ($_GET['success'] == 1 && $_GET['success'] != '') {
					?>
						Lobibox.notify('success', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-check-circle',
							msg: 'User Login Successfully.'
						});
					<?php
					}
					?>

					<?php

					if ($_GET['success'] == 11 && $_GET['success'] != '') {
					?>
						Lobibox.notify('success', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-check-circle',
							msg: 'Notice Uploaded Successfully.'
						});
					<?php
					}
					?>


					<?php

					if ($_GET['error'] == 11 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'Notice Not Uploaded.'
						});
					<?php
					}
					?>

	<?php

					if ($_GET['success'] == 1717 && $_GET['success'] != '') {
					?>
						Lobibox.notify('success', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-check-circle',
							msg: 'Status Changed Successfully.'
						});
					<?php
					}
					?>
					
					
					
						<?php

					if ($_GET['error'] == 17170 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'UTR Already Exists.'
						});
					<?php
					}
					?>
					
						<?php

					if ($_GET['error'] == 42 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'Batch Not Scheduled.'
						});
					<?php
					}
					?>


	<?php

					if ($_GET['success'] == 42 && $_GET['success'] != '') {
					?>
						Lobibox.notify('success', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-check-circle',
							msg: 'Batch Scheduled Successfully.'
						});
					<?php
					}
					?>

					
					
					
					


					<?php

					if ($_GET['error'] == 3 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'Status Not Changed.'
						});
					<?php
					}
					?>
					<?php

					if ($_GET['error'] == 66 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'Bill Already Paid.'
						});
					<?php
					}
					?>




						<?php

					if ($_GET['success'] == 333 && $_GET['success'] != '') {
					?>
						Lobibox.notify('success', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-check-circle',
							msg: 'Record Deleted Successfully.'
						});
					<?php
					}
					?>





						<?php

					if ($_GET['error'] == 33 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'Password Mismatched.'
						});
					<?php
					}
					?>

					<?php

					if ($_GET['success'] == 300 && $_GET['success'] != '') {
					?>
						Lobibox.notify('success', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-check-circle',
							msg: 'Teaching Staff Added Successfully.'
						});
					<?php
					}
					?>

					<?php

					if ($_GET['error'] == 300 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'Staff Not Added.'
						});
					<?php
					}
					?>



						<?php

					if ($_GET['success'] == 200 && $_GET['success'] != '') {
					?>
						Lobibox.notify('success', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-check-circle',
							msg: 'Photo Added Successfully.'
						});
					<?php
					}
					?>

					<?php

					if ($_GET['error'] == 200 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'Photo Not Added.'
						});
					<?php
					}
					?>



						<?php

					if ($_GET['success'] == 21 && $_GET['success'] != '') {
					?>
						Lobibox.notify('success', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-check-circle',
							msg: 'Product Details Added Successfully.'
						});
					<?php
					}
					?>

					<?php

					if ($_GET['error'] == 21 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'Product Not Added.'
						});
					<?php
					}
					?>



						<?php

					if ($_GET['success'] == 210 && $_GET['success'] != '') {
					?>
						Lobibox.notify('success', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-check-circle',
							msg: 'Category Added Successfully.'
						});
					<?php
					}
					?>

					<?php

					if ($_GET['error'] == 210 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'Category Not Added.'
						});
					<?php
					}
					?>



						<?php

					if ($_GET['success'] == 41 && $_GET['success'] != '') {
					?>
						Lobibox.notify('success', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-check-circle',
							msg: 'Free Demo Session Added Successfully.'
						});
					<?php
					}
					?>

					<?php

					if ($_GET['error'] == 41 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'Demo Session Not Added.'
						});
					<?php
					}
					?>


					<?php

					if ($_GET['success'] == 6 && $_GET['success'] != '') {
					?>
						Lobibox.notify('success', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-check-circle',
							msg: 'Bill Paid Successfully.'
						});
					<?php
					}
					?>

					<?php

					if ($_GET['error'] == 6 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'Bill Not Paid.'
						});
					<?php
					}
					?>





				};


				</script>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode" checked>
					<label class="form-check-label" for="lightmode">Light</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
					<label class="form-check-label" for="darkmode">Dark</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark">
					<label class="form-check-label" for="semidark">Semi Dark</label>
				</div>
			</div>
			<hr/>
			<div class="form-check">
				<input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
				<label class="form-check-label" for="minimaltheme">Minimal Theme</label>
			</div>
			<hr/>
			<h6 class="mb-0">Header Colors</h6>
			<hr/>
			<div class="header-colors-indigators">
				<div class="row row-cols-auto g-3">
					<div class="col">
						<div class="indigator headercolor1" id="headercolor1"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor2" id="headercolor2"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor3" id="headercolor3"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor4" id="headercolor4"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor5" id="headercolor5"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor6" id="headercolor6"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor7" id="headercolor7"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor8" id="headercolor8"></div>
					</div>
				</div>
			</div>
			<hr/>
			<h6 class="mb-0">Sidebar Colors</h6>
			<hr/>
			<div class="header-colors-indigators">
				<div class="row row-cols-auto g-3">
					<div class="col">
						<div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="assets/plugins/chartjs/js/Chart.min.js"></script>
	<script src="assets/plugins/chartjs/js/Chart.extension.js"></script>
	<script src="assets/js/index.js"></script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>

	<script src="assets/plugins/notifications/js/lobibox.min.js"></script>
<script src="assets/plugins/notifications/js/notifications.min.js"></script>
<script src="assets/plugins/notifications/js/notification-custom-script.js"></script>
<!--app JS-->
<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<!--app JS-->

<script>
	$(document).ready(function() {
		var table = $('#example2').DataTable({
			lengthChange: false,
			buttons: ['copy', 'excel', 'pdf', 'print']
		});

		table.buttons().container()
			.appendTo('#example2_wrapper .col-md-6:eq(0)');
	});
</script> 
</body>

</html>
