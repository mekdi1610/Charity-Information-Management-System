<?php
include_once('PHP/TreatmentSchedule.php');
include_once('PHP/Member.php');
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

//Call the Treatment Schedule class
$treSchObj = new TreatmentSchedule();
//Call the Member class
$memObj = new Member();

//Search for member's treatment schedule
if (isset($_POST['Search'])) {
  //Search using member's ID
  if (!empty($_POST['memberID'])) {
    $memID = $_POST["memberID"];
    $accept = $treSchObj->searchTreSchedule($memID);
  } else if (!empty($_POST['FName']) && !empty($_POST['MName']) && !empty($_POST['LName'])) {
    //Search using fullname
    $fullName = $_POST["FName"] . " " . $_POST["MName"] . " " . $_POST["LName"];
    $memObj->setFullName("Marata Kebede Tadesse");
    $memObj->searchMember();
    $memID = $memObj->getMemberID();
    $accept = $treSchObj->searchTreSchedule($memID);
  }
  //Search for simple information of the member
  $memObj->setMemberID($memID);
  $array = $memObj->searchMember();
  $Photo = $memObj->getPhoto();
  $fullName = $memObj->getFullName();
  $Gender = $memObj->getGender();
  $DateOfBirth = $memObj->getDateOfBirth();
  $today = date("Y-m-d");
  $age = $today - $DateOfBirth;
  $Height = $memObj->getHeight();
  $Weight = $memObj->getWeight();
  $HealthStatus = $memObj->getHealthStatus();
  $Des = $memObj->getMedDes();
}

//Update the treatment schedule
if (isset($_POST['Update'])) {
  $treSchObj->setTreSchID($_POST['treSchID']);
  $treSchObj->setType($_POST["Type"]);
  $treSchObj->setTreName($_POST['TN']);
  $treSchObj->setDose($_POST['Dose']);
  $treSchObj->setSDate($_POST["SD"]);
  $treSchObj->setEDate($_POST["ED"]);
  $treSchObj->setHrDlf($_POST['HD']);
  $treSchObj->setPurpose($_POST['purpose']);
  $treSchObj->setSI($_POST['SI']);
  $memberID = $_POST['MemberID'];
  $treSchObj->updateTreSchedule($empID);
}

//Remove treatment schedule
if (isset($_POST['Remove'])) {
  $treSchObj->setTreSchID($_POST['treSchID']);
  $treSchObj->removeTreSchedule();
}
if (isset($_POST['No'])) {
  echo "<script>alert('Data was not removed');
    document.location = '/Update Treatment Schedule.php' </script>";
}

