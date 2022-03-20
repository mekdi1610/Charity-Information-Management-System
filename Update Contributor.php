<?php
include_once('PHP/Contributor.php');
error_reporting(0);
session_start();
//If the employee ID is not set then the page is being accessed without authorization
if (!isset($_SESSION["EmpID"])) {
  header("Location: Login.php");
  exit();
}

//Call the Contributor class
$conObj = new Contributor();

//To search for a contributor's contact information
if (isset($_POST['Search'])) {
  $TN = '';
  $colName = "";
  $conID = $_POST["IDForSearch"];
  $conObj->setConID($conID);
  $firstName = $_POST["FName"];
  $middleName = $_POST["MName"];
  $lastName = $_POST["LName"];
  $fullName = $firstName . " " . $middleName . " " . $lastName;
  $conObj->setFullName($fullName);
  //If the ID contain "Don" in it then search in Donator table
  if ((strpos($conID, "Don") !== false)) {
    $conObj->setTableName("Donator");
    $conObj->setColName("DonID");
  }
  //If the ID contain "Vis" in it then search in Visitor table
  if ((strpos($conID, "Vis") !== false)) {
    $conObj->setTableName("Visitor");
    $conObj->setColName("VisID");
  }
  //If the ID contain "Vol" in it then search in Volunteer table
  if ((strpos($conID, "Vol") !== false)) {
    $conObj->setTableName("Volunteer");
    $conObj->setColName("VolID");
  }

  //Search for the contributor
  $acceptArray = $conObj->searchContributor();
  //Search with contributor's ID
  if (empty($acceptArray)) {
    $TN = $conObj->getTableName();
    $ID = $conID;
    $FullName = $conObj->getFullName();
    $Gender = $conObj->getGender();
    $PhoneNo = $conObj->getPhoneNo();
    $Email = $conObj->getEmail();
    $Address = $conObj->getAddress();
    $Occupation = $conObj->getOccupation();
    $Workplace = $conObj->getWorkPlace();
  } else {
    //Search with contributor's fullname
    $accept = $acceptArray[0];
    $TN = $acceptArray[1];
    $ID = $accept[0];
    $FullName = $accept[1];
    $Gender = $accept[2];
    $PhoneNo = $accept[3];
    $Email = $accept[4];
    $Address = $accept[5];
    $Occupation = $accept[6];
    $Workplace = $accept[7];
    $EmpID = $accept[8];
  }
}

//Update the contributors information
if (isset($_POST['Update'])) {
  $TN = "";
  $colName = "";
  //Assignment
  $conObj->setConID($_POST["ConID"]);
  $conObj->setFullName($_POST['FullName']);
  $conObj->setGender($_POST['Gender']);
  $conObj->setPhoneNo($_POST['PhoneNo']);
  $conObj->setEmail($_POST['Email']);
  $conObj->setAddress($_POST['Address']);
  $conObj->setOccupation($_POST['Occupation']);
  $conObj->setWorkPlace($_POST['workPlace']);
  if ($_POST["Type"] == "Visitor") {
    $conObj->setTableName("Visitor");
    $conObj->setColName("VisID");
  } else if ($_POST["Type"] == "Donator") {
    $conObj->setTableName("Donator");
    $conObj->setColName("DonID");
  } else if ($_POST["Type"] == "Volunteer") {
    $conObj->setTableName("Volunteer");
    $conObj->setColName("VolID");
  }
  $conObj->updateContributor();
}

