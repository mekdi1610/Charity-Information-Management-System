<?php
include_once('PHP/Tip.php');
//Call the Tip class
$tipObj = new Tip();

//To send tip of potential members
if (isset($_POST['Send'])) {
  $tipID = $tipObj->generateID();
  $behavior = "";
  $addiction = "";
  //To get all the values of the check boxes
  if (!empty($_POST['Behavior'])) {
    // Loop to store and display values of individual checked checkbox.
    foreach ($_POST['Behavior'] as $selected2) {
      $behavior .= $selected2 . ",";
    }
  }
  //To get all the values of the check boxes
  if (!empty($_POST['Addiction'])) {
    // Loop to store and display values of individual checked checkbox.
    foreach ($_POST['Addiction'] as $selected) {
      $addiction .= $selected . ",";
    }
  }
  //Set the attributes
  $tipObj->setTipID($tipID);
  $tipObj->setFullName($_POST['FN']);
  $tipObj->setPhoneNo($_POST['Phone']);
  $tipObj->setGender($_POST['Gender']);
  $tipObj->setLocation($_POST['location']);
  $tipObj->setOtherInfo($_POST['Description']);
  $tipObj->setBehavior($behavior);
  $tipObj->setAddiction($addiction);
  $tipObj->sendTip();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Tip Us</title>
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
  <?php include_once("HeaderHome.php");
  ?>
  </head>

  <body>
    <!--content inner-->
    <div class="content__inner">
      <h4 class="card-title" style="text-align: center"><i class="fa fa-envelope-o" aria-hidden="true"></i>Tips</h4>
      <div class="container overflow-hidden">
        <!--multisteps-form-->
        <div class="multisteps-form">
          <!--progress bar-->
          <div class="row">
            <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
              <div class="multisteps-form__progress">
                <button class="multisteps-form__progress-btn js-active" type="button" title="Personal Info">Contact Information</button>
                <button class="multisteps-form__progress-btn" type="button" title="Address">Potential Member</button>
                <button class="multisteps-form__progress-btn" type="button" title="Volunteer Information">Other Information</button>
              </div>
            </div>
          </div>
          <!--form panels-->
          <div class="row">
            <div class="col-12 col-lg-8 m-auto">
              <form class="multisteps-form__form" action="Tip Us.php" method="POST">
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="slideVert">
                  <h3 class="multisteps-form__title">Your Contact Info</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col-12 col-sm-6">
                        <input class="multisteps-form__input form-control" type="text" id="FN" name="FN" placeholder="Full Name" pattern="[^()/><\][\\\x22,;|^0-9]*" title="Should not contain special characters or numbers" maxlength="50" required />
                      </div>
                      <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                        <input class="multisteps-form__input form-control" type="number" id="Phone" name="Phone" placeholder="Phone Number" required />
                      </div>
                    </div>
                    <div class="button-row d-flex mt-4">
                      <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                    </div>
                  </div>
                </div>
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                  <h3 class="multisteps-form__title">Potential Member Information</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col-12 col-sm-6">
                        <label> Gender</label>
                        <select class="multisteps-form__select form-control" id="Gender" name="Gender" required>
                          <option selected="selected">Please Choose</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>
                      </div>
                      <div class="col-12 col-sm-6"> <label>Location</label>
                        <input class="multisteps-form__input form-control" type="text" id="location" name="location" placeholder="Example: WeloSefer, Infront of Mina Building, Addis Ababa, Ethiopia" maxlength="300" required />
                      </div>
                    </div>
                    <div class="form-row mt-4">
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
                          <input class="multisteps-form__input form-control" type="text" placeholder="Other" name="Behavior[]" />
                        </div>
                      </div>
                    </div>
                    <div class="form-row mt-4">
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
                          <input class="multisteps-form__input form-control" type="text" placeholder="Other" name="Addiction[]" />
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
                  <h3 class="multisteps-form__title">Other Information</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col">
                        <label> Other Information</label>
                        <input class="multisteps-form__input form-control" type="text" name="Description" id="" placeholder="Any information you us to know" />
                      </div>
                    </div>
                    <div class="row">
                      <div class="button-row d-flex mt-4 col-12">
                        <button class="btn btn-primary js-btn-prev button-circle-dark" type="button" title="Prev">Prev</button>
                        <button class="btn btn-success ml-auto" type="submit" title="Send" name="Send">Send</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <script type='text/javascript' src='Javascript/RegisterContributor.js'></script>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include_once("Footer.html");  ?>

    <!-- Javascript -->
    <script src='CSS/css/bootstrap.minn.css'></script>
    <script src='CSS/SliderJs/js/jq.js'></script>
    <script src="CSS/SliderJs/js/script.js"></script>
  </body>

</html>