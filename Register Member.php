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
//To open treatment schedule modal
if (isset($_SESSION["fullName"])) {
  $val = "display";
} else {
  $val = "";
}
unset($_SESSION["fullName"]);

//Call the Member class
$memObj = new Member();
//Call the Tip class
$tipObj = new Tip();

//Generate ID
$memberID = $memObj->generateID();
//To display all the tips that were placed when the page loads
$tipArray = $tipObj->viewTips("Yes");
$tipIDs = $tipArray[0];
$location = $tipArray[4];

//Register a member
if (isset($_POST['Register'])) {
  $FullName = $_POST["FName"] . " " . $_POST["MName"] . " " . $_POST["LName"];
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
  $behavior = $_POST["OtherBehavior"] . ",";
  $addiction = $_POST["OtherAddiction"] . ",";
  //Get all the values of the check boxes
  if (!empty($_POST['Behavior'])) {
    // Loop to store and display values of individual checked checkbox.
    foreach ($_POST['Behavior'] as $selected2) {
      $behavior .= $selected2 . ",";
    }
  }
  //Get all the values of the checkbox
  if (!empty($_POST['Addiction'])) {
    // Loop to store and display values of individual checked checkbox.
    foreach ($_POST['Addiction'] as $selected) {
      $addiction .= $selected . ",";
    }
  }
  //CheckBoxs
  $belongings = $_POST["OtherBelongings"] . ",";
  if (!empty($_POST['Belongings'])) {
    // Loop to store and display values of individual checked checkbox.
    foreach ($_POST['Belongings'] as $selected3) {
      $belongings .= $selected3 . ",";
    }
  }
  //Set the attributes
  $memObj->setMemberID($_POST['MemberID']);
  $memObj->setPhoto($imgContent);
  $memObj->setFullName($FullName);
  $memObj->setDateOfBirth($_POST['DOB']);
  $memObj->setGender($_POST['Gender']);
  $memObj->setMotherName($_POST['MoName']);
  $memObj->setReligion($_POST['Religion']);
  $memObj->setPlaceOfBirth($_POST['PB']);
  $memObj->setMaritalStatus($_POST['MS']);
  $memObj->setInitalLocation($_POST['IL']);
  $memObj->setAdmittedDate($_POST['AD']);
  $memObj->setHeight($_POST['Height']);
  $memObj->setWeight($_POST['Weight']);
  $memObj->setBehavior($behavior);
  $memObj->setAddiction($addiction);
  $memObj->setBelongings($belongings);
  $memObj->setHealthStatus($_POST['HealthStatus']);
  $memObj->setMedDes($_POST['MedDescription']);
  $memObj->setStatus($_POST['Status']);

  //Check if the member already exists
  $result = $memObj->checkMember();
  if ($result != "Empty") {
    echo "<script>alert('This member is already registered!');
        document.location = '/Register Member.php' </script>";
    //return $row[0];
  } else {
    $tipID = $_POST['TipID'];
    $empPresent = $_POST["EmpPresent"];
    $memObj->registerMember($tipID, $empPresent, $empID);
  }
}
//To proceed to Treatment Schedule after member registration
if (isset($_POST['RegisterWTS'])) {
  $_SESSION["memberID"] = $memberID;
  echo "<script>
      document.location = '/Schedule Treatment.php' </script>";
}
//No treatment schedule for the specific member
if (isset($_POST['RegisterWOTS'])) {
  echo "<script>
      document.location = '/Register Member.php' </script>";
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
  <title> Register Member</title>
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
  <link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
  <link rel='stylesheet' href='CSS/css/bootstrap.minn.css'>
  <link rel="stylesheet" href="CSS/css/style.css">
  <link rel="stylesheet" href="CSS/style.css">
  <!-- Including the Ajax Library -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<script>
  //To open the    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Confirmation Box that asks if the employee wants to proceed to Treatment Schedule or not
  function toModal() {
    var toAlter = document.getElementById("LName").value;
    var modal = document.getElementById("modal3");
    if (toAlter == "display") {
      modal.style.display = "block";
    }
  }
</script>

<body onload="toModal()" style="font-size:16px;">
  <?php include_once("HeaderEmployee.php"); ?>
  <!--content inner-->
  <div class="content__inner">
    <h4 class="card-title" style="text-align: center"><i class="fa fa-users" aria-hidden="true"></i>Member Registration</h4>
    <div class="container overflow-hidden">
      <!--multisteps-form-->
      <div class="multisteps-form">
        <!--progress bar-->
        <div class="row" id="forClose">
          <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
            <div class="multisteps-form__progress">
              <button class="multisteps-form__progress-btn js-active" type="button" title="Personal Info">Personal Info</button>
              <button class="multisteps-form__progress-btn" type="button" title="Member As Found">Member As Found</button>
              <button class="multisteps-form__progress-btn" type="button" title="Medical History">Medical History</button>
              <button class="multisteps-form__progress-btn" type="button" title="Credentials">Credentials</button>
            </div>
          </div>
        </div>
        <!--form panels-->
        <div class="row">
          <div class="col-12 col-lg-8 m-auto">
            <form class="multisteps-form__form" action="Register Member.php" method="POST" enctype="multipart/form-data" style="margin-bottom:130px;">
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideVert">
                <h3 class="multisteps-form__title">Your Personal Info</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label>Member ID</label>
                      <input class="multisteps-form__input form-control" type="text" id="MemberID" name="MemberID" placeholder="Member ID" value="<?php echo $memberID ?>" readonly />
                    </div>
                    <div class="col-12 col-sm-6">
                      <label>Photo</label>
                      <input class="multisteps-form__input form-control" type="file" id="Photo" name="Photo" placeholder="Photo" style="border:none; width:0px" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label>First Name</label>
                      <input class="multisteps-form__input form-control" type="text" id="FName" name="FName" placeholder="First Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="15" required />
                    </div>
                    <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                      <label>Middle Name:</label>
                      <input class="multisteps-form__input form-control" type="text" id="MName" name="MName" placeholder="Middle Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="15" required />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Last Name:</label>
                      <input class="multisteps-form__input form-control" type="text" name="LName" id="LName" placeholder="Last Name" value="<?php echo $val ?>" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="15" required />
                      <p><span id="validator"></span></p>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Date of Birth:</label>
                      <input class="multisteps-form__input form-control" type="date" placeholder="Date of Birth" name="DOB" id="dob" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                      <label>Gender</label>
                      <select class="multisteps-form__select form-control" id="Gender" name="Gender" required oninput="checkValidation()">
                        <option selected="selected">Please Choose</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>
                    <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                      <label>Mother's Name</label>
                      <input class="multisteps-form__input form-control" type="text" name="MoName" id="" placeholder="Mother Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="50" required />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                      <label>Religion</label>
                      <select class="multisteps-form__select form-control" id="Religion" name="Religion" required>
                        <option value="Please Choose">Please Choose</option>
                        <option value="Orthodox">Orthodox</option>
                        <option value="Protestant">Protestant</option>
                        <option value="Muslim">Muslim</option>
                        <option value="Catholic">Catholic</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                    <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                      <label>Place of Birth</label>
                      <input class="multisteps-form__input form-control" type="text" name="PB" id="" placeholder="Place of Birth" maxlength="30" required />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Maritial Status</label>
                      <select class="multisteps-form__select form-control" id="MS" name="MS" required>
                        <option value="Please Choose">Please Choose</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Widowed">Widowed</option>
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
                <h3 class="multisteps-form__title">Member's condition</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Tip</label>
                      <select class="multisteps-form__select form-control" id="tipID" name="TipID" required oninput="displayAdditionalInfo()">
                        <option value="Please Choose">Please Choose</option>
                        <?php
                        for ($i = 0; $i < sizeof($tipIDs); $i++) {
                          echo "<option value='$tipIDs[$i]'>";
                          _e($tipIDs[$i] . ":" . $location[$i]);
                          echo "</option>";
                        }
                        ?>
                        <option value="None">None</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Initial Location</label>
                      <select class="multisteps-form__select form-control" id="IL" name="IL" required>
                        <option value="Please Choose">Please Choose</option>
                        <option value="Hospital">Hospital</option>
                        <option value="Streets">Streets</option>
                        <option value="Police Station">Police Station</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Admitted Date:</label>
                      <input class="multisteps-form__input form-control" type="date" placeholder="Admitted Date" name="AD" />
                    </div>
                  </div>
                  <div class="form-row mt-4" name="additionalInfo" style="display: none;">
                    <div class="col">
                      <label>Behavior:</label>
                      <div style="display: inline-block;">
                        <label for="name">Suicidal</label>
                        <input type="checkbox" id="" name="Behavior[]" value="Suicidal">
                        <label for="name">Yelling</label>
                        <input type="checkbox" id="" name="Behavior[]" value="Yelling">
                        <label for="name">Laughing</label>
                        <input type="checkbox" id="" name="Behavior[]" value="Laughing">
                        <label for="name">Agressive</label>
                        <input type="checkbox" id="" name="Behavior[]" value="Agressive">
                        <label for="name">Crying</label>
                        <input type="checkbox" id="" name="Behavior[]" value="Crying">
                        <label for="name">Fainting</label>
                        <input type="checkbox" id="" name="Behavior[]" value="Fainting">
                      </div>
                      <div class="col">
                        <input class="multisteps-form__input form-control" type="text" placeholder="Other" name="OtherBehavior" />
                      </div>
                    </div>
                  </div>
                  <div class="form-row mt-4" name="additionalInfo" style="display: none;">
                    <div class="col">
                      <label>Addictions:</label>
                      <div style="display: inline-block;">
                        <label for="name">Cigarette</label>
                        <input type="checkbox" id="" name="Addiction[]" value="Cigarette">
                        <label for="name">Chat</label>
                        <input type="checkbox" id="" name="Addiction[]" value="Chat">
                        <label for="name">Alcohol</label>
                        <input type="checkbox" id="" name="Addiction[]" value="Alcohol">
                        <label for="name">Drugs</label>
                        <input type="checkbox" id="" name="Addiction[]" value="Drugs">
                      </div>
                      <div class="col">
                        <input class="multisteps-form__input form-control" type="text" placeholder="Other" name="OtherAddiction" />
                      </div>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Belongings:</label>
                      <div style="display: inline-block;">
                        <label for="name">Money</label>
                        <input type="checkbox" id="" name="Belongings[]" value="Money">
                        <label for="name">ID</label>
                        <input type="checkbox" id="" name="Belongings[]" value="ID">
                        <label for="name">Photo</label>
                        <input type="checkbox" id="" name="Belongings[]" value="Photo">
                        <label for="name">Clothes</label>
                        <input type="checkbox" id="" name="Belongings[]" value="Clothes">
                      </div>
                      <div class="col">
                        <input class="multisteps-form__input form-control" type="text" placeholder="Other" name="OtherBelongings" />
                      </div>
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
                <h3 class="multisteps-form__title">Medical history</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                    <label>Height (m)</label>
                      <input class="multisteps-form__input form-control" type="text" name="Height" id="" placeholder="Height (m)" />
                    </div>
                    
                    <div class="col-12 col-sm-6">
                    <label>Weight (kg)</label>
                      <input class="multisteps-form__input form-control" type="text" name="Weight" id="" placeholder="Weight (kg)" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Health Status</label>
                      <select class="multisteps-form__select form-control" name="HealthStatus" id="HealthStatus">
                        <option value="Please Choose">Please Choose</option>
                        <option value="Normal">Normal</option>
                        <option value="Needs Medical Attention">Needs Medical Attention</option>
                        <option value="Critical">Critical</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-row mt-4" id="critical">
                    <div class="col">
                      <label>Description:</label>
                      <textarea class="multisteps-form__input form-control" type="text" placeholder="" name="MedDescription" maxlength="1000"></textarea>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <select class="multisteps-form__select form-control" name="Status" id="p">
                        <option selected="selected">Status</option>
                        <option value="Resident">Resident</option>
                        <option value="Deceased">Deceased</option>
                        <option value="Rehabilitated">Rehabilitated</option>
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
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                <h3 class="multisteps-form__title">Credentials</h3>
                <div class="form-row mt-4">
                  <div class="col">
                    <label>Emp Present</label>
                    <input class="multisteps-form__input form-control" type="text" placeholder="Employee Present" name="EmpPresent" />
                  </div>
                </div>
                <div class="button-row d-flex mt-4">
                  <button class="btn btn-primary js-btn-prev button-circle-dark" type="button" title="Prev">Prev</button>
                  <button class="btn btn-success ml-auto" type="submit" title="Send" name="Register">Register</button>
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
  <div class="card-body">
    <!-- Modal -->
    <div class="modal" id="modal3" tabindex="-1" role="dialog" aria-hidden="true" style="height:fit-content;">
      <div class="modal-dialog" role="document" id="toLoad1">
        <div class="modal-content" id="forMember">
          <div class="modal-header no-bd">
            <h5 class="modal-title">
              <span class="fw-mediumbold"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Confirmation Box
              </span>
            </h5>
            <button type="button" class="close" aria-label="Close" style="width:15%" onclick="Hide()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p class="small"> Do you want to proceed with adding a treatment schedule for this member?</p>
            <form class="multisteps-form__form" action="Register Member.php" method="POST">
          </div>
          <div class="modal-footer no-bd">
            <button class="btn btn-primary js-btn-success" type="submit" name="RegisterWTS">Yes</button>
            <button class="btn btn-prev ml-auto" type="submit" name="RegisterWOTS">No</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
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
  <script src="table/assets/js/jquery.min.js"></script>
  <script src="table/assets/js/bootstrap.min.js"></script>
  <script src="table/assets/js/datatables.min.js"></script>
  <script>
    //Hide the dialog box of Add/ Update Ban Account
    function Hide() {
      var modal = document.getElementById("modal4");
      modal.style.display = "none";
    }
  </script>
  <script>
    //Add values from the main page to the dialog box
    function setValues() {
      var memID = document.getElementById("MemberID").value;
      window.document.getElementById("belongsTo").value = memID;
    }
    //JQuery code to display the dialog box in Manage Bank Account here.
    $("#HealthStatus").on('input', function() {
      var str = $("#HealthStatus").val();
      alert(str);
      if (str == "Critical") {
        $("#modal4").show();
        $("#modal-body").load("http://mekedonia.com:8080/ManageBankAccount.php #toLoad", function(responseTxt, statusTxt, xhr) {
          if (statusTxt == "success")
            alert("External content loaded successfully!");
          if (statusTxt == "error")
            alert("Error: " + xhr.status + ": " + xhr.statusText);
          setValues();
        });
      }
    });
  </script>
  <!-- Javascript -->
  <script src='CSS/css/bootstrap.minn.css'></script>
  <script src='CSS/SliderJs/js/jq.js'></script>
  <script src="CSS/SliderJs/js/script.js"></script>
  <script src="CSS/assets/plugins/jquery.min.js"></script>
  <script src="CSS/assets/plugins/plugins.js"></script>
  <script src="CSS/assets/js/functions.min.js"></script>
</body>

</html>