<?php
include_once('PHP/Volunteer.php');
error_reporting(0);
session_start();
//If the volunteer ID is not set then the page is being accessed without authorization
if (!isset($_SESSION["VolID"])) {
	header("Location: Login.php");
	exit();
}
//Information for the header
if ($_SESSION["FullName"] && $_SESSION["Email"]) {
	$volID = $_SESSION["VolID"];
	$fullName = $_SESSION["FullName"];
	$userName = $_SESSION["UserName"];
	$email = $_SESSION["Email"];
	$photo = $_SESSION["Photo"];
	$Occupation = $_SESSION["Occupation"];
}

//Call the Volunteer class
$volObj = new Volunteer();
$volObj->setOccupation($Occupation);
//Retrieve any requests made towards the volunteer
$accept = $volObj->getRequest();

//To prevent XSS Attack
function _e($string)
{
	echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Requests</title>
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
	<link rel="stylesheet" href="CSS/table/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
	<!-- Poppin font for table -->
	<link rel="stylesheet" href="CSS/css/style.css">
	<link rel="stylesheet" href="CSS/style.css">
</head>

<body>
	<?php include_once("HeaderVolunteer.php"); ?>

	<body>
		<!-- Blog section  -->
		<div class="section">
			<div class="container">
				<!-- Blog Posts -->
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header" style="display:flex;">
								<h4 class="card-title"><i class="fa fa-users" aria-hidden="true"></i>Requests</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="basic-datatables" class="display table table-striped table-hover">
										<thead>
											<tr>
												<th>Type</th>
												<th>Duration</th>
												<th>Number of People</th>
												<th>Start Time</th>
												<th>End Time</th>
												<th>Work Requirement</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$actID = $accept[0];
											$type = $accept[1];
											$duration = $accept[2];
											$noOfPeople = $accept[3];
											$startTime = $accept[4];
											$endTime = $accept[5];
											$skill = $accept[6];
											//Display the requests made
											for ($i = 0; $i < sizeof($actID); $i++) {
												echo "<tr>";
												echo "<td>";
												_e($type[$i]);
												echo "</td>";
												echo "<td>$duration[$i]</td>";
												echo "<td>$noOfPeople[$i]</td>";
												echo "<td>$startTime[$i]</td>";
												echo "<td>$endTime[$i]</td>";
												echo "<td>";
												_e($skill[$i]);
												echo "</td>";
												echo '<td><a href="Schedule Volunteer.php"><i class="fa fa-arrow-right"></i></a></td>';
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- end row -->
		</div><!-- end container -->
		<!-- end Blog section -->
		<?php include_once("Footer.html"); ?>
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
		<!-- Javascript-->
		<script src="CSS/table/assets/js/jquery.min.js"></script>
		<script src="CSS/table/assets/js/bootstrap.min.js"></script>
		<script src="CSS/table/assets/js/datatables.min.js"></script>
		<script src="CSS/assets/plugins/jquery.min.js"></script>
		<script src="CSS/assets/plugins/plugins.js"></script>
		<script src="CSS/assets/js/functions.min.js"></script>
		<script src='CSS/css/bootstrap.minn.css'></script>
		<script src='CSS/SliderJs/js/jq.js'></script>
		<script src="CSS/SliderJs/js/script.js"></script>
	</body>

</html>