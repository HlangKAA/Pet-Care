<?php
include '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch image data from database
    $sql = "SELECT payment_slip_image FROM roombook WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Set appropriate headers for image display
        header("Content-Type: image/jpeg");
        header("Content-Length: " . strlen($row['payment_slip_image']));
        
        // Output the image data
        echo $row['payment_slip_image'];
    } else {
        // If no image found, show a default image or error message
        header("Content-Type: image/jpeg");
        readfile("../image/no-image.jpg");
    }
    
    mysqli_stmt_close($stmt);
} else {
    // If no ID provided, show error message
    header("Content-Type: image/jpeg");
    readfile("../image/no-image.jpg");
}
?> 