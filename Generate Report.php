<?php
include_once('PHP/Report.php');
error_reporting(0);
session_start();
//If the role is not Administrator then the page is being accessed without authorization
if ($_SESSION["Role"] != "Administrator") {
  header("Location: Login.php");
  exit();
}
//Call the Report class
$repObj = new Report();
$gender = null;
$rel = null;
$iL = null;
$ms = null;
$sta = null;
$type = null;
$genderForCon = null;
$Occupation = null;
//To generate report with member information
if (isset($_POST['GenerateForMember'])) {
  if (isset($_POST['Gender'])) {
    $gender = $_POST['Gender'];
  }
  if (isset($_POST['Religion'])) {
    $rel = $_POST['Religion'];
  }
  if (isset($_POST['IL'])) {
    $iL = $_POST['IL'];
  }
  if (isset($_POST['MS'])) {
    $ms = $_POST['MS'];
  }
  if (isset($_POST['Status'])) {
    $sta = $_POST['Status'];
  }
  $report = $repObj->generateMemReport($gender, $rel, $ms, $iL, $sta);
  $memID = $report[0];
  //Display the dialog box holding the retrieved information
  echo '<style type="text/css">
    #modal {
        display: block;
    }
    </style>';
}
//To print member's report
if (isset($_POST['PrintForMember'])) {
  $repObj->printMemReport($gender, $rel, $ms, $iL, $sta);
}
//To generate report using contributor's data
if (isset($_POST['GenerateForContributor'])) {
  if (isset($_POST['Gender'])) {
    $gender = $_POST['Gender'];
  }
  if (isset($_POST['Type'])) {
    $type = $_POST['Type'];
  }
  if (isset($_POST['Occupation'])) {
    $Occupation = $_POST['Occupation'];
  }
  $reportForCon = $repObj->generateConReport($type, $gender, $Occupation);
  //Display the dialog box holding the retrieved information
  echo '<style type="text/css">
    #modal2 {
        display: block;
    }
    </style>';
}
//To print contributor's report
if (isset($_POST['PrintForCon'])) {
  $repObj->printConReport($type, $gender, $Occupation);
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
  <title>Reports</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Icon -->
  <link rel="shortcut icon" href="CSS/images/dove4.ico" />
  <!-- Accordoin CSS -->
  <link href="CSS/assets/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
  <link href="CSS/assets/css/theme.min.css" rel="stylesheet">
  <link href="CSS/assets/css/splash.min.css" rel="stylesheet">
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
<header class="site-header">
  <div class="top-header-bar">
    <div class="container">
      <div class="row flex-wrap justify-content-center justify-content-lg-between align-items-lg-center">
        <div class="col-12 col-lg-8 d-none d-md-flex flex-wrap justify-content-center justify-content-lg-start mb-3 mb-lg-0">
          <div class="header-bar-email">
            <a href="https://www.facebook.com/mekedoniahomes"><img src="CSS/images/facebook-24.png" alt=""></a>
          </div><!-- .header-bar-email -->
          <div class="header-bar-text">
            <a href="https://www.gofundme.com/f/mekedonia-charity-help-build-home-for-the-homeless"><img src="CSS/images/bird-2-24.png" alt="">
          </div><!-- .header-bar-text -->
          <div class="header-bar-text">
            <a href="#"><img src="CSS/images/phone-24.png" alt=""></a>
          </div>
        </div><!-- .col -->
        <div class="col-12 col-lg-4 d-flex flex-wrap justify-content-center justify-content-lg-end align-items-center">
          <div class="">
            <a href="Logout.php"><img src="CSS/images/logout-24.png" alt=""></a>
          </div>
        </div><!-- .col -->
      </div><!-- .row -->
    </div><!-- .container -->
  </div><!-- .top-header-bar -->
</header><!-- .site-header -->

<body>
  <!-- Blog Posts -->
  <div class="row">
    <div class="col-md-12">
      <div class="card" style="float: left; width:50%; height:600px">
        <div class="card-header" style="display:flex;">
          <h4 class="card-title">Members</h4>
        </div>
        <form method="post" action="Generate Report.php">
          <ul class="accordion single-open">
            <li class="active">
              <div class="accordion-title">
                <h6 class="font-family-tertiary font-small font-weight-medium uppercase">Gender</h6>
              </div>
              <div class="accordion-content">
                <div class="control-group">
                  <input type="checkbox" id="male" name="Gender" value="Male" class="span12">
                  <label for="ID">Male</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="Gender" value="Female" class="span12">
                  <label for="ID">Female</label>
                </div>
              </div>
            </li>
            <li>
              <div class="accordion-title">
                <h6 class="font-family-tertiary font-small font-weight-medium uppercase">Religion</h6>
              </div>
              <div class="accordion-content">
                <div class="control-group">
                  <input type="checkbox" id="male" name="Religion" value="Orthodox" class="span12">
                  <label for="ID">Orthodox</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="Religion" value="Protestant" class="span12">
                  <label for="ID">Protestant</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="Religion" value="Muslim" class="span12">
                  <label for="ID">Muslim</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="Religion" value="Catholic" class="span12">
                  <label for="ID">Catholic</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="Religion" value="Other" class="span12">
                  <label for="ID">Other</label>
                </div>
              </div>
            </li>
            <li>
              <div class="accordion-title">
                <h6 class="font-family-tertiary font-small font-weight-medium uppercase">Martial Status</h6>
              </div>
              <div class="accordion-content">
                <div class="control-group">
                  <input type="checkbox" id="male" name="MS" value="Single" class="span12">
                  <label for="ID">Single</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="MS" value="Married" class="span12">
                  <label for="ID">Married</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="MS" value="Divorced" class="span12">
                  <label for="ID">Divorced</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="MS" value="Widowed" class="span12">
                  <label for="ID">Widowed</label>
                </div>
              </div>
            </li>
            <li>
              <div class="accordion-title">
                <h6 class="font-family-tertiary font-small font-weight-medium uppercase">Intial Location</h6>
              </div>
              <div class="accordion-content">
                <div class="control-group">
                  <input type="checkbox" id="male" name="IL" value="Hospital" class="span12">
                  <label for="ID">Hospital</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="IL" value="Street" class="span12">
                  <label for="ID">Street</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="IL" value="Police Station" class="span12">
                  <label for="ID">Police Station</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="IL" value="Other" class="span12">
                  <label for="ID">Other</label>
                </div>
              </div>
            </li>
            <li>
              <div class="accordion-title">
                <h6 class="font-family-tertiary font-small font-weight-medium uppercase">Status</h6>
              </div>
              <div class="accordion-content">
                <div class="control-group">
                  <input type="checkbox" id="male" name="Status" value="Resident" class="span12">
                  <label for="ID">Resident</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="Status" value="Deceased" class="span12">
                  <label for="ID">Deceased</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="Status" value="Rehabailtated" class="span12">
                  <label for="ID">Rehabailtated</label>
                </div>
              </div>
            </li>
            <div class="modal-footer no-bd">
              <button type="submit" class="button-rounded margin-top-20" name="GenerateForMember">Generate Report</button>
            </div>
          </ul>
        </form>
      </div>
      <!-- Second-->
      <div class="card" style="float: right; width:50%; height: 600px;">
        <div class="card-header" style="display:flex;">
          <h4 class="card-title">Contributor</h4>
        </div>
        <form method="post" action="Generate Report.php">
          <ul class="accordion single-open">
            <li class="active">
              <div class="accordion-title">
                <h6 class="font-family-tertiary font-small font-weight-medium uppercase">Type</h6>
              </div>
              <div class="accordion-content">
                <div class="control-group">
                  <input type="checkbox" id="male" name="Type" value="Volunteer" class="span12">
                  <label for="ID">Volunteer</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="Type" value="Visitor" class="span12">
                  <label for="ID">Visitor</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="Type" value="Donator" class="span12">
                  <label for="ID">Donator</label>
                </div>
              </div>
            </li>
            <li>
              <div class="accordion-title">
                <h6 class="font-family-tertiary font-small font-weight-medium uppercase">Gender</h6>
              </div>
              <div class="accordion-content">
                <div class="control-group">
                  <input type="checkbox" id="male" name="Gender" value="Male" class="span12">
                  <label for="ID">Male</label>
                </div>
                <div class="control-group">
                  <input type="checkbox" id="male" name="Gender" value="Female" class="span12">
                  <label for="ID">Female</label>
                </div>
              </div>
            </li>
            <li>
              <div class="accordion-title">
                <h6 class="font-family-tertiary font-small font-weight-medium uppercase">Occupation</h6>
              </div>
              <div class="accordion-content">
                <?php
                $Occ = $repObj->getOccupation();
                for ($i = 0; $i < sizeof($Occ); $i++) {
                  //Add occupation to drop down list
                  echo '<div class="control-group">';
                  echo "<input type='checkbox' id='occ' name='Occupation' value='$Occ[$i]' class='span12'>";
                  echo "<label for='ID'>";
                  _e($Occ[$i]);
                  echo "</label>";
                  echo '</div>';
                }
                ?>
              </div>
            </li>
            <div class="modal-footer no-bd" style="margin-top: 105px;">
              <button type="submit" class="button-rounded margin-top-20" name="GenerateForContributor">Generate Report</button>
            </div>
          </ul>
        </form>
      </div>
    </div>
  </div>
  <div class="card-body">
    <!-- Modal -->
    <div class="modal" id="modal" tabindex="-1" role="dialog" aria-hidden="true" style="margin: auto; width: 800px; padding: 10px; left: 0; border-radius: 25px; right: 0; top: 50px;">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 750px; left: -125px;">
          <div class="modal-header no-bd">
            <h5 class="modal-title">
              <span class="fw-mediumbold"> Filter Member's Data
              </span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="hide()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="overflow: auto;">
            <!-- Blog Posts -->
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="multi-filter-select" class="display table table-head-bg-success table-bordered table-striped table-hover">
                        <thead>
                          <tr>
                            <th style="border-radius:25px 0px 0px 0px;">No.</th>
                            <th style="border-radius:0px 0px 0px 0px;">Full Name</th>
                            <th style="border-radius:0px 25px 0px 0px;">Admitted Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          //Display member report
                          $memID = $report[0];
                          $Name = $report[1];
                          $AD = $report[2];
                          $no = 1;
                          for ($i = 0; $i < sizeof($memID); $i++) {
                            echo "<tr>";
                            echo "<td>$no</td>";
                            echo "<td>$Name[$i]</td>";
                            echo "<td>$AD[$i]</td>";
                            $no++;
                            echo "</tr>";
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <form method="post" action="Generate Report.php">
                  <div class="modal-footer no-bd">
                    <button type="submit" class="button-rounded margin-top-20" name="PrintForMember">Print Report</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--For Contributor-->
  <div class="card-body">
    <!-- Modal -->
    <div class="modal" id="modal2" tabindex="-1" role="dialog" aria-hidden="true" style="margin: auto; width: 800px; padding: 10px; left: 0; border-radius: 25px; right: 0; top: 50px;">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 750px; left: -125px;">
          <div class="modal-header no-bd">
            <h5 class="modal-title">
              <span class="fw-mediumbold"> Filter Contributor's Data
              </span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="hide()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="overflow: auto;">
            <!-- Blog Posts -->
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="multi-filter-select" class="display table table-head-bg-success table-bordered table-striped table-hover">
                        <thead>
                          <tr>
                            <th style="border-radius:25px 0px 0px 0px;">No.</th>
                            <th style="border-radius:0px 0px 0px 0px;">Full Name</th>
                            <th style="border-radius:0px 0px 0px 0px;">Phone No.</th>
                            <th style="border-radius:0px 0px 0px 0px;">Email</th>
                            <th style="border-radius:0px 25px 0px 0px;">Work Place</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          //Display contributor report
                          $fullName = $reportForCon[0];
                          $phoneNo = $reportForCon[1];
                          $email = $reportForCon[2];
                          $wp = $reportForCon[3];
                          $no = 1;
                          for ($i = 0; $i < sizeof($fullName); $i++) {
                            echo "<tr>";
                            echo "<td>$no</td>";
                            echo "<td>$fullName[$i]</td>";
                            echo "<td>$phoneNo[$i]</td>";
                            echo "<td>$email[$i]</td>";
                            echo "<td>$wp[$i]</td>";
                            $no++;
                            echo "</tr>";
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <form method="post" action="Generate Report.php">
                  <div class="modal-footer no-bd">
                    <button type="submit" class="button-rounded margin-top-20" name="PrintForCon">Print Report</button>
                  </div>
                </form>
                <script type='text/javascript' src='Javascript/Report.js'></script>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include("Footer.html"); ?>

  <!-- Javascript -->
  <script src="CSS/assets/plugins/jquery.min.js"></script>
  <script src="CSS/assets/plugins/plugins.js"></script>
  <script src="CSS/assets/js/functions.min.js"></script>
  <script src="CSS/table/assets/js/jquery.min.js"></script>
  <script src="CSS/table/assets/js/bootstrap.min.js"></script>
  <script src="CSS/table/assets/js/datatables.min.js"></script>
  <script>
    //JQuery code to generate basic database table
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
</body>

</html>