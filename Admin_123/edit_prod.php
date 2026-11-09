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
  function myfunction(str, str1) {
    

    alert("DO YOU WANT TO CHANGE STAUTS");

    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("prog_show").innerHTML = xmlhttp.responseText;
        location.reload();

      }
    }
    xmlhttp.open("GET", "show_program.php?uid=" + str + "&status=" + str1, true);
    xmlhttp.send();
  }
</script>
<div class="page-wrapper">
   <div class="page-content">
    

         <div class="col-lg-12">
<div class="alert alert-dark border-0 bg-dark alert-dismissible fade show" style="height:60px;">
                           <div class="text-white"><h5 class="text-white">Edit Product</h5></div>
                           
                        </div>
                     </div>
                   

      <div class="card">
         <div class="card-body">
           
                   <?php
                    $id=$_GET['id'];
                    $sql1 = "SELECT * FROM product WHERE id='$id'";
                    $result1 = mysqli_query($link, $sql1);
                    if ($result1->num_rows > 0) {
                      while ($usr1 = mysqli_fetch_array($result1)) {

                             $id=$usr1['id'];
                              $product_name=$usr1['product_name'];
                             $category=$usr1['category'];
                             $short_desc=$usr1['short_desc'];
                             $long_desc=$usr1['long_desc'];
                             $unit=$usr1['unit'];
                             $price=$usr1['price'];
                             $shop_name=$usr1['shop_name'];
                             $city=$usr1['city'];
                             $stars=$usr1['stars'];
                             $status=$usr1['status'];
                             $datetime=$usr1['datetime'];

                      }
                    }
                    ?>
                         <form class="row g-3" action="edit_pro1.php" method="post">
                                   <div class="col-md-6">
                    <label for="inputFirstName" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="inputFirstName" name="p_name" value="<?php echo $product_name;?>">
                                        <input type="hidden" class="form-control" id="inputFirstName" name="id" value="<?php echo $id;?>">

                  </div>
                    <div class="col-md-6">
                    <label for="inputPassword" class="form-label">Category</label>
                      <input type="text" class="form-control" id="inputFirstName" name="category" value="<?php echo $category; ?>">
                  </div>
                     <div class="col-md-6">
                    <label for="inputPassword" class="form-label">Unit</label>
                      <input type="text" class="form-control" id="inputFirstName" name="unit" value="<?php echo $unit;?>">
                  </div>
                  <div class="col-md-6">
                    <label for="inputFirstName" class="form-label">Short Description</label>
                    <textarea class="form-control" id="inputFirstName" name="s_desc"> <?php echo $short_desc; ?></textarea> 
                  </div>
                  <div class="col-md-6">
                    <label for="inputPassword" class="form-label">Long Description</label>
                   <textarea class="form-control" id="inputFirstName" name="l_desc"><?php echo $long_desc;?> </textarea> 

                  </div>
               
                  <div class="col-md-6">
                    <label for="inputFirstName" class="form-label">Price</label>
                    <input type="text" class="form-control" id="inputFirstName" name="price" value="<?php echo $price; ?>">
                  </div>
                   
                   <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Shop Name</label>
                                        <input type="text" class="form-control" id="inputFirstName" name="shop_name" value="<?php echo $shop_name;?>">

                  </div>
                    <div class="col-md-6">
                    <label for="inputLastName" class="form-label">City</label>
                                        <input type="text" class="form-control" id="inputFirstName" name="city" value="<?php echo $city;?>">

                  </div>
                  <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Status</label>
                    
                    <select class="form-control" id="inputLastName" name="status" value="<?php echo $status; ?>">
                      <option>Avialable</option>
                      <option>Not Avialable</option>
                    </select>
                  </div>
                   <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Stars</label>
                                        <input type="text" class="form-control" id="inputFirstName" name="stars" value="<?php echo $stars;?>">

                  </div>
                 

                  
                  <div class="col-12" align="center">
                    <button type="submit" class="btn btn-outline-primary px-5">Update Product</button>
                  </div>
                </form>
                     
         </div>
      </div>
   </div>
</div>
</div>
<?php
   include('footer.php');
?>