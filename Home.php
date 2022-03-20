<?php
include_once("PHP/Volunteer.php");
error_reporting(0);
session_start();
//If the user come from login page then role is set
if (isset($_SESSION["Role"])) {
    $role = $_SESSION["Role"];
} else { //If not role is set as user
    $role = "User";
}

//Call the Volunteer class
$volObj = new Volunteer();
//To get volunteers with more schedules
$accept = $volObj->getMainVolunteers();
$volIDs = $accept[0];
$photos = $accept[1];
$fullNames = $accept[2];
$occs = $accept[3];

//To prevent XSS Attack
function _e($string)
{
    echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Mekedoniya መቄዶኒያ</title>
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
    <!-- Table CSS -->
    <link rel="stylesheet" href="CSS/table/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/table/assets/css/atlantis.min.css">
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
    <?php
    //If role is employee then employee menu will be displayed
    if ($role == "Employee") {
        include_once("HeaderEmployee.php");
    }
    //If role is volunteer then olunteer menu will be displayed
    else if ($role == "Volunteer") {
        include_once("HeaderVolunteer.php");
    }
    //If role is not set then user menu will be displayed
    else {
        include_once("HeaderHome.php");
    }
    ?>
    <div class="swiper-container hero-slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide hero-content-wrap">
                <img src="CSS/images/pic5.png" alt="">
                <div class="hero-content-overlay position-absolute w-100 h-100">
                    <div class="container h-100">
                        <div class="row h-100">
                            <div class="col-12 col-lg-8 d-flex flex-column justify-content-center align-items-start">
                                <div class="welcome-content">
                                    <header class="entry-header">
                                        <h2 class="entry-title">Welcome to Mekedoniya</h2>
                                    </header><!-- .entry-header -->
                                </div>
                                <div class="entry-content mt-4">
                                    <h5 style="color: white;">"ሰውን ለመርዳት ሰው መሆን በቂ ነው!"</h5>
                                    <p id="main">
                                        Mekedonia Homes is the home for the elderly and the mentally disabled.
                                        It is a non-governmental, non-profit and independent organization, founded on January 2010 by Dr.Biniyam Belete.
                                        The organization is headquartered in Addis Ababa, Ethiopia and located around Ayat Square.
                                    </p>
                                </div><!-- .entry-content -->
                                <footer class="entry-footer d-flex flex-wrap align-items-center mt-5">
                                    <a href="https://www.gofundme.com/f/mekedonia-charity-help-build-home-for-the-homeless" class="btn gradient-bg mr-2">Donate Now</a>
                                </footer><!-- .entry-footer -->
                            </div><!-- .col -->
                        </div><!-- .row -->
                    </div><!-- .container -->
                </div><!-- .hero-content-overlay -->
            </div><!-- .hero-content-wrap -->
            <div class="swiper-slide hero-content-wrap">
                <img src="CSS/images/bibiyiam.jpg" alt="">
                <div class="hero-content-overlay position-absolute w-100 h-100">
                    <div class="container h-100">
                        <div class="row h-100">
                            <div class="col-12 col-lg-8 d-flex flex-column justify-content-center align-items-start">
                                <header class="entry-header">
                                    <h4 style="color: white;">Dr. Biniyiam Belete</h4>
                                </header><!-- .entry-header -->
                                <div class="entry-content mt-4">
                                    <p id="main">Dr. Biniyam started this organization by rescuing elderly people into his own home. Later, he was able to fund his cause from major donors and the organization grew to what it is now.</p>
                                </div><!-- .entry-content -->
                                <footer class="entry-footer d-flex flex-wrap align-items-center mt-5">
                                    <a href="https://www.gofundme.com/f/mekedonia-charity-help-build-home-for-the-homeless" class="btn gradient-bg mr-2">Donate Now</a>
                                </footer><!-- .entry-footer -->
                            </div><!-- .col -->
                        </div><!-- .row -->
                    </div><!-- .container -->
                </div><!-- .hero-content-overlay -->
            </div><!-- .hero-content-wrap -->
            <div class="swiper-slide hero-content-wrap">
                <img src="CSS/images/elder.jpg" alt="">
                <div class="hero-content-overlay position-absolute w-100 h-100">
                    <div class="container h-100">
                        <div class="row h-100">
                            <div class="col-12 col-lg-8 d-flex flex-column justify-content-center align-items-start">
                                <header class="entry-header">
                                    <h4 style="color: white;">Our Purpose</h4>
                                </header><!-- .entry-header -->
                                <div class="entry-content mt-4">
                                    <p id="main">The purpose of Mekedonia Homes is to support the elderly and people with Mental disabilities who otherwise have no means of support or survival by providing them with shelter, clothing, food, medical and other basic services.
                                    </p>
                                </div><!-- .entry-content -->
                                <footer class="entry-footer d-flex flex-wrap align-items-center mt-5">
                                    <a href="https://www.gofundme.com/f/mekedonia-charity-help-build-home-for-the-homeless" class="btn gradient-bg mr-2">Donate Now</a>
                                </footer><!-- .entry-footer -->
                            </div><!-- .col -->
                        </div><!-- .row -->
                    </div><!-- .container -->
                </div><!-- .hero-content-overlay -->
            </div><!-- .hero-content-wrap -->
        </div><!-- .swiper-wrapper -->
        <div class="pagination-wrap position-absolute w-100">
            <div class="container">
                <div class="swiper-pagination"></div>
            </div><!-- .container -->
        </div><!-- .pagination-wrap -->
        <!-- Add Arrows -->
        <div class="swiper-button-next flex justify-content-center align-items-center">
            <span><svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1171 960q0 13-10 23l-466 466q-10 10-23 10t-23-10l-50-50q-10-10-10-23t10-23l393-393-393-393q-10-10-10-23t10-23l50-50q10-10 23-10t23 10l466 466q10 10 10 23z" />
                </svg></span>
        </div>
        <div class="swiper-button-prev flex justify-content-center align-items-center">
            <span><svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1203 544q0 13-10 23l-393 393 393 393q10 10 10 23t-10 23l-50 50q-10 10-23 10t-23-10l-466-466q-10-10-10-23t10-23l466-466q10-10 23-10t23 10l50 50q10 10 10 23z" />
                </svg></span>
        </div>
    </div><!-- .hero-slider -->
    <div class="home-page-icon-boxes">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 mt-4 mt-lg-0">
                    <div class="icon-box active">
                        <figure class="d-flex justify-content-center">
                            <img src="CSS/images/hands-gray.png" alt="">
                            <img src="CSS/images/hands-white.png" alt="">
                        </figure>
                        <header class="entry-header">
                            <h3 class="entry-title">Become a Volunteer</h3>
                        </header>
                        <div class="entry-content">
                            <p>You can involve in a number of activities per day. You can help us build a better place for the ones we are helping. Schedule your time with us!</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mt-4 mt-lg-0">
                    <div class="icon-box">
                        <figure class="d-flex justify-content-center">
                            <i class="fa fa-phone  fa-4x" aria-hidden="true"></i>
                        </figure>
                        <header class="entry-header">
                            <h3 class="entry-title">Tip Us!</h3>
                        </header>
                        <div class="entry-content">
                            <p>Tip us about the whereabouts of any elderly or mentaly disabled people so we can tend to them. </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mt-4 mt-lg-0">
                    <div class="icon-box">
                        <figure class="d-flex justify-content-center">
                            <i class="fa fa fa-calendar-plus-o fa-4x" aria-hidden="true"></i>
                        </figure>
                        <header class="entry-header">
                            <h3 class="entry-title">Celebrate with us! </h3>
                        </header>
                        <div class="entry-content">
                            <p>Have your Weddings, Birthdays, Graduations, Memorals, Sedekas, and any other occasions.</p>
                        </div>
                    </div>
                </div>
            </div><!-- .row -->
        </div><!-- .container -->
    </div><!-- .home-page-icon-boxes -->
    <div class="home-page-welcome">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6 order-2 order-lg-1">
                    <div class="welcome-content">
                        <header class="entry-header">
                            <h2 class="entry-title">How We Are Helping</h2>
                        </header><!-- .entry-header -->
                        <div class="entry-content mt-5">
                            <p>We are an organization focused on helping the lives of the elderly and those with mental disabilities, by providing all basic services (food, clothes, shelter, hygiene facilities, medical, educational and others) to the residents in the center.</p>
                        </div><!-- .entry-content -->
                        <div class="entry-footer mt-5">
                            <a href="#" class="btn gradient-bg mr-2">Read More</a>
                        </div><!-- .entry-footer -->
                    </div><!-- .welcome-content -->
                </div><!-- .col -->
                <div class="col-12 col-lg-6 mt-4 order-1 order-lg-2">
                    <img src="CSS/images/pic2.png" alt="welcome">
                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->
    </div><!-- .home-page-icon-boxes -->
    <div class="our-causes" id="our-causes">
        <div class="container">
            <div class="row">
                <div class="coL-12">
                    <div class="section-heading">
                        <h2 class="entry-title">Our Causes</h2>
                    </div><!-- .section-heading -->
                </div><!-- .col -->
            </div><!-- .row -->
            <div class="row">
                <div class="col-12">
                    <div class="swiper-container causes-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="cause-wrap">
                                    <figure class="m-0">
                                        <img src="CSS/images/res.jpg" alt="">
                                        <div class="figure-overlay d-flex justify-content-center align-items-center position-absolute w-100 h-100">
                                            <a href="https://www.gofundme.com/f/mekedonia-charity-help-build-home-for-the-homeless" class="btn gradient-bg mr-2">Donate Now</a>
                                        </div><!-- .figure-overlay -->
                                    </figure>
                                    <div class="cause-content-wrap">
                                        <header class="entry-header d-flex flex-wrap align-items-center">
                                            <h3 class="entry-title w-100 m-0"><a href="#">Our Residents</h3>
                                        </header><!-- .entry-header -->
                                        <div class="entry-content">
                                            <p class="m-0">Mekedonia's beneficiaries are homeless people picked up from different parts of the country such as Hawassa, Debre Zeit, Debre Libanos, Addis Ababa and Guder.
                                                To date Mekedonia Homes has had Over 3000 Residents.
                                            <ul>
                                                <li>1,193 - Elderly</li>
                                                <li>914 - Mentally Disabled</li>
                                                <li>526 - Bedridden</li>
                                                <li>367 - Physically Disabled residents.</li>
                                            </ul>
                                            </p>
                                        </div><!-- .entry-content -->
                                    </div><!-- .cause-content-wrap -->
                                </div><!-- .cause-wrap -->
                            </div><!-- .swiper-slide -->
                            <div class="swiper-slide">
                                <div class="cause-wrap">
                                    <figure class="m-0">
                                        <img src="CSS/images/Mission.jpg" alt="">
                                        <div class="figure-overlay d-flex justify-content-center align-items-center position-absolute w-100 h-100">
                                            <a href="https://www.gofundme.com/f/mekedonia-charity-help-build-home-for-the-homeless" class="btn gradient-bg mr-2">Donate Now</a>
                                        </div><!-- .figure-overlay -->
                                    </figure>
                                    <div class="cause-content-wrap">
                                        <header class="entry-header d-flex flex-wrap align-items-center">
                                            <h3 class="entry-title w-100 m-0"><a href="#">Our Mission</a></h3>
                                        </header><!-- .entry-header -->
                                        <div class="entry-content">
                                            <p class="m-0">
                                            <ul>
                                                <li>Helping the disabled by encouraging and rehabilitating their capacities.</li>
                                                <li>Providing education, training, and employment opportunities.</li>
                                                <li>Helping disabled and elderly people with the potential to find and retain employment</li>
                                                <li>Working on inclusive and integrated community development, biodiversity and livelihood improvement, especially for elder people and those with disabilities.</li>
                                                <li>Improving public awareness and acceptance of the capabilities, needs and problems of disabled and marginalized elder people </li>
                                            </ul>
                                            </p>
                                        </div><!-- .entry-content -->
                                    </div><!-- .cause-content-wrap -->
                                </div><!-- .cause-wrap -->
                            </div><!-- .swiper-slide -->
                            <div class="swiper-slide">
                                <div class="cause-wrap">
                                    <figure class="m-0">
                                        <img src="CSS/images/vision.jpg" alt="">
                                        <div class="figure-overlay d-flex justify-content-center align-items-center position-absolute w-100 h-100">
                                            <a href="https://www.gofundme.com/f/mekedonia-charity-help-build-home-for-the-homeless" class="btn gradient-bg mr-2">Donate Now</a>
                                        </div><!-- .figure-overlay -->
                                    </figure>
                                    <div class="cause-content-wrap">
                                        <header class="entry-header d-flex flex-wrap align-items-center">
                                            <h3 class="entry-title w-100 m-0"><a href="#">Our Success</a></h3>
                                        </header><!-- .entry-header -->
                                        <div class="entry-content">
                                            <p class="m-0">Over the past years more than 400 of our residents have been successfully rehabilitated and returned to work. Some are working as Security Guards, Caregivers, Sanitation Workers, Drivers, Hostesses, Cashiers and Tour Guides.
                                                Others have been trained to work as Laboratory Technicians, Welders, Cement workers, Carpenters or Wood Workers. Additionally some have learned weaving, sewing, tapestry & carpet making, broom & mop production even raising chickens.
                                            </p>
                                        </div><!-- .entry-content -->
                                    </div><!-- .cause-content-wrap -->
                                </div><!-- .cause-wrap -->
                            </div><!-- .swiper-slide -->
                            <div class="swiper-slide">
                                <div class="cause-wrap">
                                    <figure class="m-0">
                                        <img src="CSS/images/abiy.jpg" alt="">
                                        <div class="figure-overlay d-flex justify-content-center align-items-center position-absolute w-100 h-100">
                                            <a href="https://www.gofundme.com/f/mekedonia-charity-help-build-home-for-the-homeless" class="btn gradient-bg mr-2">Donate Now</a>
                                        </div><!-- .figure-overlay -->
                                    </figure>
                                    <div class="cause-content-wrap">
                                        <header class="entry-header d-flex flex-wrap align-items-center">
                                            <h3 class="entry-title w-100 m-0"><a href="#">Our Accomplishments</a></h3>
                                        </header><!-- .entry-header -->
                                        <div class="entry-content">
                                            <p class="m-0">
                                                We have orchestrated tours for many volunteer groups, students, employees, spiritual leaders, government officials and other members of the community to see what we do and be a part of our mission.
                                                We have partnered up with Hospitals like Korean and Amanuel Specialized Mental Hospital to assist our residents to get exceptionally good health care
                                            </p>
                                        </div><!-- .entry-content -->
                                    </div><!-- .cause-content-wrap -->
                                </div><!-- .cause-wrap -->
                            </div><!-- .swiper-slide -->
                            <div class="swiper-slide">
                                <div class="cause-wrap">
                                    <figure class="m-0">
                                        <img src="images/compound.png" alt="">
                                        <div class="figure-overlay d-flex justify-content-center align-items-center position-absolute w-100 h-100">
                                            <a href="https://www.gofundme.com/f/mekedonia-charity-help-build-home-for-the-homeless" class="btn gradient-bg mr-2">Donate Now</a>
                                        </div><!-- .figure-overlay -->
                                    </figure>
                                    <div class="cause-content-wrap">
                                        <header class="entry-header d-flex flex-wrap align-items-center">
                                            <h3 class="entry-title w-100 m-0"><a href="#">Our Projects</a></h3>
                                        </header><!-- .entry-header -->
                                        <div class="entry-content">
                                            <p class="m-0">We are building a rehabilitation on the 30 thousand Sq. foot land that was donated to us, which is located next to the Ayat Condominiums which are owned and operated by the City of Addis Ababa. </p>
                                        </div><!-- .entry-content -->
                                    </div><!-- .cause-content-wrap -->
                                </div><!-- .cause-wrap -->
                            </div><!-- .swiper-slide -->
                        </div><!-- .swiper-wrapper -->
                    </div><!-- .swiper-container -->
                    <!-- Add Arrows -->
                    <div class="swiper-button-next flex justify-content-center align-items-center">
                        <span><svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1171 960q0 13-10 23l-466 466q-10 10-23 10t-23-10l-50-50q-10-10-10-23t10-23l393-393-393-393q-10-10-10-23t10-23l50-50q10-10 23-10t23 10l466 466q10 10 10 23z" />
                            </svg></span>
                    </div>
                    <div class="swiper-button-prev flex justify-content-center align-items-center">
                        <span><svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1203 544q0 13-10 23l-393 393 393 393q10 10 10 23t-10 23l-50 50q-10 10-23 10t-23-10l-466-466q-10-10-10-23t10-23l466-466q10-10 23-10t23 10l50 50q10 10 10 23z" />
                            </svg></span>
                    </div>
                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->
    </div><!-- .our-causes -->
    <!-- Team section -->
    <div class="our-causes" id="our-causes">
        <div class="container">
            <div class="row">
                <div class="coL-12">
                    <div class="section-heading">
                        <h2 class="entry-title">Our Volunteers</h2>
                    </div><!-- .section-heading -->
                </div><!-- .col -->
            </div><!-- .row -->
            <div class="row">
                <div class="col-12">
                    <div class="row text-center">
                        <?php
                        //Display the main volunteers information
                        for ($i = 0; $i < 6; $i++) {
                            echo '<div class="col-12 col-md-6 col-lg-4">';
                            echo ' <div class="border-all border-radius padding-30">';
                            echo '<img class="img-circle-xl" src="data:image/jpg;charset=utf8;base64,' . base64_encode($photos[$i]) . '" alt="">';
                            echo '<div class="margin-top-30">';
                            echo '<h6 class="font-weight-normal margin-0 line-height-140">';
                            _e($fullNames[$i]);
                            echo '</h6>';
                            echo '<span class="font-small font-weight-normal">';
                            _e($occs[$i]);
                            echo '</span>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                    </div><!-- end row -->
                </div><!-- end container -->
            </div>
            <!-- end Team section -->
        </div>
        <div class="home-page-limestone">
            <div class="container">
                <div class="row align-items-end">
                    <div class="coL-12 col-lg-6">
                        <div class="section-heading">
                            <h2 class="entry-title" style="color:#131415">Please Come and Visit. Thank you!</h2>
                            <p class="mt-5">Whether you help through monetary donations, volunteering your time, or spreading our mission through word-of-mouth, thank you. We couldn't accomplish our goals without the help of supporters like you. </p>
                        </div><!-- .section-heading -->
                    </div><!-- .col -->
                    <div class="col-12 col-lg-6">
                        <div class="milestones d-flex flex-wrap justify-content-between">
                            <div class="col-12 col-sm-4 mt-5 mt-lg-0">
                                <div class="counter-box">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src="CSS/images/teamwork.png" alt="">
                                    </div>
                                    <div class="d-flex justify-content-center align-items-baseline">
                                        <div class="start-counter" data-to="3000" data-speed="2000"></div>
                                    </div>
                                    <h3 class="entry-title">Residents</h3><!-- entry-title -->
                                </div><!-- counter-box -->
                            </div><!-- .col -->
                            <div class="col-12 col-sm-4 mt-5 mt-lg-0">
                                <div class="counter-box">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src="CSS/images/donation.png" alt="">
                                    </div>
                                    <div class="d-flex justify-content-center align-items-baseline">
                                        <div class="start-counter" data-to="400" data-speed="2000"></div>
                                    </div>
                                    <h3 class="entry-title">Employees</h3><!-- entry-title -->
                                </div><!-- counter-box -->
                            </div><!-- .col -->
                            <div class="col-12 col-sm-4 mt-5 mt-lg-0">
                                <div class="counter-box">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src="CSS/images/dove.png" alt="">
                                    </div>
                                    <div class="d-flex justify-content-center align-items-baseline">
                                        <div class="start-counter" data-to="253" data-speed="2000"></div>
                                    </div>
                                    <h3 class="entry-title">Volunteeres</h3><!-- entry-title -->
                                </div><!-- counter-box -->
                            </div><!-- .col -->
                        </div><!-- .milestones -->
                    </div><!-- .col -->
                </div><!-- .row -->
            </div><!-- .container -->
        </div><!-- .our-causes -->
        <?php include_once("Footer.html"); ?>

        <!-- Javascript -->
        <script src="CSS/assets/plugins/jquery.min.js"></script>
        <script src="CSS/assets/plugins/plugins.js"></script>
        <script src="CSS/assets/js/functions.min.js"></script>
        <script type='text/javascript' src='CSS/js/jquery.js'></script>
        <script type='text/javascript' src='CSS/js/jquery.collapsible.min.js'></script>
        <script type='text/javascript' src='CSS/js/swiper.min.js'></script>
        <script type='text/javascript' src='CSS/js/jquery.countdown.min.js'></script>
        <script type='text/javascript' src='CSS/js/circle-progress.min.js'></script>
        <script type='text/javascript' src='CSS/js/jquery.countTo.min.js'></script>
        <script type='text/javascript' src='CSS/js/jquery.barfiller.js'></script>
        <script type='text/javascript' src='CSS/js/custom.js'></script>

</body>

</html>