<?php
session_start();

include("connection.php");
date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d h:i:s");

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



$c_name=$_POST['c_name'];
$inout=$_POST['inout'];
$res=$_POST['res'];
$time=$_POST['time'];
$branch = $_POST['branch'];
           
 

        $sql="INSERT INTO `movement`(`emp_id`,`inout`,`reasion`,`time`,`by`,`branch`) VALUES ('$c_name','$inout','$res','$time','$u_uname','$branch')";
        
 
           $result=mysqli_query($link,$sql);
           if($result>0)
           {
 
                
            $p=21;
             header("Location:movement.php?success=Data Submitted Successfully");
           }
          else
          {
             header("Location:movement.php?error=Data Not Submitted");
            
          }
            
      
?>
