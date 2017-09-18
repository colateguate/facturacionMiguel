<aside id="sidebar-left" class="sidebar-left">

	<div class="sidebar-header">
		<div class="sidebar-title">
			Menú	
		</div>
		<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>

	<div class="nano">
		<div class="nano-content">
			<nav id="menu" class="nav-main" role="navigation">
				<ul class="nav nav-main">
					<li <?php if($modulActiu=='dashboard')echo 'class="nav-active"';?> >
						<a href="<?php echo URL.'/dashboard/dashboard.php'; ?>">
							<i class="fa fa-home" aria-hidden="true"></i>
							<span>Dashboard</span>
						</a>
					</li>
					<li <?php if($modulActiu=='perfil')echo 'class="nav-active nav-parent"';else echo 'class="nav-parent"';?> >
						<a>
							<i class="fa fa-columns" aria-hidden="true"></i>
							<span>Perfil</span>
						</a>
					</li>
					<li <?php if($modulActiu=='cintesIHdd')echo 'class="nav-active nav-parent"';else echo 'class="nav-parent"';?> >
						<a>
							<i class="fa fa-hdd-o" aria-hidden="true"></i>
							<span>Control cintes i discs durs</span>
						</a>
						<ul class="nav nav-children">
							<li>
								<a href="/manage/cintes/cintesClients.php">
									Cintes backup
								</a>
							</li>
							<li>
								<a href="/manage/cintes/cintesLTO.php">
									Cintes LTO (robot)
								</a>
							</li>
							<li>
								<a href="/manage/hdds/hddsBackups.php">
									Discs durs backup's
								</a>
							</li>
							<li>
								<a href="/manage/hdds/hddsClients.php">
									Discs durs clients
								</a>
							</li>
						</ul>
					</li>
					<li <?php if($modulActiu=='controlsQualitat')echo 'class="nav-active"';?>>
						<a href="<?php echo URL.'/controlsQualitat/controlsQualitat.php'; ?>">
							<i aria-hidden="true" class="fa fa-diamond"></i>
							<span>Controls de qualitat</span>
						</a>
					</li>
					<li <?php if($modulActiu=='logs')echo 'class="nav-active"';?>>
						<a href="<?php echo URL.'/logs/loadLogs.php'; ?>">
							<i class="fa fa-history" aria-hidden="true"></i>
							<span>Logs</span>
						</a>
					</li>
					<li <?php if($modulActiu=='administracio')echo 'class="nav-active"';?>>
						<a href="<?php echo URL.'/administracio/administracio.php'; ?>">
							<i aria-hidden="true" class="fa fa-institution"></i>
							<span>Administració</span>
						</a>
					</li>
				</ul>
			</nav>
		</div>

		<script>
			// Maintain Scroll Position
			if (typeof localStorage !== 'undefined') {
				if (localStorage.getItem('sidebar-left-position') !== null) {
					var initialPosition = localStorage.getItem('sidebar-left-position'),
						sidebarLeft = document.querySelector('#sidebar-left .nano-content');
					
					sidebarLeft.scrollTop = initialPosition;
				}
			}
		</script>
	</div>
</aside>
				