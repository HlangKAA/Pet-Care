<?php

include '../config.php';
session_start();

// page redirect
$usermail="";
$usermail=$_SESSION['usermail'];
if($usermail == true){

}else{
  header("location: http://localhost/hotelmanage_system/index.php");
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/admin.css">
    <!-- loading bar -->
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="../css/flash.css">
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/roombook.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <title>PET-CARE - Admin</title>
</head>

<body>
    <!-- mobile view -->
    <div id="mobileview">
        <h5>แผงควบคุมไม่แสดงในมุมมองมือถือ</h5>
    </div>
  
    <!-- nav bar -->
    <nav class="uppernav">
        <div class="logo">
            <p>PET-CARE</p>
        </div>
        <div class="logout">
            <a href="../logout.php"><button class="btn btn-primary">ออกจากระบบ</button></a>
        </div>
    </nav>
    <nav class="sidenav">
        <ul>
            <li class="pagebtn active"><img src="../image/icon/dashboard.png">&nbsp;&nbsp;&nbsp;แผงควบคุม</li>
            <li class="pagebtn"><img src="../image/icon/bed.png">&nbsp;&nbsp;&nbsp;การจองห้องพัก</li>
            <li class="pagebtn"><img src="../image/icon/wallet.png">&nbsp;&nbsp;&nbsp;การชำระเงิน</li>            
            <li class="pagebtn"><img src="../image/icon/bedroom.png">&nbsp;&nbsp;&nbsp;ห้องพัก</li>
            <li class="pagebtn"><img src="../image/icon/staff.png">&nbsp;&nbsp;&nbsp;พนักงาน</li>
        </ul>
    </nav>

    <!-- main section -->
    <div class="mainscreen">
        <iframe class="frames frame1 active" src="./dashboard.php" frameborder="0"></iframe>
        <iframe class="frames frame2" src="./roombook.php" frameborder="0"></iframe>
        <iframe class="frames frame3" src="./payment.php" frameborder="0"></iframe>
        <iframe class="frames frame4" src="./room.php" frameborder="0"></iframe>
        <iframe class="frames frame4" src="./staff.php" frameborder="0"></iframe>
    </div>
</body>

<script src="./javascript/script.js"></script>

</html>
