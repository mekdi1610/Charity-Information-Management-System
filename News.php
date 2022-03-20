<?php
include_once('PHP/News.php');
error_reporting(0);
//Call the News Class
$newsObj = new News();
//To view previously published news
$accept = $newsObj->viewFeed();
$newsID = $accept[0];
$poster = $accept[1];
$description = $accept[2];
$date = $accept[3];

//To prevent XSS Attack
function _e($string)
{
    echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>News Feed</title>
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
    <!-- themify-icons CSS -->
    <link rel="stylesheet" href="CSS/css/themify-icons.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="CSS/css/swiper.min.css">
    <!-- CSS -->
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

    <body>
        <div class="container">
            <div class="owl-carousel owl-nav-overlay owl-dots-overlay" data-owl-items="1" data-owl-nav="true" data-owl-autoplay="true">
                <?php
                //To display all the previously added news feeds
                for ($i = 0; $i < (sizeof($newsID)); $i++) {
                    echo '<div class="portfolio-item category-3">';
                    echo '<div class="portfolio-box">';
                    echo '<div class="portfolio-img">';
                    echo "<h4 style='text-align:center;'>Project Title:";
                    _e($description[$i]);
                    echo "</h4>";
                    echo '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($poster[$i]) . '"/>';
                    echo '<div class="portfolio-title">';
                    echo '<div>';
                    echo '<h5 class="font-weight-normal">Project Title</h5>';
                    echo "<span>Date: $date[$i]</span>";
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div><!-- end container -->
        <div class="container">
            <div class="section-title">
                <h2 style="color: black;">News</h2>
            </div>
            <div class="owl-carousel owl-nav-overlay owl-dots-overlay" data-owl-items="1" data-owl-nav="true" data-owl-autoplay="true">
                <div class="portfolio-item category-3">
                    <div class="portfolio-box">
                        <div class="portfolio-img">
                            <h5 class='font-weight-normal' style='text-align:center;'>Mekedonia's Documentary</h5>
                            <iframe width="560" height="500" src="https://www.youtube.com/embed/sbyItd5N-m8" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <div class="portfolio-title">
                                <div>
                                    <h5 class="font-weight-normal">2013</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portfolio-item category-3">
                    <div class="portfolio-box">
                        <div class="portfolio-img">
                            <h5 class='font-weight-normal' style='text-align:center;'>Dr. Biniam Belete [On Riyot]</h5>
                            <iframe width="560" height="500" src="https://www.youtube.com/embed/czWWptes_zw" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <div class="portfolio-title">
                                <div>
                                    <h5 class="font-weight-normal">2016</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portfolio-item category-3">
                    <div class="portfolio-box">
                        <div class="portfolio-img">
                            <h5 class='font-weight-normal' style='text-align:center;'>Dr. Biniam Belete [On Man Ke Man with Messay Show]</h5>
                            <iframe width="560" height="500" src="https://www.youtube.com/embed/WDy_QCUfWBk" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <div class="portfolio-title">
                                <div>
                                    <h5 class="font-weight-normal">2019</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portfolio-item category-3">
                    <div class="portfolio-box">
                        <div class="portfolio-img">
                            <h5 class='font-weight-normal' style='text-align:center;'>Opening a Go Fund Me</h5>
                            <iframe width="560" height="500" src="https://www.youtube.com/embed/QdjIl4ypjhA" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <div class="portfolio-title">
                                <div>
                                    <h5 class="font-weight-normal">2019</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portfolio-item category-3">
                    <div class="portfolio-box">
                        <div class="portfolio-img">
                            <h5 class='font-weight-normal' style='text-align:center;'>Go Fund Me Campign</h5>
                            <iframe width="560" height="500" src="https://www.youtube.com/embed/hN9_IhTUKhQ" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <div class="portfolio-title">
                                <div>
                                    <h5 class="font-weight-normal">2020</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end container -->
        <div class="container">
            <div class="section-title">
                <h2 style="color: black;">About Us</h2>
            </div>
            <div class="owl-carousel owl-nav-overlay owl-dots-overlay" data-owl-items="1" data-owl-nav="true" data-owl-autoplay="true">
                <div>
                    <img src="CSS/Gallery/1.jpg" alt="">
                </div>
                <div>
                    <img src="CSS/Gallery/2.jpg" alt="">
                </div>
                <div>
                    <img src="CSS/Gallery/3.jpg" alt="">
                </div>
                <div>
                    <img src="CSS/Gallery/4.jpg" alt="">
                </div>
                <div>
                    <img src="CSS/Gallery/5.jpg" alt="">
                </div>
                <div>
                    <img src="CSS/Gallery/6.jpg" alt="">
                </div>
                <div>
                    <img src="CSS/Gallery/7.jpg" alt="">
                </div>
                <div>
                    <img src="CSS/Gallery/8.jpg" alt="">
                </div>
                <div>
                    <img src="CSS/Gallery/9.jpg" alt="">
                </div>
                <div>
                    <img src="CSS/Gallery/10.jpg" alt="">
                </div>
            </div>
            <div class="section-title">
                <h2 style="color: black;">Gallery</h2>
            </div>
        </div><!-- end container -->
    </body>
    <?php include("Footer.html"); ?>
    
    <!-- Javascript -->
    <script src="CSS/assets/plugins/jquery.min.js"></script>
    <script src="CSS/assets/plugins/plugins.js"></script>
    <script src="CSS/assets/js/functions.min.js"></script>


</html>