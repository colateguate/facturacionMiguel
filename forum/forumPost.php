<?php
session_start();

include_once('../config.php');

if (!isset($_SESSION["GUEST_DN"]) || isset($_COOKIE['pageLock']) AND $_COOKIE['pageLock'] == 1)
{
  header("location: /controllers/logoutController.php");
  die();
}

$modulActiu = 'dashboard';

$threadId	  	= $_GET['threadId'];
$arrayPosts 	= ForumPost::loadPosts($threadId);
$infoThread 	= ForumThreat::getThreadStaticInfo($threadId);
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
						<h3 class="heading-primary">Thread  <?php echo $infoThread -> id; ?></h3>

						<h4>
						<i class="fa fa-comments"></i>
							<?php echo $infoThread -> threat_post_content; ?>
						</h4>
						<ul class="comments">
							<li>
								<?php
									// Càrrega dels Posts
									foreach($arrayPosts as $index => $infoPost)
									{
										// Carreguem els post del thread
										?>
										<!-- POST PARE'S --> 
										<div class="comment">
											<div class="comment-block">
												<div class="comment-arrow"></div>
												<span class="comment-by">
													<strong><?php echo User::getUserName($infoPost -> getUserId()); ?></strong>
													<span class="pull-right">
													
														<span> <a style="cursor:pointer;" onclick="return replyTo(<?php echo $infoPost -> getPostId(); ?>);"><i class="fa fa-reply"></i> Reply</a></span>
													</span>
												</span>
												<p>
													<?php echo $infoPost -> getPostContent(); ?>
												</p>
												<?php
													if((null !== $infoPost -> getImgUploadPath()) AND strlen($infoPost -> getImgUploadPath())>1)
													{
														?>
														<span>
															<a style="cursor:pointer;" onclick='return loadImageModal("<?php echo $infoPost-> getImgUploadPath(); ?>");'>Veure imatge adjunta</a>
														</span>
														<?php

													}
												?>
												<span class="date pull-right"><?php echo $infoPost -> getPostDate()?></span>
											</div>
										</div>

										<?php
										// Carrueguem, si hi ha, les rèpliques al post
										$postReplies = $infoPost -> getPostReplies($infoPost -> getPostId());
										?>
										<ul>
										<?php
										foreach($postReplies as $index => $infoReply)
										{
											?>
											<!-- RÈPLICA -->
											<li style="list-style: none;">
												<div class="comment">
													<div class="comment-block">
														<div class="comment-arrow"></div>
														<span class="comment-by">
															<strong><?php echo User::getUserName($infoReply -> getUserId()); ?></strong>
														</span>
														<p>
															<?php echo $infoReply -> getPostContent(); ?>
														</p>
														<?php
															if((null !== $infoReply -> getImgUploadPath()) AND strlen($infoReply -> getImgUploadPath())>1)
															{
																?>
																<span>
																	<a style="cursor:pointer;" onclick='return loadImageModal("<?php echo $infoReply-> getImgUploadPath(); ?>");'>Veure imatge adjunta</a>
																</span>
																<?php

															}
														?>
														<span class="date pull-right"><?php echo $infoReply -> getPostDate(); ?></span>
													</div>
												</div>
											</li>
											<?php
										}
										?>
										</ul>
										<?php
									}
								?>
							</li>
						</ul>
					</div>


					<!-- DIV CREA A UN POST NOU -->
					<div id="newPost" class="post-block post-leave-comment" style="">
						<h3 class="heading-primary">Nou comentari</h3>

						<form action="/controllers/forumControllers/addPostController.php" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<label>Comentari *</label>
										<textarea maxlength="5000" rows="10" class="form-control" name="commentNew" id="commentNew"></textarea>
									</div>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-2">
									<input type="submit" value="Nou Comment" class="btn btn-primary btn-lg" data-loading-text="Loading...">
								</div>
								<div class="col-md-2">
									<input type="file" class="btn btn-primary btn-lg" id="imgUpload" name="files[]"/> 
								</div>
								<div class="col-md-8"></div>
							</div>
							<input type="hidden" name="newPostRepliedToId" value="0"></input>
							<input id="threadId" type="hidden", name="threadId" value="<?php echo $infoThread -> id; ?>"></input>
						</form>
					</div>

					<!-- DIV RESPOSTA A UN POST -->
					<div id="replyTo" class="post-block post-leave-comment" style="display:none">
						<h3 class="heading-primary">Respon a un comentari</h3>

						<form action="/controllers/forumControllers/addPostController.php" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<label>Comentari *</label>
										<textarea maxlength="5000" rows="10" class="form-control" name="comment" id="comment"></textarea>
									</div>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-2">
									<input type="submit" value="Nova rèplica" class="btn btn-primary btn-lg" data-loading-text="Loading...">
								</div>
								<div class="col-md-2">
									<div class="col-md-2">
										<input type="file" class="btn btn-primary btn-lg" id="imgUploadReply" name="files[]"/> 
									</div>
								</div>
								<div class="col-md-8"></div>
							</div>
							<input id="postId" type="hidden" name="postId" value=""></input>
							<input id="threadId" type="hidden", name="threadId" value="<?php echo $infoThread -> id; ?>"></input>
						</form>
					</div> 
				</section>				
		</section>

		<!-- MODAL PER CARREGAR LA IMATGE ADJUNTA AL POST -->
		<div class="modal fade" id="loadImageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-body" id="body-uploads">
		      	<img src="" id="imageLoaded" style="width:100%;height: 100%;">
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Tancar</button>
		      </div>
		    </div>
		  </div>
		</div>


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
		<script src="js/theme.js"></script>
		<!-- Theme Custom -->
		<script src="js/custom.js"></script>
		<!-- Theme Initialization Files -->
		<script src="js/theme.init.js"></script>

		<!-- Examples -->
		<script src="/assets/javascripts/dashboard/examples.dashboard.js"></script>

		<!-- CUSTOM JS -->
		<script src="/js/forum/forum.js"></script>
		<script type="text/javascript">
			$( document ).ready(function() {
				// Pujar imatge nou comentari
			    $('#openImgUpload').click(function()
			    {
			    	$('#imgUpload').click();

			    	var file_data = $('#imgUpload').prop('files')[0];   
				    //var form_data = new FormData();                  
				    form_data.append('file', file_data);
				    alert(file_data);  
				    console.log(file_data);                         
				    $.ajax({
			                url: '/controllers/forumControllers/uploadImageController.php',
			                dataType: 'POST',
			                cache: false,
			                contentType: false,
			                processData: false,
			                data: new FormData(this),                         
			                type: 'post',
			                success: function(data){
			                    alert('upload image ok!');
			                }
				     });
			    });

			    // Pujar imatge rèplica de un comentari
			    $('#openImgUploadReply').click(function()
			    {
			    	$('#imgUploadReply').click();
			    });  
			});

			function loadImageModal(imagePath)
			{
			    $("#loadImageModal").modal({width:150,height:250});
			    $("#imageLoaded").attr('src',imagePath);
			};
		</script>

	</body>
</html>