//Print treatment schedule for referral purpose
if (isset($_POST['Print'])) {
  $memberID = $_POST['MemberID'];
  $treSchObj->exportTreSchedule($memberID);
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
  <title>Update Treatment Schedule</title>
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
  <!-- For Picture CSS -->
  <link rel="stylesheet" href="CSS/assets/css/theme.min.css">
  <link rel="stylesheet" href="CSS/assets/css/splash.min.css">
  <!-- Table Styles -->
  <link rel="stylesheet" href="CSS/table/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
  <!-- Styles -->
  <link rel="stylesheet" href="CSS/css/style.css">
  <link rel="stylesheet" href="CSS/style.css">
  <!-- Including the Ajax Library -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>

<body> <?php include_once("HeaderEmployee.php"); ?>
  <!--content inner-->
  <div class="content__inner">
    <h4 class="card-title" style="text-align: center"><i class="fa fa-user-md" aria-hidden="true"></i>Update Treatment Schedule</h4>
    <div class="container overflow-hidden">
      <!--multisteps-form-->
      <div class="multisteps-form">
        <!--progress bar-->
        <div class="row">
          <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
            <div class="multisteps-form__progress">
              <button class="multisteps-form__progress-btn js-active" type="button" title="ContactInfo">Search</button>
              <button class="multisteps-form__progress-btn" type="button" title="Event Info">Treatment Information</button>
            </div>
          </div>
        </div>
        <!--form panels-->
        <div class="row">
          <div class="col-12 col-lg-8 m-auto">
            <form class="multisteps-form__form" action="Update Treatment Schedule.php" method="POST" enctype="multipart/form-data">
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 bg-white js-active" data-animation="slideVert">
                <h3 class="multisteps-form__title">Search</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col">
                      <input class="multisteps-form__input form-control" type="text" id="memberID" name="memberID" placeholder="MemberID" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <input class="multisteps-form__input form-control" type="text" id="FName" name="FName" placeholder="First Name" placeholder="Last Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <input class="multisteps-form__input form-control" type="text" id="MName" name="MName" placeholder="Middle Name" placeholder="Last Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <input class="multisteps-form__input form-control" type="text" name="LName" id="LName" placeholder="Last Name" placeholder="Last Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" />
                    </div>
                  </div>
                  <div class="button-row d-flex mt-4">
                    <button type="submit" title="Next" name="Search">Search</button>
                  </div>
                </div>
              </div>
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 bg-white" data-animation="scaleIn" style="width:900px; margin-left:-85px;">
                <h3 class="multisteps-form__title" style="text-align: center;">Personal Information</h3>
                <div class="multisteps-form__content" style="text-align: center;">
                  <div class="container text-center">
                    <img class="img-circle-xl" src='data:image/jpg;charset=utf8;base64,<?php _e(base64_encode($Photo)); ?>' />
                  </div><!-- end container -->
                  <div class="form-row mt-4">
                    <div class="col">
                      <label> Full Name: <?php _e($fullName); ?></label>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label> Gender: <?php _e($Gender); ?></label>
                    </div>
                    <div class="col-12 col-sm-6">
                      <label> Height: <?php _e($Height . "m"); ?></label>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label> Age: <?php _e($age); ?></label>
                    </div>
                    <div class="col-12 col-sm-6">
                      <label> Weight: <?php _e($Weight . "kg"); ?></label>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12">
                      <label> BMI: <?php
                                    //Calculate BMI
                                    $BMI = (($Weight) / ($Height * $Height));
                                    $catagory = "";
                                    if ($BMI < 18.5) {
                                      $catagory = "Underweight";
                                    } else if ($BMI > 18.5 && $BMI < 24.9) {
                                      $catagory = "Normal Weight";
                                    } else if ($BMI > 25 && $BMI < 29.9) {
                                      $catagory = "Overweight";
                                    } else if ($BMI >= 30) {
                                      $catagory = "Obesity";
                                    }
                                    echo $BMI . "(" . $catagory . ")";
                                    ?></label>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label> Health Status: <?php _e($HealthStatus); ?></label>
                    </div>
                    <div class="col-12 col-sm-6">
                      <label> Description: <?php _e($Des); ?></label>
                    </div>
                  </div>
                </div>
                <hr>
                <h3 class="multisteps-form__title" style="text-align: center;"><i class="fa fa-table" aria-hidden="true"></i>Treatment Information</h3>
                <div class="multisteps-form__content">
                  <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Update</th>
                        <th>Remove</th>
                      </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                    <tbody>
                      <?php
                      $treID = $accept[0];
                      $memberID = $accept[1];
                      $treType = $accept[2];
                      $treName = $accept[3];
                      $dose = $accept[4];
                      $sDate = $accept[5];
                      $eDate = $accept[6];
                      $sTime = $accept[7];
                      $HD = $accept[8];
                      $purpose = $accept[9];
                      $sInst = $accept[10];
                      $empID = $accept[11];
                      echo '<input type="text" name="MemberID" style="display:none;" value=' . $memberID[0] . '>';
                      //Display the retrieved information
                      for ($i = 0; $i < sizeof($treID); $i++) {
                        echo "<tr>";
                        echo "<td>$treID[$i]</td>";
                        echo "<td>$treType[$i]</td>";
                        echo "<td>";
                        _e($treName[$i]);
                        echo "</td>";
                        echo "<td>";
                        echo '<a href="#modal" data-toggle="modal" data-id="' . $treID[$i] . '" data-memid="' . $memberID[$i] . '" data-type="' . $treType[$i] . '" data-name="' . $treName[$i] . '" data-dose="' . $dose[$i] . '" data-sdate="' . $sDate[$i] . '" data-edate="' . $eDate[$i] . '" data-hd="' . $HD[$i] . '" data-purpose="' . $purpose[$i] . '" data-si="' . $sInst[$i] . '" data-empid="' . $empID[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-eye"></i></a></td>';
                        echo "<td>";
                        echo '<a href="#modal2" data-toggle="modal" data-id="' . $treID[$i] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show"><i class="fa fa-close"></i></a></td></tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
                <button type="submit" class="print" name="Print"><i class="fa fa-print" aria-hidden="true" style="color: #212529!important;"></i>Print</button>
              </div>
            </form>
            <div class="card-body">
              <!-- Modal -->
              <div class="modal" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header no-bd">
                      <h5 class="modal-title">
                        <span class="fw-mediumbold"> Update Treatment Schedule
                        </span>
                      </h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <p class="small"></p>
                      <form class="form-horizontal row-fluid" method="post" action="Update Treatment Schedule.php">
                        <div class="row">
                          <div class="control-group">
                            <label class="control-label" for="MemID">Member ID</label>
                            <div class="controls">
                              <input type="text" id="memID" name="MemberID" class="span12">
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="ID">Treatment ID</label>
                            <div class="controls">
                              <input type="text" id="ID" name="treSchID" class="span12">
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="Type">Treatment Type</label>
                            <div class="controls">
                              <select class="multisteps-form__select form-control" id="type" name="Type" required oninput="displayForMedication()">
                                <option value="Please Choose">Please Choose</option>
                                <option value="Medication">Medication</option>
                                <option value="Physical Therapy">Physical Therapy</option>
                              </select>
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="name">Treatment Name:</label>
                            <div class="controls">
                              <input type="text" id="name" placeholder="" name="TN" class="span12" pattern="[^/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers">
                              <select class="multisteps-form__select form-control" id="medType" name="medType" style="display:none;">
                                <option selected="selected">Medication Type</option>
                                <option id="Liquid" value="Liquid">Liquid</option>
                                <option id="Tablet" value="Tablet">Tablet</option>
                                <option id="Capsules" value="Capsules">Capsules</option>
                                <option id="Suppositories" value="Suppositories">Suppositories</option>
                                <option id="Drops" value="Drops">Drops</option>
                                <option id="Inhalers" value="Inhalers">Inhalers</option>
                                <option id="Injections" value="Injections">Injections</option>
                                <option id="Implants or patches" value="Implants or patches">Implants or patches</option>
                              </select>
                            </div>
                          </div>
                          <div class="control-group" id="dose" style="display:none;">
                            <label class="control-label" for="name">Dose:</label>
                            <div class="controls">
                              <input type="text" id="dose" placeholder="" name="Dose" class="span12">
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="name">Start Date:</label>
                            <div class="controls">
                              <input type="text" id="sDate" placeholder="" name="SD" class="span12">
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="name">End Date:</label>
                            <div class="controls">
                              <input type="text" id="eDate" placeholder="" name="ED" class="span12">
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="name">Hours Difference:</label>
                            <div class="controls">
                              <input type="Number" id="HD" placeholder="" name="HD" class="span12">
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="name">Purpose:</label>
                            <div class="controls">
                              <input type="text" id="purpose" placeholder="" name="purpose" class="span12" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers">
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="name">Instruction:</label>
                            <div class="controls">
                              <input type="text" id="SI" placeholder="" name="SI" class="span12">
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer no-bd">
                          <button type="submit" class="button-rounded margin-top-20" name="Update">Update</button>
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
  <?php include_once("Footer.html"); ?>
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
            <form class="form-horizontal row-fluid" method="post" action="Update Treatment Schedule.php" style="border:none;">
              <div class="row">
                <div class="control-group" style="border:none;">
                  <label class="control-label" for="quantity">Schedule ID:</label>
                  <div class="controls">
                    <input type="text" id="ID" name="treSchID" class="span12">
                  </div>
                </div>
              </div>
              <div class="modal-footer no-bd" style="background-color:white;">
                <button type="submit" class="button-rounded margin-top-20" name="Remove">Yes</button>
                <button type="submit" class="button-rounded margin-top-20" name="No">No</button>
              </div>
          </div>
          </form>
          <script type='text/javascript' src='Javascript/TreatmentSchedule.js'></script>
          <script type='text/javascript' src='Javascript/Buttons.js'></script>
          <script type='text/javascript' src='Javascript/Member.js'></script>
        </div>
      </div>
      <?php include_once("Footer.html"); ?>
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
          var memID = $(this).data('memid');
          var type = $(this).data('type');
          var name = $(this).data('name');
          var dose = $(this).data('dose');
          var sDate = $(this).data('sdate');
          var eDate = $(this).data('edate');
          var hou = $(this).data('hd');
          var purpose = $(this).data('purpose');
          var si = $(this).data('si');
          var empid = $(this).data('empid');
          $(".modal-content #ID").val(ID);
          $("#memID").val(memID);
          $("#type").val(type);
          $("#name").val(name);
          $("#dose").val(dose);
          $("#sDate").val(sDate);
          $("#eDate").val(eDate);
          $("#HD").val(hou);
          $("#purpose").val(purpose);
          $("#SI").val(si);
          $("#empID").val(empid);
        });
      </script>
      <!-- Javascript -->
      <script src="CSS/assets/plugins/jquery.min.js"></script>
      <script src="CSS/assets/plugins/plugins.js"></script>
      <script src="CSS/assets/js/functions.min.js"></script>
      <script src="CSS/table/assets/js/jquery.min.js"></script>
      <script src="CSS/table/assets/js/bootstrap.min.js"></script>
      <script src="CSS/table/assets/js/datatables.min.js"></script>
      <script src='CSS/css/bootstrap.minn.css'></script>
      <script src="CSS/SliderJs/js/script.js"></script>
</body>

</html>