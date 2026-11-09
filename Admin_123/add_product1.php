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

$amount= $_POST['amount'];
$remark= $_POST['remark'];
$utr= $_POST['utr'];
$mobile_no= $_POST['mobile_no'];

 $sql34 = "SELECT * FROM users WHERE mobile_no='$mobile_no'";
 
 
$result34 = mysqli_query($link, $sql34);

if ($result34->num_rows > 0) {

  while ($row34 = $result34->fetch_assoc()) {

  

    $bal =  $row34['wallete_balance'];

  }

}



               $tot=$bal+$amount;
              
               mysqli_query($link,"UPDATE users SET wallete_balance='$tot' WHERE mobile_no='$mobile_no'");
                     
 $sql="UPDATE `payment_log` SET `status`='Success',`amount`='$amount',`remark`='$remark',`utr`='$utr' WHERE mobile_no='$mobile_no' AND status='Pending' ORDER BY id DESC LIMIT 1";
          


 
           $result=mysqli_query($link,$sql);
           if($result>0)
           {
  
          
            $p=44;
             header("Location:payment_log.php?success=$p");
           }
          else
          {
            $p=44;
             header("Location:payment_log.php?error=$p");
            
          }

      
