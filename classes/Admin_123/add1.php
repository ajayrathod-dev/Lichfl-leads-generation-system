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




$train_no=mysqli_real_escape_string($link, $_POST['train_no']);
$id=mysqli_real_escape_string($link, $_POST['id']);

$mobile=mysqli_real_escape_string($link, $_POST['mobile']);
$seat_no=mysqli_real_escape_string($link, $_POST['seat_no']);
$bill_pay=mysqli_real_escape_string($link, $_POST['bill_pay']);

$c_name=mysqli_real_escape_string($link, $_POST['c_name']);
$paid=mysqli_real_escape_string($link, $_POST['paid']);
$f_det=mysqli_real_escape_string($link, $_POST['f_det']);
$status=mysqli_real_escape_string($link, $_POST['status']);





          $sql="UPDATE `take_order` SET `c_name`='$c_name',`train_no`='$train_no',`mobile_no`='$mobile',`seat_no`='$seat_no',`order_id`='$f_det',`tot_amount`='$paid',`bill_payment`='$bill_pay',`status`='$status' WHERE id='$id'";
          


 
           $result=mysqli_query($link,$sql);
           if($result>0)
           {

                     
            $p=44;
             header("Location:take_order.php?success=$p");
           }
          else
          {
            $p=44;
             header("Location:take_order.php?error=$p");
            
          }
            
      
