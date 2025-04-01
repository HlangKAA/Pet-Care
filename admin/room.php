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
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/room.css">
</head>

<body>
    <div class="addroomsection">
        <form action="" method="POST">
            <label for="room_number">เลขห้อง:</label>
            <input type="text" name="room_number" class="form-control" required>

            <label for="troom">ประเภทห้อง:</label>
            <select name="troom" class="form-control">
                <option value selected>เลือกประเภทห้อง</option>
                <option value="ห้องเล็ก - แมว">ห้องเล็ก - แมว</option>
                <option value="ห้องใหญ่ - แมว">ห้องใหญ่ - แมว</option>
                <option value="ห้องเล็ก - หมา">ห้องเล็ก - หมา</option>
                <option value="ห้องใหญ่ - หมา">ห้องใหญ่ - หมา</option>
            </select>

            <label for="status">สถานะห้อง:</label>
            <select name="status" class="form-control">
                <option value="ว่าง">ว่าง</option>
                <option value="ไม่ว่าง">ไม่ว่าง</option>
                <option value="อยู่ระหว่างซ่อมบำรุง">อยู่ระหว่างซ่อมบำรุง</option>
            </select>

            <button type="submit" class="btn btn-success" name="addroom">เพิ่มห้อง</button>
        </form>

        <?php
        if (isset($_POST['addroom'])) {
            $room_number = $_POST['room_number'];
            $typeofroom = $_POST['troom'];
            $status = $_POST['status'];

            $sql = "INSERT INTO room(room_number, type, status) VALUES ('$room_number', '$typeofroom', '$status')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                header("Location: room.php");
            }
        }
        ?>
    </div>

    <div class="searchsection mb-4">
        <form action="" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search_number" class="form-control" placeholder="ค้นหาด้วยเลขห้อง">
            </div>
            <div class="col-md-3">
                <select name="search_type" class="form-control">
                    <option value="">ทุกประเภทห้อง</option>
                    <option value="ห้องเล็ก - แมว">ห้องเล็ก - แมว</option>
                    <option value="ห้องใหญ่ - แมว">ห้องใหญ่ - แมว</option>
                    <option value="ห้องเล็ก - หมา">ห้องเล็ก - หมา</option>
                    <option value="ห้องใหญ่ - หมา">ห้องใหญ่ - หมา</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="search_status" class="form-control">
                    <option value="">ทุกสถานะ</option>
                    <option value="ว่าง">ว่าง</option>
                    <option value="ไม่ว่าง">ไม่ว่าง</option>
                    <option value="อยู่ระหว่างซ่อมบำรุง">อยู่ระหว่างซ่อมบำรุง</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </form>
    </div>

    <div class="room">
        <?php
        $where_conditions = [];
        if (isset($_GET['search_number']) && !empty($_GET['search_number'])) {
            $where_conditions[] = "room_number LIKE '%" . $_GET['search_number'] . "%'";
        }
        if (isset($_GET['search_type']) && !empty($_GET['search_type'])) {
            $where_conditions[] = "type = '" . $_GET['search_type'] . "'";
        }
        if (isset($_GET['search_status']) && !empty($_GET['search_status'])) {
            $where_conditions[] = "status = '" . $_GET['search_status'] . "'";
        }

        $sql = "SELECT * FROM room";
        if (!empty($where_conditions)) {
            $sql .= " WHERE " . implode(" AND ", $where_conditions);
        }
        $sql .= " ORDER BY CAST(room_number AS UNSIGNED) ASC";
        
        $re = mysqli_query($conn, $sql);
        ?>
        <?php
        while ($row = mysqli_fetch_array($re)) {
            $id = $row['type'];
            $status_class = '';
            switch($row['status']) {
                case 'ว่าง':
                    $status_class = 'text-success';
                    break;
                case 'ไม่ว่าง':
                    $status_class = 'text-danger';
                    break;
                case 'อยู่ระหว่างซ่อมบำรุง':
                    $status_class = 'text-warning';
                    break;
            }
            
            // ตรวจสอบว่าห้องมีการจองอยู่หรือไม่
            $check_booking_sql = "SELECT rb.* 
                                FROM roombook rb 
                                INNER JOIN room_assigned ra ON rb.id = ra.booking_id 
                                WHERE ra.room_id = " . $row['id'] . " 
                                AND rb.stat = 'Confirm'";
            $check_booking_result = mysqli_query($conn, $check_booking_sql);
            if($check_booking_result) {
                $has_booking = mysqli_num_rows($check_booking_result) > 0;
            } else {
                $has_booking = false;
            }
            
            // ตรวจสอบว่าห้องว่างหรือไม่
            $is_available = $row['status'] == 'ว่าง';
            
            if ($id == "ห้องเล็ก - แมว") {
                echo "<div class='roombox roomboxsuperior'>
                        <div class='text-center no-boder'>
                            <img src='../image/catlogo.png' alt='Cat Room' style='width: 64px; height: 64px; margin-bottom: 15px;'>
                            <h3>ห้อง " . $row['room_number'] . "</h3>
                            <h4>" . $row['type'] . "</h4>
                            <p class='" . $status_class . "'>" . $row['status'] . "</p>
                            <div class='btn-group'>
                                <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    จัดการสถานะ
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><a class='dropdown-item' href='roomstatus.php?id=" . $row['id'] . "&status=ว่าง'>เปลี่ยนเป็นว่าง</a></li>
                                    <li><a class='dropdown-item' href='roomstatus.php?id=" . $row['id'] . "&status=ไม่ว่าง'>เปลี่ยนเป็นไม่ว่าง</a></li>
                                    <li><a class='dropdown-item' href='roomstatus.php?id=" . $row['id'] . "&status=อยู่ระหว่างซ่อมบำรุง'>เปลี่ยนเป็นอยู่ระหว่างซ่อมบำรุง</a></li>
                                </ul>
                            </div>";
                if ($is_available && !$has_booking) {
                    echo "<a href='roomdelete.php?id=". $row['id'] ."'><button class='btn btn-danger'>Delete</button></a>";
                }
                echo "</div>
                    </div>";
            } else if ($id == "ห้องใหญ่ - แมว") {
                echo "<div class='roombox roomboxdeluxe'>
                        <div class='text-center no-boder'>
                            <img src='../image/catlogo.png' alt='Cat Room' style='width: 64px; height: 64px; margin-bottom: 15px;'>
                            <h3>ห้อง " . $row['room_number'] . "</h3>
                            <h4>" . $row['type'] . "</h4>
                            <p class='" . $status_class . "'>" . $row['status'] . "</p>
                            <div class='btn-group'>
                                <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    จัดการสถานะ
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><a class='dropdown-item' href='roomstatus.php?id=" . $row['id'] . "&status=ว่าง'>เปลี่ยนเป็นว่าง</a></li>
                                    <li><a class='dropdown-item' href='roomstatus.php?id=" . $row['id'] . "&status=ไม่ว่าง'>เปลี่ยนเป็นไม่ว่าง</a></li>
                                    <li><a class='dropdown-item' href='roomstatus.php?id=" . $row['id'] . "&status=อยู่ระหว่างซ่อมบำรุง'>เปลี่ยนเป็นอยู่ระหว่างซ่อมบำรุง</a></li>
                                </ul>
                            </div>";
                if ($is_available && !$has_booking) {
                    echo "<a href='roomdelete.php?id=". $row['id'] ."'><button class='btn btn-danger'>Delete</button></a>";
                }
                echo "</div>
                    </div>";
            } else if ($id == "ห้องเล็ก - หมา") {
                echo "<div class='roombox roomboxguest'>
                        <div class='text-center no-boder'>
                            <img src='../image/doglogo.png' alt='Dog Room' style='width: 64px; height: 64px; margin-bottom: 15px;'>
                            <h3>ห้อง " . $row['room_number'] . "</h3>
                            <h4>" . $row['type'] . "</h4>
                            <p class='" . $status_class . "'>" . $row['status'] . "</p>
                            <div class='btn-group'>
                                <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    จัดการสถานะ
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><a class='dropdown-item' href='roomstatus.php?id=" . $row['id'] . "&status=ว่าง'>เปลี่ยนเป็นว่าง</a></li>
                                    <li><a class='dropdown-item' href='roomstatus.php?id=" . $row['id'] . "&status=ไม่ว่าง'>เปลี่ยนเป็นไม่ว่าง</a></li>
                                    <li><a class='dropdown-item' href='roomstatus.php?id=" . $row['id'] . "&status=อยู่ระหว่างซ่อมบำรุง'>เปลี่ยนเป็นอยู่ระหว่างซ่อมบำรุง</a></li>
                                </ul>
                            </div>";
                if ($is_available && !$has_booking) {
                    echo "<a href='roomdelete.php?id=". $row['id'] ."'><button class='btn btn-danger'>Delete</button></a>";
                }
                echo "</div>
                    </div>";
            } else if ($id == "ห้องใหญ่ - หมา") {
                echo "<div class='roombox roomboxsingle'>
                        <div class='text-center no-boder'>
                            <img src='../image/doglogo.png' alt='Dog Room' style='width: 64px; height: 64px; margin-bottom: 15px;'>
                            <h3>ห้อง " . $row['room_number'] . "</h3>
                            <h4>" . $row['type'] . "</h4>
                            <p class='" . $status_class . "'>" . $row['status'] . "</p>
                            <div class='btn-group'>
                                <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    จัดการสถานะ
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><a class='dropdown-item' href='roomstatus.php?id=" . $row['id'] . "&status=ว่าง'>เปลี่ยนเป็นว่าง</a></li>
                                    <li><a class='dropdown-item' href='roomstatus.php?id=" . $row['id'] . "&status=ไม่ว่าง'>เปลี่ยนเป็นไม่ว่าง</a></li>
                                    <li><a class='dropdown-item' href='roomstatus.php?id=" . $row['id'] . "&status=อยู่ระหว่างซ่อมบำรุง'>เปลี่ยนเป็นอยู่ระหว่างซ่อมบำรุง</a></li>
                                </ul>
                            </div>";
                if ($is_available && !$has_booking) {
                    echo "<a href='roomdelete.php?id=". $row['id'] ."'><button class='btn btn-danger'>Delete</button></a>";
                }
                echo "</div>
                    </div>";
            }
        }
        ?>
    </div>

</body>

</html>