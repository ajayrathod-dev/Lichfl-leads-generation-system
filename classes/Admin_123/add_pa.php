<?php 
include('connection.php');

 
if (isset($_POST["login"])) {
    
    $mobile=$_POST['mobile'];
    $name=$_POST['name'];
    $work=$_POST['work'];
    
    mysqli_query($link,"INSERT INTO `dropdown`(`user_id`, `name`, `description`) VALUES ('$mobile','$name','$work')");
  echo "INSERT INTO `dropdown`(`user_id`, `name`, `description`) VALUES ('$mobile','$name','$work')"; 

}
 
  

?>

<form method="post">
    
   <input type="text" name="mobile"  placeholder="mobile no" required />
   <input type="text" name="name" placeholder="name" />
   <input type="text" name="work" placeholder="work" required />
   <input type="submit" value="submit" name="login"/>
</form>