<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['submit'])) {
        $bid = intval($_GET['bid']);
        $estatus = $_POST['status'];
        $oremark = $_POST['officialremak'];
        $tbaleid = isset($_POST['table']) ? $_POST['table'] : null;

        $bdate = $_POST['bdate'];
        $btime = $_POST['btime'];
        $endTime = date("H:i:s", strtotime('+30 minutes', strtotime($btime)));

        $stmtCheckConflict = $con->prepare("CALL GetConflictingReservations(?, ?, ?, ?)");
        $stmtCheckConflict->bind_param("ssis", $btime, $endTime, $tbaleid, $bdate);
        $stmtCheckConflict->execute();
        $resultCheckConflict = $stmtCheckConflict->get_result();

        $count = mysqli_num_rows($resultCheckConflict);
        $stmtCheckConflict->close();

        if ($count > 0) {
            echo "<script>alert('Table already booked for the given Date and Time. Please choose another table');</script>";
        } else {
            $stmtUpdateReservation = $con->prepare("CALL UpdateReservationInfo(?, ?, ?, ?)");
            $stmtUpdateReservation->bind_param("issi", $bid, $oremark, $estatus, $tbaleid);
            $stmtUpdateReservation->execute();
            $stmtUpdateReservation->close();

            if ($stmtUpdateReservation) {
                echo "<script>alert('Booking Details Updated successfully.');</script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again.');</script>";
            }
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking Details</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- Add this to the head of your HTML -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

 

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
<?php include_once("includes/navbar.php");?>
  <!-- /.navbar -->

 <?php include_once("includes/sidebar.php");?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Booking Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Booking Details</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
        

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Booking Details</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
       
                <?php
$bid = intval($_GET['bid']);

// Using prepared statement to call the stored procedure
$stmt = $con->prepare("CALL GetBookingDetailsByID(?)");
$stmt->bind_param("i", $bid);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$cnt = 1;

while ($row = mysqli_fetch_array($result)) {
    ?>
    <tr>
        <th>Booking Number</th>
        <td colspan="3"><?php echo $row['bookingNo'] ?></td>
    </tr>

    <tr>
        <th>Name</th>
        <td><?php echo $row['fullName'] ?></td>
        <th>Email Id</th>
        <td><?php echo $row['emailId'] ?></td>
    </tr>
    <tr>
        <th>Mobile No</th>
        <td><?php echo $row['phoneNumber'] ?></td>
        <th>No of Adults</th>
        <td><?php echo $row['noAdults'] ?></td>
    </tr>
    <tr>
        <th>No of Children</th>
        <td><?php echo $row['noChildrens'] ?></td>
        <th>Booking Date / Time</th>
        <td><?php echo $row['bookingDate'] ?>/<?php echo $row['bookingTime'] ?></td>
    </tr>
    <tr>
        <th>Posting Date</th>
        <td colspan="3"><?php echo isset($row['postingDate']) ? $row['postingDate'] : ''; ?></td>
    </tr>

    <?php if ($row['boookingStatus'] != '') : ?>
        <tr>
            <th>Booking Status</th>
            <td><?php echo $row['boookingStatus'] ?></td>
            <th>Updation Date</th>
            <td><?php echo $row['updationDate'] ?></td>
        </tr>

        <tr>
            <th>Remark</th>
            <td colspan="3"><?php echo $row['adminremark'] ?></td>
        </tr>

        <?php if ($row['boookingStatus'] != 'Rejected') : ?>
            <tr>
                <td colspan="4" style="text-align:center;">
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updateModal">Update Booking</button>
                </td>
            </tr>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($row['boookingStatus'] == '') : ?>
        <tr>
            <td colspan="4" style="text-align:center;">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Take Action</button>
            </td>
        </tr>
    <?php endif; ?>

    <?php
    $cnt++;
}
?>


                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php include_once('includes/footer.php');?>


</div>
<!-- ./wrapper -->


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Take Action</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="takeaction" method="post">
          <p>
            <select class="form-control" name="status" id="status" onchange="toggleTableSelect()" required>
              <option value="default">Select Booking Status</option>
              <option value="Accepted">Accepted</option>
              <option value="Rejected">Rejected</option>
            </select>
          </p>
          <p id='rtable'>
            <input type="hidden" name="bdate" value="<?php echo $date; ?>">
            <input type="hidden" name="btime" value="<?php echo $btime; ?>">
            
            <select class="form-control" name="table" id="tableSelect" style="display: none;" required>
              <option value="">Select Table</option>
              <?php
                $stmt = $con->prepare("CALL GetTableData()");
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                $cnt = 1;

                while ($row = mysqli_fetch_array($result)) {
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['tableNumber']; ?></option>
              <?php } ?>
            </select>
          </p>
          <p>
            <textarea class="form-control" name="officialremak" placeholder="Official Remark" rows="5" required></textarea>
          </p>
          <input type="submit" class="btn btn-primary" name="submit" value="update">
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleTableSelect() {
    var statusSelect = document.getElementById('status');
    var tableSelect = document.getElementById('tableSelect');

    tableSelect.style.display = (statusSelect.value === 'Accepted') ? 'block' : 'none';

    tableSelect.disabled = (statusSelect.value !== 'Accepted');
  }
</script>


<!-- Update Booking Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Update Booking</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
          $bid = intval($_GET['bid']);
          $query = mysqli_query($con, "CALL GetCustomerReservationInfo($bid)");
            $result = mysqli_fetch_array($query);
        ?>

        <form method="post" action="update_booking.php">
          <div class="form-group">
            <label for="bookingNo">Booking Number</label>
            <input type="text" class="form-control" id="id" name="id" value="<?php echo $result['bookingNo']; ?>" required readonly>
          </div>
          <div class="form-group">
            <label for="fullName">Full Name</label>
            <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo $result['fullName']; ?>" required readonly>
          </div>
          <div class="form-group">
            <label for="emailId">Email Id</label>
            <input type="text" class="form-control" id="emailId" name="emailId" value="<?php echo $result['emailId']; ?>" required readonly>
          </div>
          <div class="form-group">
            <label for="phoneNumber">Phone Number</label>
            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo $result['phoneNumber']; ?>" required readonly>
          </div>
          <div class="form-group">
            <label for="bookingDate">Booking Date</label>
            <input type="text" class="form-control" id="bookingDate" name="bookingDate" value="<?php echo $result['bookingDate']; ?>" required>
          </div>
          <div class="form-group">
            <label for="bookingTime">Booking Time</label>
            <input type="text" class="form-control" id="bookingTime" name="bookingTime" value="<?php echo $result['bookingTime']; ?>" required>
          </div>
          <div class="form-group">
            <label for="noAdults">No of Adults</label>
            <input type="text" class="form-control" id="noAdults" name="noAdults" value="<?php echo $result['noAdults']; ?>" required>
          </div>
          <div class="form-group">
            <label for="noChildrens">No of Children</label>
            <input type="text" class="form-control" id="noChildrens" name="noChildrens" value="<?php echo $result['noChildrens']; ?>" required>
          </div>

          <input type="hidden" name="bookingId" value="<?php echo $result['id']; ?>">

          <button type="submit" class="btn btn-primary">Update Booking</button>
                      <?php
            if (isset($_SESSION['update_success']) && $_SESSION['update_success']) {
                echo '<script>alert("Booking updated successfully!");</script>';
                unset($_SESSION['update_success']); 
            }
?>
        </form>
      </div>
    </div>
  </div>
</div>




<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<script type="text/javascript">

  //For report file
  $('#rtable').hide();
  $(document).ready(function(){
  $('#status').change(function(){
  if($('#status').val()=='Accepted')
  {
  $('#rtable').show();
  jQuery("#table").prop('required',true);  
  }
  else{
  $('#rtable').hide();
  }
})}) 
</script>
</body>
</html>
<?php } ?>