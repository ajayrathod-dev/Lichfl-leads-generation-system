

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

					if ($_GET['error'] == 1002 && $_GET['error'] != '') {
					?>
						Lobibox.notify('error', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-x-circle',
							msg: 'Complaint Not Registered.'
						});
					<?php
					}
					?>


	<?php

					if ($_GET['success'] == 1002 && $_GET['success'] != '') {
					?>
						Lobibox.notify('success', {
							pauseDelayOnHover: true,
							continueDelayOnInactiveTab: false,
							position: 'top right',
							icon: 'bx bx-check-circle',
							msg: 'Complaint Registered Successfully.'
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