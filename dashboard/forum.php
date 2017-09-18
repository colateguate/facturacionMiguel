<?php
$arrayCategories = ForumCategory::loadCategories();
?>
<section class="panel panel-transparent">
	<header class="panel-heading">
		<div class="panel-actions">
			<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
			<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
		</div>

		<h2 class="panel-title">FÒRUM</h2>
	</header>
	<div class="panel-body">
		<section class="panel panel-group">
			<header class="panel-heading bg-primary">
				<div class="widget-profile-info">
					<div class="profile-info">
						FÒRUM
					</div>
				</div>
			</header>

			<div id="accordion">
				<div class="panel panel-accordion panel-accordion-first">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse1One">
								<i class=""></i> Categories
							</a>
						</h4>
					</div>
					<div id="collapse1One" class="accordion-body collapse in">
						<div class="panel-body">
							<ul class="widget-todo-list">
								<?php

									foreach($arrayCategories as $index => $infoCategoria)
									{
										$rutaThreat = "../forum/forumIndex.php?catId=".$infoCategoria -> getCategoryId()."";
										?>
											<li>
												<div class="">
													<a class="" href="<?php echo $rutaThreat; ?>">
														<i class=""><?php echo $infoCategoria -> getTitle();?></i>
													</a>
												</div>
											</li>
										<?php
									}
								?>
								
							</ul>
							<hr class="solid mt-sm mb-lg">
							<form method="get" class="form-horizontal form-bordered">
								<div class="form-group">
									<div class="col-sm-12">
										<div class="input-group mb-md">
											<input type="text" class="form-control" name="newCategory" id="newCategory">
											<div class="input-group-btn">
												<button type="button" class="btn btn-primary" tabindex="-1" onclick="return addCategory();">Afegir</button>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</section>