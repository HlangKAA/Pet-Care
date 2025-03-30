<?php

include '../config.php';

if(isset($_GET['id'])) {
	$id = $_GET['id'];

	// อัพเดทสถานะในตาราง roombook เป็น Confirm
	$sql = "UPDATE roombook SET stat = 'Confirm' WHERE id = '$id'";
	$result = mysqli_query($conn, $sql);

	// ดึงข้อมูลการจองเพื่อคำนวณราคา
	$booking_sql = "SELECT * FROM roombook WHERE id = '$id'";
	$booking_result = mysqli_query($conn, $booking_sql);
	$booking_data = mysqli_fetch_assoc($booking_result);

	if($booking_data) {
		$Name = $booking_data['Name'];
		$Email = $booking_data['Email'];
		$Phone = $booking_data['Phone'];
		$RoomType = $booking_data['RoomType'];
		$Count = $booking_data['Count'];
		$NoofRoom = $booking_data['NoofRoom'];
		$cin = $booking_data['cin'];
		$cout = $booking_data['cout'];

		// คำนวณราคาห้อง
		$type_of_room = 0;
		if($RoomType == "ห้องเล็ก - แมว") {
			$type_of_room = 200;
		} else if($RoomType == "ห้องใหญ่ - แมว") {
			$type_of_room = 300;
		} else if($RoomType == "ห้องเล็ก - หมา") {
			$type_of_room = 300;
		} else if($RoomType == "ห้องใหญ่ - หมา") {
			$type_of_room = 500;
		}

		// คำนวณจำนวนวันและราคารวม
		$noofday = date_diff(date_create($cin), date_create($cout))->days;
		$roomtotal = intval($type_of_room) * intval($noofday) * intval($NoofRoom);

		// เพิ่มข้อมูลในตาราง payment
		$payment_sql = "INSERT INTO payment(id, Name, Email, Phone, RoomType, Count, NoofRoom, cin, cout, noofdays, roomtotal, finaltotal, stat) 
					   VALUES ('$id', '$Name', '$Email', '$Phone', '$RoomType', '$Count', '$NoofRoom', '$cin', '$cout', '$noofday', '$roomtotal', '$roomtotal', 'Confirm')";
		
		mysqli_query($conn, $payment_sql);
	}

	header("Location: roombook.php");
}

mysqli_close($conn);
?>