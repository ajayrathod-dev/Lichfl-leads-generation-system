<?php
session_start();
include("connection.php");

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
$stmt4->close();

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
function myfunction1(str1, str2) {
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("prog_show").innerHTML = xmlhttp.responseText;
            location.reload();
        }
    }
    xmlhttp.open("GET", "trans_status.php?usd=" + str1 + "&id=" + str2, true);
    xmlhttp.send();
}
</script>

<div class="page-wrapper">
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-center">
                    <div><i class="bx bxs-user me-1 font-22 text-info"></i></div>
                    <h5 class="mb-0 text-info">Chat History</h5>
                </div>
                <hr/>
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Sent By</th>
                                <th>Received By</th>
                                <th>Message</th>
                                <th>Category</th>
                                <th>Complaint Id</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sr = 1;
                            // Prepared statement to select from chat table
                            $sql1 = "SELECT * FROM chat ORDER BY id DESC";
                            $stmt1 = $link->prepare($sql1);
                            $stmt1->execute();
                            $result1 = $stmt1->get_result();
                            if ($result1->num_rows > 0) {
                                while ($usr1 = $result1->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $sr; ?></td>
                                        <td><?php  $sent=$usr1['sent_by']; 
                                            
                                            $sql20 = "SELECT * FROM admin where user_id='$sent'";
                                            $result20 = $link->query($sql20);
                                            if ($result20->num_rows > 0) {
                                                 while($row20 = $result20->fetch_assoc())
                                                 {
                                                     echo $sent." (".$row20["name"].")";
                                                 }
                                                }
                                              
                                        ?></td>
                                        <td><?php  $recieved=$usr1['recieved_by'];
                                            
                                            $sql200 = "SELECT * FROM user where mobile_no='$recieved'";
                                            $result200 = $link->query($sql200);
                                            if ($result200->num_rows > 0) {
                                                 while($row200 = $result200->fetch_assoc())
                                                 {
                                                     echo $ass." (".$row200["name"].")";
                                                 }
                                                }
                                        
                                        ?></td>
                                        <td><?php echo sanitize_input($usr1['msg']); ?></td>
                                        <td><?php echo sanitize_input($usr1['category']); ?></td>
                                        <td><?php echo sanitize_input($usr1['complaint_id']); ?></td>
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

<?php
include('footer.php');
?>