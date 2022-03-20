<?php
include_once('PHP/Member.php');
include_once('PHP/Tip.php');
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

//Call the member class
$memObj = new Member();
$memberID = "";
//Request from View Member List Page
if (isset($_GET['ID'])) {
  $memberID = $_GET['ID'];
  $memObj->setMemberID($memberID);
}
$array = $memObj->searchMember();

//If the content using memeberID or redirected from View Member List Page
if (isset($_POST['Search']) || $memberID != "") {
  //Search with an ID
  if (!empty($_POST['IDForSearch'])) {
    $memID = $_POST["IDForSearch"];
    $memObj->setMemberID($memID);
    $memObj->setFullName(" ");
    $array = $memObj->searchMember();
  } else if (!empty($_POST['FName']) && !empty($_POST['MName']) && !empty($_POST['LName'])) {
    //Search with fullname
    $fullName = $_POST["FName"] . " " . $_POST["MName"] . " " . $_POST["LName"];
    $memObj->setMemberID(" ");
    $memObj->setFullName($fullName);
    $array = $memObj->searchMember();
  }

  //Get the retrieved information
  $MemberID = $memObj->getMemberID();
  $Photo = $memObj->getPhoto();
  $fullName = $memObj->getFullName();
  $DateOfBirth = $memObj->getDateOfBirth();
  $Gender = $memObj->getGender();
  $MotherName = $memObj->getMotherName();
  $Religion = $memObj->getReligion();
  $PlaceOfBirth = $memObj->getPlaceOfBirth();
  $MaritalStatus = $memObj->getMaritalStatus();
  $InitalLocation = $memObj->getInitalLocation();
  $AdmittedDate = $memObj->getAdmittedDate();
  $Belongings = $memObj->getBeongings();
  $Height = $memObj->getHeight();
  $Weight = $memObj->getWeight();
  $HealthStatus = $memObj->getHealthStatus();
  $MedDes = $memObj->getMedDes();
  $Status = $memObj->getStatus();
  $TipID = $array[0];
  $EmpPresent = $array[1];
  if ($TipID == "None") {
    $Behavior = $memObj->getBehavior();
    $Addiction = $memObj->getAddiction();
  }
}

