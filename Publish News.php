<?php
include_once('PHP/News.php');
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

//Call the News class
$newsObj = new News();
//To view previously published news
$accept = $newsObj->viewFeed();
$newsID = $accept[0];
$poster = $accept[1];
$description = $accept[2];
$dateTime = $accept[3];

//To update news feed
if (isset($_POST['Publish'])) {
  $imgContent = "";
  //Image 
  if (!empty($_FILES["Poster"]["name"])) {
    // Get file info 
    $fileName = basename($_FILES["Poster"]["name"]);
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
    // Allow certain file formats 
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (in_array($fileType, $allowTypes)) {
      $image = $_FILES['Poster']['tmp_name'];
      $imgContent = addslashes(file_get_contents($image));
    }
  }
  $newsObj->setNewsID($_POST['NewsID']);
  $newsObj->setPoster($imgContent);
  $newsObj->setDescription($_POST['Description']);
  $newsObj->setDate($_POST["Date"] . " " . $_POST["Time"]);
  $newsObj->publishNews($empID);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Publish News</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Icon -->
  <link rel="shortcut icon" href="CSS/images/dove4.ico" />
  <!-- For Picture CSS -->
  <link href="CSS/assets/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
  <link href="CSS/assets/plugins/owl-carousel/owl.carousel.min.css" rel="stylesheet">
  <link href="CSS/assets/plugins/owl-carousel/owl.theme.default.min.css" rel="stylesheet">
  <link href="CSS/assets/plugins/magnific-popup/magnific-popup.min.css" rel="stylesheet">
  <link href="CSS/assets/plugins/sal/sal.min.css" rel="stylesheet">
  <link href="CSS/assets/css/theme.min.css" rel="stylesheet">
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
</head>

<body>
  <?php include_once("HeaderEmployee.php"); ?>

  <body>
    <div class="container">
      <h4 class="card-title" style="text-align: center"><i class="fa fa-newspaper-o" aria-hidden="true"></i>Publish News</h4>
      <div class="section-title">
      </div>
      <div class="owl-carousel owl-nav-overlay owl-dots-overlay" data-owl-items="1" data-owl-nav="true" data-owl-autoplay="true">
        <?php
        //Display previously added news
        for ($i = 0; $i < (sizeof($newsID)); $i++) {
          $getDateTime = explode(" ", $dateTime[$i]);
          $dates = $getDateTime[0];
          $times = $getDateTime[1];
          echo '<div class="portfolio-item category-3">';
          echo '<div class="portfolio-box">';
          echo '<div class="portfolio-img">';
          echo '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($poster[$i]) . '" style="width:1100; height:688px;"/>';
          echo '<a href="#modal" data-toggle="modal" data-id="' . $newsID[$i] . '" data-description="' . $description[$i] . '" data-dates="' . $dates . '" data-times="' . $times . '" data-poster="' . base64_encode($poster[$i]) . '" name="Set" class="modal-trigger" title="show"><img src="data:image/jpg;charset=utf8;base64,' . base64_encode($poster[$i]) . '"/>;</a></td>';
          echo '<div class="portfolio-title">';
          echo '<div>';
          echo '<h5 class="font-weight-normal">Project Title</h5>';
          echo "<span>'$description[$i]'</span>";
          echo '</div>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
        }
        ?>
      </div>
    </div><!-- end container -->
    <div class="card-body">
      <!-- Modal -->
      <div class="modal" id="modal" tabindex="-1" role="dialog" aria-hidden="true" style="top:50px; height:fit-content; margin-left:auto; margin-right:auto;">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header no-bd">
              <h5 class="modal-title">
                <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                <span class="fw-mediumbold"> Publish News
                </span>
              </h5>
              <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p class="small"></p>
              <form class="form-horizontal row-fluid" method="post" action="Publish News.php" enctype="multipart/form-data">
                <div class="row">
                  <div class="control-group">
                    <label class="control-label" for="fullname">News ID:</label>
                    <div class="controls">
                      <input type="text" id="id" name="NewsID" class="span12" readonly>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="poster">Attach Poster</label>
                    <div class="controls">
                      <input type="file" id="poster" name="Poster" class="span12" style="height:50px; margin-bottom:5px; width:290px;" required>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="description">Description</label>
                    <div class="controls">
                      <input type="text" id="description" name="Description" class="span12" maxlength="100">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="description">Date</label>
                    <div class="controls">
                      <input type="date" id="dates" name="Date" class="span12" required>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="description">Time</label>
                    <div class="controls">
                      <input type="time" id="times" name="Time" class="span12">
                    </div>
                  </div>
                </div>
                <div class="modal-footer no-bd">
                  <button type="submit" class="button-rounded margin-top-20" name="Publish">Publish</button>
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
    <?php include("Footer.html"); ?>

    <!--Javascript -->
    <script src="CSS/assets/plugins/jquery.min.js"></script>
    <script src="CSS/assets/plugins/plugins.js"></script>
    <script src="CSS/assets/js/functions.min.js"></script>
    <script>
      //JQuery code to display items in publish dialog box
      $(document).on("click", ".modal-trigger", function() {
        var ID = $(this).data('id');
        var description = $(this).data('description');
        var dates = $(this).data('dates');
        var times = $(this).data('times');
        var poster = $(this).data('poster');
        $(".modal-content #id").val(ID);
        $("#description").val(description);
        $("#dates").val(dates);
        $(".modal-content #times").val(times);
        $("#poster").val(poster);
      });
    </script>
  </body>

</html>