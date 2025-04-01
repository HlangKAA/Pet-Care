<?php
    session_start();
    include '../config.php';


?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PET-CARE - การชำระเงิน</title>
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
	<!-- css for table and search bar -->
	<link rel="stylesheet" href="css/roombook.css">
    <!-- sweet alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
        .exportexcel {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
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
    </style>
</head>
<body>
	<div class="searchsection">
        <input type="text" name="search_bar" id="search_bar" placeholder="Search..." onkeyup="searchFun()">
        <form action="./exportdata.php" method="post">
            <button class="exportexcel" id="exportexcel" name="exportexcel" type="submit"><i class="fa-solid fa-file-arrow-down"></i></button>
        </form>
    </div>

    <div class="roombooktable" class="table-responsive-xl">
        <?php
            // แก้ไข query เพื่อแก้ปัญหา mysqli_result เป็น boolean
            $paymenttablesql = "SELECT p.* FROM payment p ORDER BY p.id ASC";
            $paymentresult = mysqli_query($conn, $paymenttablesql);
            
            // ตรวจสอบความผิดพลาดของ query
            if ($paymentresult === false) {
                echo "Error in query: " . mysqli_error($conn);
            } else {
                $nums = mysqli_num_rows($paymentresult);
            }
        ?>
        <table class="table table-bordered" id="table-data">
            <thead>
                <tr>
                    <th scope="col">ลำดับ</th>
                    <th scope="col">ชื่อ</th>
                    <th scope="col">อีเมล</th>
                    <th scope="col">ประเภทห้อง</th>
                    <th scope="col">เลขห้อง</th>
                    <th scope="col">จำนวนห้อง</th>
                    <th scope="col">วันที่เข้าพัก</th>
                    <th scope="col">วันที่ออก</th>
                    <th scope="col">จำนวนวัน</th>
                    <th scope="col">ราคาต่อวัน</th>
                    <th scope="col">ราคารวม</th>
                    <th scope="col">สถานะ</th>
                    <th scope="col" class="action">จัดการ</th>
                </tr>
            </thead>

            <tbody>
            <?php
            while ($res = mysqli_fetch_array($paymentresult)) {
                // ดึงข้อมูลสถานะจาก roombook
                $booking_sql = "SELECT stat FROM roombook WHERE id = " . $res['id'];
                $booking_result = mysqli_query($conn, $booking_sql);
                $status = "NotConfirm";
                
                if ($booking_result && mysqli_num_rows($booking_result) > 0) {
                    $booking_row = mysqli_fetch_assoc($booking_result);
                    $status = $booking_row['stat'];
                }
                
                // คำนวณราคาต่อวัน
                $price_per_day = 0;
                if($res['RoomType']=="ห้องเล็ก - แมว") {
                    $price_per_day = 200;
                } else if($res['RoomType']=="ห้องใหญ่ - แมว") {
                    $price_per_day = 300;
                } else if($res['RoomType']=="ห้องเล็ก - หมา") {
                    $price_per_day = 300;
                } else if($res['RoomType']=="ห้องใหญ่ - หมา") {
                    $price_per_day = 500;
                }
                
                // กำหนดสีของสถานะ
                $status_class = '';
                if ($status == 'Confirm') {
                    $status_class = 'text-success';
                } else if ($status == 'Checkout') {
                    $status_class = 'text-secondary';
                } else {
                    $status_class = 'text-warning';
                }
            ?>
                <tr>
                    <td><?php echo $res['id'] ?></td>
                    <td><?php echo $res['Name'] ?></td>
                    <td><?php echo $res['Email'] ?></td>
                    <td><?php echo $res['RoomType'] ?></td>
                    <td>N/A</td>
                    <td><?php echo $res['NoofRoom'] ?></td>
                    <td><?php echo $res['cin'] ?></td>
                    <td><?php echo $res['cout'] ?></td>
                    <td><?php echo $res['noofdays'] ?></td>
                    <td><?php echo number_format($price_per_day, 2) ?> บาท</td>
                    <td><?php echo number_format($res['finaltotal'], 2) ?> บาท</td>
                    <td class="<?php echo $status_class ?>"><?php echo $status ?></td>
                    <td class="action">
                        <a href="invoiceprint.php?id=<?php echo $res['id'] ?>" target="_blank">
                            <button class="btn btn-primary"><i class="fa-solid fa-print"></i> พิมพ์</button>
                        </a>
                        <?php if($status != "Checkout") { ?>
                            <a href="paymentdelete.php?id=<?php echo $res['id'] ?>">
                                <button class="btn btn-danger"><i class="fa-solid fa-trash"></i> ลบ</button>
                            </a>
                        <?php } ?>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</body>

<script>
    //search bar logic using js
    const searchFun = () =>{
        let filter = document.getElementById('search_bar').value.toUpperCase();

        let myTable = document.getElementById("table-data");

        let tr = myTable.getElementsByTagName('tr');

        for(var i = 0; i< tr.length;i++){
            let td = tr[i].getElementsByTagName('td')[1];

            if(td){
                let textvalue = td.textContent || td.innerHTML;

                if(textvalue.toUpperCase().indexOf(filter) > -1){
                    tr[i].style.display = "";
                }else{
                    tr[i].style.display = "none";
                }
            }
        }

    }
</script>

</html>