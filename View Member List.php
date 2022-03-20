<?php
include_once('PHP/Member.php');
error_reporting(0);
session_start();
//If the employee ID is not set then the page is being accessed without authorization
if (!isset($_SESSION["EmpID"])) {
	header("Location: Login.php");
	exit();
}

//Call the Member class
$memObj = new Member();
//Retrieve member information
$accept = $memObj->viewMember();
$memberID = $accept[0];
$photo = $accept[1];
$fullName = $accept[2];
$dob = $accept[3];
$status = $accept[4];

//To prevent XSS Attack
function _e($string)
{
	echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>View Member</title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Icon -->
	<link rel="shortcut icon" href="CSS/images/dove4.ico" />
	<!-- Picture CSS -->
	<link rel="stylesheet" href="CSS/assets/plugins/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="CSS/assets/css/theme.min.css">
	<link rel="stylesheet" href="CSS/assets/css/splash.min.css">
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
</head>

<body>
	<?php include_once("HeaderEmployee.php"); ?>
	<h4 class="card-title" style="text-align: center"><i class="fa fa-picture-o" aria-hidden="true"></i>View Members</h4>
	<div class="section">
		<div class="container text-center">
			<div class="row portfolio-wrapper splash-portfolio">
				<?php
				//To display the information of member
				for ($i = 0; $i < sizeof($fullName); $i++) {
					echo '<div class="col-6 col-lg-4 col-xl-3 portfolio-item category-resume">';
					echo '<div class="portfolio-box" data-sal="slide-up" data-sal-delay="40">';
					//On page 1
					echo '<a href="#modal" data-toggle="modal" data-fullname="' . $fullName[$i] . '" data-dob="' . $dob[$i] . '" data-status="' . $status[$i] . '" name="Set" title="show" onmouseover="display(this)"></a>';
					echo '<img class="img-circle-lg" src="data:image/jpg;charset=utf8;base64,' . base64_encode($photo[$i]) . '"/>';
					echo '<div class="padding-y-20 padding-bottom-0">';
					echo '<h6 class="font-small font-weight-normal uppercase margin-0" style="color: black; top:100%;">';
					echo '<a href="Update Member.php?ID=' . $memberID[$i] . '" target="_blank" style="color:black; top:100%">';
					_e($fullName[$i]);
					echo '</a>';
					echo '</h6>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}
				?>
			</div>
		</div><!-- end container -->
	</div>
	</div><!-- end row -->
	</div><!-- end container -->
	</div>

	<div class="card-body">
		<!-- Modal -->
		<div class="modal" id="modal" tabindex="-1" role="dialog" aria-hidden="true" style="top:20px; height:fit-content; left:50%;">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header no-bd">
						<h5 class="modal-title">
							<span class="fw-mediumbold"><i class="fa fa-list" aria-hidden="true"></i> Member's Information
							</span>
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="hide()" style="width:auto">
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
										<input type="text" id="fullname" name="fullname" class="span12" style="border:none; background:white;" disabled>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="ID">Date of Birth</label>
									<div class="controls">
										<input type="text" id="dob" name="dob" class="span12" style="border:none; background:white;" disabled>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="Type">Status</label>
									<div class="controls">
										<input type="text" id="status" name="status" class="span12" style="border:none; background:white;" disabled>
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
	<?php include_once("Footer.html") ?>

	<!-- Javascript -->
	<script src="CSS/assets/plugins/jquery.min.js"></script>
	<script src="CSS/assets/plugins/plugins.js"></script>
	<script src="CSS/assets/js/functions.min.js"></script>
	<script>
		//To display member's information on the dialog box
		function display(get) {
			var modal = document.getElementById("modal");
			var fullname = $(get).data('fullname');
			var dob = $(get).data('dob');
			var status = $(get).data('status');
			$("#fullname").val(fullname);
			$("#dob").val(dob);
			$("#status").val(status);
			modal.style.display = "block";
		}
		//Hide the dialog box that displays the member information
		function hide() {
			var modal = document.getElementById("modal");
			modal.style.display = "none";
		}
	</script>
</body>

</html>