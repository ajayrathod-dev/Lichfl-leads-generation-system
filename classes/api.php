<?php
session_start();
include("connection.php");

$mobile = sanitize_input($_GET['mobile']);

if (isset($_COOKIE['uuid'])) {
    $u_uname = sanitize_input($_COOKIE['uuid']);
} else {
    $u_uname = '';
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

$sql4444 = "SELECT * FROM user WHERE mobile_no=?";
$stmt4444 = $link->prepare($sql4444);
$stmt4444->bind_param("s", $mobile);
$stmt4444->execute();
$result4444 = $stmt4444->get_result();

if ($result4444->num_rows == 0) {
    $sql = "INSERT INTO user (mobile_no) VALUES (?)";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
}

$stmt4444->execute();
$result4444 = $stmt4444->get_result();

if ($result4444->num_rows > 0) {
    while ($row4444 = $result4444->fetch_assoc()) {
        $name = sanitize_input($row4444['name']);
        $mobile_no = sanitize_input($row4444['mobile_no']);
        $desc = sanitize_input($row4444['description']);
    }
}


$sqlqqq = "UPDATE user SET modified_datetime = `date` + INTERVAL 12 HOUR + INTERVAL 30 MINUTE WHERE modified_datetime IS NULL";
$stmtqqq = $link->prepare($sqlqqq);
$stmtqqq->execute();
$sqlaaa = "UPDATE complaint SET modified_datetime = `date` + INTERVAL 12 HOUR + INTERVAL 30 MINUTE WHERE modified_datetime IS NULL";
$stmtaaa = $link->prepare($sqlaaa);
$stmtaaa->execute();
$sqlsss = "UPDATE chat SET modified_datetime = `date` + INTERVAL 12 HOUR + INTERVAL 30 MINUTE WHERE modified_datetime IS NULL";
$stmtsss = $link->prepare($sqlsss);
$stmtsss->execute();

?>
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/plugins/notifications/css/lobibox.min.css" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="assets/css/dark-theme.css" />
	<link rel="stylesheet" href="assets/css/semi-dark.css" />
	<link rel="stylesheet" href="assets/css/header-colors.css" />
	<title>संपर्क</title>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
			
				<div>
					<h4 class="logo-text">संपर्क</h4>
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
				</div>
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
				<li>
					<a href="info.php">
						<div class="parent-icon"><i class='bx bx-home-circle'></i>
						</div>
						<div class="menu-title">Dashboard</div>
					</a>
					
				</li>
				
					<li>
					<a href="complaint.php">
						<div class="parent-icon"><i class='bx bx-user-circle'></i>
						</div>
						<div class="menu-title">New Enquiry</div>
					</a>
					 
				</li>
				<li>
					<a href="report.php">
						<div class="parent-icon"><i class='bx bx-user-circle'></i>
						</div>
						<div class="menu-title">Daily Report</div>
					</a>
					 
				</li>
				<li>
					<a href="logout.php">
						<div class="parent-icon"><i class='bx bx-log-out-circle'></i>
						</div>
						<div class="menu-title">Logout</div>
					</a>
					
				</li>
				
		
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center"> 
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<?php echo $name ?>(<a href="tel:<?php echo $mobile;?>"><?php echo $mobile;?></a>) 
							<a href="edit.php?mob=<?php echo $mobile; ?>" style="text-decoration: none;">🖊️</a><br>
							<div class="title" style="font-size:10px;"><?php echo $desc; ?></div>
					<div class="top-menu ms-auto">
					    
						<ul class="navbar-nav align-items-center">
							<li class="nav-item mobile-search-icon">
						
							</li>
							<li class="nav-item dropdown dropdown-large">
							<!-- 	<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">	<i class='bx bx-category'></i>
								</a> -->
								<div class="dropdown-menu dropdown-menu-end">
									<div class="row row-cols-3 g-3 p-3">
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-cosmic text-white"><i class='bx bx-group'></i>
											</div>
											<div class="app-title">Teams</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-burning text-white"><i class='bx bx-atom'></i>
											</div>
											<div class="app-title">Projects</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-lush text-white"><i class='bx bx-shield'></i>
											</div>
											<div class="app-title">Tasks</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-kyoto text-dark"><i class='bx bx-notification'></i>
											</div>
											<div class="app-title">Feeds</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-blues text-dark"><i class='bx bx-file'></i>
											</div>
											<div class="app-title">Files</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-moonlit text-white"><i class='bx bx-filter-alt'></i>
											</div>
											<div class="app-title">Alerts</div>
										</div>
									</div>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<!-- <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">7</span>
									<i class='bx bx-bell'></i>
								</a> -->
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Notifications</p>
											<p class="msg-header-clear ms-auto">Marks all as read</p>
										</div>
									</a>
									<div class="header-notifications-list">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-primary text-primary"><i class="bx bx-group"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
												ago</span></h6>
													<p class="msg-info">5 new user registered</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-danger text-danger"><i class="bx bx-cart-alt"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Orders <span class="msg-time float-end">2 min
												ago</span></h6>
													<p class="msg-info">You have recived new orders</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-success text-success"><i class="bx bx-file"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">24 PDF File<span class="msg-time float-end">19 min
												ago</span></h6>
													<p class="msg-info">The pdf files generated</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-warning text-warning"><i class="bx bx-send"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Time Response <span class="msg-time float-end">28 min
												ago</span></h6>
													<p class="msg-info">5.1 min avarage time response</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-info text-info"><i class="bx bx-home-circle"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Product Approved <span
												class="msg-time float-end">2 hrs ago</span></h6>
													<p class="msg-info">Your new product has approved</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-danger text-danger"><i class="bx bx-message-detail"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Comments <span class="msg-time float-end">4 hrs
												ago</span></h6>
													<p class="msg-info">New customer comments recived</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-success text-success"><i class='bx bx-check-square'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs
												ago</span></h6>
													<p class="msg-info">Successfully shipped your item</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-primary text-primary"><i class='bx bx-user-pin'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day
												ago</span></h6>
													<p class="msg-info">24 new authors joined last week</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-warning text-warning"><i class='bx bx-door-open'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Defense Alerts <span class="msg-time float-end">2 weeks
												ago</span></h6>
													<p class="msg-info">45% less alerts last 4 weeks</p>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Notifications</div>
									</a>
								</div>
							</li>
						<li class="nav-item dropdown dropdown-large">
								<!-- <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span>
									<i class='bx bx-comment'></i>
								</a> -->
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Messages</p>
											<p class="msg-header-clear ms-auto">Marks all as read</p>
										</div>
									</a>
									<div class="header-message-list">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Daisy Anderson <span class="msg-time float-end">5 sec
												ago</span></h6>
													<p class="msg-info">The standard chunk of lorem</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-2.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Althea Cabardo <span class="msg-time float-end">14
												sec ago</span></h6>
													<p class="msg-info">Many desktop publishing packages</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-3.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Oscar Garner <span class="msg-time float-end">8 min
												ago</span></h6>
													<p class="msg-info">Various versions have evolved over</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-4.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Katherine Pechon <span class="msg-time float-end">15
												min ago</span></h6>
													<p class="msg-info">Making this the first true generator</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-5.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Amelia Doe <span class="msg-time float-end">22 min
												ago</span></h6>
													<p class="msg-info">Duis aute irure dolor in reprehenderit</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-6.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Cristina Jhons <span class="msg-time float-end">2 hrs
												ago</span></h6>
													<p class="msg-info">The passage is attributed to an unknown</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-7.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">James Caviness <span class="msg-time float-end">4 hrs
												ago</span></h6>
													<p class="msg-info">The point of using Lorem</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-8.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Peter Costanzo <span class="msg-time float-end">6 hrs
												ago</span></h6>
													<p class="msg-info">It was popularised in the 1960s</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-9.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">David Buckley <span class="msg-time float-end">2 hrs
												ago</span></h6>
													<p class="msg-info">Various versions have evolved over</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-10.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Thomas Wheeler <span class="msg-time float-end">2 days
												ago</span></h6>
													<p class="msg-info">If you are going to use a passage</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-11.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Johnny Seitz <span class="msg-time float-end">5 days
												ago</span></h6>
													<p class="msg-info">All the Lorem Ipsum generators</p>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Messages</div>
									</a>
								</div>
							</li>
						</ul>
					</div>
					
				</nav>
			</div>
		</header>


<div class="page-wrapper">
			<div class="page-content">
			<div class="row">
                  
                       <?php 
            $sql44 = "SELECT * FROM chat WHERE recieved_by LIKE ? ORDER BY id DESC";
            $stmt44 = $link->prepare($sql44);
            $searchPattern = '%' . $mobile_no . '%';
            $stmt44->bind_param("s", $searchPattern);
            $stmt44->execute();
            $result44 = $stmt44->get_result();

            if ($result44->num_rows > 0) {
                while ($row44 = $result44->fetch_assoc()) {
                    $cat = sanitize_input($row44['category']);
                    $sent_by = sanitize_input($row44['sent_by']);
            ?>       <div class="col-12 col-lg-8 d-flex">
                      <div class="card radius-10 w-100 <?php if($cat==1){ echo "bg-danger text-white";}else if($cat==2){ echo "bg-warning text-white";} else if($cat==3){ echo "bg-success text-white";}else{ echo "bg-light";}?>" >
					   <div class="card-body">
					        <?php  
                $sql444 = "SELECT * FROM admin WHERE user_id=?";
                $stmt444 = $link->prepare($sql444);
                $stmt444->bind_param("s", $sent_by);
                $stmt444->execute();
                $result444 = $stmt444->get_result();

                if ($result444->num_rows > 0) {
                    while ($row444 = $result444->fetch_assoc()) {
                ?>
							 <div style="font-size:12px;"><?php echo sanitize_input($row444['name']) ?> ( <?php echo $sent_by;?> )</div>
               <?php 
                    }
                }
                ?>
                <div class="text"><b><?php echo sanitize_input($row44['msg']); ?></b></div><br>
                <div class="" style="font-size:10px;">
                    <?php 
                    $datetimeFromDB = sanitize_input($row44['modified_datetime']);
                    $timestamp = strtotime($datetimeFromDB);
                    echo $formattedTime = date('d-m-Y g:i a', $timestamp);
                    ?>
                </div>
                 </div>
				</div>		 
				  </div>
						  
					  
					  <?php
                }
            }
            ?>
				   </div>
				  
				</div>
			
			</div>
		</div>

	<footer class="page-footer">
	       <form action="send.php" method="post">
	   
	        
	            <input type="hidden" name="send_by" value="<?php echo $u_uname; ?>">
                <input type="hidden" name="recieved_by" value="<?php echo $mobile_no; ?>">
		 
		 
		 <div class="input-group mb-3">
		  <input type="text" class="form-control" name="msg" aria-describedby="button-addon2" required>
		  <button type="submit" class="btn btn-outline-primary" type="button" id="button-addon2">Send</button>
		 </div>
		 
	        
	      
	  
                
	    </form>	 
	    <div style="margin-bottom:10px !important; ">		 <a href="fees_log.php?mobile=<?php echo $mobile; ?>" style="text-decoration:none;color:white;width: 100%; !important " class="btn btn-primary">Collect Fees</a>
</div>

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
