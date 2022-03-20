<?php
//Contact Us using an email platform
if (isset($_POST['Send'])) {
    //Prepare the email
    $from = $_POST["Email"];
    $headers = "From: " . $from;
    $subject = $_POST["Subject"];
    $message = "From: " . $from . " Message:" . $_POST["Message"];
    $to = 'beletebiniyam65@gmail.com';
    //Send the email
    if (mail($to, $subject, $message, $headers)) {
        echo "<script>alert('Sent Successfully! We will reply soon.');
    document.location = '/Contacts.php' </script>";
    } else {
        echo "<script>alert('Sending Failed!');
        document.location = '/Contacts.php' </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contacts</title>
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
    <!-- Map and Font -->
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
        <div class="section-title text-center">
            <h2>Visitation Hours</h2>
            <p>
                Monday - Sunday - 6:00 am - 6:00 pm</p>
        </div>
        <div id="map1">
            <h4 style="text-align: center;">Directions</h4>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3940.461301767216!2d38.8906801141606!3d9.021614391590127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b913976ff099f%3A0xea0ce22fdf7bb3c3!2zTWVrZWRvbmlhIEVsZGVycyB8IOGImOGJhOGLtuGKleGLqw!5e0!3m2!1sen!2set!4v1607379977422!5m2!1sen!2set" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </div>
    </div><!-- end container -->
    <!-- Contact section -->
    <div class="section padding-top-0">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="margin-bottom-30">
                        <h6 class="font-weight-normal margin-0">Phone:</h6>
                        <p>+251 94 113 1313</p>
                        <p>+251 92 028 9273</p>
                    </div>
                    <div class="margin-bottom-30">
                        <h6 class="font-weight-normal margin-0">Email:</h6>
                        <p>info@mekedoniahomes.org</p>
                        <p>binbenj@aol.com</p>
                    </div>
                    <div>
                        <h6 class="font-weight-normal margin-0">Address:</h6>
                        <p>Near Ayat Condominium, Addis Ababa, Ethiopia </p>
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <!-- Modal -->
                    <div class="modal-dialog" role="document">
                        <div class="modal-content" style="width:600px">
                            <h6 class="font-weight-normal margin-0" style="text-align: center;"><i class="fa fa-envelope" aria-hidden="true"></i>Contact Us</h6>
                            <div class="modal-body">
                                <p class="small"></p>
                                <form class="form-horizontal row-fluid" method="post" action="Contacts.php">
                                    <div class="row">
                                        <div class="control-group">
                                            <label class="control-label" for="Name">Name</label>
                                            <div class="controls">
                                                <input type="text" id="contact" name="Name" class="span12">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="Email">Email</label>
                                            <div class="controls">
                                                <input type="email" id="contact" name="Email" class="span12">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="Subject">Subject</label>
                                            <div class="controls">
                                                <input type="text" id="contact" name="Subject" class="span12">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="Message">Message:</label>
                                            <div class="controls">
                                                <textarea id="contact" placeholder="" name="Message" class="span12"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer no-bd">
                                        <button type="submit" class="print" name="Send">Send Message</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end row -->
        </div><!-- end container -->
    </div>
    <!-- end Contact section -->
    <?php include_once("Footer.html") ?>
    <!-- Javascript -->
    <script src="CSS/assets/plugins/jquery.min.js"></script>
    <script src="CSS/assets/plugins/plugins.js"></script>
    <script src="CSS/assets/js/functions.min.js"></script>
</body>

</html>