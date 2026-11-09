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





$id= $_POST['id'];

$p_name= $_POST['p_name'];
$s_desc= $_POST['s_desc'];
$unit= $_POST['unit'];
$l_desc= $_POST['l_desc'];
$price= $_POST['price'];
$shop_name= $_POST['shop_name'];
$status= $_POST['status'];
$stars= $_POST['stars'];
$category= $_POST['category'];
$city= $_POST['city'];





          $sql="UPDATE `product` SET `product_name`='$p_name',`category`='$category',`short_desc`='$s_desc',`long_desc`='$l_desc',`unit`='$unit',`price`='$price',`shop_name`='$shop_name',`city`='$city',`status`='$status',`stars`='$stars' WHERE id='$id'";
          


 
           $result=mysqli_query($link,$sql);
           if($result>0)
           {

                     
            $p=44;
             header("Location:add_product.php?success=$p");
           }
          else
          {
            $p=44;
             header("Location:add_product.php?error=$p");
            
          }
            
      
