<?php

require_once('config.php');

$user = unserialize($_SESSION["USER"]);

?>

<!-- start: header -->
<header class="header">
	<div class="logo-container">
		<a href="../" class="logo">
			<img src="/img/logoMetro.svg" height="35" alt="Metropolitana" />
		</a>
		<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>

	<!-- start: search & user box -->
	<div class="header-right">

		<form action="pages-search-results.html" class="search nav-form">
			<div class="input-group input-search">
				<input type="text" class="form-control" name="q" id="q" placeholder="Search...">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
				</span>
			</div>
		</form>

		<span class="separator"></span>

		<ul class="notifications">
			<?php 
				include("headerContents/tasksMessagesAlerts.php");
			?>
		</ul>

		<span class="separator"></span>

		<div id="userbox" class="userbox">
			<?php
				include("headerContents/profile.php");
			?>
		</div>
	</div>
	<!-- end: search & user box -->
</header>
<!-- end: header -->