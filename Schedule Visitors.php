<?php
include_once('PHP/Contributor.php');
include_once('PHP/Visitor.php');
include_once('PHP/VisitorSchedule.php');
error_reporting(0);
//Call the Visitor class
$visObj = new Visitor();
//Call the Visitor Schedule class
$visSchObj = new VisitorSchedule();

//Schedule Visitors
if (isset($_POST['Schedule'])) {
  $visID = $_POST['VisID'];
  $visSchObj->setNoOfPeople($_POST['NP']);
  $visSchObj->setDate($_POST["Date"]);
  $visSchObj->setTime($_POST["Time"]);
  //If FNAME is empty that means they skipped the rep information
  //ID known
  if (!empty($visID)) {
    $visObj->setConID($visID);
    $visID = $visObj->checkID(); //since ID is returned
  }
  //If they provided the rep info it is processed as so, by registering the visitor information first
  //ID generated
  else {
    $fullName = $_POST["FName"] . " " . $_POST["MName"] . " " . $_POST["LName"];
    $visObj->setFullName($fullName);
    $visID = $visObj->generateID();
    //Set the attribute
    $visObj->setConID($visID);
    $visObj->setGender($_POST['Gender']);
    $visObj->setPhoneNo($_POST['PhoneNo']);
    $visObj->setEmail($_POST['Email']);
    $visObj->setAddress($_POST['Address']);
    $visObj->setOccupation($_POST['Occupation']);
    $visObj->setWorkPlace($_POST['workPlace']);
    $empID = "None";
    $visObj->setTypeOfVis($_POST['VT']);
    $visObj->setTableName('Visitor');
    //Check if the visitor exists in the system
    $check = $visObj->checkContributor();
    if ($check == "Empty") {
      $visObj->registerVisitor($empID);
    }
  }

  //Add the visitor schedule
  $visSchID = $visSchObj->generateID($visID);
  $visSchObj->setVisSchID($visSchID);
  $visSchObj->addVisSchedule($visID);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Schedule Visitors</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Icon -->
  <link rel="shortcut icon" href="CSS/images/dove4.ico" />
  <!-- FontAwesome CSS -->
  <link rel="stylesheet" href="CSS/css/font-awesome.min.css">
  <!-- ElegantFonts CSS -->
  <link rel="stylesheet" href="CSS/css/elegant-fonts.css">
  <!-- Themify-icons CSS -->
  <link rel="stylesheet" href="CSS/css/themify-icons.css">
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="CSS/css/swiper.min.css">
  <!-- Styles -->
  <link rel="stylesheet" href="CSS/style.css">
  <!-- Align Items -->
  <link rel='stylesheet' href='CSS/css/bootstrap.minn.css'>
  <link rel="stylesheet" href="CSS/css/style.css">
</head>

<body>
  <?php include_once("HeaderHome.php"); ?>
  <!--content inner-->
  <div class="content__inner">
    <h4 class="card-title" style="text-align: center"><i class="fa fa-users" aria-hidden="true"></i>Schedule Visitors</h4>
    <p style="text-align: center">If you have already registered in our system as a visitor, you can enter your ID and skip the repesentative information (Personal Information and Address). Otherwise leave the ID box empty, we will provide you one.</p>
    <div class="container overflow-hidden">
      <!--multisteps-form-->
      <div class="multisteps-form">
        <!--progress bar-->
        <div class="row">
          <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
            <div class="multisteps-form__progress">
              <button class="multisteps-form__progress-btn js-active" type="button" title="Personal Info">Personal Information</button>
              <button class="multisteps-form__progress-btn" type="button" title="Address">Address</button>
              <button class="multisteps-form__progress-btn" type="button" title="Visitor Information">Scheule Information</button>
            </div>
          </div>
        </div>
        <!--form panels-->
        <div class="row">
          <div class="col-12 col-lg-8 m-auto">
            <form class="multisteps-form__form" action="Schedule Visitors.php" method="POST" enctype="multipart/form-data">
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideVert">
                <h3 class="multisteps-form__title">Your Personal Info</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Vis ID</label>
                      <input class="multisteps-form__input form-control" type="text" id="id" name="VisID" placeholder="If you dont have this information skip it and we will provide one for you" />
                      <p><span id="validatorID"></span></p>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <input class="multisteps-form__input form-control" type="text" id="fName" name="FName" placeholder="First Name" pattern="[A-Za-z]+" title="Should not contain special characters or numbers" maxlength="15" onclick="checkID()" />
                    </div>
                    <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                      <input class="multisteps-form__input form-control" type="text" id="mName" name="MName" placeholder="Middle Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers"  maxlength="15"/>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <input class="multisteps-form__input form-control" type="text" name="LName" id="lName" placeholder="Last Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers"  maxlength="15"/>
                    </div>
                    <div class="col-12 col-sm-6">
                      <select class="multisteps-form__select form-control" id="gender" name="Gender" required>
                        <option selected="selected">Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>
                  </div>
                  <div class="button-row d-flex mt-4">
                    <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                  </div>
                </div>
              </div>
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                <h3 class="multisteps-form__title">Address</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <input class="multisteps-form__input form-control" type="number" id="phone" name="PhoneNo" placeholder="Phone Number" />
                      <p><span id="validator"></span></p>
                    </div>
                    <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                      <input class="multisteps-form__input form-control" type="email" id="email" name="Email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Wrong Format" maxlength="30" oninput="checkValidation()" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Home Address:</label>
                      <input class="multisteps-form__input form-control" type="text" placeholder="Subcity | Woreda | House No" id="address" name="Address" maxlength="100"/>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <input class="multisteps-form__input form-control" type="text" id="occupation" name="Occupation" placeholder="Occupation" id="occupation" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="30"/>
                    </div>
                    <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                      <input class="multisteps-form__input form-control" type="text" id="workPlace" name="WP" placeholder="Work Place" id="workPlace" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="50"/>
                    </div>
                  </div>
                  <div class="button-row d-flex mt-4">
                    <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                    <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                  </div>
                </div>
              </div>
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                <h3 class="multisteps-form__title">Schedule Information</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Visitor Type</label>
                      <select class="multisteps-form__select form-control" name="VT" id="visitorType" onclick="checkID()">
                        <option value="Please Choose">Please Choose</option>
                        <option value="Company">Company | Institution</option>
                        <option value="School">School Trips</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Number of People</label>
                      <input class="multisteps-form__input form-control" type="number" id="" name="NP" placeholder="How many number of people should we expect?" required />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label>Date</label>
                      <?php
                      $Date = date('Y-m-d');
                      $today = date('Y-m-d', strtotime($Date . ' + 2 days'));
                      ?>
                      <input class="multisteps-form__input form-control" type="Date" id="date" placeholder="" name="Date" min="<?php echo $today ?>" oninput="clearDropDown(); getTime()" required />
                    </div>
                    <div class="col-12 col-sm-6">
                      <label>Time:</label>
                      <select class="multisteps-form__select form-control" id="startT" name="Time" required>
                        <option selected="selected">Please Choose</option>
                      </select>
                    </div>
                  </div>
                  <input class="multisteps-form__input form-control" type="text" id="type" placeholder="" name="type" value="Visitor" style="display:none;" />
                  <div class="button-row d-flex mt-4">
                    <button class="btn btn-primary js-btn-prev button-circle-dark" type="button" title="Prev">Prev</button>
                    <button class="btn btn-success ml-auto" type="submit" title="Send" name="Schedule">Schedule</button>
                  </div>
                </div>
              </div>
            </form>
            <script type='text/javascript' src='Javascript/Visitor.js'></script>
            <script type='text/javascript' src='Javascript/VisitorSchedule.js'></script>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <?php include_once("Footer.html");  ?>

  <!-- Javascript -->
  <script src='CSS/css/bootstrap.minn.css'></script>
  <script src='CSS/SliderJs/js/jq.js'></script>
  <script src="CSS/SliderJs/js/script.js"></script>
</body>

</html>