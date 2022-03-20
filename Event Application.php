<?php
include_once('PHP/Event.php');
$eveObj = new Event();
//To send an event application to the oragnization
if (isset($_POST['Apply'])) {
  $eveID = $eveObj->generateID();
  $eveObj->setEveID($eveID);
  $eveObj->setType($_POST["ET"]);
  $eveObj->setPepleToFeed($_POST['NPF']);
  $eveObj->setPeopleToInvite($_POST['NPI']);
  $eveObj->setDate($_POST['date']);
  $eveObj->setTime($_POST['startT']);
  $eveObj->setFullName($_POST["FN"]);
  $eveObj->setPhoneNo($_POST['PhoneNo']);
  $eveObj->setEmail($_POST['Email']);
  /////////////////////////
  $eveObj->applyForEvent();
}
//To see in advance other events and avaiable date and time
$result = $eveObj->viewEvent("Approved");
$ResID = $result[0];
$ResType = $result[1];
$ResDate = $result[4];
$ResTime = $result[5];
$ResNo = $result[9];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Event Application</title>
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
  <!--Calander CSS-->
  <link rel='stylesheet' type='text/css' href='CSS/Calander/fullcalendar.css' />
  <link rel='stylesheet' type='text/css' href='CSS/Calander/fullcalendar.print.css' media='print' />
  <!--Calander JS-->
  <script type='text/javascript' src='CSS/Calander/jquery-1.8.1.min.js'></script>
  <script type='text/javascript' src='CSS/Calander/jquery-ui-1.8.23.custom.min.js'></script>
  <script type='text/javascript' src='CSS/Calander/fullcalendar.min.js'></script>
  <script type='text/javascript'>
    //JQuery Code to render the events on the calander
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
        selectable: false,
        selectHelper: false,
        select: function(start, end, allDay) {
          var title = prompt('Event Title:');
          if (title) {
            calendar.fullCalendar('renderEvent', {
                title: title,
                start: start,
                end: end,
                allDay: allDay
              },
              true // make the event "stick"
            );
            //To put the selected date on appointment date
          }
          calendar.fullCalendar('unselect');
        },
        editable: false,
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

<body> <?php include_once("HeaderHome.php"); ?>
  <!--content inner-->
  <div class="content__inner">
    <h3 style="text-align: center"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i>Apply for an Event</h3>
    <div class="container overflow-hidden">
      <!--multisteps-form-->
      <div class="multisteps-form">
        <!--progress bar-->
        <div class="row">
          <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
            <div class="multisteps-form__progress">
              <button class="multisteps-form__progress-btn js-active" type="button" title="Calander">Calander</button>
              <button class="multisteps-form__progress-btn" type="button" title="ContactInfo">Contact Information</button>
              <button class="multisteps-form__progress-btn" type="button" title="Apply">Event Information</button>
            </div>
          </div>
        </div>
        <!--form panels-->
        <div class="row">
          <div class="col-12 col-lg-8 m-auto">
            <form class="multisteps-form__form" action="Event Application.php" method="POST" enctype="multipart/form-data">
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideVert">
                <h3 class="multisteps-form__title">Calander</h3>
                <div class="multisteps-form__content">
                  <div id='calendar'></div>
                  <div class="button-row d-flex mt-4">
                    <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                  </div>
                </div>
              </div>
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                <h3 class="multisteps-form__title">Contact Information</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col"> <label>Full Name</label>
                      <input class="multisteps-form__input form-control" type="text" id="FN" name="FN" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="50" required />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col"> <label>Phone No</label>
                      <input class="multisteps-form__input form-control" type="number" id="PhoneNo" name="PhoneNo" required />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Email</label>
                      <input class="multisteps-form__input form-control" type="email" id="Email" name="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Wrong Format" maxlength="30" required />
                    </div>
                  </div>
                  <div class="row">
                    <div class="button-row d-flex mt-4 col-12">
                      <button class="btn btn-primary js-btn-prev button-circle-dark" type="button" title="Prev">Prev</button>
                      <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                    </div>
                  </div>
                </div>
              </div>
              <!--single form panel-->
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                <h3 class="multisteps-form__title">Event Information</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col"> <label>Event Type</label>
                      <select class="multisteps-form__select form-control" id="ET" name="ET" required>
                        <option value="Please Choose">Please Choose</option>
                        <option value="Wedding">Wedding</option>
                        <option value="Birthday">Birthday</option>
                        <option value="Graduation">Graduation</option>
                        <option value="Nika">Nika</option>
                        <option value="Sedeka">Sedeka</option>
                        <option value="Memorial">Memorial</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col"> <label>Number of People to Feed</label>
                      <input class="multisteps-form__input form-control" type="number" placeholder="How many people can you feed?" id="NPF" name="NPF" required />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Number of People to Invite</label>
                      <input class="multisteps-form__input form-control" type="number" id="NPI" name="NPI" placeholder="How many people are you inviting?" required />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
                      <label>Date</label>
                      <?php $Date = date('Y-m-d');
                      $today = date('Y-m-d', strtotime($Date . ' + 5 days'));
                      ?>
                      <input class="multisteps-form__input form-control" type="date" id="date" placeholder="" name="date" min="<?php echo $today ?>" oninput="clearDropDown(); getTime();" />
                    </div>
                    <div class="col-12 col-sm-6">
                      <label>Time:</label>
                      <select class="multisteps-form__select form-control" type="text" id="startT" name="startT" required>
                        <option selected="selected">Please Choose</option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="button-row d-flex mt-4 col-12">
                      <button class="btn btn-primary js-btn-prev button-circle-dark" type="button" title="Prev">Prev</button>
                      <button class="btn btn-success ml-auto" type="submit" title="Apply" name="Apply">Apply</button>
                    </div>
                  </div>
                </div>
              </div>
              <script type='text/javascript' src='Javascript/Event.js'></script>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include_once("Footer.html"); ?>

  <!-- Javascript -->
  <script src='CSS/css/bootstrap.minn.css'></script>
  <script src="CSS/SliderJs/js/script.js"></script>

</body>

</html>