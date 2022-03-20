<?php
include_once('PHP/TreatmentSchedule.php');
include_once('PHP/Member.php');
error_reporting(0);
session_start();
//Member ID from Register Member Page
if (isset($_SESSION["memID"])) {
  $memberID = $_SESSION["memID"];
}
unset($_SESSION["memID"]);
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

//Search for memberID if it is forgotten
if (isset($_POST['Search'])) {
  $FullName = "";
  $FName = $_POST["FName"];
  $MName = $_POST["MName"];
  $LName = $_POST["LName"];
  $fullName = $_POST["FName"] . " " . $_POST["MName"] . " " . $_POST["LName"];
  $memObj->setFullName($fullName);
  $memObj->searchMember();
  $memberID = $memObj->getMemberID();
  $treSchID = $treSchObj->generateID($memberID);
}

//To add the treatment schedule
if (isset($_POST['AddSchedule'])) {
  $empID = $_SESSION["EmpID"];
  $treSchObj->setTreSchID($_POST['treSchID']);
  $treSchObj->setType($_POST["Type"]);
  $treSchObj->setTreName($_POST['TN'] . "(" . $_POST['medType'] . ")");
  $treSchObj->setDose($_POST['Dose']);
  $treSchObj->setSDate($_POST["SD"] . " " . $_POST["ST"]);
  $treSchObj->setEDate($_POST["ED"] . " " . $_POST["ST"]);
  $treSchObj->setHrDlf($_POST['HD']);
  $treSchObj->setPurpose($_POST['purpose']);
  $treSchObj->setSI($_POST['SI']);
  $memberID = $_POST['MemberID'];
  //Check if the schedule already exists
  $result = $treSchObj->checkSchedule($memberID);
  if ($result != "Empty") {
    echo "<script>alert('Schedule Already Exists!');
    document.location = '/Schedule Treatment.php' </script>";
  } else {
    $treSchObj->addTreSchedule($memberID, $empID);
  }
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
  <title>Schedule Treatment</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Icon -->
  <link rel="shortcut icon" href="CSS/images/dove4.ico" />
  <!-- FontAwesome CSS -->
  <link rel="stylesheet" href="CSS/css/font-awesome.min.css">
  <!-- ElegantFonts CSS -->
  <link rel="stylesheet" href="CSS/css/elegant-fonts.css">
  <!-- themify-icons CSS -->
  <link rel="stylesheet" href="CSS/css/themify-icons.css">
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="CSS/css/swiper.min.css">
  <!-- Styles -->
  <link rel='stylesheet' href='CSS/css/bootstrap.minn.css'>
  <link rel="stylesheet" href="CSS/css/style.css">
  <link rel="stylesheet" href="CSS/style.css">
</head>

<body>
  <?php include_once("HeaderEmployee.php"); ?>
  <!--content inner-->
  <div class="content__inner">
    <h4 class="card-title" style="text-align: center"><i class="fa fa-user-md" aria-hidden="true"></i>Treatment Schedule</h4>
    <div class="container overflow-hidden">
      <!--multisteps-form-->
      <div class="multisteps-form">
        <!--progress bar-->
        <div class="row">
          <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
            <div class="multisteps-form__progress">
              <button class="multisteps-form__progress-btn js-active" type="button" title="Search">Search</button>
              <button class="multisteps-form__progress-btn" type="button" title="Personal Info">Schedule Info</button>
            </div>
          </div>
        </div>
        <!--form panels-->
        <div class="row">
          <div class="col-12 col-lg-8 m-auto">
            <form class="multisteps-form__form" action="Schedule Treatment.php" method="POST" enctype="multipart/form-data" style="margin-bottom:150px;">
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideVert">
                <h3 class="multisteps-form__title">Search</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col">
                      <input class="multisteps-form__input form-control" type="text" id="FName" name="FName" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" placeholder="First Name" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <input class="multisteps-form__input form-control" type="text" id="MName" name="MName" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" placeholder="Middle Name" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <input class="multisteps-form__input form-control" type="text" name="LName" id="LName" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" placeholder="Last Name" />
                    </div>
                  </div>
                  <div class="button-row d-flex mt-4">
                    <button class="btn btn-primary ml-auto" type="submit" name="Search" title="Send">Search</button>
                  </div>
                </div>
              </div>
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn" style="margin-bottom:150px;">
                <h3 class="multisteps-form__title">Treatment Information</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label> Member ID</label>
                      <input class="multisteps-form__input form-control" type="text" id="MemberID" name="MemberID" value="<?php _e($memberID) ?>" />
                    </div>
                    <div class="col-12 col-sm-6">
                      <label> Tre ID</label>
                      <input class="multisteps-form__input form-control" type="text" id="TreID" id="treSchID" name="treSchID" onclick="generateTreID()" placeholder="Click Me!" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label> Treatment Type</label>
                      <select class="multisteps-form__select form-control" id="type" name="Type" required oninput="displayForMedication()">
                        <option value="Please Choose">Please Choose</option>
                        <option value="Medication">Medication</option>
                        <option value="Physical Therapy">Physical Therapy</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label> Treatment Name</label>
                      <input class="multisteps-form__input form-control" type="text" name="TN" id="" placeholder="Treatment Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="100" />
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
                  <div class="form-row mt-4" id="dose" style="display:none;">
                    <div class="col">
                      <label> Dose</label>
                      <input class="multisteps-form__input form-control" type="text" name="Dose" placeholder="Dose" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label> Start Date</label>
                      <input class="multisteps-form__input form-control" type="date" name="SD" id="" placeholder="Start Date" />
                    </div>
                    <div class="col-12 col-sm-6">
                      <label> End Date</label>
                      <input class="multisteps-form__input form-control" type="date" name="ED" id="" placeholder="End Date" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label>Start Time:</label>
                      <input class="multisteps-form__input form-control" type="time" name="ST" id="" placeholder="Start Time" />
                    </div>
                    <div class="col-12 col-sm-6">
                      <label>Hours Difference:</label>
                      <input class="multisteps-form__input form-control" type="text" name="HD" id="" placeholder="Hours Difference" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Purpose:</label>
                      <input class="multisteps-form__input form-control" type="text" name="purpose" id="" placeholder="Purpose" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="100" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Instruction:</label>
                      <input class="multisteps-form__input form-control" type="text" name="SI" id="" placeholder="Instruction" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="200" />
                    </div>
                  </div>
                  <div class="button-row d-flex mt-4">
                    <button class="btn btn-primary js-btn-prev button-circle-dark" type="button" title="Prev">Prev</button>
                    <button class="btn btn-success ml-auto" type="submit" title="Send" name="AddSchedule">Add Schedule</button>
                  </div>
                </div>
              </div>
          </div>
          <script type='text/javascript' src='Javascript/TreatmentSchedule.js'></script>
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>
  <?php include_once("Footer.html");  ?>

  <!--Javascript-->
  <script src='CSS/css/bootstrap.minn.css'></script>
  <script src='CSS/SliderJs/js/jq.js'></script>
  <script src="CSS/SliderJs/js/script.js"></script>

</body>

</html>