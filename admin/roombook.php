<?php
session_start();
include '../config.php';

// SQL for roombook
$roombooksql = "SELECT * FROM roombook";
$roombookre = mysqli_query($conn, $roombooksql);
$roombooksqldata = mysqli_fetch_all($roombookre, MYSQLI_ASSOC);

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
    <title>PET-CARE - Admin</title>
    <style>
        .searchsection {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background-color: #f8f9fa;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .searchsection input {
            padding: 8px 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 300px;
        }
        .adduser, .exportexcel {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }
        .adduser {
            background-color: #007bff;
            margin-left: 10px;
        }
        .exportexcel {
            background-color: #28a745;
        }
        .table thead th {
            background-color: #4169E1;
            color: white;
            font-weight: bold;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }
        .table tbody td {
            padding: 8px;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }
        .btn {
            margin: 2px;
        }
        .action {
            min-width: 200px;
        }
        .datesection {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .datesection div {
            width: 100%;
        }
        .datesection label {
            display: block;
            margin-bottom: 5px;
        }
        .datesection input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="page-title">การจองห้องพัก</div>
    
    <!-- guestdetailpanel -->

    <div id="guestdetailpanel">
        <form action="" method="POST" class="guestdetailpanelform" enctype="multipart/form-data">
            <div class="head">
                <h3>การจองห้องพัก</h3>
                <i class="fa-solid fa-circle-xmark" onclick="adduserclose()"></i>
            </div>
            <div class="middle">
                <div class="guestinfo">
                    <h4>ข้อมูลผู้จอง</h4>  
                    <input type="text" name="Name" placeholder="กรอกชื่อ-นามสกุล" required>
                    <input type="email" name="Email" placeholder="กรอกอีเมล" required>
                    <input type="text" name="Phone" placeholder="กรอกเบอร์โทรศัพท์" required>
                </div>

                <div class="line"></div>

                <div class="reservationinfo">
                    <h4>ข้อมูลการจอง</h4>
                    <select name="AnimalType" class="selectinput" id="animalSelect" onchange="updateRoomOptions()">
                        <option value selected>เลือกประเภทสัตว์</option>
                        <option value="แมว">แมว</option>
                        <option value="หมา">หมา</option>
                    </select>
                    <select name="RoomType" class="selectinput" id="roomSelect" disabled>
                        <option value selected>เลือกประเภทห้อง</option>
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
                        <div class="mb-3">
                            <label for="cin">วันที่เข้าพัก</label>
                            <input name="cin" type="date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="cout">วันที่ออก</label>
                            <input name="cout" type="date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <button class="btn btn-success" name="guestdetailsubmit">ยืนยันการจอง</button>
            </div>
        </form>

        <?php       
        // <!-- room availablity start-->

        $rsql ="select * from room";
        $rre= mysqli_query($conn,$rsql);
        $r = 0;
        $sc = 0;
        $gh = 0;
        $sr = 0;
        $dr = 0;

        while($rrow=mysqli_fetch_array($rre))
        {
            $r = $r + 1;
            $s = $rrow['type'];
            if($s=="ห้องเล็ก - แมว")
            {
                $sc = $sc+ 1;
            }
            if($s=="ห้องเล็ก - หมา")
            {
                $gh = $gh + 1;
            }
            if($s=="ห้องใหญ่ - หมา" )
            {
                $sr = $sr + 1;
            }
            if($s=="ห้องใหญ่ - แมว" )
            {
                $dr = $dr + 1;
            }
        }

        $csql ="select * from roombook";
        $cre = mysqli_query($conn,$csql);
        $cr =0 ;
        $csc =0;
        $cgh = 0;
        $csr = 0;
        $cdr = 0;
        while($crow=mysqli_fetch_array($cre))
        {
            // เช็คสถานะว่าไม่ใช่ Checkout
            if($crow['stat'] != 'Checkout') {
                $cr = $cr + 1;
                $cs = $crow['RoomType'];
                            
                if($cs=="ห้องเล็ก - แมว")
                {
                    $csc = $csc + 1;
                }
                            
                if($cs=="ห้องเล็ก - หมา" )
                {
                    $cgh = $cgh + 1;
                }
                if($cs=="ห้องใหญ่ - หมา")
                {
                    $csr = $csr + 1;
                }
                if($cs=="ห้องใหญ่ - แมว")
                {
                    $cdr = $cdr + 1;
                }
            }
        }
        // room availablity
        // ห้องเล็ก - แมว =>
        $f1 =$sc - $csc;
        if($f1 <=0 )
        {	
            $f1 = "NO";
        }
        // ห้องเล็ก - หมา =>
        $f2 =  $gh -$cgh;
        if($f2 <=0 )
        {	
            $f2 = "NO";
        }
        // ห้องใหญ่ - หมา =>
        $f3 =$sr - $csr;
        if($f3 <=0 )
        {	
            $f3 = "NO";
        }
        // ห้องใหญ่ - แมว =>
        $f4 =$dr - $cdr; 
        if($f4 <=0 )
        {	
            $f4 = "NO";
        }
        //total available room =>
        $f5 =$r-$cr; 
        if($f5 <=0 )
        {
            $f5 = "NO";
        }
        ?>
        <!-- room availablity end-->

        <!-- ==== room book php ====-->
        <?php       
            if (isset($_POST['guestdetailsubmit'])) {
                $Name = $_POST['Name'];
                $Email = $_POST['Email'];
                $Phone = $_POST['Phone'];
                $RoomType = $_POST['RoomType'];
                $Count = $_POST['Count'];
                $NoofRoom = $_POST['NoofRoom'];
                $cin = $_POST['cin'];
                $cout = $_POST['cout'];

                if($Name == "" || $Email == ""){
                    echo "<script>swal({
                        title: 'Fill the proper details',
                        icon: 'error',
                    });
                    </script>";
                }
                else{
                    $sta = "NotConfirm";
                    $sql = "INSERT INTO roombook(Name,Email,Phone,RoomType,Count,NoofRoom,cin,cout,stat,nodays) VALUES ('$Name','$Email','$Phone','$RoomType','$Count','$NoofRoom','$cin','$cout','$sta',datediff('$cout','$cin'))";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        // เพิ่มข้อมูลลงในตาราง payment
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
                        
                        $noofday = date_diff(date_create($cin), date_create($cout))->days;
                        $roomtotal = intval($type_of_room) * intval($noofday) * intval($NoofRoom);
                        
                        $payment_sql = "INSERT INTO payment(Name,Email,Phone,RoomType,Count,NoofRoom,cin,cout,noofdays,roomtotal,finaltotal) 
                                      VALUES ('$Name','$Email','$Phone','$RoomType','$Count','$NoofRoom','$cin','$cout','$noofday','$roomtotal','$roomtotal')";
                        
                        $payment_result = mysqli_query($conn, $payment_sql);

                        if ($payment_result) {
                            echo "<script>swal({
                                title: 'จองสำเร็จ',
                                text: 'กรุณาชำระเงินตามที่กำหนด',
                                icon: 'success',
                            });
                            </script>";
                        } else {
                            echo "<script>swal({
                                title: 'เกิดข้อผิดพลาดในการบันทึกข้อมูลการชำระเงิน',
                                icon: 'error',
                            });
                            </script>";
                        }
                    } else {
                        echo "<script>swal({
                            title: 'เกิดข้อผิดพลาดในการจอง',
                            icon: 'error',
                        });
                        </script>";
                    }
                }
            }
        ?>
    </div>

    
    <!-- ================================================= -->
    <div class="searchsection">
        <input type="text" name="search_bar" id="search_bar" placeholder="search..." onkeyup="searchFun()">
        <button class="adduser" id="adduser" onclick="adduseropen()"><i class="fa-solid fa-bookmark"></i> Add</button>
        <form action="./exportdata.php" method="post">
            <button class="exportexcel" id="exportexcel" name="exportexcel" type="submit"><i class="fa-solid fa-file-arrow-down"></i></button>
        </form>
    </div>

    <div class="roombooktable" class="table-responsive-xl">
        <?php
            // แก้ไข query เพื่อแก้ปัญหา mysqli_result เป็น boolean
            $roombooktablesql = "SELECT rb.* FROM roombook rb ORDER BY rb.id ASC";
            $roombookresult = mysqli_query($conn, $roombooktablesql);
            
            // ตรวจสอบความผิดพลาดของ query
            if ($roombookresult === false) {
                echo "Error in query: " . mysqli_error($conn);
            } else {
                $nums = mysqli_num_rows($roombookresult);
            }
        ?>
        <table class="table table-bordered" id="table-data">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Type of Room</th>
                    <th scope="col">Room No.</th>
                    <th scope="col">Count</th>
                    <th scope="col">No of Room</th>
                    <th scope="col">Check-In</th>
                    <th scope="col">Check-Out</th>
                    <th scope="col">No of Day</th>
                    <th scope="col">Status</th>
                    <th scope="col">Payment Slip</th>
                    <th scope="col" class="action">Action</th>
                </tr>
            </thead>

            <tbody>
            <?php
            while ($res = mysqli_fetch_array($roombookresult)) {
            ?>
                <tr>
                    <td><?php echo $res['id'] ?></td>
                    <td><?php echo $res['Name'] ?></td>
                    <td><?php echo $res['Email'] ?></td>
                    <td><?php echo $res['Phone'] ?></td>
                    <td><?php echo $res['RoomType'] ?></td>
                    <td>
                        <?php
                        // ดึงเลขห้องจากตาราง room_assigned
                        $room_sql = "SELECT r.room_number 
                                   FROM room_assigned ra 
                                   INNER JOIN room r ON ra.room_id = r.id 
                                   WHERE ra.booking_id = " . $res['id'];
                        $room_result = mysqli_query($conn, $room_sql);
                        $room_numbers = array();
                        while($room = mysqli_fetch_assoc($room_result)) {
                            $room_numbers[] = $room['room_number'];
                        }
                        echo !empty($room_numbers) ? implode(', ', $room_numbers) : 'N/A';
                        ?>
                    </td>
                    <td><?php echo $res['Count'] ?></td>
                    <td><?php echo $res['NoofRoom'] ?></td>
                    <td><?php echo $res['cin'] ?></td>
                    <td><?php echo $res['cout'] ?></td>
                    <td><?php echo $res['nodays'] ?></td>
                    <td><?php echo $res['stat'] ?></td>
                    <td>
                        <?php if($res['payment_slip_image']) { ?>
                            <button class="btn btn-info btn-sm" onclick="viewPaymentSlip(<?php echo $res['id'] ?>)">
                                <i class="fa-solid fa-image"></i> ดูสลิป
                            </button>
                        <?php } else { ?>
                            <span class="text-muted">ไม่มีสลิป</span>
                        <?php } ?>
                    </td>
                    <td class="action">
                        <?php
                            // เพิ่มการแสดงผลสถานะเพื่อตรวจสอบ
                            echo "<!-- Debug: Status = " . $res['stat'] . " -->";
                            
                            if($res['stat'] == "Confirm")
                            {
                                echo "<a href='roomcheckout.php?id=". $res['id'] ."'><button class='btn btn-warning'>Checkout</button></a>";
                            }
                            else if($res['stat'] == "Pending")
                            {
                                echo "<a href='roomconfirm.php?id=". $res['id'] ."'><button class='btn btn-success'>Confirm</button></a>";
                            }
                            else
                            {
                                // แสดงสถานะที่ไม่รู้จัก
                                echo "<span class='text-danger'>สถานะไม่ถูกต้อง: " . $res['stat'] . "</span>";
                            }
                        ?>
                        <a href="roombookedit.php?id=<?php echo $res['id'] ?>"><button class="btn btn-primary">Edit</button></a>
                        <a href="roombookdelete.php?id=<?php echo $res['id'] ?>"><button class='btn btn-danger'>Delete</button></a>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</body>

<!-- Payment Slip Modal -->
<div class="modal fade" id="paymentSlipModal" tabindex="-1" aria-labelledby="paymentSlipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentSlipModalLabel">สลิปการโอนเงิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="paymentSlipImage" src="" alt="Payment Slip" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script src="./javascript/roombook.js"></script>
<script>
    // Function to view payment slip
    function viewPaymentSlip(id) {
        const modal = new bootstrap.Modal(document.getElementById('paymentSlipModal'));
        const img = document.getElementById('paymentSlipImage');
        
        // Set image source to the PHP script that will fetch the image
        img.src = `get_payment_slip.php?id=${id}`;
        
        // Show the modal
        modal.show();
    }

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
            roomSelect.disabled = false;
        } else if (selectedAnimal === 'หมา') {
            roomSelect.innerHTML += `
                <option value="ห้องเล็ก - หมา">ห้องเล็ก - หมา</option>
                <option value="ห้องใหญ่ - หมา">ห้องใหญ่ - หมา</option>
            `;
            roomSelect.disabled = false;
        } else {
            roomSelect.disabled = true;
        }
    }
</script>
</html>
