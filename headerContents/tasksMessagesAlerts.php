<?php

// ja tenim la variable $user del header.php. Ja està unserialized i és un objecte de tipus User

$missatgesUser = Message::loadUserMessages($user -> getId());
$countNoLlegits = Message::getUnreadedMessages($user -> getId());

?>
<!-- TASQUES -->
<li>
	<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
		<i class="fa fa-tasks"></i>
		<span class="badge">3</span>
	</a>

	<div class="dropdown-menu notification-menu large">
		<div class="notification-title">
			<span class="pull-right label label-default">3</span>
			Tasks
		</div>

		<div class="content">
			<ul>
				<li>
					<p class="clearfix mb-xs">
						<span class="message pull-left">Generating Sales Report</span>
						<span class="message pull-right text-dark">60%</span>
					</p>
					<div class="progress progress-xs light">
						<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
					</div>
				</li>

				<li>
					<p class="clearfix mb-xs">
						<span class="message pull-left">Importing Contacts</span>
						<span class="message pull-right text-dark">98%</span>
					</p>
					<div class="progress progress-xs light">
						<div class="progress-bar" role="progressbar" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100" style="width: 98%;"></div>
					</div>
				</li>

				<li>
					<p class="clearfix mb-xs">
						<span class="message pull-left">Uploading something big</span>
						<span class="message pull-right text-dark">33%</span>
					</p>
					<div class="progress progress-xs light mb-xs">
						<div class="progress-bar" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%;"></div>
					</div>
				</li>
			</ul>
		</div>
	</div>
</li>
<!-- MISSATGERIA INTERNA -->
<li>
	<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
		<i class="fa fa-envelope"></i>
		<span class="badge"><?php echo $countNoLlegits; ?></span>
	</a>

	<div class="dropdown-menu notification-menu">
		<div class="notification-title">
			Missatgeria interna
		</div>

		<div class="content">
			<ul>
				<?php
				foreach($missatgesUser as $index => $missatge)
				{
					?>
					<!-- INFO USER WRITTEN BY -->
					<li>
						<a href="#" class="clearfix">
							<figure class="image">
								<img src="/assets/images/!sample-user.jpg" alt="" class="img-circle" />
							</figure>
							<span class="title"><?php echo $missatge -> getWrittenByInfo(); ?></span>
							<span class="message"><?php echo $missatge -> getMessage(); ?></span>
						</a>
					</li>
					<?php
				}
				?>
			</ul>

			<hr />

			<div class="text-right">
				<a href="/missatgeriaInterna/missatgeriaInternaIndex.php" class="view-more">View All</a>
			</div>
		</div>
	</div>
</li>
<!-- ALERTS -->
<li>
	<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
		<i class="fa fa-bell"></i>
		<span class="badge">3</span>
	</a>

	<div class="dropdown-menu notification-menu">
		<div class="notification-title">
			<span class="pull-right label label-default">3</span>
			Alerts
		</div>

		<div class="content">
			<ul>
				<li>
					<a href="#" class="clearfix">
						<div class="image">
							<i class="fa fa-thumbs-down bg-danger"></i>
						</div>
						<span class="title">Server is Down!</span>
						<span class="message">Just now</span>
					</a>
				</li>
				<li>
					<a href="#" class="clearfix">
						<div class="image">
							<i class="fa fa-lock bg-warning"></i>
						</div>
						<span class="title">User Locked</span>
						<span class="message">15 minutes ago</span>
					</a>
				</li>
				<li>
					<a href="#" class="clearfix">
						<div class="image">
							<i class="fa fa-signal bg-success"></i>
						</div>
						<span class="title">Connection Restaured</span>
						<span class="message">10/10/2016</span>
					</a>
				</li>
			</ul>

			<hr />

			<div class="text-right">
				<a href="#" class="view-more">View All</a>
			</div>
		</div>
	</div>
</li>