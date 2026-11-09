<?php
include('header.php');
date_default_timezone_set('Asia/Kolkata'); 
$currentDateTime = date("Y-m-d\TH:i:s");
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
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #ccc;
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
    transition: .4s;
  }

  input:checked+.slider {
    background-color: #2196F3;
  }

  input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked+.slider:before {
    transform: translateX(26px);
  }

  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
</style>

<div class="page-wrapper">
  <div class="page-content">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">+ Daily Report</button>

    <div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Daily Report</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" action="daily_report1.php" method="post" enctype="multipart/form-data">
              <div class="col-md-6">
                <label class="form-label">Department</label>
                <select class="form-control" id="department" name="dept" required>
                  <option value="">--Select Department--</option>
                  <option value="BRANCH REPORTING">BRANCH REPORTING</option>
                  <option value="LOAN DEPARTMENT">LOAN DEPARTMENT</option>
                  <option value="DEPOSIT DEPARTMENT">DEPOSIT DEPARTMENT</option>
                  <option value="RECOVERY DEPARTMENT">RECOVERY DEPARTMENT</option>
                  <option value="ACCOUNT Or INVESTMENT Or FUND">ACCOUNT Or INVESTMENT Or FUND</option>
                  <option value="ADMIN DEPARTMENT">ADMIN DEPARTMENT</option>

                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Description</label>
                <input type="text" class="form-control" name="desc" required>
              </div>

              <div id="workInputs" class="row g-3"></div>

              <div class="col-md-6">
                <label class="form-label">दिनांक</label>
                <input type="datetime-local" class="form-control" name="det" value="<?php echo $currentDateTime; ?>" required step="1">
              </div>

              <div class="col-12 text-center">
                <button type="submit" class="btn btn-outline-primary px-5" onclick="return confirm('Do you want to submit?')">जतन करा</button>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-body">
        <div class="table-responsive">
          <table id="example2" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Sr.No</th>
                <th>Achievement In Number</th>
                <th>Department</th>
                <th>Working Type</th>
                <th>Description</th>
                <th>By</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $sr=1;
              if($designation_if=='founder'|| $designation_if=='chairman' || $designation_if=='CEO' || $designation_if=='GM') {
                $sql451 = "SELECT * FROM daily_report order by id desc limit 200";
              } else {
                $sql451 = "SELECT * FROM daily_report WHERE `by`='$u_uname' order by id desc limit 200";  
              }
              $result451 = mysqli_query($link, $sql451);
              if ($result451->num_rows > 0) {
                while ($row451 = $result451->fetch_assoc()) {
              ?>
              <tr>
                <td><?php echo $sr++; ?></td>
                <td><?php echo $row451['achievement_in_no']; ?></td>
                <td><?php echo $row451['department']; ?></td>
                <td><?php echo $row451['work_type']; ?></td>
                <td><?php echo $row451['description']; ?></td>
                <td>
                  <?php
                  $byid = $row451['by'];
                  $sql45 = "SELECT * FROM admin WHERE user_id='$byid'";
                  $result45 = mysqli_query($link, $sql45);
                  if ($result45->num_rows > 0) {
                    while ($row45 = $result45->fetch_assoc()) {
                      echo $row45['emp_name']."(".$row45['designation'].") (".$row45['branch'].")";                        
                    }
                  }
                  ?>
                </td>
                <td><?php echo date("d-m-Y h:i A", strtotime($row451['date'])); ?></td>
              </tr>
              <?php } } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('footer.php'); ?>

