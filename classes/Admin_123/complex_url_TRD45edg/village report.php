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

<table id="customers">
    <?php 
    
$sql = "SELECT DISTINCT village FROM complaint";
$result = $link->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $village= $row["village"];
    echo "<tr>";
    
    
    
    
    $sql45 = "SELECT COUNT(*) as count FROM complaint WHERE village='$village'";
            $result45 = mysqli_query($link, $sql45);
            $count = 0;

            if ($result45 && $row45 = mysqli_fetch_assoc($result45)) {
                $count = $row45['count'];
            }
            
            
    
        echo "<td>".$village."</td>";
        echo "<td>".$count."</td>";
        
        
        
        
    echo "</tr>";
  }
}
    ?>
</table>
</body>
</html>
