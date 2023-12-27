<?php
include_once('admin/includes/config.php');

if (isset($_POST['submit'])) {
    $p_fullname = $_POST['name'];
    $p_emailid = $_POST['email'];
    $p_phonenumber = $_POST['phonenumber'];
    $p_bookingdate = $_POST['bookingdate'];
    $p_bookingtime = $_POST['bookingtime'];
    $p_noadults = $_POST['noadults'];
    $p_nochildrens = $_POST['nochildrens'];
    $bookingId = mt_rand(100000000, 999999999);

    $stmtName = $con->prepare("CALL CheckNameCount(?, @nameCount)");
    $stmtName->bind_param("s", $p_fullname);
    $stmtName->execute();	
    $stmtName->close();

    $countResult = mysqli_query($con, "SELECT @nameCount as nameCount");
    $nameCount = mysqli_fetch_assoc($countResult)['nameCount'];

    if ($nameCount > 0) {
        echo '<script>alert("Name already exists. Please use a different name.")</script>';
    } else {
        $stmt = $con->prepare("CALL CheckEmailCount(?, @count)");
        $stmt->bind_param("s", $p_emailid);
        $stmt->execute();
        $stmt->close();

        $countResult = mysqli_query($con, "SELECT @count as count");
        $emailCount = mysqli_fetch_assoc($countResult)['count'];

        if ($emailCount > 0) {
            echo '<script>alert("Email already exists. Please use a different email.")</script>';
        } else {
            $query = mysqli_query($con, "CALL InsertBooking('$bookingId', '$p_fullname','$p_emailid','$p_phonenumber','$p_bookingdate','$p_bookingtime','$p_noadults','$p_nochildrens')");

            if ($query) {
                echo '<script>alert("Your order sent successfully. Booking number is ' . $bookingId . '")</script>';
                echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
            } else {
                echo '<script>alert("Something went wrong. Please try again.")</script>';
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<title>Restaurant Reservation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content=""
	/>
	<script type="application/x-javascript">
		addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); }
	</script>
	<link href="css/style.css" rel='stylesheet' type='text/css' media="all">
	<link rel="stylesheet" href="css/jquery-ui.css" />
	<link href="css/wickedpicker.css" rel="stylesheet" type='text/css' media="all" />
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;600;700&display=swap" rel="stylesheet">
</head>

<body>


<section id="header">
        <a href="../rtbs/landingpage.php"><img src="images/logo2.png" class="logo"alt=""></a>

        <div>
            <ul id="navbar">
                <li><a class="active" href="landingpage.php">Home</a></li>
                <li><a href="./about/about.php">About</ax/li>
                <li><a href="contact/contact.php">Contact</a></li>
            </ul>
        </div>
       
        </div>
    </section>

	<h1 class="header-w3ls">
		Table Booking Form</h1>
	<div class="appointment-w3">
		<form action="#" method="post">
			<div class="personal">
			<h8 class="header-w3ls">
		Note: Make sure that your reservation is 2-3 days ahead of the actual date of your reservation. Thank you</h8>
	<br><br>


			<div class="main">
					<div class="form-left-w3l">

						<input type="text" class="top-up" name="name" placeholder="Name" required="">
					</div>
					<div class="form-left-w3l">

						<input type="email" name="email" placeholder="Email" required="">
					</div>
					<div class="form-right-w3ls ">

						<input class="buttom" type="text" name="phonenumber" placeholder="Phone Number" required="">
						<div class="clearfix"></div>
					</div>
				</div>
				
			</div>
			<div class="information">
				
				
				<div class="main">
	
					
					<div class="form-left-w3l">
						<input id="datepicker" name="bookingdate" type="text" placeholder="Booking Date &" required="">
						<input type="text" id="timepicker" name="bookingtime" placeholder="Time" required=""
						 onkeypress="return true;" maxlength="10">
						<div class="clear"></div>
					</div>
				</div>
				
				<div class="main">

					<div class="form-left-w3l">
						<select class="form-control" name="noadults" required>
					<option value="">Number of Adults</option>
						<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					</select>
					</div>
					<div class="form-right-w3ls">
						<select class="form-control" name="nochildrens" required>
					<option value="">Number of Children</option>
					<option value="1">0</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					</select>
					</div>
				</div>
				
			</div>
			
			<div class="btnn">
				<input type="submit" value="Reserve a Table" name="submit">
			</div>
					<div class="copy">
		<p>Check Booking <a href="check-status.php" target="_blank">Status</a></p>
	</div>
	</div>
</div>
<br><br><br><br><br><br><br><br><br>
<hr size="10" color="black">

	<script type='text/javascript' src='js/jquery-2.2.3.min.js'></script>


	<script>
  $(function() {
    $('#datepicker').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true,
      yearRange: '2023:2030'
    });
  });
		</script>


	<script src="js/jquery-ui.js"></script>
	<script>
		$(function () {
			$("#datepicker,#datepicker1,#datepicker2,#datepicker3").datepicker();
		});
	</script>

	<script type="text/javascript" src="js/wickedpicker.js"></script>
	<script type="text/javascript">
		$('.timepicker,.timepicker1').wickedpicker({ twentyFour: false });
	</script>

</body>

</html>