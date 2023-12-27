<?php
include('includes/config.php');

if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];

    $stmt = $con->prepare("CALL DeleteBooking(?)");
    $stmt->bind_param("i", $bookingId);

    if ($stmt->execute()) {
        $successMessage = "Booking deleted successfully.";

        $stmt->close();
    } else {
        echo "Error deleting booking: " . $stmt->error;

        $stmt->close();
    }
} else {
    echo "Invalid request.";
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Booking</title>
</head>
<body>

<script>
    <?php
    if (isset($successMessage)) {
        echo "alert('$successMessage');";
        echo "window.location.href = 'all-bookings.php';";
    }
    ?>
</script>

</body>
</html>
