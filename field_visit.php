<?php
include('header.php');
date_default_timezone_set('Asia/Kolkata');
?>

<style>
  body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }
  video, canvas { width: 240px; height: 180px; border: 1px solid #ccc; margin: 10px; }
  table { border-collapse: collapse; width: 100%; margin-top: 30px; }
  th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
 
  button { padding: 10px 20px; font-size: 16px; margin: 10px; cursor: pointer; }
</style>

<div class="page-wrapper">
  <div class="page-content">
    <div class="card mt-2">
      <div class="card-body">
        <h2>📸 Field Visit</h2>

        <video id="video" autoplay playsinline></video>
        <canvas id="canvas" style="display:none;"></canvas><br>

        <button onclick="captureSelfie()" class="btn btn-outline-primary px-5">📷 Capture</button>

        <form method="POST" action="field_visit1.php" onsubmit="return validateAndSubmit();">
          <input type="hidden" name="selfie" id="selfieData" required>
          <input type="hidden" name="latitude" id="latitude" required>
          <input type="hidden" name="longitude" id="longitude" required>
          <input type="hidden" name="datetime" id="datetime" required>

          <br><img id="preview" alt="Preview"><br>
          <div class="row">
          <div class="col-md-6">
           <label for="inputFirstName" class="form-label">Customer Name</label>
           <input type="text" id="inputFirstName" name="cname" required class="form-control">       
          </div>
           <div class="col-md-6">
           <label for="inputFirstName" class="form-label">Mobile No</label>
           <input type="text" id="inputFirstName" name="mob" required class="form-control">  
          </div>
           <div class="col-md-6">
           <label for="inputFirstName" class="form-label">Remark</label>
            <input type="text" id="inputFirstName" name="remark" required class="form-control">  
          </div>
           <div class="col-md-6">
           <label for="inputFirstName" class="form-label">Next Visit Date</label>
            <input type="date" id="inputFirstName" name="ndate" required class="form-control">  
          </div>
         </div>
          <button type="submit" class="btn btn-outline-primary px-5">✅ Submit</button>
          
          
<script>
  const video = document.getElementById('video');
  const selfieData = document.getElementById('selfieData');
  const preview = document.getElementById('preview');
  const canvas = document.getElementById('canvas');

  // Get camera stream
  navigator.mediaDevices.getUserMedia({ video: { width: 240, height: 180 } })
    .then(stream => video.srcObject = stream)
    .catch(err => {
      alert('❌ Camera access denied or unavailable.');
      console.error(err);
    });

  function captureSelfie() {
    canvas.width = 240;
    canvas.height = 180;
    canvas.getContext('2d').drawImage(video, 0, 0, 240, 180);
    const dataURL = canvas.toDataURL('image/jpeg', 0.3);
    selfieData.value = dataURL;
    preview.src = dataURL;
  }

  // Get location
  navigator.geolocation.getCurrentPosition(
    (pos) => {
      document.getElementById('latitude').value = pos.coords.latitude;
      document.getElementById('longitude').value = pos.coords.longitude;
    },
    (err) => {
      alert('❌ Location permission denied. Please enable location services.');
      console.error(err);
    }
  );

  // Get current local datetime
  function getLocalDateTimeString() {
    const now = new Date();
    const pad = (n) => n.toString().padStart(2, '0');
    return `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
  }
  document.getElementById('datetime').value = getLocalDateTimeString();

  // Validation before submit
  function validateAndSubmit() {
    if (!selfieData.value) {
      alert("⚠️ Please capture your selfie.");
      return false;
    }
    if (!document.getElementById('latitude').value || !document.getElementById('longitude').value) {
      alert("⚠️ Location not available. Please allow location access.");
      return false;
    }
    return true;
  }
</script>
          
          
          
        </form>
      </div>
    </div>

   <div class="card mt-2">
      <div class="card-body">

    <div class="table-responsive">
    <table id="example2" class="table table-striped table-bordered">
     <thead>
        <tr>
          <th>Sr.No</th>
          <th>Selfie</th>
          <th>Latitude /Longitude</th>
          <th>Customer Name</th>
          <th>Mobile No</th>
          <th>Address</th>
          <th>Description</th>
          <th>Next Visit Date</th>
          <th>By</th>
          <th>Date & Time</th>
        </tr>
      </thead>
      <tbody>
        <?php
        include("connection.php");
        $result = $link->query("SELECT * FROM selfie_log WHERE `by`='$u_uname'  ORDER BY id DESC LIMIT 20");
        $sr = 1;
        while ($row = $result->fetch_assoc()) {
          $img = base64_encode($row['image']);
          echo "<tr>
                  <td>{$sr}</td>
                  <td><img src='data:image/jpeg;base64,{$img}' style=' width: 100px; height: auto;'></td>
                  <td>
                    <a href='https://www.google.com/maps?q={$row['latitude']},{$row['longitude']}' target='_blank'>View On Map</td>";
                 ?>
                  <td><?php echo $row['cname']; ?></td>
                   <td><?php echo $row['cmob']; ?></td>
                   <td><?php echo $row['address']; ?></td>
                   <td><?php echo $row['description']; ?></td>
                   <td><?php echo $row['next_visit']; ?></td>
                   <td><?php
                              $byid = $row['by'];
                                    $sql45 = "SELECT * FROM admin WHERE user_id='$byid'";
                                            $result45 = mysqli_query($link, $sql45);
                                            if ($result45->num_rows > 0) {
 
                                          	while ($row45 = $result45->fetch_assoc()) {
                                     		
                                     		 echo $row45['emp_name'];                        
                                           }
                                         }
                              
                              
                              
                              
                              ?></td>
                   <td><?php echo $row['datetime']; ?></td>
                </tr>
                <?php
          $sr++;
        }
        ?>
      </tbody>
    </table>
    </div>
    </div>
  </div>
</div>


<?php include('footer.php'); ?>