//To update member's information
if (isset($_POST['Update'])) {
  //Get all the values from the checkboxes
  $behavior = "";
  $addiction = "";
  $belongings = "";
  if (!empty($_POST['Behavior'])) {
    // Loop to store and display values of individual checked checkbox.
    foreach ($_POST['Behavior'] as $selected2) {
      $behavior .= $selected2 . ",";
    }
  }
  if (!empty($_POST['Addiction'])) {
    // Loop to store and display values of individual checked checkbox.
    foreach ($_POST['Addiction'] as $selected) {
      $addiction .= $selected . ",";
    }
  }
  if (!empty($_POST['Belongings'])) {
    // Loop to store and display values of individual checked checkbox.
    foreach ($_POST['Belongings'] as $selected) {
      $belongings .= $selected . ",";
    }
  }
  //Set the attributes
  $memObj->setMemberID($_POST['MemberID']);
  $memObj->setFullName($_POST['FullName']);
  $memObj->setDateOfBirth($_POST['DOB']);
  $memObj->setGender($_POST['Gender']);
  $memObj->setMotherName($_POST['MoName']);
  $memObj->setReligion($_POST['Religion']);
  $memObj->setPlaceOfBirth($_POST['PB']);
  $memObj->setMaritalStatus($_POST['MS']);
  $memObj->setInitalLocation($_POST['IL']);
  $memObj->setAdmittedDate($_POST['AD']);
  $tipID = $_POST['TipID'];
  $memObj->setHeight($_POST['Height']);
  $memObj->setWeight($_POST['Weight']);
  $memObj->setBehavior($behavior);
  $memObj->setAddiction($addiction);
  $memObj->setBelongings($belongings);
  $memObj->setHealthStatus($_POST['HealthStatus']);
  $memObj->setMedDes($_POST['MedDes']);
  $memObj->setStatus($_POST['Status']);
  $empPrsent = $_POST["EmpPresent"];
  $memObj->updateMember($tipID, $empPrsent, $empID);
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
  <title>Update Member</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Icon -->
  <link rel="shortcut icon" href="CSS/images/dove4.ico" />
  <!-- For Picture CSS -->
  <link rel="stylesheet" href="CSS/assets/plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="CSS/assets/css/theme.min.css">
  <link rel="stylesheet" href="CSS/assets/css/splash.min.css">
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
  <link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
  <!-- Styles -->
  <link rel='stylesheet' href='CSS/css/bootstrap.minn.css'>
  <link rel="stylesheet" href="CSS/css/style.css">
  <link rel="stylesheet" href="CSS/style.css">
  <!-- Including the Ajax Library -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<?php include_once("HeaderEmployee.php"); ?>

<body>
  <!--content inner-->
  <div class="content__inner">
    <h4 class="card-title" style="text-align: center"><i class="fa fa-pencil" aria-hidden="true"></i>Update Member's Info</h4>
    <div class="container overflow-hidden">
      <!--multisteps-form-->
      <div class="multisteps-form">
        <!--progress bar-->
        <div class="row">
          <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
            <div class="multisteps-form__progress">
              <button class="multisteps-form__progress-btn js-active" type="button" title="Search">Search</button>
              <button class="multisteps-form__progress-btn" type="button" title="Personal Info">Personal Info</button>
              <button class="multisteps-form__progress-btn" type="button" title="Member As Found">Member As Found</button>
              <button class="multisteps-form__progress-btn" type="button" title="Medical History">Medical History</button>
              <button class="multisteps-form__progress-btn" type="button" title="Credentials">Credentials</button>
            </div>
          </div>
        </div>
        <!--form panels-->
        <div class="row">
          <div class="col-12 col-lg-8 m-auto">
            <form class="multisteps-form__form" action="Update Member.php" method="POST" enctype="multipart/form-data" style="margin-bottom:180px;">
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideVert">
                <h3 class="multisteps-form__title">Search</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col">
                      <input class="multisteps-form__input form-control" type="text" id="IDForSearch" name="IDForSearch" placeholder="Member ID" />
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
                    <button class="btn btn-primary ml-auto" type="submit" name="Search" title="Send">Search</button>
                  </div>
                </div>
              </div>
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                <h3 class="multisteps-form__title">Personal Information</h3>
                <div class="multisteps-form__content">
                  <div class="container text-center">
                    <img class="img-circle-xl" src='data:image/jpg;charset=utf8;base64,<?php _e(base64_encode($Photo)); ?>' />
                  </div><!-- end container -->
                  <div class="form-row mt-4">
                    <div class="col">
                      <label> Member ID</label>
                      <input class="multisteps-form__input form-control" type="text" id="MemberID" name="MemberID" placeholder="Member ID" value="<?php _e($MemberID) ?>" oninput="show()" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label> Full Name</label>
                      <input class="multisteps-form__input form-control" type="text" id="FName" name="FullName" placeholder="Full Name" value="<?php _e($fullName) ?>" placeholder="Last Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label> Date of Birth</label>
                      <input class="multisteps-form__input form-control" type="date" id="FName" name="DOB" value="<?php _e($DateOfBirth) ?>" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label> Gender</label>
                      <select class="multisteps-form__select form-control" id="Gender" name="Gender">
                        <option value="Male" id="Male">Male</option>
                        <option value="Female" id="Female">Female</option>
                      </select>
                      <?php
                      //To select a value from the select box
                      if (strpos($Gender, "Male") !== false) {
                        echo "<script> document.getElementById('Male').selected = true</script>";
                      }
                      if (strpos($Gender, "Female") !== false) {
                        echo "<script> document.getElementById('Female').selected = true</script>";
                      }
                      ?>
                    </div>
                    <div class="col-12 col-sm-6">
                      <label> Mother's Name</label>
                      <input class="multisteps-form__input form-control" type="text" name="MoName" id="" placeholder="Mother Name" value="<?php _e($MotherName) ?>" placeholder="Last Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label>Religion:</label>
                      <select class="multisteps-form__select form-control" id="Religion" name="Religion">
                        <option value="Please Choose">Please Choose</option>
                        <option id="Orthodox" value="Orthodox">Orthodox</option>
                        <option id="Protestant" value="Protestant">Protestant</option>
                        <option id="Muslim" value="Muslim">Muslim</option>
                        <option id="Catholic" value="Catholic">Catholic</option>
                        <option id="Other" value="Other">Other</option>
                      </select>
                    </div>
                    <?php
                    //To select a value from the select box
                    if (strpos($Religion, "Orthodox") !== false) {
                      echo "<script> document.getElementById('Orthodox').selected = true</script>";
                    }
                    if (strpos($Religion, "Protestant") !== false) {
                      echo "<script> document.getElementById('Protestant').selected = true</script>";
                    }
                    if (strpos($Religion, "Muslim") !== false) {
                      echo "<script> document.getElementById('Muslim').selected = true</script>";
                    }
                    if (strpos($Religion, "Catholic") !== false) {
                      echo "<script> document.getElementById('Catholic').selected = true</script>";
                    }
                    if (strpos($Religion, "Other") !== false) {
                      echo "<script> document.getElementById('Other').selected = true</script>";
                    }
                    ?>

                    <div class="col-12 col-sm-6">
                      <label>Place of Birth:</label>
                      <input class="multisteps-form__input form-control" type="text" name="PB" id="" placeholder="Place of Birth" value="<?php _e($PlaceOfBirth) ?>" placeholder="Last Name" pattern="[^()/><\][\\\x22,;" title="Should not contain special characters or numbers" >
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12">
                      <label>Martial Status:</label>
                      <select class="multisteps-form__select form-control" id="MS" name="MS">
                        <option value="Please Choose">Maritial Status</option>
                        <option id="Single" value="Single">Single</option>
                        <option id="Married" value="Married">Married</option>
                        <option id="Divorced" value="Divorced">Divorced</option>
                        <option id="Widowed" value="Widowed">Widowed</option>
                      </select>
                    </div>
                  </div>
                  <?php
                  //To select a value from the select box
                  if (strpos($MaritalStatus, "Single") !== false) {
                    echo "<script> document.getElementById('Single').selected = true</script>";
                  }
                  if (strpos($MaritalStatus, "Married") !== false) {
                    echo "<script> document.getElementById('Married').selected = true</script>";
                  }
                  if (strpos($MaritalStatus, "Divorced") !== false) {
                    echo "<script> document.getElementById('Divorced').selected = true</script>";
                  }
                  if (strpos($MaritalStatus, "Widowed") !== false) {
                    echo "<script> document.getElementById('Widowed').selected = true</script>";
                  }
                  ?>
                  <div class="button-row d-flex mt-4">
                    <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                  </div>
                </div>
              </div>
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                <h3 class="multisteps-form__title">Member's condition</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Intial Location</label>
                      <select class="multisteps-form__select form-control" id="IL" name="IL">
                        <option value="Please Choose">Intial Location</option>
                        <option id="Hospital" value="Hospital">Hospital</option>
                        <option id="Streets" value="Streets">Streets</option>
                        <option id="Police Station" value="Police Station">Police Station</option>
                        <option id="Other" value="Other">Other</option>
                      </select>
                    </div>
                  </div>
                  <?php
                  //To select a value from the select box
                  if (strpos($InitalLocation, "Hospital") !== false) {
                    echo "<script> document.getElementById('Hospital').selected = true</script>";
                  }
                  if (strpos($InitalLocation, "Streets") !== false) {
                    echo "<script> document.getElementById('Streets').selected = true</script>";
                  }
                  if (strpos($InitalLocation, "Police Station") !== false) {
                    echo "<script> document.getElementById('Police Station').selected = true</script>";
                  }
                  if (strpos($InitalLocation, "Other") !== false) {
                    echo "<script> document.getElementById('Other').selected = true</script>";
                  }
                  ?>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Admitted Date:</label>
                      <input class="multisteps-form__input form-control" type="date" placeholder="Admitted Date" name="AD" value="<?php _e($AdmittedDate) ?>" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Tip ID:</label>
                      <input class="multisteps-form__input form-control" type="text" placeholder="Tip ID" name="TipID" id="tipID" oninput="displayAdditionalInfo()" value="<?php _e($TipID) ?>" />
                    </div>
                  </div>
                  <div class="form-row mt-4" name="additionalInfo" style="display: none;">
                    <div class="col">
                      <label>Behavior:</label>
                      <div style="display: inline-block;">
                        <label for="name">Suicidal</label>
                        <input type="checkbox" id="Suicidal" name="Behavior[]" value="Suicidal">
                        <label for="name">Yelling</label>
                        <input type="checkbox" id="Yelling" name="Behavior[]" value="Yelling">
                        <label for="name">Laughing</label>
                        <input type="checkbox" id="Laughing" name="Behavior[]" value="Laughing">
                        <label for="name">Agressive</label>
                        <input type="checkbox" id="Agressive" name="Behavior[]" value="Agressive">
                        <label for="name">Crying</label>
                        <input type="checkbox" id="Crying" name="Behavior[]" value="Crying">
                        <label for="name">Fainting</label>
                        <input type="checkbox" id="Fainting" name="Behavior[]" value="Fainting">
                      </div>
                    </div>
                  </div>
                  <?php
                  //To check a value from the check boxes
                  if (strpos($Behavior, "Suicidal") !== false) {
                    echo "<script> document.getElementById('Suicidal').checked = true</script>";
                  }
                  if (strpos($Behavior, "Yelling") !== false) {
                    echo "<script> document.getElementById('Yelling').checked = true</script>";
                  }
                  if (strpos($Behavior, "Laughing") !== false) {
                    echo "<script> document.getElementById('Laughing').checked = true</script>";
                  }
                  if (strpos($Behavior, "Agressive") !== false) {
                    echo "<script> document.getElementById('Agressive').checked = true</script>";
                  }
                  if (strpos($Behavior, "Crying") !== false) {
                    echo "<script> document.getElementById('Crying').checked = true</script>";
                  }
                  if (strpos($Behavior, "Fainting") !== false) {
                    echo "<script> document.getElementById('Fainting').checked = true</script>";
                  } else {
                    echo "<script> document.getElementById('OtherBehavior').value = $Behavior</script>";
                  }
                  ?>
                  <div class="form-row mt-4" name="additionalInfo" style="display: none;">
                    <div class="col">
                      <label>Addictions:</label>
                      <div style="display: inline-block;">
                        <label for="name">Cigarette</label>
                        <input type="checkbox" id="Cigarette" name="Addiction[]" value="Cigarette">
                        <label for="name">Chat</label>
                        <input type="checkbox" id="Chat" name="Addiction[]" value="Chat">
                        <label for="name">Alcohol</label>
                        <input type="checkbox" id="Alcohol" name="Addiction[]" value="Alcohol">
                        <label for="name">Drugs</label>
                        <input type="checkbox" id="Drugs" name="Addiction[]" value="Drugs">
                      </div>
                    </div>
                  </div>
                  <?php
                  //To check a value from the check boxes
                  if (strpos($Addiction, "Cigarette") !== false) {
                    echo "<script> document.getElementById('Cigarette').checked = true</script>";
                  }
                  if (strpos($Addiction, "Chat") !== false) {
                    echo "<script> document.getElementById('Chat').checked = true</script>";
                  }
                  if (strpos($Addiction, "Alcohol") !== false) {
                    echo "<script> document.getElementById('Alcohol').checked = true</script>";
                  }
                  if (strpos($Addiction, "Drugs") !== false) {
                    echo "<script> document.getElementById('Drugs').checked = true</script>";
                  } else {
                    echo "<script> document.getElementById('OtherAddiction').value = $Addiction</script>";
                  }
                  ?>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Belongings:</label>
                      <div style="display: inline-block;">
                        <label for="name">Money</label>
                        <input type="checkbox" id="Money" name="Belongings[]" value="Money">
                        <label for="name">ID</label>
                        <input type="checkbox" id="ID" name="Belongings[]" value="ID">
                        <label for="name">Photo</label>
                        <input type="checkbox" id="Photo" name="Belongings[]" value="Photo">
                        <label for="name">Clothes</label>
                        <input type="checkbox" id="Clothes" name="Belongings[]" value="Clothes">
                      </div>
                    </div>
                  </div>
                  <?php
                  //To check a value from the check boxes
                  if (strpos($Belongings, "Money") !== false) {
                    echo "<script> document.getElementById('Money').checked = true</script>";
                  }
                  if (strpos($Belongings, "ID") !== false) {
                    echo "<script> document.getElementById('ID').checked = true</script>";
                  }
                  if (strpos($Belongings, "Photo") !== false) {
                    echo "<script> document.getElementById('Photo').checked = true</script>";
                  }
                  if (strpos($Belongings, "Clothes") !== false) {
                    echo "<script> document.getElementById('Clothes').checked = true</script>";
                  } else {
                    echo "<script> document.getElementById('OtherBelongings').value = $Belongings</script>";
                  }
                  ?>
                  <div class="button-row d-flex mt-4">
                    <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                    <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                  </div>
                </div>
              </div>
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                <h3 class="multisteps-form__title">Medical history</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label>Height (m)</label>
                      <input class="multisteps-form__input form-control" type="text" name="Height" id="" placeholder="Height" value="<?php _e($Height) ?>" />
                    </div>
                    <div class="col-12 col-sm-6">
                      <label>Weight (kg)</label>
                      <input class="multisteps-form__input form-control" type="text" name="Weight" id="" placeholder="Weight" value="<?php _e($Weight) ?>" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Health Status:</label>
                      <select class="multisteps-form__select form-control" name="HealthStatus" id="HealthStatus">
                        <option value="Please Choose">Please Choose</option>
                        <option id="Normal" value="Normal">Normal</option>
                        <option id="NMA" value="Needs Medical Attention">Needs Medical Attention</option>
                        <option id="Critical" value="Critical">Critical</option>
                      </select>
                    </div>
                  </div>
                  <?php
                  //To select a value from the select box
                  if (strpos($HealthStatus, "Normal") !== false) {
                    echo "<script> document.getElementById('Normal').selected = true</script>";
                  }
                  if (strpos($HealthStatus, "Needs Medical Attention") !== false) {
                    echo "<script> document.getElementById('NMA').selected = true</script>";
                  }
                  if (strpos($HealthStatus, "Critical") !== false) {
                    echo "<script> document.getElementById('Critical').selected = true</script>";
                  }
                  ?>
                  <div class="form-row mt-4" id="criticaldes">
                    <div class="col">
                      <label>Description:</label>
                      <textarea class="multisteps-form__input form-control" type="text" placeholder="" name="MedDes"><?php _e($MedDes) ?></textarea>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Status</label>
                      <select class="multisteps-form__select form-control" name="Status">
                        <option id="Resident" value="Resident">Resident</option>
                        <option id="Rehabilitated" value="Rehabilitated">Rehabilitated</option>
                        <option id="Deceased" value="Deceased">Deceased</option>
                      </select>
                    </div>
                  </div>
                  <?php
                  //To select a value from the select box
                  if (strpos($Status, "Resident") !== false) {
                    echo "<script> document.getElementById('Resident').selected = true</script>";
                  }
                  if (strpos($Belongings, "Rehabilitated") !== false) {
                    echo "<script> document.getElementById('Rehabilitated').selected = true</script>";
                  }
                  if (strpos($Belongings, "Deceased") !== false) {
                    echo "<script> document.getElementById('Deceased').selected = true</script>";
                  }
                  ?>
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
                <h3 class="multisteps-form__title">Credentials</h3>
                <div class="form-row mt-4">
                  <div class="col">
                    <label>Employee Present</label>
                    <input class="multisteps-form__input form-control" type="text" placeholder="Employee Present" name="EmpPresent" value="<?php _e($EmpPresent) ?>" />
                  </div>
                </div>
                <div class="button-row d-flex mt-4">
                  <button class="btn btn-primary js-btn-prev button-circle-dark" type="button" title="Prev">Prev</button>
                  <button class="btn btn-success ml-auto" type="submit" title="Send" name="Update">Update</button>
                </div>
              </div>
          </div>
          </form>
          <script type='text/javascript' src='Javascript/Member.js'></script>
          <script type='text/javascript' src='Javascript/BankAccount.js'></script>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!--Modal for Update Bank Account-->
  <div class="modal" id="modal4" tabindex="-1" role="dialog" aria-hidden="true" style="height:fit-content;">
    <div class="modal-dialog" role="document">
      <div class="modal-content" id="forMember">
        <div class="modal-header no-bd">
          <h5 class="modal-title">
            <span class="fw-mediumbold">Add/Update Bank Account
            </span>
          </h5>
          <button type="button" class="close" aria-label="Close" style="width:15%" onclick="Hide()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal-body" style="overflow:hidden;">
        </div>
      </div>
    </div>
  </div>
  </div>
  <?php include_once("Footer.html");  ?>
  <script>
    //To get Add/Update Dialog box from ManageBankAccount page
    function setValues() {
      var memID = document.getElementById("MemberID").value;
      window.document.getElementById("belongsto").value = memID;
    }
    //JQuery code to display the dialog box in Mange Bank Account page
    $("#HealthStatus").on('input', function() {
      var str = $("#HealthStatus").val();
      alert(str);
      if (str == "Critical") {
        $("#modal4").show();
        $("#modal-body").load("http://mekedonia.com/ManageBankAccount.php #toLoad", function(responseTxt, statusTxt, xhr) {
          if (statusTxt == "success")
            alert("External content loaded successfully!");
          if (statusTxt == "error")
            alert("Error: " + xhr.status + ": " + xhr.statusText);
          setValues();
        });
      }
    });
    //To hide the displayed modal
    function Hide() {
      var modal = document.getElementById("modal4");
      modal.style.display = "none";
    }
  </script>
  <!-- Javascript -->
  <script src="CSS/assets/plugins/jquery.min.js"></script>
  <script src="CSS/assets/plugins/plugins.js"></script>
  <script src="CSS/assets/js/functions.min.js"></script>
  <script src='CSS/css/bootstrap.minn.css'></script>
  <script src='CSS/SliderJs/js/jq.js'></script>
  <script src="CSS/SliderJs/js/script.js"></script>
</body>

</html>