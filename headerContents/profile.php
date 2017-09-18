<a href="#" data-toggle="dropdown">
<figure class="profile-picture">
	<img src="/assets/images/!logged-user.jpg" alt="Joseph Doe" class="img-circle" data-lock-picture="/assets/images/!logged-user.jpg" />
</figure>
<div class="profile-info" data-lock-name="<?php echo ucfirst($user -> getLogin());?>" data-lock-email="<?php echo ucfirst($user -> getDescripcio());?>">
	<span class="name"><?php echo ucfirst($user -> getLogin());?></span>
	<span class="role"><?php echo ucfirst($user -> getDescripcio());?></span>
</div>

<i class="fa custom-caret"></i>
</a>

<div class="dropdown-menu">
<ul class="list-unstyled">
	<li class="divider"></li>
	<li>
		<a role="menuitem" tabindex="-1" href="pages-user-profile.html"><i class="fa fa-user"></i> My Profile</a>
	</li>
	<li>
		<a role="menuitem" tabindex="-1" href="#" data-lock-screen="true"><i class="fa fa-lock"></i> Lock Screen</a>
	</li>
	<li>
		<a role="menuitem" tabindex="-1" href="/controllers/logoutController.php"><i class="fa fa-power-off"></i> Logout</a>
	</li>
</ul>
</div>