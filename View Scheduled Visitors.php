<?php
include_once('PHP/VisitorSchedule.php');
error_reporting(0);
session_start();
//If the employee ID is not set then the page is being accessed without authorization
if (!isset($_SESSION["EmpID"])) {
  header("Location: Login.php");
  exit();
}

//Call the Visitor Schedule class
$visSchObj = new VisitorSchedule();

//Get all the visitor schedule
$accept = $visSchObj->viewVisSchedule();
$visSchID = $accept[0];
$type = $accept[1];
$RepName = $accept[2];
$RepPhoneNo = $accept[3];
$RepEmail = $accept[4];
$RepOcc = $accept[5];
$RepWP = $accept[6];
$noOfPeople = $accept[7];
$date = $accept[8];

//Filter By Date
if (isset($_POST['Search'])) {
  $dates = $_POST['Date'];
  $accept = $visSchObj->searchByDate($dates);
  $visSchIDNew = $accept[0];
  $typeNew = $accept[1];
  $RepNameNew = $accept[2];
  $RepPhoneNoNew = $accept[3];
  $RepEmailNew = $accept[4];
  $RepOccNew = $accept[5];
  $RepWPNew = $accept[6];
  $noOfPeopleNew = $accept[7];
  $dateNew = $accept[8];
}

//Remove a visitors' schedule based on ID
if (isset($_POST['Remove'])) {
  $visSchObj->setVisSchID($_POST['VisSchID']);
  $visSchObj->removeVisitorSch();
}
if (isset($_POST['No'])) {
  echo "<script>alert('Data was not removed');
    document.location = '/View Scheduled Visitors.php' </script>";
}

