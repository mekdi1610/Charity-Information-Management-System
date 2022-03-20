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
//Get all the contact information for contributors
$accept = $conObj->viewContactInfo();
$acceptVol = $accept[0];
$acceptDon = $accept[1];
$acceptVis = $accept[2];
$OccArray = array();
$size = 0;
//Retrieve  volunteer's information
for ($i = 0; $i < sizeof($acceptVol); $i++) {
  $Vol = $acceptVol[$i];
  array_push($OccArray, $Vol[5]);
}
//Retrieve  Donator's information
for ($i = 0; $i < sizeof($acceptDon); $i++) {
  $Don = $acceptDon[$i];
  array_push($OccArray, $Don[5]);
}
//Retrieve  Visitor's information
for ($i = 0; $i < sizeof($acceptVis); $i++) {
  $Vis = $acceptVis[$i];
  array_push($OccArray, $Vis[5]);
}

//Search for contributor contact based on occupation
if (isset($_POST['Search'])) {
  $OccForSearch = $_POST["Occupation"];
  $accept = $conObj->searchByOccupation($OccForSearch);
  $acceptVol = $accept[0];
  $acceptDon = $accept[1];
  $acceptVis = $accept[2];
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
  <title>Contributor Contacts</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Icon -->
  <link rel="shortcut icon" href="CSS/images/dove4.ico" />
  <!-- Accordion CSS -->
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
  <link rel="stylesheet" href="CSS/table/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
  <link rel="stylesheet" href="CSS/style.css">
</head>

<body>
  <?php include_once("HeaderEmployee.php"); ?>

  <body>
    <div class="container">
      <!-- Blog Posts -->
      <div class="row">
        <div class="col-md-12">
          <div class="card" style="padding:0px 28px;">
            <div class="card-header">
              <h4 class="card-title"><i class="fa fa-address-book" aria-hidden="true"></i>Contributor Contact </h4>
            </div>
            <form class="form-horizontal row-fluid" action="View Contributor Contact.php" method="POST">
              <div class="control-group">
                <label class="control-label" for="">Occupation</label>
                <div class="controls">
                  <select id="" name="Occupation">
                    <option value="Please Choose">Please Choose</option>
                    <?php
                    //Populate the drop down with retrieved occupation
                    $OccArray = array_unique($OccArray);
                    foreach ($OccArray as $key => $value)
                      echo "<option value='$OccArray[$key]'>" . $OccArray[$key] . "</option>";
                    ?>
                  </select>
                </div>
              </div>
              <div><button type="submit" name="Search">Search</button></div>
            </form>
            <ul class="accordion">
              <li class="active">
                <div class="accordion-title">
                  <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Volunteer</h6>
                </div>
                <div class="accordion-content">
                  <div class="card-body">
                    <div class="table-responsive">
                      <form class="form-horizontal row-fluid" action="Update Schedule Treatment.php" method="post">
                        <table id="basic-datatables" class="display table table-bordered">
                          <thead>
                            <tr>
                              <th>Full Name</th>
                              <th>Phone No.</th>
                              <th>Email</th>
                              <th>Occupaton</th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                            <?php
                            //Display volunteer's information
                            for ($i = 0; $i < sizeof($acceptVol); $i++) {
                              echo "<tr>";
                              $Vol = $acceptVol[$i];
                              echo '<td>';
                              echo '<a href="#modal" data-toggle="modal" data-fullname="' . $Vol[0] . '" data-gender="' . $Vol[1] . '" data-phone="' . $Vol[2] . '" data-email="' . $Vol[3] . '" data-address="' . $Vol[4] . '" data-occupation="' . $Vol[5] . '" data-workplace="' . $Vol[6] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show" onmouseover="display(this)">';
                              _e($Vol[0]);
                              echo '</a></td>';
                              echo "<td> $Vol[2]</td>";
                              echo "<td>";
                              _e($Vol[3]);
                              echo "</td>";
                              echo "<td>";
                              _e($Vol[5]);
                              echo "</td>";
                              echo "</tr>";
                            }
                            ?>
                          </tbody>
                        </table>
                    </div>
              </li>
              <!--Donator//////////////////////////////////////////////////-->
              <li>
                <div class="accordion-title">
                  <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Donator</h6>
                </div>
                <div class="accordion-content">
                  <div class="card-body">
                    <div class="table-responsive">
                      <form class="form-horizontal row-fluid" action="Update Schedule Treatment.php" method="post">
                        <table class="display table table-bordered">
                          <thead>
                            <tr>
                              <th>Full Name</th>
                              <th>Phone No.</th>
                              <th>Email</th>
                              <th>Occupaton</th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                            <?php
                            //Display donator's information
                            for ($i = 0; $i < sizeof($acceptDon); $i++) {
                              echo "<tr>";
                              $Don = $acceptDon[$i];
                              echo '<td>';
                              echo '<a href="#modal" data-toggle="modal" data-fullname="' . $Don[0] . '" data-gender="' . $Don[1] . '" data-phone="' . $Don[2] . '" data-email="' . $Don[3] . '" data-address="' . $Don[4] . '" data-occupation="' . $Don[5] . '" data-workplace="' . $Don[6] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show" onmouseover="display(this)">';
                              _e($Don[0]);
                              echo '</a></td>';
                              echo "<td> $Don[2]</td>";
                              echo "<td>";
                              _e($Don[3]);
                              echo "</td>";
                              echo "<td>";
                              _e($Don[5]);
                              echo "</td>";
                              echo "</tr>";
                            }
                            ?>
                          </tbody>
                        </table>
                    </div>
              </li>
              <!--Visitor//////////////////////////////////////////////-->
              <li>
                <div class="accordion-title">
                  <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Visitor</h6>
                </div>
                <div class="accordion-content">
                  <div class="card-body">
                    <div class="table-responsive">
                      <form class="form-horizontal row-fluid" action="Update Schedule Treatment.php" method="post">
                        <table id="basic-datatables" class="display table table-bordered">
                          <thead>
                            <tr>
                              <th>Full Name</th>
                              <th>Phone No.</th>
                              <th>Email</th>
                              <th>Occupaton</th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                            <?php
                            //Display visitor's information
                            for ($i = 0; $i < sizeof($acceptVis); $i++) {
                              echo "<tr>";
                              $Vis = $acceptVis[$i];
                              echo '<td>';
                              echo '<a href="#modal" data-toggle="modal" data-fullname="' . $Vis[0] . '" data-gender="' . $Vis[1] . '" data-phone="' . $Vis[2] . '" data-email="' . $Vis[3] . '" data-address="' . $Vis[4] . '" data-occupation="' . $Vis[5] . '" data-workplace="' . $Vis[6] . '" name="Set" class="btn btn-link btn-primary btn-lg modal-trigger" title="show" onmouseover="display(this)">';
                              _e($Vis[0]);
                              echo '</a></td>';
                              echo "<td> $Vis[2]</td>";
                              echo "<td>";
                              _e($Vis[3]);
                              echo "</td>";
                              echo "<td>";
                              _e($Vis[5]);
                              echo "</td>";
                              echo "</tr>";
                            }
                            ?>
                          </tbody>
                        </table>
                    </div>
              </li>
            </ul>
            </form>
            <div class="card-body">
              <!-- Modal -->
              <div class="modal" id="modal" tabindex="-1" role="dialog" aria-hidden="true" style="top:50px; height:fit-content">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header no-bd">
                      <h5 class="modal-title">
                        <i class="fa fa-address-card" aria-hidden="true"></i> <span class="fw-mediumbold"> Contact Info.
                        </span>
                      </h5>
                      <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close" onclick="hide()">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <p class="small"></p>
                      <form class="form-horizontal row-fluid" method="post" action="View Contributor Contact.php">
                        <div class="row">
                          <div class="control-group">
                            <label class="control-label" for="fullname">Full Name:</label>
                            <div class="controls">
                              <input type="text" class="contacts" id="fullname" name="fullname" class="span12" style="border:none; background:white;" disabled>
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="ID">Gender</label>
                            <div class="controls">
                              <input type="text" class="contacts" id="gender" name="gender" class="span12" style="border:none; background:white;" disabled>
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="Type">Phone No.</label>
                            <div class="controls">
                              <input type="text" class="contacts" id="phone" name="Type" class="span12" style="border:none; background:white;" disabled>
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="name">Email:</label>
                            <div class="controls">
                              <input type="text" class="contacts" id="email" placeholder="" name="TN" class="span12" style="border:none; background:white;" disabled>
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="name">Address:</label>
                            <div class="controls">
                              <input type="text" class="contacts" id="address" placeholder="" name="Dose" class="span12" style="border:none; background:white;" disabled>
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="name">Occupation:</label>
                            <div class="controls">
                              <input type="text" class="contacts" id="occupation" placeholder="" name="SD" class="span12" style="border:none; background:white;" disabled>
                            </div>
                          </div>
                          <div class="control-group">
                            <label class="control-label" for="name">Work Place:</label>
                            <div class="controls">
                              <input type="text" class="contacts" id="workplace" placeholder="" name="ED" class="span12" style="border:none; background:white;" disabled>
                            </div>
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
    <?php include("Footer.html"); ?>
    <!-- Javascript -->
    <script src="table/assets/js/jquery.min.js"></script>
    <script src="table/assets/js/bootstrap.min.js"></script>
    <script src="table/assets/js/datatables.min.js"></script>
    <script src="CSS/assets/plugins/jquery.min.js"></script>
    <script src="CSS/assets/plugins/plugins.js"></script>
    <script src="CSS/assets/js/functions.min.js"></script>
    <script>
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
      function display(get) {
        //alert("hello");
        var modal = document.getElementById("modal");
        var fullname = $(get).data('fullname');
        var gender = $(get).data('gender');
        var phone = $(get).data('phone');
        var email = $(get).data('email');
        var address = $(get).data('address');
        var occupation = $(get).data('occupation');
        var workplace = $(get).data('workplace');
        $("#fullname").val(fullname);
        $("#gender").val(gender);
        $("#phone").val(phone);
        $("#email").val(email);
        $("#address").val(address);
        $("#occupation").val(occupation);
        $("#workplace").val(workplace);
        modal.style.display = "block";
      }

      function hide() {
        //alert("none");
        var modal = document.getElementById("modal");
        modal.style.display = "none";
      }
    </script>
</body>

</html>