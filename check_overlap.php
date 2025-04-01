<?php
include 'config.php';

// Get POST data
$roomType = $_POST['roomType'];
$checkIn = $_POST['checkIn'];
$checkOut = $_POST['checkOut'];

// Convert dates to MySQL format
$checkIn = date('Y-m-d', strtotime($checkIn));
$checkOut = date('Y-m-d', strtotime($checkOut));

// Query to check for overlapping bookings
$sql = "SELECT * FROM roombook 
        WHERE RoomType = ? 
        AND stat != 'Cancelled'
        AND (
            (cin <= ? AND cout > ?) OR
            (cin < ? AND cout >= ?) OR
            (cin >= ? AND cout <= ?)
        )";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssssss", $roomType, $checkOut, $checkIn, $checkOut, $checkIn, $checkIn, $checkOut);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$hasOverlap = mysqli_num_rows($result) > 0;

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['hasOverlap' => $hasOverlap]);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?> 