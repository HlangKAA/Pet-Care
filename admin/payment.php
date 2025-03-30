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
    <title>PET-CARE - Admin</title>
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
	<!-- css for table and search bar -->
	<link rel="stylesheet" href="css/roombook.css">
    <!-- sweet alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
	<div class="searchsection">
        <input type="text" name="search_bar" id="search_bar" placeholder="ค้นหา..." onkeyup="searchFun()">
        <form action="./exportdata.php" method="post">
            <button class="exportexcel" id="exportexcel" name="exportexcel" type="submit"><i class="fa-solid fa-file-arrow-down"></i></button>
        </form>
    </div>

    <div class="roombooktable" class="table-responsive-xl">
        <?php
            $paymenttablesql = "SELECT * FROM payment";
            $paymentresult = mysqli_query($conn, $paymenttablesql);

            $nums = mysqli_num_rows($paymentresult);
        ?>
        <table class="table table-bordered" id="table-data">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">ชื่อ</th>
                    <th scope="col">อีเมล</th>
                    <th scope="col">ประเภทห้อง</th>
                    <th scope="col">จำนวนห้อง</th>
                    <th scope="col">วันที่เข้าพัก</th>
                    <th scope="col">วันที่ออก</th>
                    <th scope="col">จำนวนวัน</th>
                    <th scope="col">ราคาห้อง/วัน</th>
                    <th scope="col">ราคารวม</th>
                    <th scope="col">สลิปการโอนเงิน</th>
                    <th scope="col" class="action">จัดการ</th>
                </tr>
            </thead>

            <tbody>
            <?php
            while ($res = mysqli_fetch_array($paymentresult)) {
                // Get payment slip from roombook table
                $roombook_sql = "SELECT payment_slip_image FROM roombook WHERE id = ?";
                $roombook_stmt = mysqli_prepare($conn, $roombook_sql);
                mysqli_stmt_bind_param($roombook_stmt, "i", $res['id']);
                mysqli_stmt_execute($roombook_stmt);
                $roombook_result = mysqli_stmt_get_result($roombook_stmt);
                $roombook_row = mysqli_fetch_assoc($roombook_result);
                $payment_slip = isset($roombook_row['payment_slip_image']) ? $roombook_row['payment_slip_image'] : null;
                mysqli_stmt_close($roombook_stmt);

                // Calculate price per day
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
            ?>
                <tr>
                    <td><?php echo $res['id'] ?></td>
                    <td><?php echo $res['Name'] ?></td>
                    <td><?php echo $res['Email'] ?></td>
                    <td><?php echo $res['RoomType'] ?></td>
                    <td><?php echo $res['NoofRoom'] ?></td>
                    <td><?php echo $res['cin'] ?></td>
                    <td><?php echo $res['cout'] ?></td>
                    <td><?php echo $res['noofdays'] ?></td>
                    <td><?php echo number_format($price_per_day, 2) ?> บาท</td>
                    <td><?php echo number_format($res['finaltotal'], 2) ?> บาท</td>
                    <td>
                        <?php if($payment_slip) { ?>
                            <button class="btn btn-info btn-sm" onclick="viewPaymentSlip(<?php echo $res['id'] ?>)">
                                <i class="fa-solid fa-image"></i> ดูสลิป
                            </button>
                        <?php } else { ?>
                            <span class="text-muted">ไม่มีสลิป</span>
                        <?php } ?>
                    </td>
                    <td class="action">
                        <a href="invoiceprint.php?id=<?php echo $res['id'] ?>" target="_blank"><button class="btn btn-primary"><i class="fa-solid fa-print"></i> พิมพ์</button></a>
                        <a href="paymentdelete.php?id=<?php echo $res['id'] ?>"><button class='btn btn-danger'>ลบ</button></a>
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

    // Function to view payment slip
    function viewPaymentSlip(id) {
        const modal = new bootstrap.Modal(document.getElementById('paymentSlipModal'));
        const img = document.getElementById('paymentSlipImage');
        
        // Set image source to the PHP script that will fetch the image
        img.src = `get_payment_slip.php?id=${id}`;
        
        // Show the modal
        modal.show();
    }

</script>

</html>