<script>
const workingData = {
            "BRANCH REPORTING":["GOLD LOAN", "GOLD ACCOUNT", "LOAN AGAINST DEPOSIT", "LOAN AGAINST PIGMY", "DEPOSIT ACCOUNT", "DEPOSIT AMOUNT", "SAVING ACCOUNT", "RD ACCOUNT", "CURRENT ACCOUNT", "DEMAND ACCOUNT", "DEMAND AMOUNT", "RECOVERY ACCOUNT", "RECOVERY AMOUNT", "DIFFERENCE ACCOUNT", "DIFFERENCE AMOUNT", "OTRR", "TOTAL DEPOSIT", "TOTAL LOAN", "TOTAL LOAN AGAINST DEPOSIT", "CASH", "BANK", "RECEIPT", "PAYMENT", "INVESTMENT", "OD", "CASA", "CD. RATIO", "NEW MEMBER", "LOAN SHARES", "MEMBER RESIGNATION", "NEW INVESTMENT", "INVESTMENT RENEWAL", "OD REPAID", "OD TAKEN", "TOTAL EXPENSES"],
            "LOAN DEPARTMENT":["PERSONAL LOAN", "EMPLOYEE SALARY LOAN", "MORTGAGE LOAN", "BUSINESS LOAN", "BACHAT GAT LOAN FLAT", "JLG D BACHAT GAT LOAN", "EMPLOYEE GOLD PURCHASE LOAN", "BUSINESS LOAN 2 FLAT", "VEHICAL LOAN", "MEMBER LOAN", "SALARI LOAN", "WARE HOUSE LOAN", "TERM LOAN", "CASH CREDIT LOAN", "LOAN AG.DEPOSIT", "SIMPLE LOAN", "BUSINESS LOAN 1 FLAT", "SHREE SIDDHIVINAYAK GOLD LOAN", "PERSONAL LOAN 1 FLAT", "LOAN AG.PIGMY DEPOSIT", "FINBUSS GOLD LOAN", "FINBUSS SONAR GOLD LOAN", "FINBUSS GOLD PURCHASE LOAN", "KISAN GOLD LOAN", "GOLD LOAN", "GOLD LOAN REGULAR", "GOLD LOAN SONAR"],
            "DEPOSIT DEPARTMENT":["NEW DEPOSIT", "RENEWAL DEPOSIT", "WITHDRAWL DEPOSIT", "PREMATURE DEPOSIT", "SHARES AMOUNT", "SHARES ACCOUNT", "SAVING ACCOUNT", "CURRENT ACCOUNT", "RD ACCOUNT"],
            "RECOVERY DEPARTMENT":["PERSONAL LOAN", "EMPLOYEE SALARY LOAN", "MORTGAGE LOAN", "BUSINESS LOAN", "BACHAT GAT LOAN FLAT", "JLG D BACHAT GAT LOAN", "EMPLOYEE GOLD PURCHASE LOAN", "BUSINESS LOAN 2 FLAT", "VEHICAL LOAN", "MEMBER LOAN", "SALARY LOAN", "WARE HOUSE LOAN", "TERM LOAN", "CASH CREDIT LOAN", "LOAN AG.DEPOSIT", "SIMPLE LOAN", "BUSINESS LOAN 1/2 FLAT", "GOLD LOAN", "PERSONAL LOAN 1 FLAT", "DEMAND ACCOUNT", "DEMAND AMOUNT", "RECOVERY ACCOUNT", "RECOVERY AMOUNT", "DIFFERENCE ACCOUNT", "DIFFERENCE AMOUNT", "OTRR", "TOTAL RECOVERY", "PENDING RECOVERY", "PENDING LOAN AMOUNT", "PENDING LOAN ACCOUNT", "OVERDUE ACCOUNT", "TOTAL ACCOUNT", "CURRENT ACCOUNT", "TOTAL CASH RECOVERY"],
            "ACCOUNT Or INVESTMENT Or FUND": ["SHARES", "FUNDS", "DEPOSIT", "LOAN", "GOLD LOAN", "LOAN AG. DEPOSIT", "INVESTMENT", "CASH IN HAND", "CASH AT BANK", "OD", "OD TAKEN", "OD PAID", "RECIEPTS", "PAYMENTS", "CRR RATIO", "SLR RATIO", "CD RATIO", "CASA RATIO", "CASA DEPOSIT"],
            "ADMIN DEPARTMENT":["VISIT REGISTER", "INWARD REGISTER", "OUTWARD REGISTER", "MOVEMENT", "TODAYS HALF DAY", "TODAYS LEAVE", "TOMMORROWS LEAVE", "TOMMORROWS HALF DAY", "EXPENSES"]
};
 

$('#department').change(function () {
  const department = $(this).val();
  const container = $('#workInputs');
  container.empty();

  if (workingData[department]) {
    workingData[department].forEach(type => {
      container.append(`
        <div class="col-md-6 mt-2">
          <label class="form-label">${type}</label>
          <input type="text" name="work_data[${type}]" class="form-control" placeholder="Enter number for ${type}">
        </div>
      `);
    });
  }
});
</script>
