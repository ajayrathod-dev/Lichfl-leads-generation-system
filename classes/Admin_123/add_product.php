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
                           <div class="text-white"><h5 class="text-white"> Product List</h5></div>
                        </div>
                     </div>
                     <div class="mb-2">

                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">+ Add Product</button>
                     <div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Add Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <form class="row g-3" action="add_product1.php" method="post" enctype="multipart/form-data">
                                  <div class="col-md-6">
                    <label for="inputFirstName" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="inputFirstName" name="p_name">
                  </div>
                    <div class="col-md-6">
                    <label for="inputPassword" class="form-label">Category</label>
                     <select class="form-control" id="inputLastName" name="category">
                      <option>Select</option>

<?php

               $sql1 = "SELECT * FROM category order by id DESC";
                    $result1 = mysqli_query($link, $sql1);
                    if ($result1->num_rows > 0) {
                      while ($usr1 = mysqli_fetch_array($result1)) {
                    ?>


                      <option><?php echo $usr1['category_name']; ?></option>
                                          
                       

                   <?php
                                              }
                    }
                    ?>
                  </select>

                  </div>
                     <div class="col-md-6">
                    <label for="inputPassword" class="form-label">Unit</label>
                      <input type="text" class="form-control" id="inputFirstName" name="unit">
                  </div>
                  <div class="col-md-6">
                    <label for="inputFirstName" class="form-label">Short Description</label>
                    <textarea class="form-control" id="inputFirstName" name="s_desc"></textarea> 
                  </div>
                  <div class="col-md-6">
                    <label for="inputPassword" class="form-label">Long Description</label>
                   <textarea class="form-control" id="inputFirstName" name="l_desc"></textarea> 

                  </div>
               
                  <div class="col-md-6">
                    <label for="inputFirstName" class="form-label">Price</label>
                    <input type="text" class="form-control" id="inputFirstName" name="price">
                  </div>
                   
                   <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Shop Name</label>
                                        <input type="text" class="form-control" id="inputFirstName" name="shop_name">

                  </div>
                    <div class="col-md-6">
                    <label for="inputLastName" class="form-label">City</label>
                                        <input type="text" class="form-control" id="inputFirstName" name="city">

                  </div>
                  <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Status</label>
                    
                    <select class="form-control" id="inputLastName" name="status">
                      <option>Avialable</option>
                      <option>Not Avialable</option>
                    </select>
                  </div>
                   <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Upload Image 1</label>

                     <input type="file" class="form-control" id="exampleInputEmail1" required name="uploadfile">

                   </div>
                    <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Upload Image 2</label>

                     <input type="file" class="form-control" id="exampleInputEmail1" required name="uploadfile2">

                   </div>
                    <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Upload Image 3</label>

                     <input type="file" class="form-control" id="exampleInputEmail1" required name="uploadfile3">

                   </div>
                    <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Upload Image 4</label>

                     <input type="file" class="form-control" id="exampleInputEmail1" required name="uploadfile4">

                   </div>
                   <div class="col-md-6">
                    <label for="inputLastName" class="form-label">Stars</label>
                                        <input type="text" class="form-control" id="inputFirstName" name="stars">

                  </div>
                 

                  
                  <div class="col-12" align="center">
                    <button type="submit" class="btn btn-outline-primary px-5">Add Product</button>
                  </div>
                </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          
                          </div>
                        </div>
                      </div>
                    </div>
                    </div>

      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="example2" class="table table-striped table-bordered">
                  <thead>
                   <tr>
                     <th>Sr.No</th>
                     <th>Image 1</th>
                     <th>Image 2</th>
                     <th>Image 3</th>
                     <th>Image 4</th>
                     <th>Product Name</th>
                     <th>Category</th>
                     <th>Short Description</th>
                     <th>Long Description</th>
                     <th>Unit</th>
                     <th>Price</th> 
                     <th>Shop Name</th>
                     <th>City</th>
                      <th>Status</th>
                     <th>Star</th>
                     <th>Date</th>
                       <th>Action</th>
                     

                   </tr>
                 </thead>
                 <tbody>
                   <?php
                    $sr = 1;
                    $sql1 = "SELECT * FROM product order by id DESC";
                    $result1 = mysqli_query($link, $sql1);
                    if ($result1->num_rows > 0) {
                      while ($usr1 = mysqli_fetch_array($result1)) {
                    ?>
                       <tr>
                         <td><?php echo $sr; ?></td>
                         <td><img src="uploads/<?php  echo  $usr1['image'];?>" style="width: 100px;"></td>
                          <td><img src="uploads/<?php  echo  $usr1['image2'];?>" style="width: 100px;"></td>
                           <td><img src="uploads/<?php  echo  $usr1['image3'];?>" style="width: 100px;"></td>
                            <td><img src="uploads/<?php  echo  $usr1['image4'];?>" style="width: 100px;"></td>
                          <td><?php echo $usr1['product_name']; ?></td> 
                         <td><?php echo $usr1['category']; ?></td> 
                         <td><?php echo $usr1['short_desc']; ?></td>
                         <td><?php echo $usr1['long_desc']; ?></td>
                         <td><?php echo $usr1['unit']; ?></td>
                        
                         <td><?php echo $usr1['price']; ?></td>
                         <td><?php echo $usr1['shop_name']; ?></td>
                         <td><?php echo $usr1['city']; ?></td>
                         <td>

                          <span class="badge rounded-pill bg-warning text-dark"><?php echo $usr1['status']; ?></span></td>
                           <td><?php echo $usr1['stars']; ?></td>
                           <td><?php echo $usr1['datetime']; ?></td>

                            <td><a href="edit_prod.php?id=<?php echo $usr1['id']; ?>">Edit</a>
                               <a href="delete_prod.php?id=<?php echo $usr1['id']; ?>">Delete</a>
                            </td>
                       
                       </tr>

                   <?php
                        $sr++;
                      }
                    }
                    ?>
                 </tbody>

               </table>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<?php
   include('footer.php');
?>