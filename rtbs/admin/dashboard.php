<?php session_start();
// Database Connection
include('includes/config.php');
//Validating Session
if(strlen($_SESSION['aid'])==0)
  { 
header('location:index.php');
}
else{ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">


  <!-- Navbar -->
<?php include_once('includes/navbar.php');?>

  <!-- Main Sidebar Container -->

<?php include_once('includes/sidebar.php');?>
    <!-- Sidebar -->


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">

        <?php if ($_SESSION['utype'] == 1): ?>
    <div class="col-lg-4 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <?php
                // Prepare and execute the stored procedure
                $stmt = $con->prepare("CALL GetSubAdminCount(@subadmin_count)");
                $stmt->execute();

                // Get the result from the stored procedure
                $subadmin_count = $con->query("SELECT @subadmin_count")->fetch_assoc()['@subadmin_count'];

                echo '<h3>' . $subadmin_count . '</h3>';
                ?>
                <p>Sub Admins</p>
            </div>

            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
            <a href="manage-subadmins.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
<?php endif; ?>

          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
            <?php
              $stmt = $con->prepare("CALL GetAllBookingsCount()");
              $stmt->execute();
              $result = $stmt->get_result();
              $stmt->close();

              if ($result->num_rows > 0) {
                  $row = $result->fetch_assoc();
                  $allbookings = $row['totalBookings'];
              } else {
                  $allbookings = 0;
              }
              ?>

              <div class="inner">
                  <h3><?php echo $allbookings;?></h3>
                  <p>All bookings</p>
              </div>

              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="all-bookings.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
            <?php
                $stmt = $con->prepare("CALL GetNewBookingsCount()");
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $newbookings = $row['newBookingsCount'];
                } else {
                    $newbookings = 0;
                }
                ?>

                <div class="inner">
                    <h3><?php echo $newbookings;?></h3>
                    <p>New Bookings</p>
                </div>

              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="new-bookigs.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->


<hr />


          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
            <?php
                $stmt = $con->prepare("CALL GetAcceptedBookingsCount()");
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $acceptedbookings = $row['acceptedBookingsCount'];
                } else {
                    $acceptedbookings = 0;
                }
                ?>

                <div class="inner">
                    <h3><?php echo $acceptedbookings;?></h3>
                    <p>Accepted Bookings</p>
                </div>

              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="accepted-bookings.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
 
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
            <?php
              $stmt = $con->prepare("CALL GetRejectedBookingsCount()");
              $stmt->execute();
              $result = $stmt->get_result();
              $stmt->close();

              if ($result->num_rows > 0) {
                  $row = $result->fetch_assoc();
                  $rejectedbookings = $row['rejectedBookingsCount'];
              } else {
                  $rejectedbookings = 0;
              }
              ?>

              <div class="inner">
                  <h3><?php echo $rejectedbookings;?></h3>
                  <p>Rejected Bookings</p>
              </div>

              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="rejected-bookings.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          


          
<div class="col-lg-4 col-6">
    <div class="small-box bg-primary">
        <?php
        $stmt = $con->prepare("CALL audit_CustomerReservationInfo()");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $auditBookingsCount = $row['auditBookingsCount']; 
        } else {
            $auditBookingsCount = 0;
        }
        ?>
        <div class="inner">
            <h3><?php echo $auditBookingsCount; ?></h3>
            <p>Audit Trail</p>
        </div>
        <div class="icon">
            <i class="ion ion-person"></i>
        </div>
        <a href="audit_trail.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>


<div class="col-lg-4 col-6">
    <div class="small-box bg-info">
        <?php
        $stmt = $con->prepare("CALL view_BackupReservationInfo()");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $backupBookingsCount = $row['backupBookingsCount'];
        } else {
            $backupBookingsCount = 0;
        }
        ?>
        <div class="inner">
            <h3><?php echo $backupBookingsCount; ?></h3>
            <p>Backup Bookings</p>
        </div>
        <div class="icon">
            <i class="ion ion-person"></i>
        </div>
        <a href="Backup_booking.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>


          <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
        <?php
          // Assuming $con is your database connection
          $queryDeleted = mysqli_query($con, "CALL deleted_bookings()");
          $deletedBookingsCount = mysqli_num_rows($queryDeleted);
          ?>
            <div class="inner">
                <h3><?php echo $deletedBookingsCount; ?></h3>
                <p>Deleted Bookings</p>
            </div>
            <div class="icon">
                <i class="ion ion-trash-b"></i>
            </div>
            <a href="deleted_bookings.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
 















     
          <!-- ./col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php include_once('includes/footer.php');?>


</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard.js"></script>
</body>
</html>
<?php } ?>
