<?php
include_once("PHP/Tip.php");
//Call the Tip class
$tipObj = new Tip();
//To display notification on the menu
$count = $tipObj->getCount();
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
                    <div class="">
                        <a href="Logout.php"><img src="CSS/images/logout-24.png" alt=""></a>
                    </div>
                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->
    </div><!-- .top-header-bar -->
    <div>
        <div class="col-12 d-flex flex-wrap justify-content-between align-items-center"><img class="d-block" src="CSS/images/dove4.png" alt="logo"></a>
            <nav class=" align-items-center">
                <ul id="nav" class=" align-content-center">
                    <li class="current-menu-item"><a href="Home.php">Home</a></li>
                    <li><a href="#">Members</a>
                        <ul>
                            <li><a href="Register Member.php">Register Member</a></li>
                            <li><a href="Update Member.php">Update Member</a></li>
                            <li><a href="View Member List.php">View Members</a></li>
                            <li><a href="#">Shedule Treatment</a>
                                <ul>
                                    <li><a href="Schedule Treatment.php">Add Schedule</a></li>
                                    <li><a href="Update Treatment Schedule.php">Update Schedule</a></li>
                                    <li><a href="Add Daily Schedule.php">Daily Schedule</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#">Organization</a>
                        <ul>
                            <li><a href="Manage Supply List.php">Supply List</a></li>
                            <li><a href="ManageBankAccount.php">Bank Account</a></li>
                            <li><a href="Manage Daily Activity.php">Daily Activity</a></li>
                            <li><a href="Publish News.php">Publish News</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Contributors</a>
                        <ul>
                            <li><a href="Register Contributor.php">Register Contributor</a></li>
                            <li><a href="Update Contributor.php">Update Contributor</a></li>
                            <li><a href="View Contributor Contact.php">Contact Info</a></li>
                            <li><a href="View Scheduled Volunteers.php">Scheduled Volunteers</a></li>
                            <li><a href="View Scheduled Visitors.php">Scheduled Visitors</a></li>
                        </ul>
                    </li>
                    <li><a href="View Event.php">Events</a></li>
                    <li><a href="View Tips.php">Tips
                            <i class="fa fa-bell-o"></i>
                            <span class="notification">
                                <?php 
                                //Display notification for the menu
                                echo '<b>';
                                echo $count;
                                echo '</b>'; ?></span>
                        </a></li>
                    <li><a href="Signup.php">Sign up</a></li>
                </ul>
            </nav><!-- .site-navigation -->
            <!-- .site-navigation -->
            <div class="hamburger-menu d-lg-none" onclick="myFunction()">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div><!-- .hamburger-menu -->
        </div>
    </div><!-- .nav-bar -->
</header><!-- .site-header -->