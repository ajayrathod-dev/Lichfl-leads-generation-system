<?php
session_start();
include("connection.php");


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
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
input[type=text],input[type=date], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

div {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
</style>
<style>
.button {
  background-color: #04AA6D; /* Green */
  border: none;
  color: white;
  padding: 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  border-radius: 4px;
}

</style>


<div>
  <form action="" method="GET>
    <label for="fname">Complete Report Date</label>
    <input type="date" id="fname" name="birth" placeholder="date" required>
    <input type="submit" value="Find">
  </form>
  
  <!--<table border="1" style="display: none;">-->
    
    <table border="1" style="display: none;">
    <?php
    $all_records_msg = ""; // Initialize a string to hold all records
    if (isset($_GET['birth'])) {
        $today = $_GET['birth'];
        $sql4 = "SELECT * FROM admin where user_id='$u_uname'"; 
        $result4 = mysqli_query($link, $sql4);
        if ($result4->num_rows > 0) {
            while ($row4 = $result4->fetch_assoc()) {
                $user_id = $row4['user_id'];
                $name = $row4['name'];
                $sql45 = "SELECT * FROM chat WHERE sent_by='$user_id' AND date LIKE '%$today%'";
                $result45 = mysqli_query($link, $sql45);
                if ($result45) {
                    $num_rows = mysqli_num_rows($result45);
                    if ($num_rows > 0) { 
                        while ($row45 = $result45->fetch_assoc()) {
                            // Processing each chat record if needed
                        }
                    } 
                }

                $sql44 = "SELECT * FROM complaint WHERE registred_by='$user_id' AND modified_datetime LIKE '%$today%'"; 
                $result44 = mysqli_query($link, $sql44);
                $num_rows1 = mysqli_num_rows($result44); 
                
                $sql444 = "SELECT * FROM complaint WHERE assign_to='$user_id' AND category='3'";
                $result444 = mysqli_query($link, $sql444);
                $num_rows2 = mysqli_num_rows($result444);
                
                $sql4443 = "SELECT * FROM complaint WHERE assign_to='$user_id' AND category !='3'";
                $result4443 = mysqli_query($link, $sql4443);
                $num_rows3 = mysqli_num_rows($result4443);
                
                $sql45 = "SELECT * FROM chat WHERE sent_by='$user_id' AND modified_datetime LIKE '%$today%'";
                $result45 = mysqli_query($link, $sql45);
                $num_rows4 = mysqli_num_rows($result45);
                
                $formatted_date = date("d-m-Y", strtotime($today));
                ?>
                <tr>
                    <td>नाव :</td>
                    <td><?php echo $name; ?></td>
                </tr>
                <tr>
                    <td>तारीख :</td>
                    <td><?php echo $formatted_date; ?></td>
                </tr>
                <tr>
                    <td>आज नोंदवलेले अभिप्राय :</td>
                    <td><?php echo $num_rows4; ?></td>
                </tr>
                <tr>
                    <td>आज नोंदवलेली प्रकरणे :</td>
                    <td><?php echo $num_rows1; ?></td>
                </tr>
                <tr>
                    <td>सोडवलेली एकूण प्रकरणे :</td>
                    <td><?php echo $num_rows2; ?></td>
                </tr>
                <tr>
                    <td>एकूण थकीत प्रकरणे :</td>
                    <td><?php echo $num_rows3; ?></td>
                </tr>
                  
                <tr>
                    <td></td>
                    <td></td> 
                </tr>
                
                <?php
               
               
                }
            }
        }
    
    ?>
</table>
 
<a href="https://api.whatsapp.com/send?phone=&text=<?php
    $all_records_msg = ""; // Initialize a string to hold all records
    if (isset($_GET['birth'])) {
        $today = $_GET['birth'];
        echo "*Daily Report*%0A";
        $formatted_date = date("d-m-Y", strtotime($today));
        echo "*".$formatted_date."*%0A%0A";
                $sql454 = "SELECT * FROM chat WHERE sent_by='7020259943' AND modified_datetime LIKE '%$today%'";
                $result454 = mysqli_query($link, $sql454);
                $num_rows43 = mysqli_num_rows($result454);
                
                $sql4540 = "SELECT * FROM chat WHERE sent_by='9503831621' AND modified_datetime LIKE '%$today%'"; 
                $result4540 = mysqli_query($link, $sql4540);
                $num_rows4300 = mysqli_num_rows($result4540);
                
            
               $sql4544 = "SELECT * FROM chat WHERE sent_by='9518546670' AND modified_datetime LIKE '%$today%'";
                $result4544 = mysqli_query($link, $sql4544);
                $num_rows430 = mysqli_num_rows($result4544);  
                $to=$num_rows43+$num_rows430+$num_rows4300;
                
                $sql45400 = "SELECT * FROM chat WHERE sent_by='8888627777' AND modified_datetime LIKE '%$today%'"; 
                $result45400 = mysqli_query($link, $sql45400); 
                $num_rows43000 = mysqli_num_rows($result45400);
                
                
                $sql454001 = "SELECT * FROM chat WHERE sent_by='9096298962' AND modified_datetime LIKE '%$today%'"; 
                $result454001 = mysqli_query($link, $sql454001); 
                $num_rows430001 = mysqli_num_rows($result454001);
                $bg=$num_rows43000+$num_rows430001;
                
                $sql45442 = "SELECT * FROM complaint WHERE assign_to='9021145612' AND category='3'AND modified_datetime LIKE '%$today%'";
                $result45442 = mysqli_query($link, $sql45442);
                $num_rows4302 = mysqli_num_rows($result45442);  
                
                $sql454421 = "SELECT * FROM complaint WHERE assign_to='9960787115' AND category='3'AND modified_datetime LIKE '%$today%'";
                $result454421 = mysqli_query($link, $sql454421);
                $num_rows43021 = mysqli_num_rows($result454421);  
                
                $sql454421r = "SELECT * FROM complaint WHERE assign_to='9921345812' AND category='3'AND modified_datetime LIKE '%$today%'";
                $result454421r = mysqli_query($link, $sql454421r);
                $num_rows43021r = mysqli_num_rows($result454421r);  
                
                 $sql454421r1 = "SELECT * FROM complaint WHERE assign_to='8356038801' AND category='3'AND modified_datetime LIKE '%$today%'";
                $result454421r1 = mysqli_query($link, $sql454421r1);
                $num_rows43021r1 = mysqli_num_rows($result454421r1);  
                
                $tots=$num_rows43021+$num_rows43021r+$num_rows43021r1;
                
      
        
        $sql4 = "SELECT * FROM admin where user_id='$u_uname'";
        $result4 = mysqli_query($link, $sql4);
        if ($result4->num_rows > 0) {
            while ($row4 = $result4->fetch_assoc()) {
                $user_id = $row4['user_id'];
                $name = $row4['name'];
                $sql45 = "SELECT * FROM chat WHERE sent_by='$user_id' AND modified_datetime LIKE '%$today%'";
                $result45 = mysqli_query($link, $sql45);
                if ($result45) {
                    $num_rows = mysqli_num_rows($result45);
                    if ($num_rows > 0) {
                        while ($row45 = $result45->fetch_assoc()) {
                            // Processing each chat record if needed
                        }
                    }
                }

                $sql44 = "SELECT * FROM complaint WHERE registred_by='$user_id' AND modified_datetime LIKE '%$today%'";
                $result44 = mysqli_query($link, $sql44);
                $num_rows1 = mysqli_num_rows($result44);
                $sql444 = "SELECT * FROM complaint WHERE assign_to='$user_id' AND category='3'";
                $result444 = mysqli_query($link, $sql444);
                $num_rows2 = mysqli_num_rows($result444);
                $sql4443 = "SELECT * FROM complaint WHERE assign_to='$user_id' AND category !='3'";
                $result4443 = mysqli_query($link, $sql4443);
                $num_rows3 = mysqli_num_rows($result4443);
                $sql45 = "SELECT * FROM chat WHERE sent_by='$user_id' AND modified_datetime LIKE '%$today%'";
                $result45 = mysqli_query($link, $sql45);
                $num_rows4 = mysqli_num_rows($result45);
 
                
                    echo "नाव :".$name."%0A"; 
                    echo "*आज नोंदवलेले अभिप्राय:* " .$num_rows4."%0A";
                    echo "*आज नोंदवलेली प्रकरणे :* ". $num_rows1."%0A";
                    echo "सोडवलेली एकूण प्रकरणे  :". $num_rows2."%0A";
                    echo "एकूण थकीत प्रकरणे : ". $num_rows3."%0A%0A";
                
             
               
                }
            }
        }
    
    ?> " class="button">Send All Data on WhatsApp</a> 
    
</div>
 <div>
  <form method="GET">
    <label for="lname">Date</label>
    <input type="date" class="form-control" id="inputFirstName" name="date" required>

   
    <input type="submit" value="Find">
  </form>
         <?php
         if (isset($_GET['date'])) {
                         $date = $_GET['date']; 
                        $sql22 = "SELECT * FROM dropdown";
                        $result22 = $link->query($sql22);
                        if ($result22->num_rows > 0) 
                        {
 
                           while($row22 = $result22->fetch_assoc())
                           {
                               ?>
                               
                               <a href="https://api.whatsapp.com/send?phone=+917775958623&text=<?php  
       
        $mob = $row22['user_id'];
       echo "*Daily Report*%0A";
       
       
        $sql45 = "SELECT * FROM admin WHERE user_id ='$mob'"; 
        $result45 = mysqli_query($link, $sql45);
        if ($result45->num_rows > 0) {
            while ($row45 = $result45->fetch_assoc()) {
                $user_id = $row45['user_id'];
                $name = $row45['name'];
                
                $formatted_date1 = date("d-m-Y", strtotime($date));
                 $date1 = date("Y-m-d", strtotime($date));
                
                echo "*नाव* :*".$name." (".$user_id.")*%0A";
                echo "*तारीख* :*".$formatted_date1."*%0A%0A"; 
                
                $sql455 = "SELECT * FROM complaint WHERE assign_to='$user_id' AND category='3'AND modified_datetime LIKE '%$date1%'";
                $result455 = mysqli_query($link, $sql455);
                if ($result455) {
                    $num_rows12 = mysqli_num_rows($result455);
                    if ($num_rows12 > 0) { 
                        while ($row455 = $result455->fetch_assoc()) {
                         $mobile_no=$row455['mobile_no'];
                         $complaint_title=$row455['complaint_title'];
                         $names=$row455['name'];
                         $village=$row455['village'];
                         $taluka=$row455['taluka'];
                         $destrict=$row455['destrict'];
                       
                             
              
                    echo "*मतदार* : ".$names. "(".$mobile_no.")%0A";  
                    echo "*विषय* : ".$complaint_title."%0A";
                    echo "*स्तिथी* : Completed %0A";
                    echo "*पत्ता* : ".$village.", ".$taluka.", ".$destrict."%0A%0A";
                
                            
                     }
                    } 
                }

                echo "*आज एकूण सोडवलेली प्रकरणे* : *".$num_rows12."*%0A";
                
               
                }
            }
        
    
    ?>" class="button"> <?php echo $row22['name']; ?></a> 
                      
                       <?php  }
                          }
                        
                        }
                        ?>
  



</div>



