<?php  
date_default_timezone_set("Asia/Kolkata");
$DB_SERVER = "localhost";
$DB_NAME = "ranada";
$username = "ranada";
$password = "Ranada@1232";
$link = mysqli_connect($DB_SERVER, $username, $password, $DB_NAME);
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
} 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Days List Generator</title>
    <style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
</style>
</head>
<body>

<h2>Enter Month, Year, and Mobile Number</h2>
<form method="post" action="">
    <label for="month">Month:</label>
    <input type="number" id="month" name="month" min="1" max="12" required>
    <label for="year">Year:</label>
    <input type="number" id="year" name="year" min="1900" max="2100" required>
    <label for="uid">Mobile No:</label>
    <input type="number" id="uid" name="uid" required>
    <input type="submit" name="submit" value="Generate Days">
</form>
 <table id="customers">
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $month = intval($_POST['month']);
    $year = intval($_POST['year']);
    $user_id = $_POST['uid'];

    // Validate the month and year
    if ($month >= 1 && $month <= 12 && $year >= 1900 && $year <= 2100) {
        $start_date = new DateTime("$year-$month-01");
        $end_date = clone $start_date;
        $end_date->modify('last day of this month');
        $interval = new DateInterval('P1D');
        $date_range = new DatePeriod($start_date, $interval, $end_date->modify('+1 day'));

        echo "<h3>List of Days for $month/$year</h3>";
        echo "<tr>";
         echo "<td>Name/Date</td>";
        foreach ($date_range as $date) {
            $current_date = $date->format('Y-m-d');
          
           

            echo "<td>" . $date->format('Y-m-d') . " (" . $date->format('l') . ")</td>";
        }
        echo "</tr>";
        
        
        
        
$sql = "SELECT * FROM admin";
$result = $link->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $user_id= $row["user_id"];
     $name= $row["name"];
    echo "<tr>";
    echo "<td>".$name."</td>";
    foreach ($date_range as $date) {
            $current_date = $date->format('Y-m-d');
            
            // Query to count messages for this date and user
            $sql45 = "SELECT COUNT(*) as count FROM chat WHERE sent_by='$user_id' AND DATE(date) = '$current_date'";
            $result45 = mysqli_query($link, $sql45);
            $count = 0;

            if ($result45 && $row45 = mysqli_fetch_assoc($result45)) {
                $count = $row45['count'];
            }
            ?>
           
            <td style="<?php
            if($count==0)
            { 
               echo 'background-color:red';
               
            }
            else
            {
             echo "background-color:green";
             
             }?>">
             
           <?php  echo "(".$count.")"; ?>
             
             </td>
            
        <?php
    }
        echo "</tr>";
    
    }
} else {
  echo "0 results";
}
        
        
        
        
        
    
    } else {
        echo "<p>Please enter a valid month and year.</p>";
    }
}
?>
</table>
</body>
</html>
