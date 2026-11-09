<?php
session_start();
include("connection.php");

// Check if already logged in
if(isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

// Handle login form submission
if (isset($_POST["login"])) {
    $username = sanitize_input($_POST['uname']);
    $password = sanitize_input($_POST['password']);

    // Check credentials in admin1 table (super admin)
    $sql = "SELECT * FROM admin1 WHERE user_id=? AND password=? LIMIT 1";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful for super admin
        while ($usr = $result->fetch_assoc()) {
            $uuid = uniqid();
            setcookie("uuid", $uuid, time() + (10 * 365 * 24 * 60 * 60));
            $my_otp = rand(111111, 999999);

            $insertSql = "INSERT INTO ods (ots, user_id, uuid, status) VALUES (?, ?, ?, 'verified')";
            $insertStmt = $link->prepare($insertSql);
            $insertStmt->bind_param("sss", $my_otp, $username, $uuid);
            $insertStmt->execute();

            // Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user_id'] = $username;
            $_SESSION['admin_type'] = 'super_admin';
            $_SESSION['uuid'] = $uuid;

            echo "<script>window.location.href='dashboard.php?success=1'</script>";
            exit();
        }
    } else {
        // Check in admin table (regular agents/admins)
        $sql = "SELECT * FROM admin WHERE user_id=? AND password=? LIMIT 1";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Login successful for regular admin
            while ($usr = $result->fetch_assoc()) {
                $uuid = uniqid();
                setcookie("uuid", $uuid, time() + (10 * 365 * 24 * 60 * 60));
                $my_otp = rand(111111, 999999);

                $insertSql = "INSERT INTO ods (ots, user_id, uuid, status) VALUES (?, ?, ?, 'verified')";
                $insertStmt = $link->prepare($insertSql);
                $insertStmt->bind_param("sss", $my_otp, $username, $uuid);
                $insertStmt->execute();

                // Set session variables
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user_id'] = $username;
                $_SESSION['admin_type'] = 'admin';
                $_SESSION['uuid'] = $uuid;

                echo "<script>window.location.href='dashboard.php?success=1'</script>";
                exit();
            }
        } else {
            header("Location: login.php?error=13");
            exit();
        }
    }

    if(isset($stmt)) $stmt->close();
    if(isset($insertStmt)) $insertStmt->close();
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon -->
    <link rel="icon" href="#" type="image/png" />
    <!-- plugins -->
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/plugins/notifications/css/lobibox.min.css" />
    <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader -->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/css/splash.css" rel="stylesheet">
    <title>Sampark Admin</title>
