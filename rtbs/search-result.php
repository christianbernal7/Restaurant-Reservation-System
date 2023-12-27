<?php session_start();
error_reporting(0);
include('admin/includes/config.php');


  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Restaurent Table Booking System | search result</title>


  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="css/status.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body>
<div>




  <div>

  <h1 class="header-w3ls">
		Booking Status</h1>
    <br><br>
        </div>
      </div>
    </section>
    <section>
      <div>
        <div class="row">
          <div class="col-12">
            <div class="card">
        

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Search Details</h3>
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
                  <?php
                      if ($_SERVER["REQUEST_METHOD"] == "POST") {
                          $sdata = $_POST['searchdata'];

                          $stmt = $con->prepare("CALL GetBookingDetails(?)");
                          $stmt->bind_param("s", $sdata);
                          $stmt->execute();

                          $result = $stmt->get_result();

                          $cnt = 1;
                          while ($row = $result->fetch_assoc()) {
                              echo '<tr>';
                              echo '<td>' . $cnt . '</td>';
                              echo '<td>' . $row['bookingNo'] . '</td>';
                              echo '<td>' . $row['fullName'] . '</td>';
                              echo '<td>' . $row['emailId'] . '</td>';
                              echo '<td>' . $row['phoneNumber'] . '</td>';
                              echo '<td>' . $row['noAdults'] . '</td>';
                              echo '<td>' . $row['noChildrens'] . '</td>';
                              echo '<td>' . $row['bookingDate'] . '/' . $row['bookingTime'] . '</td>';
                              echo '<td>' . $row['postingDate'] . '</td>';
                              echo '<th><a href="booking-details.php?bid=' . $row['id'] . '" title="View Details" class="btn btn-primary btn-xm" target="blank"> View Details</a></th>';
                              echo '</tr>';

                              $cnt++;
                          }

                          $stmt->close();
                      }

                      $con->close();
                      ?>

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
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
<?php include_once('includes/footer.php');?>

  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
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
