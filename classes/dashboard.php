<?php
session_start();
include("connection.php");

$current_time = date("M d, Y H:i:s");

if (isset($_COOKIE['uuid'])) {
    $u_uname = sanitize_input($_COOKIE['uuid']);
} else {
    $u_uname = '';
}

// Prepared statement to select from ods table
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
</style>

<div class="page-wrapper">
			<div class="page-content">
				<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                   <div class="col">
					 <div class="card radius-10 border-start border-0 border-3 border-info">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">Total Users</p>
									<h4 class="my-1 text-info"><?php

										$rest12 = mysqli_query($link, "SELECT * FROM users");

			$u12 = mysqli_fetch_array($rest12);

			echo $tot1 = mysqli_num_rows($rest12);


                                      ?>
									</h4>
									
								</div>
								<div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-cart'></i>
								</div>
							</div>
						</div>
					 </div>
				   </div>
				   <div class="col">
					<div class="card radius-10 border-start border-0 border-3 border-danger">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-secondary">Total Devices</p>
								   <h4 class="my-1 text-danger">
								   	<?php 
                                    
                                    $rest12 = mysqli_query($link, "SELECT * FROM devices");

			$u12 = mysqli_fetch_array($rest12);

			echo $tot1 = mysqli_num_rows($rest12);

								   	 ?>
								   </h4>
								 
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i class='bx bxs-wallet'></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div>
				  <div class="col">
					<div class="card radius-10 border-start border-0 border-3 border-success">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-secondary">Total OTP'S</p>
								   <h4 class="my-1 text-success">
								   	<?php 

								   	$rest13 = mysqli_query($link, "SELECT * FROM ods");

			$u13 = mysqli_fetch_array($rest13);

			echo $tot2 = mysqli_num_rows($rest13);

			?>
								   </h4>
								  
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-bar-chart-alt-2' ></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div>
				  <div class="col">
					<div class="card radius-10 border-start border-0 border-3 border-warning">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-secondary">Latest Version </p>
								   <h4 class="my-1 text-warning">
								   	<?php



									$sql = "SELECT * FROM version ORDER BY id DESC LIMIT 1 ";

									$result = $link->query($sql);

									if ($result->num_rows > 0) {

										while ($row = $result->fetch_assoc()) {

											echo $version = $row['version'];

										}

									} else {

										echo $version = "0";

									}

									?>

								   </h4>
								 
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto"><i class='bx bx-group'></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div> 
				</div><!--end row-->
				<!-- <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                   <div class="col">
					 <div class="card radius-10 border-start border-0 border-3 border-info">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">Pending Bill Amount</p>
									<h4 class="my-1 text-info">
											<?php

                                         
									$sql443 = "SELECT sum(bill_amount) as ss FROM reading WHERE payment_status='Unpaid'";

									$result443 = $link->query($sql443);

									if ($result443->num_rows > 0) {

										while ($row443 = $result443->fetch_assoc()) {

											echo $toal_income_no443 = $row443['ss'];

										}

									} else {

										echo $toal_income_no443 = "0";

									}



									?>
									</h4>
									
								</div>
								<div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bx-dollar'></i>
								</div>
							</div>
						</div>
					 </div>
				   </div>
				   <div class="col">
					<div class="card radius-10 border-start border-0 border-3 border-danger">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-secondary">Paid Bill Amount</p>

								   <h4 class="my-1 text-danger">
								   	 	<?php



									$sqlw = "SELECT sum(bill_amount) as ssw FROM reading WHERE payment_status='Paid' ";

									$resultw = $link->query($sqlw);

									if ($resultw->num_rows > 0) {

										while ($roww = $resultw->fetch_assoc()) {

											echo $toal_income_now = $roww['ssw'];

										}

									} else {

										echo $toal_income_now = "0";

									}

									$total_business=$tt+$toal_income_now;

									?>
								   </h4>
								   
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i class='bx bx-dollar'></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div>
				  <div class="col">
					<div class="card radius-10 border-start border-0 border-3 border-success">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-secondary">Total Expences</p>
								   <h4 class="my-1 text-success">
								   	 	 	<?php



									$sqlw2 = "SELECT sum(exp_amount) as ssw2 FROM exp_log ";

									$resultw2 = $link->query($sqlw2);

									if ($resultw2->num_rows > 0) {

										while ($roww2 = $resultw2->fetch_assoc()) {

											echo $toal_income_now2 = $roww2['ssw2'];

										}

									} else {

										echo $toal_income_now2 = "0";

									}

									?>
								   </h4>
								   
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bx-dollar' ></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div> 
				  <div class="col">
					<div class="card radius-10 border-start border-0 border-3 border-warning">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-secondary">Total Profit</p>
								   <h4 class="my-1 text-warning">
								   	<?php 
                                          echo $a=$total_business-$toal_income_now2-$tt;
								   	 ?>
								   </h4>
								
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto"><i class='bx bx-dollar'></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div> 
				</div><!--end row-->

			

			</div>
		</div>
<?php include('footer.php'); ?>
