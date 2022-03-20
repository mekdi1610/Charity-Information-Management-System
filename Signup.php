<?php
include_once('PHP/Employee.php');
error_reporting(0);
session_start();
//If the employee ID is not set then the page is being accessed without authorization
if (!isset($_SESSION["EmpID"])) {
  header("Location: Login.php");
  exit();
}
//Call the Employee class
$empObj = new Employee();

//To add new employee's personal and account information
if (isset($_POST['Signup']) || isset($_POST['Update'])) {
  $newPassword = $_POST["Password"];
  $confirmPassword = $_POST["ConfirmPassword"];
  if ($newPassword == $confirmPassword) {
    $empID = $empObj->generateID();
    $empObj->setEmpID($empID);
    $empObj->setFullName($_POST["FN"]);
    $empObj->setUserName($_POST["UserName"]);
    $empObj->setPassword($newPassword);
    $empObj->setRole($_POST["Role"]);
    if (isset($_POST['Signup'])) {
      $empObj->addEmployee();
    }
    if (isset($_POST['Update'])) {
      $empObj->updateEmployee();
    }
  } else {
    echo "<script>alert('Passwords Don't Match');
		document.location = 'Signup.php' </script>";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Signup</title>
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
  <?php include_once("HeaderEmployee.php"); ?>
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
          <div class="col-12 col-lg-8 m-auto">
            <form class="multisteps-form__form" action="Signup.php" method="POST" style="top: 19px;height: 407px;box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);">
              <!--single form panel-->
              <div class="multisteps-form__panel p-4 rounded bg-white js-active" data-animation="slideVert" style="box-shadow: 0 3px 7px rgba(0, 0, 0, 0.6);">
                <h3 class="multisteps-form__title"><i class="fa fa-user-o" aria-hidden="true"></i>Signup</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Full Name</label>
                      <input class="multisteps-form__input form-control" type="text" id="fullName" name="FN" pattern="[^()/><\][\\\x22,;|^0-9]*" required />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>UserName</label>
                      <input class="multisteps-form__input form-control" type="text" id="userName" name="UserName" placeholder="" required /><i class="fa fa-search" id="show-password" onclick="searchAccount()"></i>
                      <p><span id="validatorID"></span></p>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Password</label>
                      <input class="multisteps-form__input form-control" type="password" id="password" name="Password" required onclick="checkUserName()"><i class="fa fa-eye" id="show-password" onclick="showPassword()"></i>
                      <p><span id="validator2"></span></p>
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Cofirm Password</label>
                      <input class="multisteps-form__input form-control" type="password" id="ConfirmPassword" name="ConfirmPassword" placeholder="" required onclick="checkPassword()" />
                    </div>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col">
                      <label>Role</label>
                      <select class="multisteps-form__select form-control" id="role" name="Role" required>
                        <option value="Please Choose">Please Choose</option>
                        <option value="Employee">Employee</option>
                        <option value="Administrator">Administrator</option>
                      </select>
                    </div>
                  </div>
                  <div class="button-row d-flex mt-4">
                    <button class="btn" type="submit" title="Send" name="Signup">Signup</button>
                    <button class="btn" type="submit" title="Send" name="Update">Update</button>
                  </div>
                </div>
              </div>
          </div>
        </div>
        </form>
        <script type='text/javascript' src='Javascript/Account.js'></script>
        <script type='text/javascript' src='Javascript/Employee.js'></script>
      </div>
    </div>
  </div>
  </div>
  </div>
  <!-- Javascript -->
  <script src='CSS/css/bootstrap.minn.css'></script>
  <script src='CSS/SliderJs/js/jq.js'></script>
  <script src="CSS/SliderJs/js/script.js"></script>
  <script>
  </script>
</body>
<?php include_once("Footer.html"); ?>

</html>