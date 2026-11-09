<?php
session_start();
include("connection.php");

// Check if UUID cookie is set
$u_uname = '';
if (isset($_COOKIE['uuid'])) {
    $u_uname = sanitize_input($_COOKIE['uuid']);
}

// Prepare and execute query to verify user
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

include('header.php');
date_default_timezone_set('Asia/Kolkata');
?>

<style>
  .selfie-container { max-width: 100%; }
  video, canvas { 
    width: 100%; 
    max-width: 300px; 
    height: auto; 
    border: 1px solid #ccc; 
    margin: 10px auto; 
    display: block;
    border-radius: 10px;
  }
  table { border-collapse: collapse; width: 100%; margin-top: 30px; }
  th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
  button { padding: 12px 24px; font-size: 16px; margin: 10px; cursor: pointer; }
  #preview { 
    max-width: 100%; 
    max-width: 300px;
    height: auto; 
    margin: 10px auto; 
    display: block;
    border-radius: 10px;
    border: 2px solid #007bff;
  }
  
  /* Mobile Responsive Styles */
  @media (max-width: 768px) {
    .card-body {
      padding: 15px;
    }
    video, canvas, #preview {
      max-width: 250px;
    }
    button {
      width: 100%;
      margin: 5px 0;
    }
    .table-responsive {
      font-size: 0.8rem;
    }
    th, td {
      padding: 6px 4px;
    }
  }
  
  @media (max-width: 576px) {
    video, canvas, #preview {
      max-width: 200px;
    }
    .card-body {
      padding: 10px;
    }
    h2 {
      font-size: 1.3rem;
    }
    h4 {
      font-size: 1.1rem;
    }
    .table-responsive {
      font-size: 0.7rem;
    }
    th, td {
      padding: 4px 2px;
    }
    .btn {
      padding: 10px 15px;
      font-size: 0.9rem;
    }
  }
  
  /* Selfie image styling in table */
  .selfie-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
  }
  
  @media (max-width: 576px) {
    .selfie-thumb {
      width: 40px;
      height: 40px;
    }
  }
</style>

<div class="page-wrapper">
  <div class="page-content">
    <div class="card mt-2">
      <div class="card-body">
        <h2 class="text-center mb-4">Selfie Capture</h2>

        <div class="selfie-container text-center">
          <video id="video" autoplay playsinline></video>
          <canvas id="canvas" style="display:none;"></canvas>
          
          <div class="d-flex flex-column flex-md-row justify-content-center gap-2 mb-3">
            <button onclick="captureSelfie()" class="btn btn-outline-primary">
              <i class='bx bx-camera me-2'></i>Capture Selfie
            </button>
          </div>

          <form method="POST" action="selfie_log1.php" onsubmit="return validateAndSubmit();" class="mt-3">
            <input type="hidden" name="selfie" id="selfieData" required>
            <input type="hidden" name="latitude" id="latitude" required>
            <input type="hidden" name="longitude" id="longitude" required>
            <input type="hidden" name="datetime" id="datetime" required> 

            <img id="preview" alt="Preview" style="display:none;" class="mb-3">
            
            <div class="row justify-content-center">
              <div class="col-12 col-md-6 col-lg-4">
                <label for="inputDescription" class="form-label">Status</label>
                <select class="form-control" id="inputDescription" name="desc" required>
                    <option value="">--Select Status--</option>
                    <option value="In">In</option>
                    <option value="Out">Out</option>
                </select>       
              </div>
            </div>
            
            <button type="submit" class="btn btn-primary mt-3 px-4">
              <i class='bx bx-check-circle me-2'></i>Submit Selfie
            </button>
          </form>
        </div>
      </div>
    </div>

   <div class="card mt-4">
      <div class="card-body">
        <h4 class="text-center mb-3">Selfie History</h4>
        <div class="table-responsive">
          <table id="example2" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Selfie</th>
                <th>Location</th>
                <th>Address</th>
                <th>Status</th>
                <th>By</th>
                <th>Date & Time</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT * FROM selfie_log WHERE `by` = ? ORDER BY id DESC LIMIT 20";
              $stmt = $link->prepare($sql);
              $stmt->bind_param("s", $u_uname);
              $stmt->execute();
              $result = $stmt->get_result();
              $sr = 1;
              
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $img = base64_encode($row['image']);
                  echo "<tr>
                          <td>{$sr}</td>
                          <td><img src='data:image/jpeg;base64,{$img}' class='selfie-thumb'></td>
                          <td>
                            <a href='https://www.google.com/maps?q={$row['latitude']},{$row['longitude']}' target='_blank' class='btn btn-sm btn-outline-primary'>View Map</a>
                          </td>
                          <td><small>{$row['address']}</small></td>
                          <td><span class='badge " . ($row['description'] == 'In' ? 'bg-success' : 'bg-warning') . "'>{$row['description']}</span></td>
                          <td><small>{$u_uname}</small></td>
                          <td><small>{$row['datetime']}</small></td>
                        </tr>";
                  $sr++;
                }
              } else {
                echo "<tr><td colspan='7' class='text-center py-4'>No selfies found. Capture your first selfie above.</td></tr>";
              }
              $stmt->close();
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const video = document.getElementById('video');
  const selfieData = document.getElementById('selfieData');
  const preview = document.getElementById('preview');
  const canvas = document.getElementById('canvas');

  // Get camera stream
  navigator.mediaDevices.getUserMedia({ 
    video: { 
      width: { ideal: 300 },
      height: { ideal: 300 },
      facingMode: "user" 
    } 
  })
  .then(stream => video.srcObject = stream)
  .catch(err => {
    alert('Camera access denied or unavailable. Please allow camera access to capture selfies.');
    console.error(err);
  });

  function captureSelfie() {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataURL = canvas.toDataURL('image/jpeg', 0.8);
    selfieData.value = dataURL;
    preview.src = dataURL;
    preview.style.display = 'block';
    
    // Scroll to preview
    preview.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  // Get location
  navigator.geolocation.getCurrentPosition(
    (pos) => {
      document.getElementById('latitude').value = pos.coords.latitude;
      document.getElementById('longitude').value = pos.coords.longitude;
    },
    (err) => {
      alert('Location permission denied. Please enable location services for accurate tracking.');
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
      alert("Please capture your selfie before submitting.");
      return false;
    }
    if (!document.getElementById('latitude').value || !document.getElementById('longitude').value) {
      alert("Location not available. Please allow location access and try again.");
      return false;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bx bx-loader bx-spin me-2"></i> Submitting...';
    submitBtn.disabled = true;
    
    return true;
  }
  
  // Handle page responsiveness
  window.addEventListener('resize', function() {
    // Adjust camera feed size on resize
    if (video.srcObject) {
      const containerWidth = document.querySelector('.selfie-container').offsetWidth;
      video.style.maxWidth = Math.min(300, containerWidth - 40) + 'px';
    }
  });
</script>

<?php include('footer.php'); ?>