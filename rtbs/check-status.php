<?php include_once('admin/includes/config.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Restaurant Reservation System</title>
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

	<link href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
</head>

<body>
	<h1 class="header-w3ls">
		Check Status</h1>
	<div class="appointment-w3">
		<form action="search-result.php" method="post">
			<div class="personal">
			
			<div class="main1">
				<div class="form-left-w3l1">
					<select class="top-up" name="searchdata" required="">
						<option value="" selected>Booking Status</option>
						<option value="Accepted">Accepted</option>
						<option value="Rejected">Rejected</option>
					</select>
				</div>
			</div>
			<div class="btnn">
				<input type="submit" value="Search" name="submit">
			</div>
					<div class="copy">
		<p>Check Booking <a href="check-status.php" target="_blank">Status</a></p>
	</div>
	
	</div>


	
	

	<script type='text/javascript' src='js/jquery-2.2.3.min.js'></script>
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