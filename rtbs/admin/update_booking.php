<?php


include('includes/config.php');
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $bookingId = $_POST['bookingId'];
    $bookingDate = $_POST['bookingDate'];
    $bookingTime = $_POST['bookingTime'];
    $noAdults = $_POST['noAdults'];
    $noChildrens = $_POST['noChildrens'];


    $stmt = $con->prepare("CALL UpdateReservation(?, ?, ?, ?, ?)");
    $stmt->bind_param('issii', $bookingId, $bookingDate, $bookingTime, $noAdults, $noChildrens);

    if ($stmt->execute()) {
        
        $_SESSION['update_success'] = true;
        header("Location: booking-details.php?bid=" . $bookingId);
        exit();
    } else {
      
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>