//To prevent XSS Attack
function _e($string)
{
  echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Visitors Schedules
  </title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Icon -->
  <link rel="shortcut icon" href="CSS/images/dove4.ico" />
  <!-- Accordoin CSS -->
  <link rel="stylesheet" href="CSS/assets/plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="CSS/assets/css/theme.min.css">
  <link rel="stylesheet" href="CSS/assets/css/splash.min.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="CSS/css/bootstrap.min.css">
  <link href="CSS/assets/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome CSS -->
  <link rel="stylesheet" href="CSS/css/font-awesome.min.css">
  <!-- ElegantFonts CSS -->
  <link rel="stylesheet" href="CSS/css/elegant-fonts.css">
  <!-- themify-icons CSS -->
  <link rel="stylesheet" href="CSS/css/themify-icons.css">
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="CSS/css/swiper.min.css">
  <!-- Styles -->
  <link rel="stylesheet" href="CSS/table/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
  <link rel="stylesheet" href="CSS/style.css">
</head>

<body>

  <body><?php include_once("HeaderEmployee.php") ?>
    <hr>
    <div class="container">
      <div>
        <h4 class="card-title" style="text-align:center"><i class="fa fa-clock-o" aria-hidden="true"></i></i>Visitors' Schedule</h4>
      </div>
      <!-- Blog Posts -->
      <div class="row">
        <div class="col-md-12">
          <div class="card" style="padding:0px 28px;">
            <h4 class="card-title">Search By Date: </h4>
            <form class="form-horizontal row-fluid" action="View Scheduled Visitors.php" method="POST">
              <div class="control-group">
                <label class="control-label" for="">Date</label>
                <div class="controls">
                  <input type="date" id="date" placeholder="" name="Date" class="span12">
                </div>
              </div>
              <div><button type="submit" name="Search">Search</button></div>
            </form>
          </div>
        </div>
      </div>
      <ul class="accordion">
        <li class="active">
          <div class="accordion-title">
            <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Today's Schedules</h6>
          </div>
          <div class="accordion-content">
            <div class="card-body">
              <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Organization</th>
                      <th>#People</th>
                      <th>Rep. Name</th>
                      <th>Phone No.</th>
                      <th>Remove</th>
                    </tr>
                  </thead>
                  <tfoot>
                  </tfoot>
                  <tbody>
                    <?php
                    //To display today's visitors
                    for ($i = 0; $i < sizeof($visSchID); $i++) {
                      $today = date("Y-m-d");
                      if ($date[$i] == $today) {
                        echo "<tr>";
                        echo "<td>$date[$i]</td>";
                        echo "<td>";
                        _e($RepWP[$i]);
                        echo "</td>";
                        echo "<td>$noOfPeople[$i]</td>";
                        echo "<td>";
                        _e($RepName[$i]);
                        echo "</td>";
                        echo "<td>$RepPhoneNo[$i]</td>";
                        echo '<td><a href="#modal2" data-toggle="modal" data-id="' . $visSchID[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-close"></i></a></td>';
                        echo "</tr>";
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </li>
        <!--/////////////////////////////-->
        <li>
          <div class="accordion-title">
            <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Recurring Schedules</h6>
          </div>
          <div class="accordion-content">
            <div class="card-body">
              <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Organization</th>
                      <th>#People</th>
                      <th>Rep. Name</th>
                      <th>Phone No.</th>
                      <th>Remove</th>
                    </tr>
                  </thead>
                  <tfoot>
                  </tfoot>
                  <tbody>
                    <?php
                    //To display recurring visitors
                    for ($i = 0; $i < sizeof($visSchID); $i++) {
                      $today = date("Y-m-d");
                      if ($date[$i] > $today) {
                        echo "<tr>";
                        echo "<td>$date[$i]</td>";
                        echo "<td>";
                        _e($RepWP[$i]);
                        echo "</td>";
                        echo "<td>$noOfPeople[$i]</td>";
                        echo "<td>";
                        _e($RepName[$i]);
                        echo "</td>";
                        echo "<td>$RepPhoneNo[$i]</td>";
                        echo '<td><a href="#modal2" data-toggle="modal" data-id="' . $visSchID[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-close"></i></a></td>';
                        echo "</tr>";
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </li>
        <!--/////////////////////////////-->
        <li>
          <div class="accordion-title">
            <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Previous Schedules</h6>
          </div>
          <div class="accordion-content">
            <div class="card-body">
              <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Organization</th>
                      <th>#People</th>
                      <th>Rep. Name</th>
                      <th>Phone No.</th>
                      <th>Remove</th>
                    </tr>
                  </thead>
                  <tfoot>
                  </tfoot>
                  <tbody>
                    <?php
                    //To display previously scheduled visitors
                    for ($i = 0; $i < sizeof($visSchID); $i++) {
                      $today = date("Y-m-d");
                      if ($date[$i] < $today) {
                        echo "<tr>";
                        echo "<td>$date[$i]</td>";
                        echo "<td>";
                        _e($RepWP[$i]);
                        echo "</td>";
                        echo "<td>$noOfPeople[$i]</td>";
                        echo "<td>";
                        _e($RepName[$i]);
                        echo "</td>";
                        echo "<td>$RepPhoneNo[$i]</td>";
                        echo '<td><a href="#modal2" data-toggle="modal" data-id="' . $visSchID[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-close"></i></a></td>';
                        echo "</tr>";
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </li>
        <!--/////////////////////////////-->
        <li>
          <div class="accordion-title">
            <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Filter By Date</h6>
          </div>
          <div class="accordion-content">
            <div class="card-body">
              <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Organization</th>
                      <th>#People</th>
                      <th>Rep. Name</th>
                      <th>Phone No.</th>
                      <th>Remove</th>
                    </tr>
                  </thead>
                  <tfoot>
                  </tfoot>
                  <tbody>
                    <?php
                    //To display filtered schedules
                    for ($i = 0; $i < sizeof($visSchIDNew); $i++) {
                      echo "<tr>";
                      echo "<td>$dateNew[$i]</td>";
                      echo "<td>";
                      _e($RepWPNew[$i]);
                      echo "</td>";
                      echo "<td>$noOfPeopleNew[$i]</td>";
                      echo "<td>";
                      _e($RepNameNew[$i]);
                      echo "</td>";
                      echo "<td>$RepPhoneNoNew[$i]</td>";
                      echo '<td><a href="#modal2" data-toggle="modal" data-id="' . $visSchIDNew[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-close"></i></a></td>';
                      echo "</tr>";
                    }

                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <!-- Modal -->
      <div class="modal" id="modal2" tabindex="-1" role="dialog" aria-hidden="true" style="top:50px; height:fit-content">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header no-bd">
              <h5 class="modal-title">
                <span class="fw-mediumbold"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Confirmation Box
                </span>
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="width:15%">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p class="small" style="padding-left:75px;"> Are you sure you want remove this schedule?</p>
              <form class="multisteps-form__form" action="View Scheduled Visitors.php" method="POST">
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col" style="padding-left:15px; padding-right:15px;">
                      <label>Schedule ID </label>
                      <input class="multisteps-form__input form-control" type="text" id="id" name="VisSchID" />
                    </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer no-bd">
              <button type="submit" class="button-rounded margin-top-20" name="Remove">Yes</button>
              <button type="submit" class="button-rounded margin-top-20" name="No">No</button>
            </div>
            </form>
            <script type='text/javascript' src='Buttons.js'></script>
          </div>
        </div>
      </div>
    </div>
    </div>
    <?php include_once("Footer.html"); ?>
    <!-- Javascript -->
    <script src="CSS/assets/plugins/jquery.min.js"></script>
    <script src="CSS/assets/plugins/plugins.js"></script>
    <script src="CSS/assets/js/functions.min.js"></script>
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
    <script>
      //JQuery Code to close the modal
      $(document).on("click", ".modal-trigger", function() {
        var IDs = $(this).data('id');
        $(".modal-content #id").val(IDs);
      });
    </script>
  </body>

</html>