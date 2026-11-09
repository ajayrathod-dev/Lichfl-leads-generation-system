<?php
session_start();
include("connection.php");
date_default_timezone_set('Asia/Kolkata');

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
	}
} else {
	header("location:logout.php");
	exit;
}

$achievements = $_POST['work_data'];
$dept = mysqli_real_escape_string($link, $_POST['dept']);
$desc = mysqli_real_escape_string($link, $_POST['desc']);
$det = mysqli_real_escape_string($link, $_POST['det']);

$inserted = 0;

foreach ($achievements as $work_type => $value) {
	if (!empty($value)) {
		$work_type_clean = mysqli_real_escape_string($link, $work_type);
		$value_clean = mysqli_real_escape_string($link, $value);

		$sql = "INSERT INTO `daily_report`(`achievement_in_no`, `department`,`work_type`, `description`, `by`, `date`)
				VALUES ('$value_clean','$dept','$work_type_clean','$desc','$u_uname','$det')";
		
		if (mysqli_query($link, $sql)) {
			$inserted++;
		}
	}
}

if ($inserted > 0) {
	$p = "$inserted Record(s) Submitted Successfully";
	header("Location:daily_report.php?success=$p");
} else {
	$p = "No valid input submitted";
	header("Location:daily_report.php?error=$p");
}
?>
