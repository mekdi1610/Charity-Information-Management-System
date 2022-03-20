<?php
include_once('PHP/VolunteerSchedule.php');
error_reporting(0);
session_start();
//For the header
if ($_SESSION["FullName"] && $_SESSION["Email"]) {
  $volID = $_SESSION["VolID"];
  $fullName = $_SESSION["FullName"];
  $userName = $_SESSION["UserName"];
  $email = $_SESSION["Email"];
  $photo = $_SESSION["Photo"];
  $Occupation = $_SESSION["Occupation"];
}
//If the volunteer ID is not set then the page is being accessed without authorization
if (!isset($_SESSION["VolID"])) {
  header("Location: Login.php");
  exit();
}
$dates = "";
//Call the Volunteer Schedule class
$volSchObj = new VolunteerSchedule();

//To display all the volunteer's schedule
$accept = $volSchObj->searchVolSchedule($volID);
$volSchID = $accept[0];
$dateTime = $accept[1];
$activity = $accept[2];

//To add volunter's schedule
if (isset($_POST['Schedule'])) {
  $volSchID = $volSchObj->generateID($volID);
  $volSchObj->setVolSchID($volSchID);
  $volSchObj->setDate($_POST["date"] . ' ' . $_POST["Time"]);
  $activity = $_POST["Activity"];
  $volSchObj->addVolSchedule($volID, $activity);
}

//To update a recurring schedule
if (isset($_POST['Update'])) {
  $volSchObj->setVolSchID($_POST['VolSchID']);
  $volSchObj->setDate($_POST["Date"] . ' ' . $_POST["Time"]);
  $activity = $_POST['Activity'];
  $volSchObj->updateVolSchedule($activity);
}

//To remove a schedule and free up the spot
if (isset($_POST['Remove'])) {
  $volSchObj->setVolSchID($_POST['VolSchID']);
  $volSchObj->removeVolSchedule();
}
if (isset($_POST['No'])) {
  echo "<script>alert('Data was not removed');
    document.location = '/Schedule Volunteer.php' </script>";
}

