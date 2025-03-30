<?php

include '../config.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // ลบข้อมูลจากตาราง payment
    $deletesql = "DELETE FROM payment WHERE id = ?";
    $stmt = mysqli_prepare($conn, $deletesql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);
    
    if($result) {
        echo "<script>
            alert('ลบข้อมูลสำเร็จ');
            window.location.href='payment.php';
        </script>";
    } else {
        echo "<script>
            alert('เกิดข้อผิดพลาดในการลบข้อมูล');
            window.location.href='payment.php';
        </script>";
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: payment.php");
}

mysqli_close($conn);
?>