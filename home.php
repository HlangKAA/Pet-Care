<?php

include 'config.php';
session_start();

// page redirect
$usermail="";
$usermail=$_SESSION['usermail'];
if($usermail == true){

}else{
  header("location: index.php");
}

?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/home.css">
    <title>PET-CARE</title>  
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="./admin/css/roombook.css">
</head>

<body>
  <nav>
    <div class="logo">
      <p>PET-CARE</p>
    </div>
    <ul>
      <li><a href="#firstsection">หน้าแรก</a></li>
      <li><a href="#secondsection">ห้องพัก</a></li>
      <li><a href="#thirdsection">สิ่งอำนวยความสะดวก</a></li>
      <li><button class="btn btn-danger" onclick="window.location.href='./logout.php'">ออกจากระบบ</button></li>
    </ul>
  </nav>

  <section id="firstsection" class="carousel slide carousel_section" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="carousel-image" src="./image/cathotel1.png">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/cathotel2.png">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/doghotel1.png">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/doghotel2.png">
        </div>

        <div class="welcomeline">
          <h1 class="welcometag">Welcome to PET-CARE</h1>
        </div>

      <!-- bookbox -->
      <div id="guestdetailpanel">
        <form action="" method="POST" class="guestdetailpanelform" enctype="multipart/form-data">
            <div class="head">
                <h3>การจองห้องพัก</h3>
                <i class="fa-solid fa-circle-xmark" onclick="closebox()"></i>
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
                    <select name="AnimalType" class="selectinput" id="animalSelect" onchange="updateRoomOptions()" required>
                        <option value selected>เลือกประเภทสัตว์</option>
                        <option value="แมว">แมว</option>
                        <option value="หมา">หมา</option>
                    </select>
                    <select name="RoomType" class="selectinput" id="roomSelect" disabled required>
                        <option value selected>เลือกประเภทห้อง</option>
                    </select>
                    <select name="Count" class="selectinput" required>
                        <option value selected>จำนวนสัตว์เลี้ยง</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                    <select name="NoofRoom" class="selectinput" required>
                        <option value selected>จำนวนห้อง</option>
                        <option value="1">1</option>
                    </select>
                    <div class="datesection">
                        <span>
                            <label for="cin">วันที่เข้าพัก</label>
                            <input name="cin" type="date" id="checkIn" required>
                        </span>
                        <span>
                            <label for="cout">วันที่ออก</label>
                            <input name="cout" type="date" id="checkOut" required>
                        </span>
                    </div>
                </div>
            </div>
            <div class="footer">
                <button type="button" class="btn btn-success" onclick="showPaymentDetails()">ดูรายละเอียดราคา</button>
                <button type="button" class="btn btn-danger" onclick="closebox()">ยกเลิก</button>
            </div>
        </form>

        <!-- Payment Details Panel -->
        <div id="paymentDetailsPanel" style="display: none;">
            <!-- <div class="head">
                <h3>รายละเอียดการชำระเงิน</h3>
                <i class="fa-solid fa-circle-xmark" onclick="closePaymentPanel()"></i>
            </div> -->
            <div class="middle">
                <div class="booking-summary">
                    <h4>สรุปการจอง</h4>
                    <div id="bookingSummary"></div>
                </div>
                <div class="payment-info">
                    <h4>ข้อมูลการชำระเงิน</h4>
                    <div class="qr-code">
                        <img src="./image/QRcode.png" alt="QR Code" style="max-width: 200px;">
                        <p>สแกน QR Code เพื่อชำระเงิน</p>
                    </div>
                    <div class="price-details">
                        <h4>รายละเอียดราคา</h4>
                        <div id="priceDetails"></div>
                    </div>
                </div>
                <div class="payment-upload">
                    <h4>อัพโหลดสลิปการโอนเงิน</h4>
                    <form action="" method="POST" class="payment-form" enctype="multipart/form-data">
                        <input type="hidden" name="Name" id="payment_name">
                        <input type="hidden" name="Email" id="payment_email">
                        <input type="hidden" name="Phone" id="payment_phone">
                        <input type="hidden" name="RoomType" id="payment_roomtype">
                        <input type="hidden" name="Count" id="payment_count">
                        <input type="hidden" name="NoofRoom" id="payment_noofroom">
                        <input type="hidden" name="cin" id="payment_cin">
                        <input type="hidden" name="cout" id="payment_cout">
                        <input type="hidden" name="total_price" id="total_price">
                        
                        <input type="file" name="payment_slip" id="payment_slip" accept="image/*" required>
                        <p class="text-muted">* รองรับไฟล์รูปภาพเท่านั้น (jpg, jpeg, png) ขนาดไม่เกิน 5MB</p>
                        <div class="action-buttons">
                            <button type="button" class="btn-edit" onclick="editBooking()">แก้ไขข้อมูล</button>
                            <button type="submit" name="guestdetailsubmit" class="btn btn-success" onclick="return validateAndSubmit()">ยืนยันการชำระเงิน</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
                $total_price = $_POST['total_price'];

                // Validate all required fields
                if(empty($Name) || empty($Email) || empty($Phone) || empty($RoomType) || 
                   empty($Count) || empty($NoofRoom) || empty($cin) || empty($cout)) {
                    echo "<script>
                        Swal.fire({
                            title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                            text: 'กรุณาตรวจสอบและกรอกข้อมูลที่จำเป็นให้ครบถ้วน',
                            icon: 'warning',
                            confirmButtonText: 'ยืนยัน',
                            allowOutsideClick: false,
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    </script>";
                    exit();
                }

                // Validate dates
                $checkInDate = strtotime($cin);
                $checkOutDate = strtotime($cout);
                $today = strtotime(date('Y-m-d'));

                if($checkInDate < $today) {
                    echo "<script>
                        Swal.fire({
                            title: 'วันที่ไม่ถูกต้อง',
                            text: 'วันที่เข้าพักต้องไม่น้อยกว่าวันปัจจุบัน',
                            icon: 'warning',
                            confirmButtonText: 'ยืนยัน',
                            allowOutsideClick: false,
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    </script>";
                    exit();
                }

                if($checkOutDate <= $checkInDate) {
                    echo "<script>
                        Swal.fire({
                            title: 'วันที่ไม่ถูกต้อง',
                            text: 'วันที่ออกต้องมากกว่าวันที่เข้าพัก',
                            icon: 'warning',
                            confirmButtonText: 'ยืนยัน',
                            allowOutsideClick: false,
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    </script>";
                    exit();
                }

                // Validate room type and count
                $count = intval($Count);
                if($count >= 3 && strpos($RoomType, 'ห้องเล็ก') !== false) {
                    echo "<script>
                        Swal.fire({
                            title: 'ประเภทห้องไม่เหมาะสม',
                            text: 'กรุณาเลือกห้องใหญ่สำหรับสัตว์เลี้ยง 3-4 ตัว',
                            icon: 'warning',
                            confirmButtonText: 'ยืนยัน',
                            allowOutsideClick: false,
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    </script>";
                    exit();
                }

                // Handle file upload
                if (!isset($_FILES["payment_slip"]) || $_FILES["payment_slip"]["error"] != 0) {
                    echo "<script>
                        Swal.fire({
                            title: 'กรุณาอัพโหลดสลิปการโอนเงิน',
                            text: 'ไม่พบไฟล์ที่อัพโหลด',
                            icon: 'warning',
                            confirmButtonText: 'ยืนยัน',
                            allowOutsideClick: false,
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    </script>";
                    exit();
                }

                // Check if image file is actual image or fake image
                $check = getimagesize($_FILES["payment_slip"]["tmp_name"]);
                if($check === false) {
                    echo "<script>
                        Swal.fire({
                            title: 'ไฟล์ไม่ใช่รูปภาพ',
                            text: 'กรุณาอัพโหลดไฟล์รูปภาพเท่านั้น',
                            icon: 'warning',
                            confirmButtonText: 'ยืนยัน',
                            allowOutsideClick: false,
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    </script>";
                    exit();
                }

                // Check file size (max 5MB)
                if ($_FILES["payment_slip"]["size"] > 5000000) {
                    echo "<script>
                        Swal.fire({
                            title: 'ไฟล์มีขนาดใหญ่เกินไป',
                            text: 'ขนาดไฟล์ต้องไม่เกิน 5MB',
                            icon: 'warning',
                            confirmButtonText: 'ยืนยัน',
                            allowOutsideClick: false,
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    </script>";
                    exit();
                }

                // Get file extension
                $file_extension = strtolower(pathinfo($_FILES["payment_slip"]["name"], PATHINFO_EXTENSION));
                
                // Allow certain file formats
                if($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
                    echo "<script>
                        Swal.fire({
                            title: 'รูปแบบไฟล์ไม่ถูกต้อง',
                            text: 'รองรับเฉพาะไฟล์ JPG, JPEG & PNG เท่านั้น',
                            icon: 'warning',
                            confirmButtonText: 'ยืนยัน',
                            allowOutsideClick: false,
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    </script>";
                    exit();
                }

                // Read file content
                $image_data = file_get_contents($_FILES["payment_slip"]["tmp_name"]);
                
                // Calculate number of days
                $daysDiff = ceil(($checkOutDate - $checkInDate) / (60 * 60 * 24));
                
                // Insert into database
                $sta = "Pending";
                $sql = "INSERT INTO roombook(Name,Email,Phone,RoomType,Count,NoofRoom,cin,cout,stat,nodays,payment_slip_image) 
                        VALUES ('$Name','$Email','$Phone','$RoomType','$Count','$NoofRoom','$cin','$cout','$sta',$daysDiff,?)";
                
                // Prepare statement
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $image_data);
                
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>
                        Swal.fire({
                            title: 'จองห้องพักสำเร็จ',
                            text: 'กรุณารอการยืนยันจากเจ้าหน้าที่',
                            icon: 'success',
                            confirmButtonText: 'ยืนยัน',
                            allowOutsideClick: false,
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'home.php';
                            }
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถบันทึกข้อมูลได้',
                            icon: 'error',
                            confirmButtonText: 'ยืนยัน',
                            allowOutsideClick: false,
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    </script>";
                }
                
                mysqli_stmt_close($stmt);
            }
        ?>
          </div>

    </div>
  </section>
    
  <section id="secondsection"> 
    <img src="./image/homeanimatebg.svg">
    <div class="ourroom">
      <h1 class="head">ห้องพักของเรา</h1>
      <div class="roomselect">
        <div class="roombox">   
          <div class="hotelphoto h1">
          </div>
          <div class="roomdata">
            <h2>ห้องเล็ก - แมว</h2>
            <p style="color: white;">ห้องเล็กสำหรับสัตว์เลี้ยง 1 - 2 ตัว</p>
            <button class="btn btn-primary bookbtn" onclick="openbookbox()">จองห้องพัก</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h2">
          </div>
          <div class="roomdata">
            <h2>ห้องใหญ่ - แมว</h2>
            <p style="color: white;">ห้องใหญ่สำหรับสัตว์เลี้ยง 3 - 4 ตัว</p>
            <button class="btn btn-primary bookbtn" onclick="openbookbox()">จองห้องพัก</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h3">
          </div>
          <div class="roomdata">
            <h2>ห้องเล็ก - หมา</h2>
            <p style="color: white;">ห้องเล็กสำหรับสัตว์เลี้ยง 1 - 2 ตัว</p>
            <button class="btn btn-primary bookbtn" onclick="openbookbox()">จองห้องพัก</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h4">
          </div>
          <div class="roomdata">
            <h2>ห้องใหญ่ - หมา</h2>
            <p style="color: white;">ห้องใหญ่สำหรับสัตว์เลี้ยง 3 - 4 ตัว</p>
            <button class="btn btn-primary bookbtn" onclick="openbookbox()">จองห้องพัก</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="thirdsection">
    <h1 class="head">สิ่งอำนวยความสะดวก</h1>
    <div class="facility">
      <div class="box">
        <h2>เครื่องฟอกอากาศ</h2>
      </div>
      <div class="box">
        <h2>มีอาหาร</h2>
      </div>
      <div class="box">
        <h2>พนักงานดูแลสัตว์</h2>
      </div>
      <div class="box">
        <h2>สนามหญ้า</h2>
      </div>
    </div>
  </section>
</body>

<script>
    var bookbox = document.getElementById("guestdetailpanel");
    var paymentPanel = document.getElementById("paymentDetailsPanel");

    // Add date validation
    document.getElementById('checkIn').addEventListener('change', function() {
        const checkIn = new Date(this.value);
        const checkOut = new Date(document.getElementById('checkOut').value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (checkIn < today) {
            swal({
                title: 'วันที่ไม่ถูกต้อง',
                text: 'วันที่เข้าพักต้องไม่น้อยกว่าวันปัจจุบัน',
                icon: 'warning',
            });
            this.value = '';
            return;
        }

        if (checkOut && checkIn >= checkOut) {
            swal({
                title: 'วันที่ไม่ถูกต้อง',
                text: 'วันที่ออกต้องมากกว่าวันที่เข้าพัก',
                icon: 'warning',
            });
            this.value = '';
        }
    });

    document.getElementById('checkOut').addEventListener('change', function() {
        const checkIn = new Date(document.getElementById('checkIn').value);
        const checkOut = new Date(this.value);

        if (checkIn && checkIn >= checkOut) {
            swal({
                title: 'วันที่ไม่ถูกต้อง',
                text: 'วันที่ออกต้องมากกว่าวันที่เข้าพัก',
                icon: 'warning',
            });
            this.value = '';
        }
    });

    openbookbox = () =>{
      bookbox.style.display = "flex";
    }
    closebox = () =>{
      bookbox.style.display = "none";
      // Reset form when closing
      document.querySelector('.guestdetailpanelform').reset();
    }

    function closePaymentPanel() {
        paymentPanel.style.display = "none";
    }

    function showPaymentDetails() {
        const form = document.querySelector('.guestdetailpanelform');
        const formData = new FormData(form);
        const bookingDetails = {
            name: formData.get('Name'),
            email: formData.get('Email'),
            phone: formData.get('Phone'),
            animalType: formData.get('AnimalType'),
            roomType: formData.get('RoomType'),
            count: formData.get('Count'),
            noOfRoom: formData.get('NoofRoom'),
            checkIn: formData.get('cin'),
            checkOut: formData.get('cout')
        };

        // Validate required fields with specific messages
        if (!bookingDetails.name) {
            swal({
                title: 'กรุณากรอกชื่อ-นามสกุล',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }
        if (!bookingDetails.email) {
            swal({
                title: 'กรุณากรอกอีเมล',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }
        if (!bookingDetails.phone) {
            swal({
                title: 'กรุณากรอกเบอร์โทรศัพท์',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }
        if (!bookingDetails.animalType) {
            swal({
                title: 'กรุณาเลือกประเภทสัตว์',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }
        if (!bookingDetails.roomType) {
            swal({
                title: 'กรุณาเลือกประเภทห้อง',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }
        if (!bookingDetails.count) {
            swal({
                title: 'กรุณาเลือกจำนวนสัตว์เลี้ยง',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }
        if (!bookingDetails.noOfRoom) {
            swal({
                title: 'กรุณาเลือกจำนวนห้อง',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }
        if (!bookingDetails.checkIn) {
            swal({
                title: 'กรุณาเลือกวันที่เข้าพัก',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }
        if (!bookingDetails.checkOut) {
            swal({
                title: 'กรุณาเลือกวันที่ออก',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }

        // Validate dates
        const checkInDate = new Date(bookingDetails.checkIn);
        const checkOutDate = new Date(bookingDetails.checkOut);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Check if check-in date is in the past
        if (checkInDate < today) {
            swal({
                title: 'วันที่ไม่ถูกต้อง',
                text: 'วันที่เข้าพักต้องไม่น้อยกว่าวันปัจจุบัน',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }

        // Check if check-out date is before or equal to check-in date
        if (checkOutDate <= checkInDate) {
            swal({
                title: 'วันที่ไม่ถูกต้อง',
                text: 'วันที่ออกต้องมากกว่าวันที่เข้าพัก',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }

        // Validate room type and count
        const count = parseInt(bookingDetails.count);
        if (count >= 3 && bookingDetails.roomType.includes('ห้องเล็ก')) {
            swal({
                title: 'ประเภทห้องไม่เหมาะสม',
                text: 'กรุณาเลือกห้องใหญ่สำหรับสัตว์เลี้ยง 3-4 ตัว',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }

        // Proceed with booking process
        proceedWithBooking(bookingDetails);
    }

    function proceedWithBooking(bookingDetails) {
        // Update hidden inputs in payment form
        document.getElementById('payment_name').value = bookingDetails.name;
        document.getElementById('payment_email').value = bookingDetails.email;
        document.getElementById('payment_phone').value = bookingDetails.phone;
        document.getElementById('payment_roomtype').value = bookingDetails.roomType;
        document.getElementById('payment_count').value = bookingDetails.count;
        document.getElementById('payment_noofroom').value = bookingDetails.noOfRoom;
        document.getElementById('payment_cin').value = bookingDetails.checkIn;
        document.getElementById('payment_cout').value = bookingDetails.checkOut;

        // Calculate number of days and update display
        const checkInDate = new Date(bookingDetails.checkIn);
        const checkOutDate = new Date(bookingDetails.checkOut);
        const diffTime = Math.abs(checkOutDate - checkInDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        // Calculate room price
        let roomPrice = 0;
        switch(bookingDetails.roomType) {
            case 'ห้องเล็ก - แมว':
                roomPrice = 200;
                break;
            case 'ห้องใหญ่ - แมว':
                roomPrice = 300;
                break;
            case 'ห้องเล็ก - หมา':
                roomPrice = 300;
                break;
            case 'ห้องใหญ่ - หมา':
                roomPrice = 500;
                break;
        }

        // Calculate total price
        const totalPrice = roomPrice * diffDays * parseInt(bookingDetails.noOfRoom);

        // Update hidden total price input
        document.getElementById('total_price').value = totalPrice;

        // Update booking summary
        document.getElementById('bookingSummary').innerHTML = `
            <p>ชื่อ-นามสกุล: ${bookingDetails.name}</p>
            <p>อีเมล: ${bookingDetails.email}</p>
            <p>เบอร์โทรศัพท์: ${bookingDetails.phone}</p>
            <p>ประเภทสัตว์: ${bookingDetails.animalType}</p>
            <p>ประเภทห้อง: ${bookingDetails.roomType}</p>
            <p>จำนวนสัตว์เลี้ยง: ${bookingDetails.count} ตัว</p>
            <p>จำนวนห้อง: ${bookingDetails.noOfRoom} ห้อง</p>
            <p>วันที่เข้าพัก: ${bookingDetails.checkIn}</p>
            <p>วันที่ออก: ${bookingDetails.checkOut}</p>
            <p>จำนวนวัน: ${diffDays} วัน</p>
        `;

        // Update price details
        document.getElementById('priceDetails').innerHTML = `
            <p>ราคาห้องต่อวัน: ${roomPrice} บาท</p>
            <p>จำนวนห้อง: ${bookingDetails.noOfRoom} ห้อง</p>
            <p>จำนวนวัน: ${diffDays} วัน</p>
            <p class="total-price">ราคารวมทั้งหมด: ${totalPrice} บาท</p>
        `;

        // Show payment panel
        paymentPanel.style.display = "flex";
    }

    function validateAndSubmit() {
        const paymentSlip = document.getElementById('payment_slip').files[0];
        if (!paymentSlip) {
            swal({
                title: 'กรุณาอัพโหลดสลิปการโอนเงิน',
                text: 'กรุณาอัพโหลดสลิปการโอนเงินเพื่อยืนยันการจอง',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return false;
        }

        // Validate file type and size
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (!validTypes.includes(paymentSlip.type)) {
            swal({
                title: 'รูปแบบไฟล์ไม่ถูกต้อง',
                text: 'รองรับเฉพาะไฟล์ JPG, JPEG & PNG เท่านั้น',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return false;
        }

        if (paymentSlip.size > maxSize) {
            swal({
                title: 'ไฟล์มีขนาดใหญ่เกินไป',
                text: 'ขนาดไฟล์ต้องไม่เกิน 5MB',
                icon: 'warning',
                confirmButtonText: 'ยืนยัน',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return false;
        }

        // Show confirmation dialog
        return new Promise((resolve) => {
            swal({
                title: 'ยืนยันการจอง',
                text: 'คุณต้องการยืนยันการจองใช่หรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ใช่, ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                resolve(result.isConfirmed);
            });
        });
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
        } else if (selectedAnimal === 'หมา') {
            roomSelect.innerHTML += `
                <option value="ห้องเล็ก - หมา">ห้องเล็ก - หมา</option>
                <option value="ห้องใหญ่ - หมา">ห้องใหญ่ - หมา</option>
            `;
        }

        // Enable/disable room select based on animal selection
        roomSelect.disabled = !selectedAnimal;
    }

    function editBooking() {
        // Hide payment panel
        paymentPanel.style.display = "none";
        // Show booking form
        bookbox.style.display = "flex";
    }

    // Add event listener for bed selection
    document.addEventListener('DOMContentLoaded', function() {
        const bedSelect = document.querySelector('select[name="Count"]');
        const roomSelect = document.querySelector('select[name="RoomType"]');
        
        bedSelect.addEventListener('change', function() {
            const selectedAnimal = document.getElementById('animalSelect').value;
            const selectedBed = parseInt(this.value);
            const currentRoomType = roomSelect.value;
            
            if (selectedAnimal) {
                if (selectedBed >= 3) {
                    // If 3-4 pets selected, force room type to large
                    if (selectedAnimal === 'แมว') {
                        roomSelect.value = 'ห้องใหญ่ - แมว';
                    } else {
                        roomSelect.value = 'ห้องใหญ่ - หมา';
                    }
                } else if (selectedBed <= 2) {
                    // If 1-2 pets selected, allow both room types
                    if (!currentRoomType.includes('ห้องใหญ่')) {
                        if (selectedAnimal === 'แมว') {
                            roomSelect.value = 'ห้องเล็ก - แมว';
                        } else {
                            roomSelect.value = 'ห้องเล็ก - หมา';
                        }
                    }
                }
            }
        });
    });
</script>
</html>