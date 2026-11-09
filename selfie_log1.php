<?php
session_start();
include("connection.php");
date_default_timezone_set('Asia/Kolkata');

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imageData = $_POST['selfie'];
    $latitude = sanitize_input($_POST['latitude']);
    $longitude = sanitize_input($_POST['longitude']);
    $datetime = sanitize_input($_POST['datetime']);
    $desc = sanitize_input($_POST['desc']);

    // Validate required fields
    if (empty($imageData) || empty($latitude) || empty($longitude) || empty($datetime) || empty($desc)) {
        header("Location: selfie_log.php?error=All fields are required");
        exit;
    }

    // Get address from coordinates
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=18&addressdetails=1";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Reverse Geocoding');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $address = isset($data['display_name']) ? $data['display_name'] : "Address not found";

    // Process image data
    $img_binary = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
    
    if ($img_binary === false) {
        header("Location: selfie_log.php?error=Invalid image data");
        exit;
    }

    // Update admin status
    if($desc == "In") {
        $updateStmt = $link->prepare("UPDATE admin SET present_staff='In' WHERE user_id=?");
        $updateStmt->bind_param("s", $u_uname);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        $updateStmt = $link->prepare("UPDATE admin SET present_staff='Out' WHERE user_id=?");
        $updateStmt->bind_param("s", $u_uname);
        $updateStmt->execute();
        $updateStmt->close();
    }

    // Insert selfie log
    $stmt = $link->prepare("INSERT INTO selfie_log (image, latitude, longitude, datetime, `by`, description, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $img_binary, $latitude, $longitude, $datetime, $u_uname, $desc, $address);

    if ($stmt->execute()) {
        header("Location: selfie_log.php?success=Data Submitted Successfully");
    } else {
        header("Location: selfie_log.php?error=Data Not Submitted: " . $stmt->error);
    }
    $stmt->close();
} else {
    header("Location: selfie_log.php?error=Invalid request method");
}
$link->close();
?>