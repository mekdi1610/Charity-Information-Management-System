<?php
include_once('PHP/Supply.php');
include_once('PHP/BankAccount.php');
include_once('PHP/Member.php');
//error_reporting(0);
//Call the Supply class
$supObj = new Supply();
//Call the Bank Account class
$accObj = new BankAccount();
//Call the Member class
$memObj = new Member();

//Retrieve all the supplies from the database
$supply = $supObj->viewSupplies();
$supID = $supply[0];
$product = $supply[1];
$type = $supply[2];
$description = $supply[3];
$quantity = $supply[4];
$urgencyLevel = $supply[5];
//Retrieve all the bank accounts from the database
$bAccount = $accObj->viewBankAccount();
$accID = $bAccount[0];
$bankName = $bAccount[1];
$branchName = $bAccount[2];
$accName = $bAccount[3];
$accNo = $bAccount[4];
$transferInfo = $bAccount[5];
$country = $bAccount[7];
//Retrieve all the critical members from the database
$mem = $memObj->getCriticalMembers();
$photo = $mem[0];
$fullName = $mem[1];
$CD = $mem[2];
$bankNameMem = $mem[3];
$branchNameMem = $mem[4];
$accNameMem = $mem[5];
$accNoMem = $mem[6];
$transferInfoMem = $mem[7];
$countryMem = $mem[8];

