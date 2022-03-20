<?php
include_once('PHP/Daily Activity.php');
error_reporting(0);
session_start();
//If the employee ID is not set then the page is being accessed without authorization
if (!isset($_SESSION["EmpID"])) {
  header("Location: Login.php");
  exit();
}
if (isset($_SESSION["EmpID"])) {
  $empID = $_SESSION["EmpID"];
}

//Call the Daily Activity class
$actObj = new DailyActivity();
//Retrieve all the daily activites
$accept = $actObj->viewActivities();
$actID = $actObj->generateID();
//To add or update an activity
if (isset($_POST['Add']) || isset($_POST['Update'])) {
  $actObj->setActID($_POST['ID']);
  $duration = "";
  if (!empty($_POST['Duration'])) {
    // Loop to store and display values of individual checked checkbox.
    foreach ($_POST['Duration'] as $selected2) {
      $duration .= $selected2 . ",";
    }
  }
  //Set all the vairables of daily activity
  $actObj->setType($_POST['Type']);
  $actObj->setDuration($duration);
  $actObj->setPeopleNeeded($_POST['NoofPeople']);
  $actObj->setStartTime($_POST['ST']);
  $actObj->setEndTime($_POST['ET']);
  $actObj->setoccNeeded($_POST['occNeeded']);
  //Add an activity
  if (isset($_POST['Add'])) {
    $actObj->addActivity($empID);
  }
  //Update an activity
  if (isset($_POST['Update'])) {
    $actObj->updateActivity($empID);
  }
}

