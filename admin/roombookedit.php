<?php

include '../config.php';

// fetch room data
$id = $_GET['id'];

$sql ="Select * from roombook where id = '$id'";
$re = mysqli_query($conn,$sql);
while($row=mysqli_fetch_array($re))
{
    $Name = $row['Name'];
    $Email = $row['Email'];
    $Phone = $row['Phone'];
    $cin = $row['cin'];
    $cout = $row['cout'];
    $noofday = $row['nodays'];
    $stat = $row['stat'];
}

if (isset($_POST['guestdetailedit'])) {
    $EditName = $_POST['Name'];
    $EditEmail = $_POST['Email'];
    $EditPhone = $_POST['Phone'];
    $EditRoomType = $_POST['RoomType'];
    $EditCount = $_POST['Count'];
    $EditNoofRoom = $_POST['NoofRoom'];
    $Editcin = $_POST['cin'];
    $Editcout = $_POST['cout'];

    $sql = "UPDATE roombook SET Name = '$EditName', Email = '$EditEmail', Phone = '$EditPhone', RoomType = '$EditRoomType', Count = '$EditCount', NoofRoom = '$EditNoofRoom', cin = '$Editcin', cout = '$Editcout', nodays = datediff('$Editcout','$Editcin') WHERE id = '$id'";

    $result = mysqli_query($conn, $sql);

    $type_of_room = 0;
    if($EditRoomType == "ห้องเล็ก - แมว") {
        $type_of_room = 3000;
    } else if($EditRoomType == "ห้องใหญ่ - แมว") {
        $type_of_room = 2000;
    } else if($EditRoomType == "ห้องเล็ก - หมา") {
        $type_of_room = 1500;
    } else if($EditRoomType == "ห้องใหญ่ - หมา") {
        $type_of_room = 1000;
    }
    
    // คำนวณจำนวนวัน
    $Editnoofday = date_diff(date_create($Editcin), date_create($Editcout))->days;
    
    // คำนวณราคารวม
    $editttot = intval($type_of_room) * intval($Editnoofday) * intval($EditNoofRoom);
    $editfintot = $editttot;

    $psql = "UPDATE payment SET Name = '$EditName', Email = '$EditEmail', RoomType = '$EditRoomType', Count = '$EditCount', NoofRoom = '$EditNoofRoom', cin = '$Editcin', cout = '$Editcout', noofdays = '$Editnoofday', roomtotal = '$editttot', finaltotal = '$editfintot' WHERE id = '$id'";

    $paymentresult = mysqli_query($conn, $psql);

    if ($paymentresult) {
        header("Location: roombook.php");
    }
}
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- sweet alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./css/roombook.css">
    <style>
        #editpanel{
            position : fixed;
            z-index: 1000;
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            /* align-items: center; */
            background-color: #00000079;
        }
        #editpanel .guestdetailpanelform{
            height: 620px;
            width: 1170px;
            background-color: #ccdff4;
            border-radius: 10px;  
            /* temp */
            position: relative;
            top: 20px;
            animation: guestinfoform .3s ease;
        }

    </style>
    <title>Document</title>
</head>
<body>
    <div id="editpanel">
        <form method="POST" class="guestdetailpanelform">
            <div class="head">
                <h3>แก้ไขการจอง</h3>
                <a href="./roombook.php"><i class="fa-solid fa-circle-xmark"></i></a>
            </div>
            <div class="middle">
                <div class="guestinfo">
                    <h4>ข้อมูลผู้จอง</h4>
                    <input type="text" name="Name" placeholder="กรอกชื่อ-นามสกุล" value="<?php echo $Name ?>">
                    <input type="email" name="Email" placeholder="กรอกอีเมล" value="<?php echo $Email ?>">
                    <input type="text" name="Phone" placeholder="กรอกเบอร์โทรศัพท์" value="<?php echo $Phone ?>">
                </div>

                <div class="line"></div>

                <div class="reservationinfo">
                    <h4>ข้อมูลการจอง</h4>
                    <select name="AnimalType" class="selectinput" id="animalSelect" onchange="updateRoomOptions()">
                        <option value selected>เลือกประเภทสัตว์เลี้ยง</option>
                        <option value="แมว">แมว</option>
                        <option value="หมา">หมา</option>
                    </select>
                    <select name="RoomType" class="selectinput" id="roomSelect">
                        <option value selected>เลือกประเภทห้อง</option>
                        <option value="ห้องเล็ก - แมว">ห้องเล็ก - แมว</option>
                        <option value="ห้องใหญ่ - แมว">ห้องใหญ่ - แมว</option>
                        <option value="ห้องเล็ก - หมา">ห้องเล็ก - หมา</option>
                        <option value="ห้องใหญ่ - หมา">ห้องใหญ่ - หมา</option>
                    </select>
                    <select name="Count" class="selectinput">
                        <option value selected>จำนวนสัตว์เลี้ยง</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                    <select name="NoofRoom" class="selectinput">
                        <option value selected>จำนวนห้อง</option>
                        <option value="1">1</option>
                    </select>
                    <div class="datesection">
                        <span>
                            <label for="cin">วันที่เข้าพัก</label>
                            <input name="cin" type="date" value="<?php echo $cin ?>">
                        </span>
                        <span>
                            <label for="cout">วันที่ออก</label>
                            <input name="cout" type="date" value="<?php echo $cout ?>">
                        </span>
                    </div>
                </div>
            </div>
            <div class="footer">
                <button class="btn btn-success" name="guestdetailedit">บันทึก</button>
            </div>
        </form>
    </div>

    <script>
        function updateRoomOptions() {
            const animalSelect = document.getElementById('animalSelect');
            const roomSelect = document.getElementById('roomSelect');
            const selectedAnimal = animalSelect.value;

            // Clear existing options
            roomSelect.innerHTML = '<option value selected>เลือกประเภทห้อง</option>';

            if (selectedAnimal === 'แมว') {
                roomSelect.innerHTML += `
                    <option value="ห้องเล็ก - แมว">ห้องเล็ก - แมว</option>
                    <option value="ห้องใหญ่ - แมว">ห้องใหญ่ - แมว</option>
                `;
            } else if (selectedAnimal === 'หมา') {
                roomSelect.innerHTML += `
                    <option value="ห้องเล็ก - หมา">ห้องเล็ก - หมา</option>
                    <option value="ห้องใหญ่ - หมา">ห้องใหญ่ - หมา</option>
                `;
            }
        }
    </script>
</body>
</html>