//Remove contributor from the list
if (isset($_POST['Remove'])) {
  $TN = "";
  $colName = "";
  //Assignment
  $conID = $_POST["ConID"];
  if ($_POST["Type"] == "Visitor") {
    $TN = "Visitor";
    $colName = 'VisID';
    echo "Vis";
  } else if ($_POST["Type"] == "Donator") {
    $TN = "Donator";
    $colName = 'DonID';
  } else if ($_POST["Type"] == "Volunteer") {
    $TN = "Volunteer";
    $colName = 'VolID';
  }
}
if (isset($_POST['Yes'])) {
  $TN = "";
  $colName = "";
  //Assignment
  $conObj->setConID($_POST["ConID"]);
  if ($_POST["Type"] == "Visitor") {
    $TN = "Visitor";
    $colName = 'VisID';
    echo "Vis";
  } else if ($_POST["Type"] == "Donator") {
    $TN = "Donator";
    $colName = 'DonID';
  } else if ($_POST["Type"] == "Volunteer") {
    $TN = "Volunteer";
    $colName = 'VolID';
  }
  $conObj->setTableName($TN);
  $conObj->setColName($colName);
  $conObj->removeContributor();
}
if (isset($_POST['No'])) {
  echo "<script>alert('Data was not removed');
  document.location = '/Update Contributor.php' </script>";
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
  <title>Update Contributor</title>
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
  <?php include_once("HeaderEmployee.php");
  ?>
  </head>

  <body>
    <!--content inner-->
    <div class="content__inner">
      <h4 class="card-title" style="text-align: center"><i class="fa fa-pencil" aria-hidden="true"></i>Update Contributor's Info</h4>
      <div class="container overflow-hidden">
        <!--multisteps-form-->
        <div class="multisteps-form">
          <!--progress bar-->
          <div class="row">
            <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
              <div class="multisteps-form__progress">
                <button class="multisteps-form__progress-btn js-active" type="button" title="Search" id="Search">Search</button>
                <button class="multisteps-form__progress-btn" type="button" title="Personal Info" id="ToHide">Personal Information</button>
                <button class="multisteps-form__progress-btn" type="button" title="Address" id="ToHide">Address</button>
              </div>
            </div>
          </div>
          <!--form panels-->
          <div class="row">
            <div class="col-12 col-lg-8 m-auto">
              <form class="multisteps-form__form" id="Search" action="Update Contributor.php" method="POST" enctype="multipart/form-data">
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideVert">
                  <h3 class="multisteps-form__title">Search</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col">
                        <input class="multisteps-form__input form-control" type="text" id="IDForSearch" name="IDForSearch" placeholder="Contributor ID" />
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <input class="multisteps-form__input form-control" type="text" id="FName" name="FName" placeholder="First Name" placeholder="First Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" />
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
                      <button class="btn btn-primary ml-auto js-btn-next" type="submit" title="Next" name="Search">Search</button>
                    </div>
                  </div>
                </div>
                <form class="multisteps-form__form" action="Update Contributor.php" method="POST" enctype="multipart/form-data" id="ToHide">
                  <!--single form panel-->
                  <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="slideVert">
                    <h3 class="multisteps-form__title">Your Personal Info</h3>
                    <div class="multisteps-form__content">
                      <div class="form-row mt-4">
                        <div class="col">
                          <label> Contributor ID </label>
                          <input class="multisteps-form__input form-control" type="text" id="ConID" name="ConID" value="<?php _e($ID) ?>" readonly/>
                        </div>
                      </div>
                      <div class="form-row mt-4">
                        <div class="col">
                          <label>Type</label>
                          <select class="multisteps-form__select form-control" id="type" name="Type">
                            <option selected="selected">Please Choose</option>
                            <option id="Visitor" value="Visitor">Visitor</option>
                            <option id="Donator" value="Donator">Donator</option>
                            <option id="Volunteer" value="Volunteer">Volunteer</option>
                          </select>
                        </div>
                      </div>
                      <?php
                      //To select a value from the select box
                      if (strpos($TN, "Visitor") !== false) {
                        echo "<script> document.getElementById('Visitor').selected = true</script>";
                      }
                      if (strpos($TN, "Volunteer") !== false) {
                        echo "<script> document.getElementById('Volunteer').selected = true</script>";
                      }
                      if (strpos($TN, "Donator") !== false) {
                        echo "<script> document.getElementById('Donator').selected = true</script>";
                      }
                      ?>
                      <div class="form-row mt-4">
                        <div class="col-12 col-sm-6">
                          <label> Full Name </label>
                          <input class="multisteps-form__input form-control" type="text" id="FullName" name="FullName" value="<?php _e($FullName) ?>" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" />
                        </div>
                        <div class="col-12 col-sm-6">
                          <label> Gender </label>
                          <select class="multisteps-form__select form-control" id="Gender" name="Gender" value="<?php _e($Gender) ?>">
                            <option selected="selected">Gender</option>
                            <option id="Male" value="Male">Male</option>
                            <option id="Female" value="Female">Female</option>
                          </select>
                        </div>
                      </div>
                      <?php
                      //To select a value from the select box
                      if (strpos($Gender, "Male") !== false) {
                        echo "<script> document.getElementById('Male').selected = true</script>";
                      }
                      if (strpos($Gender, "Female") !== false) {
                        echo "<script> document.getElementById('Female').selected = true</script>";
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
                    <h3 class="multisteps-form__title">Address</h3>
                    <div class="multisteps-form__content">
                      <div class="form-row mt-4">
                        <div class="col-12 col-sm-6">
                          <input class="multisteps-form__input form-control" type="number" id="Phone" name="PhoneNo" placeholder="Phone Number" value="<?php _e($PhoneNo) ?>" />
                        </div>
                        <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                          <input class="multisteps-form__input form-control" type="email" id="Email" name="Email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Wrong Format" value="<?php _e($Email) ?>" />
                        </div>
                      </div>
                      <div class="form-row mt-4">
                        <div class="col">
                          <label>Home Address:</label>
                          <input class="multisteps-form__input form-control" type="text" placeholder="Subcity | Woreda | House No" name="Address" value="<?php _e($Address) ?>" />
                        </div>
                      </div>
                      <div class="form-row mt-4">
                        <div class="col-12 col-sm-6">
                          <label> Occupation </label>
                          <input class="multisteps-form__input form-control" type="text" id="Occupation" name="Occupation" placeholder="Occupation" value="<?php _e($Occupation) ?>" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" />
                        </div>
                        <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                          <label> Work Place </label>
                          <input class="multisteps-form__input form-control" type="text" id="WP" name="workPlace" value="<?php _e($Workplace) ?>" />
                        </div>
                      </div>
                    </div>
                    <div class="button-row d-flex mt-4">
                      <button class="btn btn-success ml-auto" type="submit" title="Send" name="Update">Update</button>
                      <a href="#modal2" data-toggle="modal" data-id="<?php _e($ID) ?>" data-type="<?php _e($TN) ?>" style="width:100%;" class="modal-trigger" title="show"><button class="btn ml-auto" type="submit" title="Send" name="Remove">Remove</button></a>;
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
    <div class="card-body">
      <!-- Modal -->
      <div class="modal" id="modal2" tabindex="-1" role="dialog" aria-hidden="true" style="left:0; right:0;">
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
              <p class="small" style="padding-left:75px;"> Are you sure you want remove this contributor?</p>
              <form class="multisteps-form__form" action="Update Contributor.php" method="POST" enctype="multipart/form-data">
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col" style="padding-left:15px; padding-right:15px;">
                      <label> Contributor ID </label>
                      <input class="multisteps-form__input form-control" type="text" id="ID" name="ConID" readonly />
                    </div>
                  </div>
                  <div class="form-row mt-4" style="display:none;">
                    <div class="col" style="padding-left:15px; padding-right:15px;">
                      <label> Type </label>
                      <input class="multisteps-form__input form-control" type="text" id="Type" name="Type" />
                    </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer no-bd">
              <button class="btn btn-primary js-btn-success" type="submit" name="Yes">Yes</button>
              <button class="btn btn-prev ml-auto" type="submit" name="No">No</button>
            </div>
            </form>
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
      });
    </script>
    <script>
      //To display all the values from database on the update dialog box
      $(document).on("click", ".modal-trigger", function() {
        var ID = $(this).data('id');
        var Type = $(this).data('type');
        $("#ID").val(ID);
        $("#Type").val(Type);
      });
    </script>
  </body>

</html>