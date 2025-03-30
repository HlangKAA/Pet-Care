<?php
include 'config.php';
session_start();

function prepareAndExecute($conn, $sql, $params)
{
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('mysqli error: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    return $stmt;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Sweet Alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <!-- Loading Bar -->
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="./css/flash.css">
    <title>Pet-Care Hotel</title>
</head>

<body>
    <!-- Carousel -->
    <section id="carouselExampleControls" class="carousel slide carousel_section" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="carousel-image" src="./image/cathotel1.jpg">
            </div>
            <div class="carousel-item">
                <img class="carousel-image" src="./image/cathotel2.jpg">
            </div>
            <div class="carousel-item">
                <img class="carousel-image" src="./image/doghotel1.jpg">
            </div>
            <div class="carousel-item">
                <img class="carousel-image" src="./image/doghotel2.jpg">
            </div>
        </div>
    </section>

    <!-- Main Section -->
    <section id="auth_section">
        <div class="logo">
            <!-- <img class="bluebirdlogo" src="./image/bluebirdlogo.png" alt="logo"> -->
            <p>Pet-Care</p>
        </div>
        <div class="auth_container">
            <!-- Login -->
            <div id="Log_in">
                <h2>เข้าสู่ระบบ</h2>
                <div class="role_btn">
                    <div class="btns active">ผู้ใช้ทั่วไป</div>
                    <div class="btns">พนักงาน</div>
                </div>

                <!-- User Login -->
                <?php
                if (isset($_POST['user_login_submit'])) {
                    $email = $_POST['Email'];
                    $password = $_POST['Password'];
                    $sql = "SELECT * FROM signup WHERE Email = ? AND Password = BINARY ?";
                    $stmt = prepareAndExecute($conn, $sql, [$email, $password]);
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $_SESSION['usermail'] = $email;
                        header("Location: home.php");
                        exit();
                    } else {
                        echo "<script>swal({ title: 'อีเมลหรือรหัสผ่านไม่ถูกต้อง', icon: 'error', });</script>";
                    }
                }
                ?>
                <form class="user_login authsection active" id="userlogin" action="" method="POST">
                    <div class="form-floating">
                        <input type="email" class="form-control" name="Email" placeholder=" ">
                        <label for="Email">อีเมล</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="Password" placeholder=" ">
                        <label for="Password">รหัสผ่าน</label>
                    </div>
                    <button type="submit" name="user_login_submit" class="auth_btn">เข้าสู่ระบบ</button>
                    <div class="footer_line">
                        <h6>ยังไม่มีบัญชี? <span class="page_move_btn" onclick="signuppage()">สมัครสมาชิก</span></h6>
                    </div>
                </form>

                <!-- Employee Login -->
                <?php
                if (isset($_POST['Emp_login_submit'])) {
                    $email = $_POST['Emp_Email'];
                    $password = $_POST['Emp_Password'];
                    $sql = "SELECT * FROM emp_login WHERE Emp_Email = ? AND Emp_Password = BINARY ?";
                    $stmt = prepareAndExecute($conn, $sql, [$email, $password]);
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $_SESSION['usermail'] = $email;
                        header("Location: admin/admin.php");
                        exit();
                    } else {
                        echo "<script>swal({ title: 'อีเมลหรือรหัสผ่านไม่ถูกต้อง', icon: 'error', });</script>";
                    }
                }
                ?>
                <form class="employee_login authsection" id="employeelogin" action="" method="POST">
                    <div class="form-floating">
                        <input type="email" class="form-control" name="Emp_Email" placeholder=" ">
                        <label for="floatingInput">อีเมล</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="Emp_Password" placeholder=" ">
                        <label for="floatingPassword">รหัสผ่าน</label>
                    </div>
                    <button type="submit" name="Emp_login_submit" class="auth_btn">เข้าสู่ระบบ</button>
                </form>
            </div>

            <!-- Sign Up -->
            <?php
            if (isset($_POST['user_signup_submit'])) {
                $username = $_POST['Username'];
                $email = $_POST['Email'];
                $password = $_POST['Password'];
                $cpassword = $_POST['CPassword'];

                if ($username == "" || $email == "" || $password == "") {
                    echo "<script>swal({ title: 'กรุณากรอกข้อมูลให้ครบถ้วน', icon: 'error', });</script>";
                } else {
                    if ($password == $cpassword) {
                        $sql_check = "SELECT * FROM signup WHERE Email = ?";
                        $stmt_check = prepareAndExecute($conn, $sql_check, [$email]);
                        $result = $stmt_check->get_result();

                        if ($result->num_rows > 0) {
                            echo "<script>swal({ title: 'อีเมลนี้มีอยู่ในระบบแล้ว', icon: 'error', });</script>";
                        } else {
                            $sql_insert = "INSERT INTO signup (Username, Email, Password) VALUES (?, ?, ?)";
                            $stmt_insert = prepareAndExecute($conn, $sql_insert, [$username, $email, $password]);

                            if ($stmt_insert->affected_rows > 0) {
                                $_SESSION['usermail'] = $email;
                                header("Location: home.php");
                                exit();
                            } else {
                                echo "<script>swal({ title: 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง', icon: 'error', });</script>";
                            }
                        }
                    } else {
                        echo "<script>swal({ title: 'รหัสผ่านไม่ตรงกัน', icon: 'error', });</script>";
                    }
                }
            }
            ?>
            <div id="sign_up">
                <h2>สมัครสมาชิก</h2>
                <form class="user_signup" id="usersignup" action="" method="POST">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="Username" placeholder=" ">
                        <label for="Username">ชื่อผู้ใช้</label>
                    </div>
                    <div class="form-floating">
                        <input type="email" class="form-control" name="Email" placeholder=" ">
                        <label for="Email">อีเมล</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="Password" placeholder=" ">
                        <label for="Password">รหัสผ่าน</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="CPassword" placeholder=" ">
                        <label for="CPassword">ยืนยันรหัสผ่าน</label>
                    </div>
                    <button type="submit" name="user_signup_submit" class="auth_btn">สมัครสมาชิก</button>
                    <div class="footer_line">
                        <h6>มีบัญชีอยู่แล้ว? <span class="page_move_btn" onclick="loginpage()">เข้าสู่ระบบ</span></h6>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="./javascript/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
