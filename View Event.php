<?php
include_once('PHP/Event.php');
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

//Call the Event class
$eveObj = new Event();
//Retrieve all the events that need review 
$accept = $eveObj->viewEvent("Pending");

//To approve or deny an event
if (isset($_POST['Approve']) || isset($_POST['Deny'])) {
	$fullName = $_POST['Name'];
	$eveObj->setEveID($_POST['EventID']);
	$eveObj->setFullName($fullName);
	$eveObj->setEmail($_POST['Email']);
	//Prepare email
	$head = "Mekedonia Homes for the Elderly and Mentally Disabled";
	$greeting = "Hello, " . $fullName;

	//Approve Event
	if (isset($_POST['Approve'])) {
		$eveObj->setPeopleNeeded($_POST['PN']);
		$eveObj->setHall($_POST['Hall']);
		$empID = $_POST['EmpID'];
		$subject = "Event Approved";
		$message = $head . "\n" . $greeting . "\n We have approved your application to celebrate your special occassion with us. We will be expecting you on that date. If you decided to change the date or venue please contact us 3 days in advance.\nPlease feel free to contact us with further questions or comment.\n Thank you!";
		$eveObj->sendEmail($subject, $message);
		$eveObj->approveEvent($empID);
	}
	//Deny Event
	if (isset($_POST['Deny'])) {
		$subject = "Event Deined";
		$message = $head . "\n" . $greeting . "\n" . "We have reviewed your application but, " . $_POST['Message'] . "\nPlease feel free to contact us with farther questions or comment.\n Thank you!";
		$empID = "Emp-1";
		$eveObj->sendEmail($subject, $message);
		$eveObj->denyEvent($empID);
	}
}

//To view all the recurring and previous events on the calander
$result = $eveObj->viewEvent("Approved");
$ResID = [];
$ResType = [];
$ResDate = [];
$ResID = $result[0];
$ResType = $result[1];
$ResDate = $result[4];
$ResTime = $result[5];
$ResNo = $result[9];

