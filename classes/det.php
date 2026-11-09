<?php

header('Content-Type: application/json');
$servername = "localhost";
$username = "ranada";
$password = "Ranada@1232";
$dbname = "ranada";

// Create connection
$link = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($link->connect_error) {
  die("Connection failed: " . $link->connect_error);
}






// Check if mobile number is passed as a parameter
if (isset($_GET['mobile'])) {
    $mobile_no = mysqli_real_escape_string($link, $_GET['mobile']);

    // SQL query to fetch name and description based on mobile number
    $sql = "SELECT name, description FROM user WHERE mobile_no='$mobile_no'";

    // Execute the query
    $result = mysqli_query($link, $sql);

    if ($result->num_rows > 0) {
        // Fetch the data and store it in an array
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        // Return the data in JSON format
        echo json_encode($data);
    } else {
        // Return an empty array if no data is found
        echo json_encode([]);
    }
} else {
    // Return an error if mobile_no is not provided
    echo json_encode(['error' => 'No mobile number provided']);
}

// Close the database connection
mysqli_close($link);
?>

