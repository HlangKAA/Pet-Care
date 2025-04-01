<?php
session_start();
include '../config.php';

if (!isset($_GET['id'])) {
    header("HTTP/1.0 404 Not Found");
    exit("ไม่พบรหัสการจอง");
}

$id = $_GET['id'];

// ดึงข้อมูลรูปภาพสลิปการโอนเงิน
$sql = "SELECT payment_slip_image FROM roombook WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if ($row['payment_slip_image']) {
        // ส่งรูปภาพกลับไป
        $image_data = $row['payment_slip_image'];
        
        // ตรวจสอบชนิดรูปภาพ
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->buffer($image_data);
        
        // ตั้งค่า header สำหรับการแสดงรูปภาพ
        header("Content-Type: $mime_type");
        header("Content-Length: " . strlen($image_data));
        
        // แสดงรูปภาพ
        echo $image_data;
        exit;
    }
}

// หากไม่พบรูปภาพ ให้แสดงข้อความแจ้ง
header("HTTP/1.0 404 Not Found");
echo "ไม่พบรูปภาพสลิปการโอนเงิน";
?> 