//To prevent XSS Attack
function _e($string)
{
	echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>View Events</title>
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
	<!-- Themify-icons CSS -->
	<link rel="stylesheet" href="CSS/css/themify-icons.css">
	<!-- Swiper CSS -->
	<link rel="stylesheet" href="CSS/css/swiper.min.css">
	<!-- Styles -->
	<link rel="stylesheet" href="CSS/style.css">
	<link rel="stylesheet" href="CSS/css/style.css">
	<!-- Table Styles -->
	<link rel="stylesheet" href="CSS/table/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
	<!--Calander CSS-->
	<link rel='stylesheet' type='text/css' href='CSS/Calander/fullcalendar.css' />
	<link rel='stylesheet' type='text/css' href='CSS/Calander/fullcalendar.print.css' media='print' />
	<!--Calander JS-->
	<script type='text/javascript' src='CSS/Calander/jquery-1.8.1.min.js'></script>
	<script type='text/javascript' src='CSS/Calander/jquery-ui-1.8.23.custom.min.js'></script>
	<script type='text/javascript' src='CSS/Calander/fullcalendar.min.js'></script>
	<script type='text/javascript'>
		//JQuery Code to display events on the calander
		$(document).ready(function() {
			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
			var calendar = $('#calendar').fullCalendar({
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				selectable: true,
				selectHelper: true,
				select: function(start, end, allDay) {
					//To put the date on date text box
					var ED = document.getElementById("ED");
					var years = start.toLocaleDateString().replace(/\//g, "-");
					var year = start.getFullYear();
					var month = start.getMonth();
					var day = start.getDate();
					ED.value = year + "-" + (month + 1) + "-" + day;
					//To put the start time on start time text box
					var startT = document.getElementById("startT");
					var hr = start.getHours();
					var min = start.getMinutes();
					var sec = start.getSeconds();
					startT.value = hr + ":" + min + ":" + sec;
					calendar.fullCalendar('renderEvent', {
							title: title,
							start: start,
							end: end,
							allDay: allDay
						},
						true // make the event "stick"
					);
					calendar.fullCalendar('unselect');
				},
				editable: true,
				events: [{
					//Date retervied
				}]
			});
			'<?php for ($i = 0; $i < $ResNo; $i++) : ?>'
			'<?php $presentDate = strtotime($ResDate[$i]);
					$presentTime = explode(":", $ResTime[$i]);
					$year = date("Y", $presentDate);
					$month = date("m", $presentDate) - 1;
					$date = date("d", $presentDate);
					if ($presentTime[0] == "2") {
						$presentTime[0] = "14";
					}
					$hour = $presentTime[0];
					$minute = "00";
					$second = "00";
					$hourEnd = $hour + 2;
					$minuteEnd = "00";
					$secondEnd = "00";
				?>'
			calendar.fullCalendar('renderEvent', {
					title: '<?php echo "Event: " . $ResType[$i] ?>',
					start: new Date('<?php echo $year ?>', '<?php echo $month ?>', '<?php echo $date ?>', '<?php echo $hour ?>', '<?php echo $minute ?>', '<?php echo $second ?>'),
					end: new Date('<?php echo $year ?>', '<?php echo $month ?>', '<?php echo $date ?>', '<?php echo $hourEnd ?>', '<?php echo $minuteEnd ?>', '<?php echo $secondEnd ?>'),
					allDay: false
				},
				true
			);
			'<?php endfor ?> '
		});
	</script>
</head>

<body> <?php include_once("HeaderEmployee.php"); ?>
	<div class="content__inner">
		<h4 class="card-title" style="text-align: center"><i class="fa fa-calendar" aria-hidden="true"></i>Events</h4>
		<div class="container overflow-hidden">
			<!--multisteps-form-->
			<div class="multisteps-form">
				<!--progress bar-->
				<div class="row">
					<div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
						<div class="multisteps-form__progress">
							<button class="multisteps-form__progress-btn js-active" type="button" title="ContactInfo">ContactInfo</button>
							<button class="multisteps-form__progress-btn" type="button" title="Event Info">Event Information</button>
						</div>
					</div>
				</div>
				<!--form panels-->
				<form class="multisteps-form__form" action="View Event.php" method="POST" enctype="multipart/form-data">
					<!--single form panel-->
					<div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideVert">
						<h3 class="multisteps-form__title">Calander</h3>
						<div class="multisteps-form__content">
							<div id='calendar'></a>
							</div>
							<div class="button-row d-flex mt-4">
								<button class="ml-auto js-btn-next" type="button" title="Next">Next</button>
							</div>
						</div>
					</div>
					<!--single form panel-->
					<div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
						<h3 class="multisteps-form__title">Approve | Deny Event</h3>
						<div class="multisteps-form__content">
							<div class="card-body">
								<div class="table-responsive">
									<form class="form-horizontal row-fluid" action="Update Schedule Treatment.php" method="post">
										<table id="basic-datatables" class="display table table-striped table-hover">
											<thead>
												<tr>
													<th>Event Type</th>
													<th>#Feed</th>
													<th>#Invite</th>
													<th>Date</th>
													<th>Time Slot</th>
													<th>Full Name</th>
													<th>Phone No.</th>
													<th>Approve</th>
													<th>Deny</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$ID = $accept[0];
												$eveType = $accept[1];
												$NPF = $accept[2];
												$NTI = $accept[3];
												$Date = $accept[4];
												$Time = $accept[5];
												$FN = $accept[6];
												$Phone = $accept[7];
												$Email = $accept[8];
												//Display the event information
												for ($i = 0; $i < sizeof($eveType); $i++) {
													echo "<tr>";
													echo "<td>$eveType[$i]</td>";
													echo "<td>$NPF[$i]</td>";
													echo "<td>$NTI[$i]</td>";
													echo "<td>$Date[$i]</td>";
													echo "<td>$Time[$i]</td>";
													echo "<td>";
													_e($FN[$i]);
													echo "</td>";
													echo "<td>$Phone[$i]</td>";
													echo '<td><a href="#modal2" data-toggle="modal" data-id="' . $ID[$i] . '" data-name="' . $FN[$i] . '" data-email="' . $Email[$i] . '" name="Set" class="modal-trigger" title="show"><i class="fa fa-check"></i></a></td>';
													echo '<td><a href="#modal3" data-toggle="modal" data-id="' . $ID[$i] . '" data-name="' . $FN[$i] . '" data-email="' . $Email[$i] . '" name="Set" class="modal-trigger" title="show"><i class="fa fa-close"></i></a></td>';
													echo "</tr>";
												}
												?>
											</tbody>
										</table>
									</form>
								</div>
							</div>
							<div class="button-row d-flex mt-4">
								<button class="ml-auto js-btn-prev" type="button" title="Previous">Previous</button>
							</div>
						</div>
					</div>
				</form>
				<!--First Modal for Approve Event-->
				<div class="card-body">
					<!-- Modal -->
					<div class="modal" id="modal2" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header no-bd">
									<h5 class="modal-title">
										<span class="fw-mediumbold"> Approve Event
										</span>
									</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<p class="small"></p>
									<form class="form-horizontal row-fluid" method="post" action="View Event.php">
										<div class="row">
											<div class="control-group">
												<label class="control-label" for="MemID">Event ID</label>
												<div class="controls">
													<input type="text" placeholder="" id="EventID" name="EventID" class="span12" readonly>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="FN">Full Name</label>
												<div class="controls">
													<input type="text" placeholder="" id="name" name="Name" class="span12" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="50" required>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="email">Email</label>
												<div class="controls">
													<input type="email" placeholder="" id="email" name="Email" class="span12" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Wrong Format" maxlength="30" required>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="PN">Volunteer's Needed</label>
												<div class="controls">
													<input type="text" placeholder="Number of people needed for the event" id="PN" name="PN" class="span12" required>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="Hall">Hall</label>
												<div class="controls">
													<select id="Hall" name="Hall" class="span12" required>
														<option value="Please Choose">Please Choose</option>
														<option value="1">1</option>
														<option value="2">2</option>
													</select>
												</div>
											</div>
											<hr>
										</div>
										<div class="modal-footer no-bd" style="width:100%;">
											<button type="submit" name="Approve">Approve</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--Second Modal for Deny Event-->
				<div class="card-body">
					<!-- Modal -->
					<div class="modal" id="modal3" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header no-bd">
									<h5 class="modal-title">
										<span class="fw-mediumbold"> Deny Event
										</span>
									</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<p class="small"></p>
									<form class="form-horizontal row-fluid" method="post" action="View Event.php">
										<div class="row">
											<div class="control-group">
												<label class="control-label" for="eveID">Event ID</label>
												<div class="controls">
													<input type="text" placeholder="" id="EventID" name="EventID" class="span12" readonly>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="FN">Full Name</label>
												<div class="controls">
													<input type="text" placeholder="" id="name" name="Name" class="span12">
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="email">Email</label>
												<div class="controls">
													<input type="email" placeholder="" id="email" name="Email" class="span12">
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="msg">Message</label>
												<div class="controls">
													<input type="text" id="message" name="Message" class="span12" placeholder="Why are you denying this event." requireds>
												</div>
											</div>
											<hr>
										</div>
										<div class="modal-footer no-bd" style="width:100%;">
											<button type="submit" name="Deny">Deny</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--First Modal for Add Event-->
		<div class="card-body">
			<!-- Modal -->
			<div class="modal" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header no-bd">
							<h5 class="modal-title">
								<span class="fw-mediumbold"> Add Event
								</span>
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<p class="small"></p>
							<form class="form-horizontal row-fluid" method="post" action="Event Application.php">
								<div class="row">
									<div class="control-group">
										<label class="control-label" for="MemID">Full Name</label>
										<div class="controls">
											<input type="text" placeholder="Full Name" id="FN" name="FN" class="span12">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="ID">Phone No</label>
										<div class="controls">
											<input type="number" placeholder="+251#########" id="PhoneNo" name="PhoneNo" class="span12">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="Type">Email</label>
										<div class="controls">
											<input type="email" id="Email" name="Email" placeholder="Email" class="span12">
										</div>
									</div>
									<hr>
									<div class="control-group">
										<label class="control-label" for="MemID">Event Type</label>
										<div class="controls">
											<select class="span12" id="ET" name="ET">
												<option value="Please Choose">Please Choose</option>
												<option value="Wedding">Wedding</option>
												<option value="Birthday">Birthday</option>
												<option value="Graduation">Graduation</option>
												<option value="Nika">Nika</option>
												<option value="Sedeka">Sedeka</option>
												<option value="Memorials">Memorials</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="ID">PeopleToFeed</label>
										<div class="controls">
											<input type="number" placeholder="How many people can you feed?" id="NPF" name="NPF" class="span12">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="Type">PeopleToInvite</label>
										<div class="controls">
											<input type="number" id="NPI" name="NPI" placeholder="How many people are you inviting?" class="span12">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="name">Date:</label>
										<div class="controls">
											<input type="text" id="ED" placeholder="" name="date" class="span12">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="name">Start Time:</label>
										<div class="controls">
											<select class="span12" id="startT" name="startT" required>
												<option selected="selected">Please Choose</option>
												<option value="10:00AM-12:00PM">10:00AM-12:00PM</option>
												<option value="12:00PM-2:00PM">12:00PM-2:00PM</option>
												<option value="2:00PM-4:00PM">2:00PM-4:00PM</option>
											</select>
										</div>
									</div>
								</div>
								<div class="modal-footer no-bd" style="width:100%">
									<button type="submit" class="button-rounded margin-top-20" name="Apply">Apply</button>
								</div>
							</form>
							<script type='text/javascript' src='Javascript/Event.js'></script>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include_once("Footer.html") ?>
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
				var Name = $(this).data('name');
				var Email = $(this).data('email');
				$(".modal-content #EventID").val(ID);
				$(".modal-content #name").val(Name);
				$(".modal-content #email").val(Email);
			});
		</script>
		<!-- Javascript -->
		<script src='CSS/css/bootstrap.minn.css'></script>
		<script src="CSS/SliderJs/js/script.js"></script>
		<script src="CSS/table/assets/js/bootstrap.min.js"></script>
		<script src="CSS/table/assets/js/datatables.min.js"></script>
</body>

</html>