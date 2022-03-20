<?php
include_once('PHP/Volunteer.php');
include_once('PHP/Donator.php');
include_once('PHP/Visitor.php');
session_start();
if (isset($_SESSION["Role"])) {
  $role = $_SESSION["Role"];
  //If employee access this page certain aspects are hidden
  echo '<style type="text/css">
   #toHide {
       display: none;
   }
   #btnContinue {
    display: none;
}
   </style>';
  $empID = $_SESSION["EmpID"];
} else {
  //If user access this page certain aspects are hidden
  $role = "User";
  echo '<style type="text/css">
  #btnContinue2 {
      display: none;
  }
  </style>';
  $empID = "None";
}

//Call the Volunteer class
$volObj = new Volunteer();
//Call the Donator class
$donObj = new Donator();
//Call the Visitor class
$visObj = new Visitor();

//To register contributor
if (isset($_POST['Register'])) {
  $fullName = $_POST["FName"] . " " . $_POST["MName"] . " " . $_POST["LName"];
  //Register donator information
  if (($_POST['Type']) == "Donator") {
    //Set the attributes
    $donID = $donObj->generateID();
    $donObj->setConID($donID);
    $donObj->setFullName($fullName);
    $donObj->setGender($_POST['Gender']);
    $donObj->setPhoneNo($_POST['PhoneNo']);
    $donObj->setEmail($_POST['Email']);
    $donObj->setAddress($_POST['Address']);
    $donObj->setOccupation($_POST['Occupation']);
    $donObj->setWorkPlace($_POST['workPlace']);
    $donObj->setTableName("Donator");
    //Check if the donator already exists
    $check = $donObj->checkContributor();
    if ($check == "Empty") {
      $donObj->registerDonator($empID);
    }
  }

  //Register visitor information
  if (($_POST['Type']) == "Visitor") {
    //Set the attributes
    $visObj->setFullName($fullName);
    $visID = $visObj->generateID();
    $visObj->setConID($visID);
    $visObj->setGender($_POST['Gender']);
    $visObj->setPhoneNo($_POST['PhoneNo']);
    $visObj->setEmail($_POST['Email']);
    $visObj->setAddress($_POST['Address']);
    $visObj->setOccupation($_POST['Occupation']);
    $visObj->setWorkPlace($_POST['workPlace']);
    $visObj->setTableName("Visitor");
    $visObj->setTypeOfVis('Individual');
    //Check if the visitor already exists
    $check = $visObj->checkContributor();
    if ($check == "Empty") {
      $visObj->registerVisitor($empID);
    }
  }

  //Register volunteer information
  if (($_POST['Type']) == "Volunteer") {
    //Image 
    if (!empty($_FILES["Photo"]["name"])) {
      // Get file info 
      $fileName = basename($_FILES["Photo"]["name"]);
      $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
      // Allow certain file formats 
      $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
      if (in_array($fileType, $allowTypes)) {
        $image = $_FILES['Photo']['tmp_name'];
        $imgContent = addslashes(file_get_contents($image));
      }
    }

    //If the volunteer already works in the compound
    $y = "";
    if (($_POST['inRes']) == "Yes") {
      $y = 1;
    }
    if (($_POST['inRes']) == "No") {
      $y = 0;
    }
    //Generate ID
    $volID = $volObj->generateID();

    //Prefered days
    $days = "";
    if (!empty($_POST['Days'])) {
      // Loop to store and display values of individual checked checkbox.
      foreach ($_POST['Days'] as $selected2) {
        $days .= $selected2 . ",";
      }
    }

    //Set the attributes
    $volObj->setConID($volID);
    $volObj->setFullName($fullName);
    $volObj->setGender($_POST['Gender']);
    $volObj->setPhoneNo($_POST['PhoneNo']);
    $volObj->setEmail($_POST['Email']);
    $volObj->setAddress($_POST['Address']);
    $volObj->setOccupation($_POST['Occupation']);
    $volObj->setWorkPlace($_POST['workPlace']);
    $volObj->setPhoto($imgContent);
    $volObj->setSkill($_POST['Skill']);
    $volObj->setDays($days);
    $volObj->setInRes($y);
    $volObj->setEFullName($_POST['EfullName']);
    $volObj->setEPhoneNo($_POST['EPhoneNo']);
    $volObj->setTableName("Volunteer");
    //Check if the visitor already exists
    $check = $volObj->checkContributor();
    if ($check == "Empty") {
      $volObj->registerVolunteer($empID);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title> Register Contributor</title>
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
  </style>
</head>

<body>
  <?php
  //If employee access this page, display employee's menu
  if ($role == "Employee") {
    include_once("HeaderEmployee.php");
  }
  //If user access this page, display user's menu
  else {
    include_once("HeaderHome.php");
  }
  ?>
  </head>

  <body>
    <!--content inner-->
    <div class="content__inner">
      <h4 class="card-title" style="text-align: center"><i class="fa fa-users" aria-hidden="true"></i>Contributor Registration</h4>
      <div class="container overflow-hidden">
        <!--multisteps-form-->
        <div class="multisteps-form">
          <!--progress bar-->
          <div class="row">
            <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
              <div class="multisteps-form__progress">
                <button class="multisteps-form__progress-btn js-active" type="button" title="Personal Info">Personal Information</button>
                <button class="multisteps-form__progress-btn" type="button" title="Address">Address</button>
                <button class="multisteps-form__progress-btn" type="button" title="Volunteer Information" name="toHide" style="display: none;">Volunteer Information</button>
                <button class="multisteps-form__progress-btn" type="button" title="Emergency Contact" name="toHide" style="display: none;">Emergency Contact</button>
              </div>
            </div>
          </div>
          <!--form panels-->
          <div class="row">
            <div class="col-12 col-lg-8 m-auto">
              <form class="multisteps-form__form" action="Register Contributor.php" method="POST" enctype="multipart/form-data" style="margin-bottom:130px;">
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideVert">
                  <h3 class="multisteps-form__title">Your Personal Info</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col">
                        <select class="multisteps-form__select form-control" id="type" name="Type" required oninput="displayContent()">
                          <option selected="selected">Type of Contribution</option>
                          <option value="Visitor">Visitor</option>
                          <option value="Donator">Donator</option>
                          <option value="Volunteer">Volunteer</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-row mt-4" id="photoHide" style="display: none;">
                      <div class="col" stlye="display: none;">
                        <label>Photo</label>
                        <input class="multisteps-form__input form-control" type="file" name="Photo" style="height:50px; margin-bottom:5px;" />
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col-12 col-sm-6">
                        <label>First Name</label>
                        <input class="multisteps-form__input form-control" type="text" id="FName" name="FName" placeholder="First Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="15" required />
                      </div>
                      <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                        <label>Middle Name</label>
                        <input class="multisteps-form__input form-control" type="text" id="MName" name="MName" placeholder="Middle Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="15" required />
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Last Name</label>
                        <input class="multisteps-form__input form-control" type="text" name="LName" id="LName" placeholder="Last Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="15" required />
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <select class="multisteps-form__select form-control" id="Gender" name="Gender" required>
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
                        <label>Phone No.</label>
                        <input class="multisteps-form__input form-control" type="number" id="phone" name="PhoneNo" placeholder="Phone Number" required/>
                        <p><span id="validator"></span></p>
                      </div>
                      <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                        <label>Email</label>
                        <input class="multisteps-form__input form-control" type="email" id="email" name="Email" placeholder="Email" required oninput="checkValidation()" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Wrong Format" maxlength="30" required />
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Home Address:</label>
                        <input class="multisteps-form__input form-control" type="text" placeholder="Subcity | Woreda | House No" name="Address" maxlength="100"/>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col-12 col-sm-6">
                        <label>Occupation</label>
                        <input class="multisteps-form__input form-control" type="text" id="Occupation" name="Occupation" placeholder="Occupation" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers"  maxlength="30" required />
                      </div>
                      <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                        <label>Work Place</label>
                        <input class="multisteps-form__input form-control" type="text" id="WP" name="workPlace" placeholder="Work Place" maxlength="50"/>
                      </div>
                    </div>
                    <div class="button-row d-flex mt-4">
                      <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                      <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next" name="toHide" style="display: none;">Next</button>
                      <button class="btn btn-success ml-auto" type="submit" title="Send" name="Register" id="forVisDon">Register</button>
                    </div>
                  </div>
                </div>
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn" name="toHide" style="display: none;">
                  <h3 class="multisteps-form__title">Volunteer Information</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Skill</label>
                        <input class="multisteps-form__input form-control" type="text" name="Skill" id="" placeholder="Special Skill" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="30"/>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Prefered Days:</label>
                        <div style="display: inline-block;">
                          <label for="name">Mon</label>
                          <input type="checkbox" id="" name="Days[]" value="Monday">
                          <label for="name">Tue</label>
                          <input type="checkbox" id="" name="Days[]" value="Tuesday">
                          <label for="name">Wed</label>
                          <input type="checkbox" id="" name="Days[]" value="Wednesday">
                          <label for="name">Thurs</label>
                          <input type="checkbox" id="" name="Days[]" value="Thursday">
                          <label for="name">Fri</label>
                          <input type="checkbox" id="" name="Days[]" value="Friday">
                          <label for="name">Sat</label>
                          <input type="checkbox" id="" name="Days[]" value="Saturday">
                          <label for="name">Sun</label>
                          <input type="checkbox" id="" name="Days[]" value="Sunday">
                        </div>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <select class="multisteps-form__select form-control" name="inRes">
                          <option selected="selected">Currently In Resident</option>
                          <option value="Yes">Yes</option>
                          <option value="No">No</option>
                        </select>
                      </div>
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
                <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn" name="toHide" style="display: none;">
                  <h3 class="multisteps-form__title">Emergency Contact</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Full Name</label>
                        <input class="multisteps-form__input form-control" type="text" name="EfullName" id="" placeholder="Full Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="50"/>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Phone No.</label>
                        <input class="multisteps-form__input form-control" type="number" name="EPhoneNo" id="" placeholder="Phone Number"/>
                      </div>
                    </div>
                    <div class="form-row mt-4" id="toHide">
                      <div class="col">
                        <div style="display: inline-block; margin-left:10px;">
                          <input type="checkbox" id="approve" name="approve" value="false" style="margin-left:100px;" onclick="approval()">
                          <label for="name">I hereby approve that I filled this form with my own will.</label>
                          <label for="name" style="text-align:center;"> I approve that the organization or any involved entities has not made any influnce on me regarding the information that I have given out.</label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="button-row d-flex mt-4 col-12">
                        <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                        <button class="btn btn-success ml-auto" type="submit" title="Send" name="Register" id="btnContinue" disabled>Register</button>
                        <button class="btn btn-success ml-auto" type="submit" title="Send" name="Register" id="btnContinue2">Register</button>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            </form>
            <script type='text/javascript' src='Javascript/Contributor.js'></script>
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