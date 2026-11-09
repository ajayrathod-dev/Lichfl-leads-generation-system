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

function myfunction1(str1,str2)

{

  if (window.XMLHttpRequest)

    {// code for IE7+, Firefox, Chrome, Opera, Safari

        xmlhttp=new XMLHttpRequest();

    }

  else

    {// code for IE6, IE5

       xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

    }

   xmlhttp.onreadystatechange=function()

    {

      if (xmlhttp.readyState==4 && xmlhttp.status==200)

      {

      document.getElementById("prog_show").innerHTML=xmlhttp.responseText;

         location.reload();

 

      }

      }

  xmlhttp.open("GET","trans_status.php?usd="+str1+"&id="+str2,true);

  xmlhttp.send();

}

</script>
<div class="page-wrapper">
   <div class="page-content">
    

    	<div class="card">
         <div class="card-body">
             	<div class="card-title d-flex align-items-center">
										<div><i class="bx bxs-user me-1 font-22 text-info"></i> 
										</div>
										<h5 class="mb-0 text-info">Registration Details</h5>
									</div>
									<hr/>
            <div class="table-responsive">
               <table id="example2" class="table table-striped table-bordered">
                  <thead>
                   <tr>
                     <th>Sr.No</th>
                     <th>Name</th>
                     <th>Mobile No</th>
                     <th>Whats App No</th>
                     <th>Email</th>
                     <th>Date</th>
                     <th>Gender</th>
                     <th>Village</th>
                     <th>Taluka</th>
                     <th>District</th>
                     <th>Intrest</th>
                     <th>Welcome</th>
                     <th>Date</th>
                     
                   </tr>
                 </thead>
                 <tbody>
                   <?php
                   $message="नमस्कार,

तेरणा युथ फाऊंडेशन परिवारात आपले स्वागत आहे. युवकांनी पुढाकार घेऊन युवकांसाठी सुरु केलेली ही एक सामाजिक चळवळ आहे. युथ फाऊंडेशनच्या माध्यमातून आपण सामजिक उपक्रमांसह रोजगार मेळावा, प्रशिक्षण शिबिर, क्रीडा स्पर्धा असे विविध उपक्रम राबवणार आहोत. या उपक्रमांमध्ये सहभागी होण्यासाठी आपला युनिक आयडी क्रमांक महत्त्वाचा आहे. आपल्या भागातील युवक /युवतींना तेरणा फाऊंडेशनचे सदस्य म्हणून सहभागी होण्यासाठी आपण प्रयत्न कराल आणि युवकांसाठीची ही चळवळ आणखी मजबूत कराल, अशी मला अपेक्षा आहे.

कृपया तेरणा युथ फाऊंडेशनचा अधिकृत संपर्क क्रमांक म्हणून आपण हा मोबाईल क्रमांक सेव्ह करावा. 

धन्यवाद.

आपलाच,
मेघ राणाजगजितसिंह पाटील
https://youth.ternatrust.org
                   ";
                    $sr = 1;
                    $sql1 = "SELECT * FROM  registration order by id DESC";
                    $result1 = mysqli_query($link, $sql1);
                    if ($result1->num_rows > 0) {
                      while ($usr1 = mysqli_fetch_array($result1)) {
                          
                         $phone= $usr1['whats_app'];
                    ?>
                       <tr>
                         <td><?php echo $sr; ?></td>
                         <td> <?php echo $usr1['name']; ?></td>
                         <td> <?php echo $usr1['mobile_no']; ?></td>
                         <td> <?php echo $usr1['whats_app']; ?></td>
                         <td> <?php echo $usr1['email']; ?></td>
                         <td> <?php echo $usr1['date']; ?></td>
                         <td> <?php echo $usr1['gender']; ?></td>
                         <td> <?php echo $usr1['village']; ?></td>
                         <td> <?php echo $usr1['taluka']; ?></td>
                         <td> <?php echo $usr1['district']; ?></td>
                         <td> <?php echo $usr1['intrest']; ?></td>
                          <td><a href="https://api.whatsapp.com/send?phone=<?php echo $phone; ?>&text=<?php echo $message;?>" class="btn btn-success" target="_blank">Whatsapp</button></td>
                         <td> <?php echo $usr1['dt']; ?></td>
                        
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