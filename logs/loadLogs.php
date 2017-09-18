<?php
session_start();

include_once('../config.php');

if (!isset($_SESSION["GUEST_DN"]) || isset($_COOKIE['pageLock']) AND $_COOKIE['pageLock'] == 1)
{
  header("location: /controllers/logoutController.php");
  die();
}

$modulActiu = 'logs';

$errorLogs  = Log::getErrorLogs();
$accesLogs  = Log::getAccessLogs();
$customLogs = Log::getAccionsLogs();

?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Metropolitana Intranet - Logs</title>
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

		<!-- Theme CSS -->
		<link rel="stylesheet" href="/assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="/assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="/assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="/assets/vendor/modernizr/modernizr.js"></script>

	</head>
	<body>
		<section class="body">

			<!-- start: header -->
			<?php
				include_once('../header.php');
			?>
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<?php
					include_once('../aside.php');
				?>
				<!-- end: sidebar -->

				<section role="main" class="content-body has-toolbar">
					<header class="page-header">
						<h2>Logs</h2>
					</header>

					<!-- start: page -->
					<div class="inner-toolbar clearfix">
						<ul>
							<li class="right">
								<ul class="nav nav-pills nav-pills-primary">
									<li>
										<label>Type</label>
									</li>
									<li class="active">
										<a href="#access-log" data-toggle="tab">Access Log</a>
									</li>
									<li>
										<a href="#error-log" data-toggle="tab">Error Log</a>
									</li>
									<li>
										<a href="#custom-log" data-toggle="tab">Accions Log</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>

					<section class="panel">
						<div class="panel-body tab-content">
							<!-- TAULA QUE CONTÉ ELS ACCES LOGS -->
							<div id="access-log" class="tab-pane active">
								<table class="table table-striped table-no-more table-bordered  mb-none">
									<thead>
										<tr>
											<th style="width: 10%"><span class="text-weight-normal text-sm">Type</span></th>
											<th style="width: 15%"><span class="text-weight-normal text-sm">User</span></th>
											<th style="width: 15%"><span class="text-weight-normal text-sm">Date</span></th>
											<th><span class="text-weight-normal text-sm">Message</span></th>
										</tr>
									</thead>
									<tbody class="log-viewer">
									<?php
									foreach($accesLogs as $index => $infoLog)
									{
										$infoError = $infoLog -> getInfo();
										?>
										<tr>
											<!-- TIPUS ERROR -->
											<td data-title="Type" class="pt-md pb-md">
												<i class="fa fa-info fa-fw text-info text-md va-middle"></i>
												<span><?php echo $infoError['status']; ?></span>
											</td>
											<!-- USUARI CAUSANT -->
											<td data-title="Type" class="pt-md pb-md">
												<span><?php echo $infoLog -> getUserId(); ?></span>
											</td>
											<!-- DATA -->
											<td data-title="Date" class="pt-md pb-md">
												<?php echo $infoLog -> getData(); ?>
											</td>
											<td data-title="Message" class="pt-md pb-md">
												<?php echo $infoError['info']; ?>
											</td>
										</tr>
										<?php
									}
									?>
									</tbody>
								</table>
							</div>
							<!-- TAULA QUE CONTÉ ELS ERROR LOGS -->
							<div id="error-log" class="tab-pane">
								<table class="table table-striped table-no-more table-bordered mb-none">
									<thead>
										<tr>
											<th style="width: 10%"><span class="text-weight-normal text-sm">Type</span></th>
											<th style="width: 15%"><span class="text-weight-normal text-sm">User</span></th>
											<th style="width: 15%"><span class="text-weight-normal text-sm">Date</span></th>
											<th><span class="text-weight-normal text-sm">Message</span></th>
										</tr>
									</thead>
									<tbody class="log-viewer">
									<?php
									foreach($errorLogs as $index => $infoLog)
									{
										$infoError = $infoLog -> getInfo();
										?>
										<tr>
											<!-- TIPUS ERROR -->
											<td data-title="Type" class="pt-md pb-md">
												<i class="fa fa-times-circle fa-fw text-danger text-md va-middle"></i>
												<span><?php echo $infoError['status']; ?></span>
											</td>
											<!-- USUARI CAUSANT -->
											<td data-title="Type" class="pt-md pb-md">
												<span><?php echo $infoLog -> getUserId(); ?></span>
											</td>
											<!-- DATA -->
											<td data-title="Date" class="pt-md pb-md">
												<?php echo $infoLog -> getData(); ?>
											</td>
											<td data-title="Message" class="pt-md pb-md">
												<?php echo $infoError['info']; ?>
											</td>
										</tr>
										<?php
									}
									?>
									</tbody>
								</table>
							</div>
							<!-- TAULA QUE CONTÉ ELS CUSTOM LOGS -->
							<div id="custom-log" class="tab-pane">
								<table class="table table-striped table-no-more table-bordered  mb-none">
									<thead>
										<tr>
											<th style="width: 13%"><span class="text-weight-normal text-sm">Type</span></th>
											<th style="width: 15%"><span class="text-weight-normal text-sm">User</span></th>
											<th style="width: 15%"><span class="text-weight-normal text-sm">Date</span></th>
											<th><span class="text-weight-normal text-sm">Message</span></th>
										</tr>
									</thead>
									<tbody class="log-viewer">
									<?php
									foreach($customLogs as $index => $infoLog)
									{
										$infoError = $infoLog -> getInfo();
										?>
										<tr>
											<!-- TIPUS ERROR -->
											<td data-title="Type" class="pt-md pb-md">
												<i class="fa fa-info fa-fw text-info text-md va-middle"></i>
												<span><?php echo $infoError['status']; ?></span>
											</td>
											<!-- USUARI CAUSANT -->
											<td data-title="Type" class="pt-md pb-md">
												<span><?php echo $infoLog -> getUserId(); ?></span>
											</td>
											<!-- DATA -->
											<td data-title="Date" class="pt-md pb-md">
												<?php echo $infoLog -> getData(); ?>
											</td>
											<td data-title="Message" class="pt-md pb-md">
												<?php echo $infoError['info']; ?>
											</td>
										</tr>
										<?php
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
					</section>
					<!-- end: page -->
				</section>
			</div>
		</section>

		<!-- Vendor -->
		<script src="/assets/vendor/jquery/jquery.js"></script>
		<script src="/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="/assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="/assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="/assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="/assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="/assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="/assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="/assets/javascripts/theme.init.js"></script>

	</body>
</html>