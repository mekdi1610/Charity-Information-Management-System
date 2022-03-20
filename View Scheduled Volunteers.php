<?php
include_once('PHP/VolunteerSchedule.php');
error_reporting(0);
session_start();
//If the employee ID is not set then the page is being accessed without authorization
if (!isset($_SESSION["EmpID"])) {
    header("Location: Login.php");
    exit();
}

//Call the Volunteer Schedule class
$volSchObj = new VolunteerSchedule();
//Get all the volunteers schedule 
$accept = $volSchObj->viewVolSchedule();
$volSchID = $accept[0];
$volID = $accept[1];
$fullName = $accept[2];
$dateTime = $accept[3];
$activity = $accept[4];
$size = 0;

//Retrieve volunteer schedule based on date
if (isset($_POST['Search'])) {
    $Date = $_POST["Date"];
    $volSchObj->setDate($Date);
    $accept = $volSchObj->searchByDate();
    $volSchID = $accept[0];
    $volID = $accept[1];
    $fullName = $accept[2];
    $dateTime = $accept[3];
    $activity = $accept[4];
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
    <title>Volunteer Schedules</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Icon -->
    <link rel="shortcut icon" href="CSS/images/dove4.ico" />
    <!-- Accordoin CSS -->
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
    <!-- Styles -->
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/table/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
</head>

<body>
    <?php include_once("HeaderEmployee.php"); ?>
    <div class="container">
        <!-- Blog Posts -->
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="padding:0px 28px;">
                    <div class="card-header">
                        <h4 class="card-title" style="text-align:center"><i class="fa fa-clock-o" aria-hidden="true"></i></i>Volunteer's Schedule</h4>
                    </div>
                    <form class="form-horizontal row-fluid" action="View Scheduled Volunteers.php" method="POST">
                        <div class="control-group">
                            <label class="control-label" for="Date">Date</label>
                            <div class="controls">
                                <input type="Date" id="Date" name="Date" class="span12">
                            </div>
                        </div>
                        <div><button type="submit" name="Search">Search</button></div>
                    </form>
                </div>
            </div>
        </div>
        <ul class="accordion">
            <li class="active">
                <div class="accordion-title">
                    <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Today's Schedules</h6>
                </div>
                <div class="accordion-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Vol ID</th>
                                        <th>Full Name</th>
                                        <th>Date</th>
                                        <th>Activity</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                    <?php
                                    //To display today's volunteer schedules
                                    for ($i = 0; $i < sizeof($volSchID); $i++) {
                                        $explode = explode(" ", $dateTime[$i]);
                                        $date = $explode[0];
                                        $time = $explode[1];
                                        $today = date("Y-m-d");
                                        if ($date == $today) {
                                            echo "<tr>";
                                            echo "<td> $volID[$i]</td>";
                                            echo "<td> $fullName[$i]</td>";
                                            echo "<td> $dateTime[$i]</td>";
                                            echo "<td>";
                                            _e($activity[$i]);
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
            </li>
            <!--Recurring//////////////////////////////////////////////////-->
            <li>
                <div class="accordion-title">
                    <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Recurring Schedules</h6>
                </div>
                <div class="accordion-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Vol ID</th>
                                        <th>Full Name</th>
                                        <th>Date</th>
                                        <th>Activity</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                    <?php
                                    //To display recurring volunteer schedules
                                    for ($i = 0; $i < sizeof($volSchID); $i++) {
                                        $explode = explode(" ", $dateTime[$i]);
                                        $date = $explode[0];
                                        $time = $explode[1];
                                        $today = date("Y-m-d");
                                        if (strtotime($date) > strtotime($today)) {
                                            echo "<tr>";
                                            echo "<td> $volID[$i]</td>";
                                            echo "<td> $fullName[$i]</td>";
                                            echo "<td> $dateTime[$i]</td>";
                                            echo "<td>";
                                            _e($activity[$i]);
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
            </li>
            <!--Previous//////////////////////////////////////////////-->
            <li>
                <div class="accordion-title">
                    <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Previous Schedules | Filter By Date</h6>
                </div>
                <div class="accordion-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Vol ID</th>
                                        <th>Full Name</th>
                                        <th>Date</th>
                                        <th>Activity</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                    <?php
                                    //To display previous volunteer schedules
                                    for ($i = 0; $i < sizeof($volSchID); $i++) {
                                        echo "<tr>";
                                        echo "<td> $volID[$i]</td>";
                                        echo "<td> $fullName[$i]</td>";
                                        echo "<td> $dateTime[$i]</td>";
                                        echo "<td>";
                                        _e($activity[$i]);
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
            </li>
        </ul>
    </div>
    </div>
    </div>
    </div>
    </div>
    <?php include("Footer.html"); ?>
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
        function display(get) {
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
        //To hide modal
        function hide() {
            var modal = document.getElementById("modal");
            modal.style.display = "none";
        }
    </script>
    <!-- Javascript -->
    <script src="CSS/table/assets/js/jquery.min.js"></script>
    <script src="CSS/table/assets/js/bootstrap.min.js"></script>
    <script src="CSS/table/assets/js/datatables.min.js"></script>
    <script src="CSS/assets/plugins/jquery.min.js"></script>
    <script src="CSS/assets/plugins/plugins.js"></script>
    <script src="CSS/assets/js/functions.min.js"></script>
</body>

</html>