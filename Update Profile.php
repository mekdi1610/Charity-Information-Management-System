<?php
include_once 'PHP/Volunteer.php';
error_reporting(0);
session_start();
//If the Volunteer ID is not set then the page is being accessed without authorization
if (!isset($_SESSION["VolID"])) {
  header("Location: Login.php");
  exit();
}

//Get some information on login
if ($_SESSION["FullName"] && $_SESSION["Email"]) {
  $fullName = $_SESSION["FullName"];
  $userName = $_SESSION["UserName"];
  $email = $_SESSION["Email"];
  $photo = $_SESSION["Photo"];

  //Call the Volunteer class
  $volObj = new Volunteer();
  //Search volunteer information using username
  $volObj->searchVolunteer($userName);
  $volID = $volObj->getConID();
  $gender = $volObj->getGender();
  $phoneNo = $volObj->getPhoneNo();
  $email = $volObj->getEmail();
  $address = $volObj->getAddress();
  $occupation = $volObj->getOccupation();
  $workPlace = $volObj->getWorkPlace();
  $skill = $volObj->getSkill();
  $days = $volObj->getDays();
  $inRes = $volObj->getInRes();
  $eFullName = $volObj->getEFullName();
  $ePhoneNo = $volObj->getEPhoneNo();
  $password = $volObj->getPassword();
}

