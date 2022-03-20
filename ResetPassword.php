<?php
include_once('PHP/Account.php');
//error_reporting(0);
session_start();

//Call the Account class
$accObj = new Account();
//First time logging in
if (isset($_SESSION['UserName'])) {
  $accObj->setUserName($_SESSION['UserName']);
  $uname = $_SESSION['UserName'];
}

//Forgot password
if (isset($_GET['key']) && isset($_GET['token'])) {
  $uname = $_GET['uname'];
  $email = $_GET['key'];
  $token = $_GET['token'];
  $expDate = $_GET['expdate'];
  $curDate = date("Y-m-d");
}

//Resetting the password
if (isset($_POST['Reset'])) {
  $newPassword = $_POST["NewPassword"];
  $confirmPassword = $_POST["ConfirmPassword"];
  if ($newPassword == $confirmPassword) {
    $accObj->setUserName($_POST["UserName"]);
    $accObj->setPassword($newPassword);
    $accObj->firstTimeLogin();
  } else {
    echo "<script>alert('Passwords Dont Match');
		document.location = '/ResetPassword.php' </script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title> Reset Password</title>
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
  <link rel="stylesheet" href="CSS/style.css">
  <link rel='stylesheet' href='CSS/css/bootstrap.minn.css'>
  <link rel="stylesheet" href="CSS/css/style.css">
</head>

<body>
  <?php
  //If the expire date is greater than current date the token of forgot password is valid.
  if ($expDate >= $curDate) { ?>
    <!--content inner-->
    <div class="content__inner">
      <div class="container overflow-hidden">
        <!--multisteps-form-->
        <div class="multisteps-form">
          <!--progress bar-->
          <div class="row">
            <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
              <div class="multisteps-form__progress">
              </div>
            </div>
          </div>
          <!--form panels-->
          <div class="row">
            <div class="col-12 col-lg-8 m-auto" id="Login">
              <form class="multisteps-form__form" action="ResetPassword.php" method="POST" style="top: 19px;height: 407px;box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);">
                <!--single form panel-->
                <div class="multisteps-form__panel p-4 rounded bg-white js-active" data-animation="slideVert" style="box-shadow: 0 3px 7px rgba(0, 0, 0, 0.6);">
                  <h3 class="multisteps-form__title">Reset Password</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col">
                        <label> User Name</label>
                        <input class="multisteps-form__input form-control" type="text" id="UserName" name="UserName" required value="<?php echo $uname ?>" readonly/>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label> New Password</label>
                        <input class="multisteps-form__input form-control" type="password" id="password" name="NewPassword" required /><i class="fa fa-eye" id="show-password" onclick="showPassword()"></i>
                        <p><span id="validator2"></span></p>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <label>Confirm Password</label>
                        <input class="multisteps-form__input form-control" type="password" id="password" name="ConfirmPassword" placeholder="" required onclick="checkPassword()" />
                      </div>
                    </div>
                    <div class="button-row d-flex mt-4">
                      <button class="btn" type="submit" title="Send" name="Reset">Reset Password</button>
                    </div>
                  </div>
                </div>
            </div>
          </div>
          </form>
          <script type='text/javascript' src='Javascript/Account.js'></script>
        </div>
      </div>
    </div>
    </div>
    </div>
  <?php }
  //If the token expires then the user should create a new token
  else {
    echo "<p>This forget password link has been expired</p>";
  }
  ?>
  <!-- Javascript -->
  <script src='CSS/css/bootstrap.minn.css'></script>
  <script src='CSS/SliderJs/js/jq.js'></script>
  <script src="CSS/SliderJs/js/script.js"></script>
</body>

</html>