//To prevent XSS Attack
function _e($string)
{
  echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<script>
</script>
<!DOCTYPE html>
<html lang="en">

<head>
  <title> Schedule Volunteer </title>
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
  <link rel="stylesheet" href="CSS/table/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
  <!-- Styles -->
  <link rel='stylesheet' href='CSS/css/bootstrap.minn.css'>
  <!-- For Button CSS Rectangle-->
  <link rel="stylesheet" href="CSS/css/style.css">
  <link rel="stylesheet" href="CSS/style.css">
</head>

<body>
  <?php include_once("HeaderVolunteer.php"); ?>
  </head>

  <body>
    <!--content inner-->
    <div class="content__inner">
      <h3 style="text-align: center"><i class="fa fa-calendar-check-o" aria-hidden="true"></i>Schedule Information</h3>
      <div class="container overflow-hidden">
        <!--multisteps-form-->
        <div class="multisteps-form">
          <!--progress bar-->
          <div class="row">
            <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
              <div class="multisteps-form__progress">
                <button class="multisteps-form__progress-btn js-active" type="button" title="Schedule Info">Add Schedule</button>
                <button class="multisteps-form__progress-btn" type="button" title="View Schedule">View Schedules</button>
              </div>
            </div>
          </div>
          <!--form panels-->
          <div class="row">
            <div class="col-12 col-lg-8 m-auto">
              <form class="multisteps-form__form" action="Schedule Volunteer.php" method="POST" enctype="multipart/form-data">
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideVert">
                  <h4 class="multisteps-form__title">Add Schedule</h4>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Date</label>
                        <input class="multisteps-form__input form-control" type="date" id="date" placeholder="" name="date" oninput="clearDropDown(); getActivityForAdd(); getEventForAdd()" />
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Get Involved with:</label>
                        <select class="multisteps-form__select form-control" id="activity" name="Activity" oninput="getTimeForAdd()">
                          <option value="Please Choose">Please Choose</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col-12 col-sm-6">
                        <label>Start Time:</label>
                        <input class="multisteps-form__input form-control" type="text" id="stime" placeholder="" name="STime" readonly />
                      </div>
                      <div class="col-12 col-sm-6">
                        <label>End Time:</label>
                        <input class="multisteps-form__input form-control" type="text" id="etime" placeholder="" name="STime" readonly />
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Choose Time:</label>
                        <input class="multisteps-form__input form-control" type="time" name="Time" class="span2" ondblclick="checkTime()" />
                      </div>
                    </div>
                    <div class="button-row d-flex mt-4">
                      <button type="submit" title="Send" name="Schedule">Schedule</button>
                    </div>
                  </div>
                </div>
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                  <h3 class="multisteps-form__title">Reschedule</h3>
                  <div class="multisteps-form__content">
                    <div class="card-body">
                      <div class="table-responsive">
                        <form class="form-horizontal row-fluid" action="Add Volunteer Schedule.php" method="post">
                          <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                              <tr>
                                <th>Date</th>
                                <th>Activity</th>
                                <th>Update</th>
                                <th>Remove</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              //To display the recurring volunteer schedules
                              for ($i = 0; $i < sizeof($volSchID); $i++) {
                                $explode = explode(" ", $dateTime[$i]);
                                $date = $explode[0];
                                $time = $explode[1];
                                echo "<tr>";
                                echo "<tr>";
                                echo "<td>$dateTime[$i]</td>";
                                echo "<td>";
                                _e($activity[$i]);
                                echo "</td>";
                                echo "<td>";
                                echo '<a href="#modal" data-toggle="modal" data-id="' . $volSchID[$i] . '" data-date="' . $date . '" data-time="' . $time . '" data-activity="' . $activity[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-eye"></i></a></td>';
                                echo "<td>";
                                echo '<a href="#modal2" data-toggle="modal" data-id="' . $volSchID[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-close"></i></a></td></tr>';
                              }
                              ?>
                            </tbody>
                          </table>
                        </form>
                      </div>
                    </div>
                    <div class="button-row d-flex mt-4">
                      <button class="ml-auto js-btn-prev" type="button" title="Previous">Previous</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <!-- Modal -->
      <div class="modal" id="modal" tabindex="-1" role="dialog" aria-hidden="true" style="top:50px; height:fit-content; left:50%;">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header no-bd">
              <h5 class="modal-title">
                <span class="fw-mediumbold"> Reschedule
                </span>
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="width:0px">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p class="small"></p>
              <form class="form-horizontal row-fluid" method="post" action="Schedule Volunteer.php">
                <div class="row">
                  <div class="control-group">
                    <label class="control-label" for="id">Schedule ID</label>
                    <div class="controls">
                      <input type="text" id="id" name="VolSchID" class="span12">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="date">Date</label>
                    <div class="controls">
                      <input type="date" id="dateu" name="Date" class="span12" oninput="clearDropDown(); getActivityForUpdate(); getEventForUpdate()" required>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="activityu">Activity</label>
                    <div class="controls">
                      <select id="activityu" name="Activity" class="span12" oninput="getTimeForUpdate()" required>
                        <option value="Please Choose">Please Choose</option>
                      </select>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="time">Start Time</label>
                    <div class="controls">
                      <input type="text" id="stimeu" name="Time" class="span12" readonly>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="time">End Time</label>
                    <div class="controls">
                      <input type="text" id="etimeu" name="Time" class="span12" readonly>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="time">Time</label>
                    <div class="controls">
                      <input type="time" id="time" name="Time" class="span12" required>
                    </div>
                  </div>
                </div>
                <div class="modal-footer no-bd" style="width:100%;">
                  <button type="submit" name="Update">Update</button>
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
              <p class="small" style="padding-left:75px;"> Are you sure you want remove this schedule?</p>
              <form class="form-horizontal row-fluid" method="post" action="Schedule Volunteer.php" style="border:none;">
                <div class="row">
                  <div class="control-group" style="border:none;">
                    <label class="control-label" for="quantity">Schedule ID:</label>
                    <div class="controls">
                      <input type="text" id="id" name="VolSchID" class="span12" readonly>
                    </div>
                  </div>
                </div>
                <div class="modal-footer no-bd" style="background-color:white;">
                  <button type="submit" class="button-rounded margin-top-20" name="Remove">Yes</button>
                  <button type="submit" class="button-rounded margin-top-20" name="No">No</button>
                </div>
            </div>
            </form>
            <script type='text/javascript' src='Buttons.js'></script>
            <script type='text/javascript' src='Javascript/Volunteer Schedule.js'></script>
          </div>
        </div>
      </div>
    </div>
    <?php include_once("Footer.html");  ?>

    <!-- Javascript -->
    <script src="CSS/assets/plugins/jquery.min.js"></script>
    <script src="CSS/assets/plugins/plugins.js"></script>
    <script src="CSS/assets/js/functions.min.js"></script>
    <script src='CSS/css/bootstrap.minn.css'></script>
    <script src='CSS/SliderJs/js/jq.js'></script>
    <script src="CSS/SliderJs/js/script.js"></script>
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
        // Add Row
      });
    </script>
    <script>
      //To display all the values from database on the update dialog box
      $(document).on("click", ".modal-trigger", function() {
        var ID = $(this).data('id');
        var date = $(this).data('date');
        var time = $(this).data('time');
        var activity = $(this).data('activity');
        $(".modal-content #id").val(ID);
        $("#dateu").val(date);
        $("#time").val(time);
        $("#activityu").val(activity);
      });
    </script>


  </body>

</html>