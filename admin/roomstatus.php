<?php
include '../config.php';

if(isset($_GET['id']) && isset($_GET['status'])) {
    $room_id = $_GET['id'];
    $new_status = $_GET['status'];
    
    // ดึงข้อมูลห้อง
    $room_sql = "SELECT * FROM room WHERE id = $room_id";
    $room_result = mysqli_query($conn, $room_sql);
    $room_data = mysqli_fetch_assoc($room_result);
    
    if($room_data) {
        // ตรวจสอบว่าห้องมีการจองอยู่หรือไม่
        $check_booking_sql = "SELECT * FROM roombook WHERE RoomType = '" . $room_data['type'] . "' AND stat = 'Confirm'";
        $check_booking_result = mysqli_query($conn, $check_booking_sql);
        $has_booking = mysqli_num_rows($check_booking_result) > 0;
        
        // ถ้าห้องมีการจองอยู่และต้องการเปลี่ยนเป็นว่าง
        if($has_booking && $new_status == 'ว่าง') {
            echo "<script>
                    alert('ไม่สามารถเปลี่ยนสถานะห้องเป็นว่างได้เนื่องจากมีการจองอยู่');
                    window.location.href='room.php';
                  </script>";
            exit;
        }
        
        // อัพเดทสถานะห้อง
        $update_sql = "UPDATE room SET status = '$new_status' WHERE id = $room_id";
        $result = mysqli_query($conn, $update_sql);
        
        if($result) {
            echo "<script>
                    alert('อัพเดทสถานะห้องเรียบร้อยแล้ว');
                    window.location.href='room.php';
                  </script>";
        } else {
            echo "<script>
                    alert('เกิดข้อผิดพลาดในการอัพเดทสถานะห้อง');
                    window.location.href='room.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('ไม่พบข้อมูลห้อง');
                window.location.href='room.php';
              </script>";
    }
} else {
    echo "<script>
            alert('ข้อมูลไม่ถูกต้อง');
            window.location.href='room.php';
          </script>";
}

mysqli_close($conn);
?> 