//Update the volunteer's information
if (isset($_POST['Update'])) {
  $newPassword = $_POST["NewPassword"];
  $confirmPassword = $_POST["ConfirmPassword"];
  if ($newPassword == $confirmPassword) {
    $Days = "";
    if (!empty($_POST['Days'])) {
      // Loop to store and display values of individual checked checkbox.
      foreach ($_POST['Days'] as $selected2) {
        $Days .= $selected2 . ",";
        echo $Days;
      }
    }
    //Check whether the volunteer is working in the organization or not
    $y = "";
    if (($_POST['inRes']) == "Yes") {
      $y = 1;
    }
    if (($_POST['inRes']) == "No") {
      $y = 0;
    }

    //Set the values of contributors
    $volObj->setConID($_POST["ConID"]);
    $volObj->setFullName($_POST['FN']);
    $volObj->setGender($_POST['Gender']);
    $volObj->setPhoneNo($_POST['PhoneNo']);
    $volObj->setEmail($_POST['Email']);
    $volObj->setAddress($_POST['Address']);
    $volObj->setOccupation($_POST['Occupation']);
    $volObj->setWorkPlace($_POST['workPlace']);
    $volObj->setSkill($_POST['Skill']);
    $volObj->setDays($Days);
    $volObj->setInRes($y);
    $volObj->setEFullName($_POST['EfullName']);
    $volObj->setEPhoneNo($_POST['EPhoneNo']);
    $volObj->setUserName($_POST['UserName']);
    $volObj->setPassword($_POST['NewPassword']);
    $volObj->setRole("Volunteer");
    $volObj->updateProfile();
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
  <title>Update Profile</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Icon -->
  <link rel="shortcut icon" href="CSS/images/dove4.ico" />
  <!-- For Picture CSS -->
  <link rel="stylesheet" href="CSS/assets/plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="CSS/assets/css/theme.min.css">
  <link rel="stylesheet" href="CSS/assets/css/splash.min.css">
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
  <link rel='stylesheet' href='CSS/css/bootstrap.minn.css'>
  <link rel="stylesheet" href="CSS/css/style.css">
  <link rel="stylesheet" href="CSS/style.css">

</head>

<body>
  <?php include_once("HeaderVolunteer.php");
  ?>
  </head>

  <body>
    <!--content inner-->
    <div class="content__inner">
      <h3 style="text-align: center"><i class="fa fa-user" aria-hidden="true"></i>Profile</h3>
      <div class="container overflow-hidden">
        <!--multisteps-form-->
        <div class="multisteps-form">
          <!--progress bar-->
          <div class="row">
            <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
              <div class="multisteps-form__progress">
                <button class="multisteps-form__progress-btn js-active" type="button" title="Personal Info.">Personal Information</button>
                <button class="multisteps-form__progress-btn" type="button" title="Address">Address</button>
                <button class="multisteps-form__progress-btn" type="button" title="Volunteer Information">Volunteer Information</button>
                <button class="multisteps-form__progress-btn" type="button" title="Emergency Contact">Emergency Contact</button>
                <button class="multisteps-form__progress-btn" type="button" title="Account Info.">Account Information</button>
              </div>
            </div>
          </div>
          <!--form panels-->
          <div class="row">
            <div class="col-12 col-lg-8 m-auto">
              <form class="multisteps-form__form" action="Update Profile.php" method="POST" enctype="multipart/form-data">
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideVert">
                  <h3 class="multisteps-form__title">Your Personal Info</h3>
                  <div class="multisteps-form__content">
                    <div class="container text-center">
                      <img class="img-circle-xl" src='data:image/jpg;charset=utf8;base64,<?php echo base64_encode($photo); ?>' />
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Your ID</label>
                        <input class="multisteps-form__input form-control" type="text" id="ConID" name="ConID" value="<?php _e($volID); ?>" readonly/>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Full Name</label>
                        <input class="multisteps-form__input form-control" type="text" id="FN" name="FN" value="<?php _e($fullName) ?>" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" />
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <select class="multisteps-form__select form-control" id="Gender" name="Gender" required>
                          <option selected="selected">Gender</option>
                          <option id="Male" value="Male">Male</option>
                          <option id="Female" value="Female">Female</option>
                        </select>
                      </div>
                      <?php
                      if (strpos($gender, "Male") !== false) {
                        echo "<script> document.getElementById('Male').selected = true</script>";
                      }
                      if (strpos($gender, "Female") !== false) {
                        echo "<script> document.getElementById('Female').selected = true</script>";
                      }
                      ?>
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
                        <label>Phone No.</label>
                        <input class="multisteps-form__input form-control" type="number" id="Phone" name="PhoneNo" value="<?php _e($phoneNo); ?>" />
                        <p><span id="validator"></span></p>
                      </div>
                      <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                        <label>Email</label>
                        <input class="multisteps-form__input form-control" type="email" id="Email" name="Email" placeholder="Email" value="<?php _e($email); ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Wrong Format" />
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Home Address:</label>
                        <input class="multisteps-form__input form-control" type="text" value="<?php _e($address); ?>" name="Address" />
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col-12 col-sm-6">
                        <label>Occupation</label>
                        <input class="multisteps-form__input form-control" type="text" id="Occupation" name="Occupation" placeholder="Occupation" value="<?php _e($occupation); ?>" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" />
                      </div>
                      <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                        <label>Work Place</label>
                        <input class="multisteps-form__input form-control" type="text" id="WP" name="workPlace" value="<?php _e($workPlace); ?>" placeholder="Last Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" />
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
                  <h3 class="multisteps-form__title">Volunteer Information</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Skill</label>
                        <input class="multisteps-form__input form-control" type="text" name="Skill" id="" value="<?php _e($skill); ?>" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" />
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Prefered Days:</label>
                        <div style="display: inline-block;">
                          <label for="name">Mon</label>
                          <input type="checkbox" id="Monday" name="Days[]" value="Mon">
                          <label for="name">Tue</label>
                          <input type="checkbox" id="Tuesday" name="Days[]" value="Tue">
                          <label for="name">Wed</label>
                          <input type="checkbox" id="Wednesday" name="Days[]" value="Wed">
                          <label for="name">Thurs</label>
                          <input type="checkbox" id="Thrusday" name="Days[]" value="Thurs">
                          <label for="name">Fri</label>
                          <input type="checkbox" id="Friday" name="Days[]" value="Fri">
                          <label for="name">Sat</label>
                          <input type="checkbox" id="Saturday" name="Days[]" value="Sat">
                          <label for="name">Sun</label>
                          <input type="checkbox" id="Sunday" name="Days[]" value="Sun">
                        </div>
                      </div>
                      <?php
                      if (strpos($days, "Mon") !== false) {
                        echo "<script> document.getElementById('Monday').checked = true</script>";
                      }
                      if (strpos($days, "Tue") !== false) {
                        echo "<script> document.getElementById('Tuesday').checked = true</script>";
                      }
                      if (strpos($days, "Wed") !== false) {
                        echo "<script> document.getElementById('Wednesday').checked = true</script>";
                      }
                      if (strpos($days, "Thurs") !== false) {
                        echo "<script> document.getElementById('Thrusday').checked = true</script>";
                      }
                      if (strpos($days, "Fri") !== false) {
                        echo "<script> document.getElementById('Friday').checked = true</script>";
                      }
                      if (strpos($days, "Sat") !== false) {
                        echo "<script> document.getElementById('Saturday').checked = true</script>";
                      }
                      if (strpos($days, "Sun") !== false) {
                        echo "<script> document.getElementById('Sunday').checked = true</script>";
                      }
                      ?>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Currently In Resident</label>
                        <select class="multisteps-form__select form-control" name="inRes">
                          <option selected="selected">Currently In Resident</option>
                          <option id="Yes" value="Yes">Yes</option>
                          <option id="No" value="No">No</option>
                        </select>
                      </div>
                      <?php
                      if (strpos($inRes, "1") !== false) {
                        echo "<script> document.getElementById('Yes').selected = true</script>";
                      }
                      if (strpos($inRes, "0") !== false) {
                        echo "<script> document.getElementById('No').selected = true</script>";
                      }
                      ?>
                    </div>
                    <div class="row">
                      <div class="button-row d-flex mt-4 col-12">
                        <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                        <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                  <h3 class="multisteps-form__title">Emergency Contact</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Full Name</label>
                        <input class="multisteps-form__input form-control" type="text" name="EfullName" id="" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" value="<?php _e($eFullName); ?>" />
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Phone No.</label>
                        <input class="multisteps-form__input form-control" type="number" name="EPhoneNo" id="" value="<?php _e($ePhoneNo) ?>" />
                      </div>
                    </div>
                    <div class="row">
                      <div class="button-row d-flex mt-4 col-12">
                        <button class="btn btn-primary js-btn-prev button-circle-dark" type="button" title="Prev">Prev</button>
                        <button class="btn btn-success ml-auto" type="submit" title="Send" name="Update">Update</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                  <h3 class="multisteps-form__title">Account Information</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>UserName</label>
                        <input class="multisteps-form__input form-control" type="text" name="UserName" id="" value="<?php _e($userName); ?>" readonly/>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Password</label>
                        <input class="multisteps-form__input form-control" type="password" name="NewPassword" id="password" value="<?php _e($password); ?>" /><i class="fa fa-eye" id="show-password" onclick="showPassword()"></i>
                        <p><span id="validator2"></span></p>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Confirm Password</label>
                        <input class="multisteps-form__input form-control" type="password" name="ConfirmPassword" id="Confirmpassword" onclick="checkPassword()">
                      </div>
                    </div>
                    <div class="row">
                      <div class="button-row d-flex mt-4 col-12">
                        <button class="btn btn-primary js-btn-prev button-circle-dark" type="button" title="Prev">Prev</button>
                        <button class="btn btn-success ml-auto" type="submit" title="Send" name="Update">Update</button>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            </form>
            <script type='text/javascript' src='Javascript/Account.js'></script>
            <script type='text/javascript' src='Javascript/Contributor.js'></script>

          </div>
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
  </body>

</html>