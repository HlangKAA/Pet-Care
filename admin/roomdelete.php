<?php

include '../config.php';

$id = $_GET['id'];

// ลบข้อมูลจากตาราง room
$delete_sql = "DELETE FROM room WHERE id = $id";
$delete_result = mysqli_query($conn, $delete_sql);

if (!$delete_result) {
    echo "<script>
            alert('เกิดข้อผิดพลาดในการลบข้อมูล: " . mysqli_error($conn) . "');
            window.location.href='room.php';
          </script>";
    exit;
}

echo "<script>
        alert('ลบห้องเรียบร้อยแล้ว');
        window.location.href='room.php';
      </script>";
?>