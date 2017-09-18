<?php
session_start();

include_once('../config.php');

if (!isset($_SESSION["GUEST_DN"]) || isset($_COOKIE['pageLock']) AND $_COOKIE['pageLock'] == 1)
{
  header("location: /controllers/logoutController.php");
  die();
}

$modulActiu = 'dashboard';

$categoryId  	= $_GET['catId'];
$categoryTitle 	= ForumCategory::getCategoryTitle($categoryId);
$arrayThreats 	= ForumThreat::loadThreats($categoryId);
$guestDn 		= $_SESSION['GUEST_DN'];
$user 			= unserialize($_SESSION["USER"]);


?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Metropolitana Intranet - Fòrum</title>
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

				<!-- Vendor CSS -->
		<link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="/vendor/animate/animate.min.css">
		<link rel="stylesheet" href="/vendor/simple-line-icons/css/simple-line-icons.min.css">
		<link rel="stylesheet" href="/vendor/owl.carousel/assets/owl.carousel.min.css">
		<link rel="stylesheet" href="/vendor/owl.carousel/assets/owl.theme.default.min.css">
		<link rel="stylesheet" href="/vendor/magnific-popup/magnific-popup.min.css">

		<!-- Theme CSS -->
		<link rel="stylesheet" href="/css/theme.css">
		<link rel="stylesheet" href="/css/theme-elements.css">
		<link rel="stylesheet" href="/css/theme-blog.css">
		<link rel="stylesheet" href="/css/theme-shop.css">

		<!-- Skin CSS -->
		<link rel="stylesheet" href="/css/skins/default.css">

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="/css/custom.css">

		<!-- Head Libs -->
		<script src="/vendor/modernizr/modernizr.min.js"></script>

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
						<h2 style="margin-top: -25px;">Fòrum</h2>
					</header>

					<!-- start: page -->
					<div class="">
						<h3 class="heading-primary"><i class="fa fa-comments"></i>Threads disponibles a <?php echo ucfirst($categoryTitle); ?></h3>

						<ul class="comments">
							<li>
								<?php
									foreach($arrayThreats as $index => $infoThreat)
									{
										$threadId = $infoThreat -> getThreatId();
										?>
										<div class="comment">
											<div class="comment-block">
												<div class="comment-arrow"></div>
												<span class="comment-by">
													<strong><?php echo User::getUserName($user -> getId()); ?></strong>
													<span class="pull-right">
														<span><a id="thread_<?php echo $threadId; ?>" href="/forum/forumPost.php?threadId=<?php echo $threadId; ?>"><i class="fa fa-reply"></i> Respondre al thread</a></span>
													</span>
												</span>
												<p>
													<?php echo $infoThreat -> getThreatPostContent() ?>
												</p>
												<span class="date pull-right"><?php echo $infoThreat -> getThreatDate()?></span>
											</div>
										</div>
										<?php
									} 
								?>
							</li>
						</ul>
					</div>
					<div class="row">
							<div class="col-md-8"></div>
							<div class="col-md-4">
								<input style="float:right;" id="nouFil" type="submit" value="Publicar nou fil" class="btn btn-primary btn-lg" data-loading-text="Loading..." onclick="return newThread(<?php echo $categoryId; ?>);">
							</div>
						</div>

					<!-- AFEGIR UN NOU THREAD -->	
					
					<div id="addThread" class="post-block post-leave-comment" style="display:none">
						<h3 class="heading-primary">Títol del nou fil:</h3>

						<form action="/controllers/forumControllers/addThreadController.php" method="post">
							<div class="row">
								<div class="form-group">
									<div class="row">
										<div class="col-md-6">
											<label>Títol *</label>
											<input class="form-control" name="newThreadTitle" id="newThreadTitle"></input>
										</div>
										<div class="col-md-6"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<div class="row">
										<div class="form-group">
											<div class="col-md-12">
												<label>Post *</label>
												<textarea maxlength="5000" rows="10" class="form-control" name="comment" id="comment"></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
							<br>
							<div class="row">
								
								<div class="col-md-12">
									<input type="submit" value="Publicar nou fil" class="btn btn-primary btn-lg" data-loading-text="Loading...">
								</div>
							</div>
							<input id="postId" type="hidden" name="postId" value=""></input>
							<input name="categoryId" type="hidden" value="<?php echo $categoryId; ?>"></input>
						</form>
					</div> 
				</section>				
		</section>



		<!-- Vendor -->
		<script src="/assets/vendor/jquery/jquery.js"></script>
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
		<script src="/vendor/jquery/jquery.min.js"></script>
		<script src="/vendor/jquery.appear/jquery.appear.min.js"></script>
		<script src="/vendor/jquery.easing/jquery.easing.min.js"></script>
		<script src="/vendor/jquery-cookie/jquery-cookie.min.js"></script>
		<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="/vendor/common/common.min.js"></script>
		<script src="/vendor/jquery.validation/jquery.validation.min.js"></script>
		<script src="/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
		<script src="/vendor/jquery.gmap/jquery.gmap.min.js"></script>
		<script src="/vendor/jquery.lazyload/jquery.lazyload.min.js"></script>
		<script src="/vendor/isotope/jquery.isotope.min.js"></script>
		<script src="/vendor/owl.carousel/owl.carousel.min.js"></script>
		<script src="/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
		<script src="/vendor/vide/vide.min.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="/assets/javascripts/theme.js"></script>
		<!-- Theme Custom -->
		<script src="/assets/javascripts/theme.custom.js"></script>
		<!-- Theme Initialization Files -->
		<script src="/assets/javascripts/theme.init.js"></script>
		<!-- Theme Base, Components and Settings -->
		<script src="/js/theme.js"></script>
		<!-- Theme Custom -->
		<script src="/js/custom.js"></script>
		<!-- Theme Initialization Files -->
		<script src="/js/theme.init.js"></script>

		<!-- Examples -->
		<script src="/assets/javascripts/dashboard/examples.dashboard.js"></script>

		<!-- CUSTOM JS-->
		<script src="/js/forum/forum.js"></script>
	</body>

</html>