//To prevent XSS Attack
function _e($string)
{
  echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Donate Now</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Icon -->
  <link rel="shortcut icon" href="CSS/images/dove4.ico" />
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="CSS/css/bootstrap.min.css">
  <!-- themify-icons CSS -->
  <link rel="stylesheet" href="CSS/css/themify-icons.css">
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="CSS/css/swiper.min.css">
  <!-- Alignement and picture CSS -->
  <link href="CSS/assets/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
  <link href="CSS/assets/plugins/owl-carousel/owl.carousel.min.css" rel="stylesheet">
  <link href="CSS/assets/plugins/owl-carousel/owl.theme.default.min.css" rel="stylesheet">
  <link href="CSS/assets/plugins/magnific-popup/magnific-popup.min.css" rel="stylesheet">
  <link href="CSS/assets/plugins/sal/sal.min.css" rel="stylesheet">
  <link href="CSS/assets/css/theme.min.css" rel="stylesheet">
  <!-- Styles -->
  <link rel="stylesheet" href="CSS/style.css">
</head>

<body>
  <?php include_once("HeaderHome.php"); ?>
  <!-- About section -->
  <div class="container">
    <div class="section-title">
      <h2 style="color: black;">Supply Needs</h2>
    </div>
    <ul class="accordion single-open rounded">
      <li class="active">
        <div class="accordion-title">
          <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Sanitary Products</h6>
        </div>
        <div class="accordion-content">
          <div class="card-body">
            <div class="table-responsive">
              <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Urgency Level</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  //Display the retrieved supplies (Sanitary Product)
                  $no = 1;
                  for ($i = 0; $i < sizeof($supID); $i++) {
                    if ($type[$i] == "Sanitary Product") {
                      echo "<tr>";
                      echo "<td> $no </td>";
                      echo "<td>";
                      _e($product[$i]);
                      echo "</td>";
                      echo "<td>";
                      _e($description[$i]);
                      echo "</td>";
                      echo "<td>";
                      _e($quantity[$i]);
                      echo "</td>";
                      echo "<td> $urgencyLevel[$i] </td>";
                      echo "</tr>";
                      $no++;
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="accordion-title">
          <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Clothing</h6>
        </div>
        <div class="accordion-content">
          <div class="card-body">
            <div class="table-responsive">
              <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Urgency Level</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  //Display the retrieved supplies (Clothing)
                  $no = 1;
                  for ($i = 0; $i < sizeof($supID); $i++) {
                    if ($type[$i] == "Clothing") {
                      echo "<tr>";
                      echo "<td> $no </td>";
                      echo "<td>";
                      _e($product[$i]);
                      echo "</td>";
                      echo "<td>";
                      _e($description[$i]);
                      echo "</td>";
                      echo "<td>";
                      _e($quantity[$i]);
                      echo "</td>";
                      echo "<td> $urgencyLevel[$i] </td>";
                      echo "</tr>";
                      $no++;
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="accordion-title">
          <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Food & Drinks </h6>
        </div>
        <div class="accordion-content">
          <div class="card-body">
            <div class="table-responsive">
              <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Urgency Level</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  //Display the retrieved supplies (Food & Drink)
                  $no = 1;
                  for ($i = 0; $i < sizeof($supID); $i++) {
                    if ($type[$i] == "Food & Drink") {
                      echo "<tr>";
                      echo "<td> $no </td>";
                      echo "<td>";
                      _e($product[$i]);
                      echo "</td>";
                      echo "<td>";
                      _e($description[$i]);
                      echo "</td>";
                      echo "<td>";
                      _e($quantity[$i]);
                      echo "</td>";
                      echo "<td> $urgencyLevel[$i] </td>";
                      echo "</tr>";
                      $no++;
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="accordion-title">
          <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Medication</h6>
        </div>
        <div class="accordion-content">
          <div class="card-body">
            <div class="table-responsive">
              <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Urgency Level</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  //Display the retrieved supplies (Food & Drink)
                  $no = 1;
                  for ($i = 0; $i < sizeof($supID); $i++) {
                    if ($type[$i] == "Medication") {
                      echo "<tr>";
                      echo "<td> $no </td>";
                      echo "<td>";
                      _e($product[$i]);
                      echo "</td>";
                      echo "<td>";
                      _e($description[$i]);
                      echo "</td>";
                      echo "<td>";
                      _e($quantity[$i]);
                      echo "</td>";
                      echo "<td> $urgencyLevel[$i] </td>";
                      echo "</tr>";
                      $no++;
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="accordion-title">
          <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Equipment</h6>
        </div>
        <div class="accordion-content">
          <div class="card-body">
            <div class="table-responsive">
              <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Urgency Level</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  //Display the retrieved supplies (Equipment)
                  $no = 1;
                  for ($i = 0; $i < sizeof($supID); $i++) {
                    if ($type[$i] == "Equipment") {
                      echo "<tr>";
                      echo "<td> $no </td>";
                      echo "<td>";
                      _e($product[$i]);
                      echo "</td>";
                      echo "<td>";
                      _e($description[$i]);
                      echo "</td>";
                      echo "<td>";
                      _e($quantity[$i]);
                      echo "</td>";
                      echo "<td> $urgencyLevel[$i] </td>";
                      echo "</tr>";
                      $no++;
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="accordion-title">
          <h6 class="font-family-tertiary font-small font-weight-normal uppercase">Construction Materials</h6>
        </div>
        <div class="accordion-content">
          <div class="card-body">
            <div class="table-responsive">
              <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Urgency Level</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  //Display the retrieved supplies (Construction Materials)
                  $no = 1;
                  for ($i = 0; $i < sizeof($supID); $i++) {
                    if ($type[$i] == "Construction Materials") {
                      echo "<tr>";
                      echo "<td> $no </td>";
                      echo "<td>";
                      _e($product[$i]);
                      echo "</td>";
                      echo "<td>";
                      _e($description[$i]);
                      echo "</td>";
                      echo "<td>";
                      _e($quantity[$i]);
                      echo "</td>";
                      echo "<td> $urgencyLevel[$i] </td>";
                      echo "</tr>";
                      $no++;
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
  <div class="container">
    <div class="section-title">
      <h2 style="color: black;">Account Information</h2>
    </div>
    <div class="masonry masonry-column-3 masonry-spacing-30">
      <?php
      //Display the retrieved bank accounts
      for ($i = 0; $i < sizeof($accID); $i++) {
        echo '<div class="masonry-item">';
        echo '<div class="padding-40 box-shadow-with-hover">';
        echo "<b>";
        _e($bankName[$i]);
        echo "</b>";
        echo "<p><b>Branch Name: </b>";
        _e($branchName[$i]);
        echo "</p>";
        echo "<p><b>Account Name: </b>";
        _e($accName[$i]);
        echo "</p>";
        echo "<p><b>Account No.: </b> $accNo[$i] </p>";
        echo "<p><b>Transfer Info.: </b>";
        _e($transferInfo[$i]);
        echo "</p>";
        echo "<p>Country: </b>";
        _e($country[$i]);
        echo "</p>";
        echo '</div>';
        echo '</div>';
      }
      ?>
    </div>
  </div>
  <div class="container">
    <div class="section-title">
      <h2 style="color: black;">Critical Members</h2>
    </div>
    <div class="row text-center">
      <?php
      //echo $fullName[0], $fullName[1];
      //Display the retrieved information of critical members
      for ($i = 0; $i < 2; $i++) {
        echo '<div class="col-12 col-md-6 col-lg-4">';
        echo ' <div class="border-all border-radius padding-30">';
        echo '<img class="img-circle-xl" src="data:image/jpg;charset=utf8;base64,' . base64_encode($photo[$i]) . '" alt="">';
        echo '<div class="margin-top-30">';
        echo '<h6 class="font-weight-normal margin-0 line-height-140">';
      
        _e($fullName[$i]);
        echo '</h6>';
        echo '<span class="font-small font-weight-normal">';
        _e($CD[$i]);
        echo '</span>';
        echo '</div>';
        if($i==0){
          echo '<button class="btn gradient-bg mr-2 modal-trigger" onclick="display()">Donate Now</button>';
        }
        else{
          echo '<button class="btn gradient-bg mr-2 modal-trigger" onclick="display2()">Donate Now</button>';
        }
        
        echo '</div>';
        echo '</div>';
      }
      
        //Dialog box for gisplaying personal bank accounts of critical members
        echo '<div onclick="Tohide()" class="modal" id="modal" tabindex="-1" role="dialog" aria-hidden="true" style="/* height: fit-content; */width: 450px;left: 60%;height: 350px;background-color: white; top:70px; border-radius:40px;">';
        echo '<div class="modal-dialog" role="document">';
        echo '<div class="modal-header no-bd">';
        echo '<h5 class="modal-title">';
        echo '<span class="fw-mediumbold"> Donate!';
        echo '</span>';
        echo '</h5>';
        echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close' style= 'width:15%'>";
        echo '<span>&times;</span>';
        echo '</button>';
        echo '</div>';
        echo '<div class="masonry">';
        echo '<div class="masonry-item">';
        echo '<div class="padding-40 box-shadow-with-hover">';
        echo "<b>";
        
        _e($bankNameMem[0]);
        echo "</b>";
        echo "<p><b>Branch Name: </b>";
        _e($branchNameMem[0]);
        echo "</p>";
        echo "<p><b>Account Name: </b>";
        _e($accNameMem[0]);
        echo "</p>";
        echo "<p><b>Account No.: </b>";
        _e($accNoMem[0]);
        echo "</p>";
        echo "<p><b>Transfer Info.: </b>";
        _e($transferInfoMem[0]);
        echo "</p>";
        echo "<p>Country: </b>";
        _e($countryMem[0]);
        echo "</p>";
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
         //Dialog box for gisplaying personal bank accounts of critical members
         echo '<div onclick="Tohide()" class="modal" id="modal2" tabindex="-1" role="dialog" aria-hidden="true" style="/* height: fit-content; */width: 450px;left: 60%;height: 350px;background-color: white; top:70px; border-radius:40px;">';
         echo '<div class="modal-dialog" role="document">';
         echo '<div class="modal-header no-bd">';
         echo '<h5 class="modal-title">';
         echo '<span class="fw-mediumbold"> Donate!';
         echo '</span>';
         echo '</h5>';
         echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close' style= 'width:15%'>";
         echo '<span>&times;</span>';
         echo '</button>';
         echo '</div>';
         echo '<div class="masonry">';
         echo '<div class="masonry-item">';
         echo '<div class="padding-40 box-shadow-with-hover">';
         echo "<b>";
         
         _e($bankNameMem[1]);
         echo "</b>";
         echo "<p><b>Branch Name: </b>";
         _e($branchNameMem[1]);
         echo "</p>";
         echo "<p><b>Account Name: </b>";
         _e($accNameMem[1]);
         echo "</p>";
         echo "<p><b>Account No.: </b>";
         _e($accNoMem[1]);
         echo "</p>";
         echo "<p><b>Transfer Info.: </b>";
         _e($transferInfoMem[1]);
         echo "</p>";
         echo "<p>Country: </b>";
         _e($countryMem[1]);
         echo "</p>";
         echo '</div>';
         echo '</div>';
         echo '</div>';
         echo '</div>';
         echo '</div>';
        
      
      ?>
    </div>
  </div>
  <script type='text/javascript' src='Javascript/Buttons.js'></script>
  <script type='text/javascript' src='Javascript/Donation.js'></script>
  </div>
  </div>
  </div>
  </div>
  <?php include_once("Footer.html"); ?>

  <!-- Javascript -->
  <script src="CSS/table/assets/js/jquery.min.js"></script>
  <script src="CSS/table/assets/js/bootstrap.min.js"></script>
  <script src="CSS/table/assets/js/datatables.min.js"></script>
  <script src="CSS/assets/plugins/jquery.min.js"></script>
  <script src="CSS/assets/plugins/plugins.js"></script>
  <script src="CSS/assets/js/functions.min.js"></script>
</body>

</html>