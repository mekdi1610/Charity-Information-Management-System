<?php
include_once('php/Account.php');
session_start();
//Unset all the variables set with session
unset($_SESSION["UserName"]);
unset($_SESSION["FullName"]);
unset($_SESSION["Role"]);

//Call the Account class
$accObj = new Account();
if (isset($_POST['Login'])) {
  $accObj->setUserName($_POST["UserName"]);
  $accObj->setPassword($_POST["password"]);
  /////////////////////////
  $accObj->login();
  exit();
}
//To send an email with the respective token if password is forgotten
if (isset($_POST['ForgotPassword'])) {
  $accObj->setUserName($_POST["UserName"]);
  $accObj->forgotPassword();
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title> Login </title>
  <meta charset="UTF-8">
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
            <form class="multisteps-form__form" action="Login.php" method="POST" style="top: 19px;height: 407px;box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);">
              <!--single form panel-->
              <div class="multisteps-form__panel p-4 rounded bg-white js-active" data-animation="slideVert" style="box-shadow: 0 3px 7px rgba(0, 0, 0, 0.6);">
                <h3 class="multisteps-form__title"><i class="fa fa-user-o" aria-hidden="true"></i>Login</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col">
                      <label> User Name</label>
                      <input class="multisteps-form__input form-control" type="text" id="UserName" name="UserName" required />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Password</label>
                      <input class="multisteps-form__input form-control" type="password" id="password" name="password" placeholder=""/><i class="fa fa-eye" id="show-password" onclick="showPassword()"></i>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <input type="submit" name="ForgotPassword" class="psw" style="text-decoration: underline;border: none;background: none;text-align: right;" value="Forgot Password">
                    </div>
                  </div>
                  <div class="button-row d-flex mt-4">
                    <button class="btn" type="submit" title="Send" name="Login">Login</button>
                  </div>
                </div>
              </div>
          </div>
        </div>
        <script type='text/javascript' src='Javascript/Account.js'></script>
        </form>
      </div>
    </div>
  </div>
  </div>
  </div>
  <!-- Javascript -->
  <script src='CSS/css/bootstrap.minn.css'></script>
  <script src='CSS/SliderJs/js/jq.js'></script>
  <script src="CSS/SliderJs/js/script.js"></script>
</body>

</html>