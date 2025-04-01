<?php

include '../config.php';

if(isset($_GET['id'])) {
	$id = $_GET['id'];

	// ดึงข้อมูลการจอง
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

		// ดึงรายการห้องว่างตามประเภทที่จอง
		$available_rooms_sql = "SELECT * FROM room WHERE type = '$RoomType' AND status = 'ว่าง'";
		$available_rooms_result = mysqli_query($conn, $available_rooms_sql);
		$available_rooms = array();
		while($room = mysqli_fetch_assoc($available_rooms_result)) {
			$available_rooms[] = $room;
		}

		// ถ้ามีห้องว่างพอ
		if(count($available_rooms) >= $NoofRoom) {
			// แสดงฟอร์มเลือกห้อง
			echo "<!DOCTYPE html>
			<html lang='th'>
			<head>
				<meta charset='UTF-8'>
				<meta name='viewport' content='width=device-width, initial-scale=1.0'>
				<title>เลือกห้อง</title>
				<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet'>
				<style>
					.room-selection {
						max-width: 800px;
						margin: 50px auto;
						padding: 20px;
						background: white;
						border-radius: 10px;
						box-shadow: 0 0 10px rgba(0,0,0,0.1);
					}
					.room-option {
						padding: 15px;
						margin: 10px 0;
						border: 1px solid #ddd;
						border-radius: 5px;
						cursor: pointer;
					}
					.room-option:hover {
						background: #f8f9fa;
					}
					.selected {
						background: #e3f2fd;
						border-color: #2196f3;
					}
				</style>
			</head>
			<body>
				<div class='container'>
					<div class='room-selection'>
						<h2 class='mb-4'>เลือกห้องสำหรับการจอง</h2>
						<form method='POST' action=''>
							<input type='hidden' name='booking_id' value='$id'>";

			// แสดงตัวเลือกห้อง
			for($i = 0; $i < $NoofRoom; $i++) {
				echo "<div class='mb-3'>
						<label class='form-label'>ห้องที่ " . ($i + 1) . "</label>
						<select name='selected_rooms[]' class='form-select' required>";
				foreach($available_rooms as $room) {
					echo "<option value='" . $room['id'] . "'>ห้อง " . $room['room_number'] . "</option>";
				}
				echo "</select>
					</div>";
			}

			echo "<div class='mt-4'>
					<button type='submit' name='confirm_booking' class='btn btn-primary'>ยืนยันการจอง</button>
					<a href='roombook.php' class='btn btn-secondary'>ยกเลิก</a>
				</div>
			</form>
			</div>
		</div>
		</body>
		</html>";
		} else {
			echo "<script>
					alert('ไม่มีห้องว่างเพียงพอสำหรับการจองนี้');
					window.location.href='roombook.php';
				  </script>";
		}
	} else {
		echo "<script>
				alert('ไม่พบข้อมูลการจอง');
				window.location.href='roombook.php';
			  </script>";
	}
}

// จัดการการยืนยันการจอง
if(isset($_POST['confirm_booking']) && isset($_POST['selected_rooms'])) {
	$booking_id = $_POST['booking_id'];
	$selected_rooms = $_POST['selected_rooms'];

	// อัพเดทสถานะในตาราง roombook เป็น Confirm
	$sql = "UPDATE roombook SET stat = 'Confirm' WHERE id = '$booking_id'";
	$result = mysqli_query($conn, $sql);

	// เก็บเลขห้องที่จองทั้งหมด
	$room_numbers = array();
	
	// บันทึกข้อมูลห้องที่จองลงในตาราง room_assigned
	foreach($selected_rooms as $room_id) {
		// บันทึกการจองห้อง
		$room_assigned_sql = "INSERT INTO room_assigned (booking_id, room_id) VALUES ('$booking_id', '$room_id')";
		mysqli_query($conn, $room_assigned_sql);
		
		// อัพเดทสถานะห้อง
		$update_room_sql = "UPDATE room SET status = 'ไม่ว่าง' WHERE id = '$room_id'";
		mysqli_query($conn, $update_room_sql);

		// ดึงเลขห้อง
		$room_sql = "SELECT room_number FROM room WHERE id = '$room_id'";
		$room_result = mysqli_query($conn, $room_sql);
		$room_data = mysqli_fetch_assoc($room_result);
		$room_numbers[] = $room_data['room_number'];
	}

	// อัพเดทเลขห้องในตาราง payment
	$room_numbers_str = implode(', ', $room_numbers);
	$update_payment_sql = "UPDATE payment SET room_number = '$room_numbers_str' WHERE id = '$booking_id'";
	mysqli_query($conn, $update_payment_sql);

	// ดึงข้อมูลการจองสำหรับเพิ่มในตาราง payment
	$booking_sql = "SELECT * FROM roombook WHERE id = '$booking_id'";
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
		$noofday = date_diff(date_create($cin), date_create($cout))->days;

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

		$roomtotal = intval($type_of_room) * intval($noofday) * intval($NoofRoom);

		// เพิ่มข้อมูลในตาราง payment
		$payment_sql = "INSERT INTO payment(id, Name, Email, Phone, RoomType, Count, NoofRoom, cin, cout, noofdays, roomtotal, finaltotal, stat) 
					   VALUES ('$booking_id', '$Name', '$Email', '$Phone', '$RoomType', '$Count', '$NoofRoom', '$cin', '$cout', '$noofday', '$roomtotal', '$roomtotal', 'Confirm')";
		
		mysqli_query($conn, $payment_sql);

		echo "<script>
				alert('ยืนยันการจองเรียบร้อยแล้ว');
				window.location.href='roombook.php';
			  </script>";
	}
}

mysqli_close($conn);
?>