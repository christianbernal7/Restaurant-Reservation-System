<?php session_start();
//error_reporting(0);
// Database Connection
include('admin/includes/config.php');


  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Restaurant Reservation System</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="css/status.css">
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
 


 
<div>

<h1 class="header-w3ls">
  Booking Details</h1>
  <br><br>
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
                <h3 class="card-title">Booking Details</h3>
              </div>
          
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
       
                <tbody>
                <?php
$bid = intval($_GET['bid']);

$stmt = $con->prepare("CALL Getinfobookings(?)");
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
            <th>No of Childs</th>
            <td><?php echo $row['noChildrens'] ?></td>
            <th>Booking Date / Time</th>
            <td><?php echo $row['bookingDate'] ?>/<?php echo $row['bookingTime'] ?></td>
        </tr>
        <tr>
            <th>Posting Date</th>
            <td colspan="3"><?php echo $row['postingDate'] ?></td>
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
        <?php endif; ?>

    <?php
        $cnt++;
    }
    ?>
</tbody>

     
                </table>
              </div>
           
            </div>
        
          </div>
       
        </div>
    
      </div>

    </section>

  </div>




</div>










<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
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

<script src="../dist/js/adminlte.min.js"></script>

<script src="../dist/js/demo.js"></script>

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
