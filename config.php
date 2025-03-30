<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "pet_care_hotel";

$conn = mysqli_connect($host, $username, $password, $database);

if(!$conn){
    die("Cannot connect to the database"); // Changed to a more user-friendly error message
}
// else{
//     echo "<script>alert('connection successfully.')</script>";
// }
?>