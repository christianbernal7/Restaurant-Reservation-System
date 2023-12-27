<?php
require_once("includes/config.php");

if (!empty($_POST["username"])) {
    $uname = $_POST["username"];

    $stmt = $con->prepare("CALL CheckUsernameAvailability(?, @result)");
    $stmt->bind_param("s", $uname);
    $stmt->execute();

    $result = $con->query("SELECT @result")->fetch_assoc()['@result'];

    if ($result === 'exists') {
        echo "<span style='color:red'>Username already exists. Try with another username</span>";
        echo "<script>$('#submit').prop('disabled', true);</script>";
    } else {
        echo "<span style='color:green'>Username available for Registration.</span>";
        echo "<script>$('#submit').prop('disabled', false);</script>";
    }

    $stmt->close();
}
?>
