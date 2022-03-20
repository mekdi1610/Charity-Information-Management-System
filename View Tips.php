<?php
include_once('PHP/Tip.php');
error_reporting(0);
session_start();
//If the employee ID is not set then the page is being accessed without authorization
if (!isset($_SESSION["EmpID"])) {
	header("Location: Login.php");
	exit();
}

//Call the Tip class
$tipObj = new Tip();
//To view tips that are already seen
$accept = $tipObj->viewTips('Yes');
//To view tips that are not seen
$notify = $tipObj->viewTips('No');
//Status is "Seen"
$tipIDNew = $notify[0];
$tipperNameNew = $notify[1];
$tipperPhoneNoNew = $notify[2];
$genderNew = $notify[3];
$locationNew = $notify[4];
$behaviorNew = $notify[5];
$addicitionNew = $notify[6];
$otherInfoNew = $notify[7];
//Status is "Not Seen"
$tipID = $accept[0];
$tipperName = $accept[1];
$tipperPhoneNo = $accept[2];
$gender = $accept[3];
$location = $accept[4];
$behavior = $accept[5];
$addicition = $accept[6];
$otherInfo = $accept[7];

//To prevent XSS Attack
function _e($string)
{
	echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>View Tips</title>
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

<body onExit="changeSeen()">
	<?php include_once("HeaderEmployee.php"); ?>
	<!-- Blog section  -->
	<div class="container">
		<!-- Blog Posts -->
		<div>
			<h4 class="card-title" style="text-align: center"><i class="fa fa-envelope-open" aria-hidden="true"></i>Tips</h4>
		</div>
		<ul class="accordion">
			<li class="active">
				<div class="accordion-title">
					<h6 class="font-family-tertiary font-small font-weight-normal uppercase">New Tips</h6>
				</div>
				<div class="accordion-content">
					<!-- Blog Posts -->
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table id="basic-datatables" class="display table table-hover">
											<thead>
												<tr>
													<th>Name</th>
													<th>Phone No.</th>
													<th>Gender</th>
													<th>Location</th>
													<th>Behavior</th>
													<th>Addicition</th>
													<th>Related Info</th>
												</tr>
											</thead>
											<tbody>
												<?php
												//To display new tips sent to the organization
												for ($i = 0; $i < sizeof($tipIDNew); $i++) {
													echo "<tr>";
													echo "<td>" . $tipperNameNew[$i] . "</td>";
													echo "<td> $tipperPhoneNoNew[$i] </td>";
													echo "<td> $genderNew[$i] </td>";
													echo "<td>";
													_e($locationNew[$i]);
													echo "</td>";
													echo "<td>";
													_e($behaviorNew[$i]);
													echo "</td>";
													echo "<td>";
													_e($addicitionNew[$i]);
													echo "</td>";
													echo "<td>";
													_e($otherInfoNew[$i]);
													echo "</td>";
													echo "</tr>";
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
			</li>
			<!--Previous//////////////////////////////////////////////////-->
			<li>
				<div class="accordion-title">
					<h6 class="font-family-tertiary font-small font-weight-normal uppercase">Previous Tips</h6>
				</div>
				<div class="accordion-content">
					<div class="card-body">
						<div class="table-responsive">
							<table id="basic-datatables" class="display table table-hover">
								<thead>
									<tr>
										<th>Name</th>
										<th>Phone No.</th>
										<th>Gender</th>
										<th>Location</th>
										<th>Behavior</th>
										<th>Addicition</th>
										<th>Related Info</th>
									</tr>
								</thead>
								<tfoot>
								</tfoot>
								<tbody>
									<?php
									//To display previously sent to the organization
									for ($i = 0; $i < sizeof($tipID); $i++) {
										echo '<tr>';
										echo "<td>";
										_e($tipperName[$i]);
										echo "</td>";
										echo "<td> $tipperPhoneNo[$i] </td>";
										echo "<td> $gender[$i] </td>";
										echo "<td>";
										_e($location[$i]);
										echo "</td>";
										echo "<td>";
										_e($behavior[$i]);
										echo "</td>";
										echo "<td>";
										_e($addicition[$i]);
										echo "</td>";
										echo "<td>";
										_e($otherInfo[$i]);
										echo "</td>";
										echo "</tr>";
									}
									?>
								</tbody>
							</table>
						</div>
						<div>
						</div>
			</li>
		</ul>
	</div>
	<script>
		function changeSeen() {
			<?php
			include_once('PHP/Tip.php');
			$tipObj = new Tip();
			$tipObj->changeStatus();
			?>
		}
	</script>
	<?php include_once("Footer.html") ?>
	<!-- Javascript -->
	<script src="CSS/table/assets/js/jquery.min.js"></script>
	<script src="CSS/table/assets/js/bootstrap.min.js"></script>
	<script src="CSS/table/assets/js/datatables.min.js"></script>
	<script src="CSS/assets/plugins/jquery.min.js"></script>
	<script src="CSS/assets/plugins/plugins.js"></script>
	<script src="CSS/assets/js/functions.min.js"></script>
</body>

</html>