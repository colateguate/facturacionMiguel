<?php
session_start ();

include_once('../config.php');

if (!isset ($_SESSION["GUEST_DN"]) || isset ($_COOKIE['pageLock']) AND $_COOKIE['pageLock'] == 1) {
    header ("location: /controllers/logoutController.php");
    die ();
}

$modulActiu = 'dashboard'; // Variable que utilitzarem a /aside.php per saber quina part del menú marcar com activa
$guestDn = $_SESSION['GUEST_DN'];
?>
<!doctype html>
<html class="fixed">
    <head>

        <!-- Basic -->
        <meta charset="UTF-8">

        <title>Metropolitana Intranet | Dashboard</title>
        <meta name="keywords" content="HTML5 Admin Template" />
        <meta name="description" content="Porto Admin - Responsive HTML5 Template">
        <meta name="author" content="okler.net">

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <!-- Web Fonts  -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

        <!-- Vendor CSS -->
        <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.css" />

        <link rel="stylesheet" href="/assets/vendor/font-awesome/css/font-awesome.css" />
        <link rel="stylesheet" href="/assets/vendor/magnific-popup/magnific-popup.css" />
        <link rel="stylesheet" href="/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

        <!-- Specific Page Vendor CSS -->
        <link rel="stylesheet" href="/assets/vendor/jquery-ui/jquery-ui.css" />
        <link rel="stylesheet" href="/assets/vendor/jquery-ui/jquery-ui.theme.css" />
        <link rel="stylesheet" href="/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
        <link rel="stylesheet" href="/assets/vendor/morris.js/morris.css" />

        <!-- Theme CSS -->
        <link rel="stylesheet" href="/assets/stylesheets/theme.css" />

        <!-- Skin CSS -->
        <link rel="stylesheet" href="/assets/stylesheets/skins/default.css" />

        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="/assets/stylesheets/theme-custom.css">

        <!-- Head Libs -->
        <script src="/assets/vendor/modernizr/modernizr.js"></script>


        <link rel="stylesheet" href="/assets/vendor/jstree/themes/default/style.css" />
        <script src="/assets/vendor/jquery/jquery.js"></script>
    </head>
    <body>
        <section class="body">

            <?php
            include("../header.php");
            ?>

            <div class="inner-wrapper">
                <!-- start: sidebar -->
                <?php include_once("../aside.php"); ?>
                <!-- end: sidebar -->
                <section role="main" class="content-body">
                    <header class="page-header">
                        <h2>Dashboard</h2>

                        <div class="right-wrapper pull-right">
                            <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
                        </div>
                    </header>

                    <!-- start: page -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <?php
                            include("forum.php");
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <?php
                            include("faq.php");
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <?php
                            include("finder.php");
                            ?>
                        </div>
                    </div>
                    <!-- end: page -->
                </section>
            </div>

            <aside id="sidebar-right" class="sidebar-right">
                <div class="nano">
                    <div class="nano-content">
                        <a href="#" class="mobile-close visible-xs">
                            Collapse <i class="fa fa-chevron-right"></i>
                        </a>

                        <div class="sidebar-right-wrapper">

                            <div class="sidebar-widget widget-calendar">
                                <h6>Upcoming Tasks</h6>
                                <div data-plugin-datepicker data-plugin-skin="dark" ></div>

                                <ul>
                                    <li>
                                        <time datetime="2016-04-19T00:00+00:00">04/19/2016</time>
                                        <span>Company Meeting</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </section>

        <!-- Vendor -->
        
        <script src="/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
        <script src="/assets/vendor/bootstrap/js/bootstrap.js"></script>
        <script src="/assets/vendor/nanoscroller/nanoscroller.js"></script>
        <script src="/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script src="/assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
        <script src="/assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>

        <!-- Specific Page Vendor -->
        <script src="/assets/vendor/jquery-ui/jquery-ui.js"></script>
        <script src="/assets/vendor/jqueryui-touch-punch/jqueryui-touch-punch.js"></script>
        <script src="/assets/vendor/jquery-appear/jquery-appear.js"></script>
        <script src="/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
        <script src="/assets/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.js"></script>
        <script src="/assets/vendor/flot/jquery.flot.js"></script>
        <script src="/assets/vendor/flot.tooltip/flot.tooltip.js"></script>
        <script src="/assets/vendor/flot/jquery.flot.pie.js"></script>
        <script src="/assets/vendor/flot/jquery.flot.categories.js"></script>
        <script src="/assets/vendor/flot/jquery.flot.resize.js"></script>
        <script src="/assets/vendor/jquery-sparkline/jquery-sparkline.js"></script>
        <script src="/assets/vendor/raphael/raphael.js"></script>
        <script src="/assets/vendor/morris.js/morris.js"></script>
        <script src="/assets/vendor/gauge/gauge.js"></script>
        <script src="/assets/vendor/snap.svg/snap.svg.js"></script>
        <script src="/assets/vendor/liquid-meter/liquid.meter.js"></script>
        <script src="/assets/vendor/jqvmap/jquery.vmap.js"></script>
        <script src="/assets/vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>
        <script src="/assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
        <script src="/assets/vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>
        <script src="/assets/vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>
        <script src="/assets/vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>
        <script src="/assets/vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>
        <script src="/assets/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>
        <script src="/assets/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>

        <script src="/assets/vendor/jstree/jstree.js"></script>

        <!-- Theme Base, Components and Settings -->
        <script src="/assets/javascripts/theme.js"></script>

        <!-- Theme Custom -->
        <script src="/assets/javascripts/theme.custom.js"></script>

        <!-- Theme Initialization Files -->
        <script src="/assets/javascripts/theme.init.js"></script>

        <!-- Examples -->
        <script src="/assets/javascripts/dashboard/examples.dashboard.js"></script>
        
        <script src="/assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
        <script src="/assets/vendor/pnotify/pnotify.custom.js"></script>
        <script src="/js/forum/forum.js"></script>

    </body>
</html>
