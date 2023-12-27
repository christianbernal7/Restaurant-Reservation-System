<?php session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['aid'])==0)
  { 
header('location:index.php');
}
else{


  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accepted Bookings</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php include_once("includes/navbar.php");?>

 <?php include_once("includes/sidebar.php");?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Accepted Bookings</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Accepted Bookings</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
        

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Bookings Details</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Bookings No</th>
                    <th>Name</th>
                    <th>Email Id</th>
                    <th>Mobile No</th>
                    <th>No. Adults</th>
                    <th>No of Childrens</th>
                    <th>Boking Date/Time</th>
                     <th>Posting Date</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                        $query = mysqli_query($con, "CALL GetAcceptedBookings()");
                        $cnt = 1;

                        while ($result = mysqli_fetch_array($query)) {
                    ?>

                  <tr>
                    <td><?php echo $cnt;?></td>
                    <td><?php echo $result['bookingNo']?></td>
                    <td><?php echo $result['fullName']?></td>
                    <td><?php echo $result['emailId']?></td>
                   <td><?php echo $result['phoneNumber']?></td>
                   <td><?php echo $result['noAdults']?></td>
                    <td><?php echo $result['noChildrens']?></td>
                    <td><?php echo $result['bookingDate']?>/<?php echo $result['bookingTime']?></td>
                    <td><?php echo $result['postingDate']?></td>
                    <th>
     <a href="booking-details.php?bid=<?php echo $result['id'];?>" title="View Details" class="btn btn-primary btn-xm"> View Details</a> 
 </th>
                  </tr>
         <?php $cnt++;} ?>
             
                  </tbody>
                  <tfoot>
   <tr>
              <th>#</th>
                    <th>Bookings No</th>
                    <th>Name</th>
                    <th>Email Id</th>
                    <th>Mobile No</th>
                    <th>No. Adults</th>
                    <th>No of Childrens</th>
                    <th>Boking Date/Time</th>
                     <th>Posting Date</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
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

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
<script src="../dist/js/adminlte.min.js"></script>
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
</body>
</html>
<?php } ?>