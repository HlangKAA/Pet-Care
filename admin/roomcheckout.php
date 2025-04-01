<?php
include '../config.php';

if(isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    
    // ดึงข้อมูลการจอง
    $booking_sql = "SELECT rb.*, r.id as room_id, r.room_number 
                    FROM roombook rb 
                    INNER JOIN room_assigned ra ON rb.id = ra.booking_id
                    INNER JOIN room r ON ra.room_id = r.id
                    WHERE rb.id = '$booking_id'";
    $booking_result = mysqli_query($conn, $booking_sql);
    
    if(mysqli_num_rows($booking_result) > 0) {
        // อัพเดทสถานะการจองเป็น Checkout
        $update_booking_sql = "UPDATE roombook SET stat = 'Checkout' WHERE id = '$booking_id'";
        $update_booking_result = mysqli_query($conn, $update_booking_sql);
        
        if($update_booking_result) {
            // อัพเดทสถานะห้องเป็นว่าง
            $room_ids = array();
            
            while($booking_data = mysqli_fetch_assoc($booking_result)) {
                $room_id = $booking_data['room_id'];
                $room_ids[] = $room_id;
            }
            
            // อัพเดทสถานะห้องตามเลขห้องที่จองไว้
            if(!empty($room_ids)) {
                $room_ids_str = implode(',', $room_ids);
                $update_room_sql = "UPDATE room SET status = 'ว่าง' WHERE id IN ($room_ids_str)";
                $update_room_result = mysqli_query($conn, $update_room_sql);
                
                if($update_room_result) {
                    // อัพเดทสถานะในตาราง payment
                    $update_payment_sql = "UPDATE payment SET stat = 'Checkout' WHERE id = '$booking_id'";
                    mysqli_query($conn, $update_payment_sql);
                    
                    echo "<script>
                            alert('เช็คเอาท์เรียบร้อยแล้ว');
                            window.location.href='roombook.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('เกิดข้อผิดพลาดในการอัพเดทสถานะห้อง: " . mysqli_error($conn) . "');
                            window.location.href='roombook.php';
                          </script>";
                }
            } else {
                // กรณีไม่พบข้อมูลห้องที่จอง แต่ผ่านการเช็คแล้วว่ามีการจอง
                // ให้อัพเดทสถานะจากประเภทห้องแทน (วิธีเดิม)
                $booking_sql = "SELECT * FROM roombook WHERE id = '$booking_id'";
                $booking_result = mysqli_query($conn, $booking_sql);
                $booking_data = mysqli_fetch_assoc($booking_result);
                
                $update_room_sql = "UPDATE room SET status = 'ว่าง' WHERE type = '" . $booking_data['RoomType'] . "'";
                $update_room_result = mysqli_query($conn, $update_room_sql);
                
                if($update_room_result) {
                    // อัพเดทสถานะในตาราง payment
                    $update_payment_sql = "UPDATE payment SET stat = 'Checkout' WHERE id = '$booking_id'";
                    mysqli_query($conn, $update_payment_sql);
                    
                    echo "<script>
                            alert('เช็คเอาท์เรียบร้อยแล้ว');
                            window.location.href='roombook.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('เกิดข้อผิดพลาดในการอัพเดทสถานะห้อง');
                            window.location.href='roombook.php';
                          </script>";
                }
            }
        } else {
            echo "<script>
                    alert('เกิดข้อผิดพลาดในการอัพเดทสถานะการจอง');
                    window.location.href='roombook.php';
                  </script>";
        }
    } else {
        // กรณีไม่พบข้อมูลการจองห้องในตาราง room_assigned ให้ใช้วิธีเดิม
        $booking_sql = "SELECT * FROM roombook WHERE id = '$booking_id'";
        $booking_result = mysqli_query($conn, $booking_sql);
        $booking_data = mysqli_fetch_assoc($booking_result);
        
        if($booking_data) {
            // อัพเดทสถานะการจองเป็น Checkout
            $update_booking_sql = "UPDATE roombook SET stat = 'Checkout' WHERE id = '$booking_id'";
            $update_booking_result = mysqli_query($conn, $update_booking_sql);
            
            if($update_booking_result) {
                // อัพเดทสถานะห้องเป็นว่าง
                $update_room_sql = "UPDATE room SET status = 'ว่าง' WHERE type = '" . $booking_data['RoomType'] . "'";
                $update_room_result = mysqli_query($conn, $update_room_sql);
                
                if($update_room_result) {
                    // อัพเดทสถานะในตาราง payment
                    $update_payment_sql = "UPDATE payment SET stat = 'Checkout' WHERE id = '$booking_id'";
                    mysqli_query($conn, $update_payment_sql);
                    
                    echo "<script>
                            alert('เช็คเอาท์เรียบร้อยแล้ว');
                            window.location.href='roombook.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('เกิดข้อผิดพลาดในการอัพเดทสถานะห้อง');
                            window.location.href='roombook.php';
                          </script>";
                }
            } else {
                echo "<script>
                        alert('เกิดข้อผิดพลาดในการอัพเดทสถานะการจอง');
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
} else {
    echo "<script>
            alert('ข้อมูลไม่ถูกต้อง');
            window.location.href='roombook.php';
          </script>";
}

mysqli_close($conn);
?> 