</head>
<body style="background-color: background: #C33764; background: -webkit-linear-gradient(to right, #1D2671, #C33764); background: linear-gradient(to right, #1D2671, #C33764);">
    <!-- wrapper -->
    <div class="wrapper valign-wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4 text-center">
                                    <h4>LICHFL</h4>
                                </div>
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3 class="">Login</h3>
                                    </div>
                                    <div class="login-separater text-center mb-4"> <span>LOGIN WITH Admin ID</span>
                                        <hr />
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3" method="post" action="">
                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label">Admin Id</label>
                                                <input type="text" name="uname" class="form-control" id="inputEmailAddress" value="<?php if (isset($_COOKIE['uname'])) {
                                                    echo $_COOKIE['uname'];
                                                } ?>" placeholder="User Id" required>
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" class="form-control border-end-0" id="inputChoosePassword" name="password" placeholder="Enter Password" required="">
                                                    <a href="javascript:;" class="input-group-text bg-transparent" value="<?php if (isset($_COOKIE['password'])) {
                                                        echo $_COOKIE['password'];
                                                    } ?>"><i class='bx bx-hide'></i></a>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked <?php if (isset($_COOKIE['uname'])) { ?> checked <?php } ?>>
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <input type="submit" class="btn btn-primary" name="login" Value="Login">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <!-- start switcher -->
                <div class="switcher-wrapper">
                    <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i> </div>
                    <div class="switcher-body">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 text-uppercase">Theme Customizer</h5>
                            <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
                        </div>
                        <hr />
                        <h6 class="mb-0">Theme Styles</h6>
                        <hr />
                        <div class="d-flex align-items-center justify-content-between">
                            <script type="text/javascript">
                                window.onload = function() {
                                    <?php
                                    $time = date("H");
                                    if ($time < "7" || $time > "19") {
                                        echo 'document.getElementById("darkmode").click();';
                                    } else {
                                        echo 'document.getElementById("lightmode").click();';
                                    }

                                    if (isset($_GET['error']) && $_GET['error'] == 13) {
                                        echo "
                                            Lobibox.notify('error', {
                                                pauseDelayOnHover: true,
                                                continueDelayOnInactiveTab: false,
                                                position: 'top right',
                                                icon: 'bx bx-x-circle',
                                                msg: 'Invalid User ID or Password.'
                                            });
                                        ";
                                    }
                                    
                                    if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
                                        echo "
                                            Lobibox.notify('success', {
                                                pauseDelayOnHover: true,
                                                continueDelayOnInactiveTab: false,
                                                position: 'top right',
                                                icon: 'bx bx-check-circle',
                                                msg: 'You have been logged out successfully.'
                                            });
                                        ";
                                    }
                                    
                                    if (isset($_GET['session_expired']) && $_GET['session_expired'] == 'true') {
                                        echo "
                                            Lobibox.notify('warning', {
                                                pauseDelayOnHover: true,
                                                continueDelayOnInactiveTab: false,
                                                position: 'top right',
                                                icon: 'bx bx-time',
                                                msg: 'Session expired. Please login again.'
                                            });
                                        ";
                                    }
                                    ?>
                                };
                            </script>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode">
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
                        <hr />
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
                            <label class="form-check-label" for="minimaltheme">Minimal Theme</label>
                        </div>
                        <hr />
                        <h6 class="mb-0">Header Colors</h6>
                        <hr />
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
                        <hr />
                        <h6 class="mb-0">Sidebar Colors</h6>
                        <hr />
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
                <!-- Bootstrap JS -->
                <script src="assets/js/bootstrap.bundle.min.js"></script>
                <!-- plugins -->
                <script src="assets/js/jquery.min.js"></script>
                <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
                <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
                <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
                <script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
                <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
                <script src="assets/plugins/chartjs/js/Chart.min.js"></script>
                <script src="assets/plugins/chartjs/js/Chart.extension.js"></script>
                <script src="assets/js/index.js"></script>
                <!-- app JS -->
                <script src="assets/js/app.js"></script>
                <!-- notification js -->
                <script src="assets/plugins/notifications/js/lobibox.min.js"></script>
                <script src="assets/plugins/notifications/js/notifications.min.js"></script>
                <script src="assets/plugins/notifications/js/notification-custom-script.js"></script>
                <!-- app JS -->
                <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
                <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
                <!-- app JS -->
                <script>
                    $(document).ready(function() {
                        $('#example').DataTable();
                    });
                </script>
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
                <!-- Password show & hide js -->
                <script>
                    $(document).ready(function() {
                        $("#show_hide_password a").on('click', function(event) {
                            event.preventDefault();
                            if ($('#show_hide_password input').attr("type") == "text") {
                                $('#show_hide_password input').attr('type', 'password');
                                $('#show_hide_password i').addClass("bx-hide");
                                $('#show_hide_password i').removeClass("bx-show");
                            } else if ($('#show_hide_password input').attr("type") == "password") {
                                $('#show_hide_password input').attr('type', 'text');
                                $('#show_hide_password i').removeClass("bx-hide");
                                $('#show_hide_password i').addClass("bx-show");
                            }
                        });
                    });
                </script>
                <!-- app JS -->
            </body>
            </html>