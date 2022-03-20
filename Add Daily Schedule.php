<?php
include_once('PHP/DailyTreatment.php');
error_reporting(0);
session_start();
//If the employee ID is not set then the page is being accessed without authorization
if (!isset($_SESSION["EmpID"])) {
  header("Location: Login.php");
  exit();
}
//Call the Daily Treatment class
$dailyTreObj = new DailyTreatment();
//To export information of daily treatment as it expires
if (isset($_POST['Export'])) {
  $dailyTreObj->exportOnExpire();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Daily Schedules</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Icon -->
  <link rel="shortcut icon" href="CSS/images/dove4.ico" />
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="CSS/css/bootstrap.min.css">
  <!-- FontAwesome CSS -->
  <link rel="stylesheet" href="CSS/css/font-awesome.min.css">
  <!-- ElegantFonts CSS -->
  <link rel="stylesheet" href="CSS/css/elegant-fonts.css">
  <!-- themify-icons CSS -->
  <link rel="stylesheet" href="CSS/css/themify-icons.css">
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="CSS/css/swiper.min.css">
  <!-- Styles -->
  <link rel="stylesheet" href="CSS/style.css">
  <!-- Table Styles -->
  <link rel="stylesheet" href="CSS/table/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
</head>

<body>
  <?php include_once("HeaderEmployee.php"); ?>
  <script type='text/javascript' src='Javascript/Daily Schedule.js'></script>

  <body>
    <ul class="nav nav-pills margin-bottom-20">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" onclick="showMedication()" href="#first"><i class="fa fa-medkit"></i>Medication</a>
        <input type="text" id="type" style="display: none;">
      </li>
      <li class="nav-item">
        <a class="nav-link" onclick="showTreatment()" data-toggle="tab" href="#first"><i class="fa fa-wheelchair" aria-hidden="true"></i>Physical Therapy</a>
        <input type="text" id="type" style="display: none;">
      </li>
    </ul>
    <hr>
    <div class="container">
      <div>
        <h4 class="card-title" style="text-align: center;"><i class="fa fa-user-md" aria-hidden="true"></i>Daily Schedule</h4>
      </div>
      <form class="form-horizontal row-fluid" action="Add Daily Schedule.php" method="POST" style="border: none;">
        <div class="control-group">
          <label class="control-label">Employee Shifts:</label>
          <div class="controls">
            <select id="shift" name="shift" class="span10" oninput="adjustTable(this); display()">
              <option value="Please Choose">Please Choose</option>
              <option value="Shift 1">08:00-16:00</option>
              <option value="Shift 2">16:00-24:00</option>
              <option value="Shift 3">00:00-8:00</option>
            </select>
          </div>
        </div>
        <p><span id="txtHint" style="display: none;"></span></p>
      </form>
      <hr>
      <!-- Blog section  -------------------------------------------------------->
      <div class="section">
        <div class="container">
          <!-- Blog Posts -->
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <form class="form-horizontal row-fluid" action="Add Daily Schedule.php" method="post">
                      <table id="basic-datatables" class="display table table-hover">
                        <div class="tab-content">
                          <div class="tab-pane fade show active" id="first">
                            <tbody>
                              <!-- Go to /php/DailyTreatment.php-->
                              <?php
                              $dailyTreObj = new DailyTreatment();
                              $time = date("H:i");
                              $shift = "";
                              if ($time > "08:00" && $time < "16:00") {
                                $shift = "Shift 1";
                              } else if ($time > "16:01" && $time < "23:59") {
                                $shift = "Shift 2";
                              } else {
                                $shift = "Shift 3";
                              }
                              $accept = $dailyTreObj->getDailyTreatment($shift, "Medication");
                              ?>
                            </tbody>
                      </table>
                    </form>
                    <script type='text/javascript' src='Javascript/Daily Schedule.js'></script>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include("Footer.html"); ?>
    <!-- Javascript-->
    <script src="CSS/table/assets/js/jquery.min.js"></script>
    <script src="CSS/table/assets/js/bootstrap.min.js"></script>
    <script src="CSS/table/assets/js/datatables.min.js"></script>

    <script>
      //JQuery Code to display basic database table
      $(document).ready(function() {
        $('#basic-datatables').DataTable({});
        $('#multi-filter-select').DataTable({
          "pageLength": 5,
          initComplete: function() {
            this.api().columns().every(function() {
              var column = this;
              var select = $('<select class="form-control"><option value=""></option></select>')
                .appendTo($(column.footer()).empty())
                .on('change', function() {
                  var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val()
                  );
                  column
                    .search(val ? '^' + val + '$' : '', true, false)
                    .draw();
                });
              column.data().unique().sort().each(function(d, j) {
                select.append('<option value="' + d + '">' + d + '</option>')
              });
            });
          }
        });
      });
    </script>
  </body>

</html>