//To remove an activity from the list
if (isset($_POST['Remove'])) {
  $actObj->setActID($_POST['ActID']);
  $actObj->removeActivity();
}
if (isset($_POST['No'])) {
  echo "<script>alert('Data was not removed');
    document.location = '/Manage Daily Activity.php' </script>";
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
  <title>Daily Activities</title>
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

  <body>
    <!-- Blog section  -->
    <div class="section">
      <div class="container">
        <!-- Blog Posts -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header" style="display:flex;">
                <h4 class="card-title" style="text-align: center"><i class="fa fa-list-alt" aria-hidden="true"></i>Manage Daily Activities</h4>
                <a href="#modal" data-toggle="modal" data-id="<?php echo "$actID" ?>" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show" style="margin-left:auto;" onclick="buttonAdd()"><i class="fa fa-plus"></i></a>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Act ID</th>
                        <th>Type</th>
                        <th>Duration</th>
                        <th>Number of People</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Work Requirement</th>
                        <th>Emp ID</th>
                        <th>Update</th>
                        <th>Remove</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $actID = $accept[0];
                      $type = $accept[1];
                      $duration = $accept[2];
                      $peopleNeeded = $accept[3];
                      $startTime = $accept[4];
                      $endTime = $accept[5];
                      $occNeeded = $accept[6];
                      $empID = $accept[7];
                      //Dispaly all the added activities
                      for ($i = 0; $i < sizeof($actID); $i++) {
                        echo "<tr>";
                        echo "<td>$actID[$i]</td>";
                        echo "<td>";
                        _e($type[$i]);
                        echo "</td>";
                        echo "<td>$duration[$i]</td>";
                        echo "<td>$peopleNeeded[$i]</td>";
                        echo "<td>$startTime[$i]</td>";
                        echo "<td>$endTime[$i]</td>";
                        echo "<td>";
                        _e($occNeeded[$i]);
                        echo "</td>";
                        echo "<td>$empID[$i]</td>";
                        echo "<td>";
                        echo '<div class="form-button-action">';
                        echo '<a href="#modal" data-toggle="modal" data-id="' . $actID[$i] . '" data-type="' . $type[$i] . '" data-duration="' . $duration[$i] . '" data-NP="' . $peopleNeeded[$i] . '" data-ST="' . $startTime[$i] . '" data-ET="' . $endTime[$i] . '" data-occNeeded="' . $occNeeded[$i] . '" data-empID="' . $empID[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"  onclick="buttonUpdate()"><i class="fa fa-eye"></i></a></td></div>';
                        echo "<td>";
                        echo '<div class="form-button-action">';
                        echo '<a href="#modal2" data-toggle="modal" data-id="' . $actID[$i] . '" data-memid="' . $type[$i] . '" data-type="' . $duration[$i] . '" data-name="' . $peopleNeeded[$i] . '" data-dose="' . $startTime[$i] . '" data-sdate="' . $endTime[$i] . '" data-occneeded="' . $occNeeded[$i] . '" data-empID="' . $empID[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-close"></i></a></td></div>';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <!-- Modal -->
          <div class="modal" id="modal" tabindex="-1" role="dialog" aria-hidden="true" style="top:50px; height:fit-content">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header no-bd">
                  <h5 class="modal-title">
                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                    <span class="fw-mediumbold"> Add/Update Activity
                    </span>
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearCheckBox()">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p class="small"></p>
                  <form class="form-horizontal row-fluid" method="post" action="Manage Daily Activity.php" onload="changeState()">
                    <div class="row">
                      <div class="control-group">
                        <label class="control-label" for="pwd">ID:</label>
                        <div class="controls">
                          <input type="text" id="id" name="ID" class="span12" readonly required>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label" for="Type">Type:</label>
                        <div class="controls">
                          <input type="text" id="type" name="Type" class="span12" maxlength="50" required>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label" for="pwd">Duration:</label>
                        <div class="controls">
                          <label for="name">Daily</label>
                          <input type="checkbox" id="Daily" name="Duration[]" value="Daily" onclick="changeState()">
                          <label for="name">Mon</label>
                          <input type="checkbox" id="Mon" name="Duration[]" value="Monday">
                          <label for="name">Tue</label>
                          <input type="checkbox" id="Tue" name="Duration[]" value="Tuesday">
                          <label for="name">Wed</label>
                          <input type="checkbox" id="Wed" name="Duration[]" value="Wednesday">
                          <label for="name">Thurs</label>
                          <input type="checkbox" id="Thurs" name="Duration[]" value="Thursday">
                          <label for="name">Fri</label>
                          <input type="checkbox" id="Fri" name="Duration[]" value="Friday">
                          <label for="name">Sat</label>
                          <input type="checkbox" id="Sat" name="Duration[]" value="Saturday">
                          <label for="name">Sun</label>
                          <input type="checkbox" id="Sun" name="Duration[]" value="Sunday">
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label" for="pwd">Number of People:</label>
                        <div class="controls">
                          <input type="number" id="NP" name="NoofPeople" class="span12" required>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label" for="pwd">Start Time:</label>
                        <div class="controls">
                          <input type="time" id="ST" name="ST" class="span12" required>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label" for="pwd">End Time:</label>
                        <div class="controls">
                          <input type="time" id="ET" name="ET" class="span12" required>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label" for="pwd">Occupation Req:</label>
                        <div class="controls">
                          <input type="text" id="occNeeded" name="occNeeded" class="span12" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="30" required />
                        </div>
                      </div>
                      <hr>
                      <div class="modal-footer no-bd" style="width:100%">
                        <input type="submit" class="button" id="btn" name="Add" value="Add" style="width:100%;"></button>
                      </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    <div class="card-body">
      <!-- Modal -->
      <div class="modal" id="modal2" tabindex="-1" role="dialog" aria-hidden="true" style="top:50px; height:fit-content">
        <div class="modal-dialog" role="document">
          <div class="modal-content" id="forMember">
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
              <p class="small" style="padding-left:75px;"> Are you sure you want remove this activity?</p>
              <form class="form-horizontal row-fluid" method="post" action="Manage Daily Activity.php" style="border:none;">
                <div class="row">
                  <div class="control-group" style="border:none;">
                    <label class="control-label" for="quantity">Activity ID:</label>
                    <div class="controls">
                      <input type="text" id="id" name="ActID" class="span12" readonly>
                    </div>
                  </div>
                </div>
                <div class="modal-footer no-bd" style="background-color:white;">
                  <button type="submit" class="button-rounded margin-top-20" name="Remove">Yes</button>
                  <button type="submit" class="button-rounded margin-top-20" name="No">No</button>
                </div>
            </div>
            </form>
            <script type='text/javascript' src='Javascript/Buttons.js'></script>
            <script type='text/javascript' src='Javascript/DailyActivity.js'></script>
          </div>
        </div>
      </div>
    </div>
    </div><!-- end row -->
    </div><!-- end container -->
    <!-- end Blog section -->
    <?php include("Footer.html"); ?>

    <!-- Javascript -->
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
      //To display all the values from database on the update dialog box
      $(document).on("click", ".modal-trigger", function() {
        var ID = $(this).data('id');
        var type = $(this).data('type');
        var duration = $(this).data('duration');
        var np = $(this).data('np');
        var st = $(this).data('st');
        var et = $(this).data('et');
        var occNeeded = $(this).data('occneeded');
        var empid = $(this).data('empid');
        $(".modal-content #id").val(ID);
        $("#type").val(type);
        var allIds = ["Mon", "Tue", "Wed", "Thurs", "Fri", "Sat", "Sun", "Daily"];
        if (duration.includes("Daily")) {
          $("#Daily").prop("checked", true);
        } else {
          if (duration.includes("Monday")) {
            $("#Mon").prop("checked", true);
          }
          if (duration.includes("Tuesday")) {
            $("#Tue").prop("checked", true);
          }
          if (duration.includes("Wednesday")) {
            $("#Wed").prop("checked", true);
          }
          if (duration.includes("Thursday")) {
            $("#Thurs").prop("checked", true);
          }
          if (duration.includes("Friday")) {
            $("#Fri").prop("checked", true);
          }
          if (duration.includes("Saturday")) {
            $("#Sat").prop("checked", true);
          }
          if (duration.includes("Sunday")) {
            $("#Sun").prop("checked", true);
          }
        }
        $("#NP").val(np);
        $("#ST").val(st);
        $("#ET").val(et);
        $("#occNeeded").val(occNeeded);
        $("#empid").val(empid);
      });
    </script>
  </body>

</html>