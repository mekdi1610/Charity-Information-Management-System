<?php
session_start();
if ($_SESSION["FullName"] && $_SESSION["Email"]) {
    $volID = $_SESSION["VolID"];
    $fullName = $_SESSION["FullName"];
    $userName = $_SESSION["UserName"];
    $email = $_SESSION["Email"];
    $photo = $_SESSION["Photo"];
    $Occupation = $_SESSION["Occupation"];
}
?>
<header class="site-header">
    <div class="top-header-bar">
        <div class="container">
            <div class="row flex-wrap justify-content-center justify-content-lg-between align-items-lg-center">
                <div class="col-12 col-lg-8 d-none d-md-flex flex-wrap justify-content-center justify-content-lg-start mb-3 mb-lg-0">
                    <div class="header-bar-email">
                        <a href="https://www.facebook.com/mekedoniahomes"><img src="CSS/images/facebook-24.png" alt=""></a>
                    </div><!-- .header-bar-email -->
                    <div class="header-bar-text">
                        <a href="https://www.gofundme.com/f/mekedonia-charity-help-build-home-for-the-homeless"><img src="CSS/images/bird-2-24.png" alt="">
                    </div><!-- .header-bar-text -->
                    <div class="header-bar-text">
                        <a href="#"><img src="CSS/images/phone-24.png" alt=""></a>
                    </div>
                </div><!-- .col -->
                <div class="col-12 col-lg-4 d-flex flex-wrap justify-content-center justify-content-lg-end align-items-center">
                    <div class="header-bar-text">
                        <a data-toggle="dropdown" href="#" aria-expanded="false">
                            <div class="avatar-sm">
                                <img src="data:image/jpg;charset=utf8;base64,<?php _e(base64_encode($photo)); ?>" alt="..." class="avatar-img rounded-circle" style="width:50px; height: 50px">
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated fadeIn">
                            <div class="dropdown-user-scroll scrollbar-outer">
                                <li>
                                    <div class="user-box">
                                        <div class="avatar-lg"><img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($photo); ?>" alt="image profile" class="avatar-img rounded" style="width:50px; height: 50px"></div>
                                        <div class="u-text">
                                            <b>
                                                <p><?php echo $fullName ?></p>
                                            </b>
                                            <p class="text-muted"><?php echo $email ?></p><a href="Update Profile.php" class="btn" title="Send" style="color: white; background: #FF4800; border-radius: 6px;">Update Profile</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="Logout.php">Logout</a>
                                    </form>
                                </li>
                            </div>
                        </ul>
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="col-12 d-flex flex-wrap justify-content-between align-items-center"><img class="d-block" src="CSS/images/dove4.png" alt="logo"></a>
            <nav class=" align-items-center">
                <ul id="nav" class="align-content-center">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="Schedule Volunteer.php">Volunteer</a></li>
                    <li><a href="View Request.php">Request</a></li>
                </ul>
            </nav><!-- .site-navigation -->
            <div class="hamburger-menu d-lg-none" onclick="myFunction()">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div><!-- .hamburger-menu -->
        </div>
    </div>
    <!--    </div> .row -->
    <!--  </div> .container -->
    <!-- </div> .nav-bar -->
</header><!-- .site-header -->