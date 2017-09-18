<?php
session_start();

include_once('../../config.php');

// Controlem que hi hagi sessio i el bloqueig de la pantalla
if (!isset($_SESSION["GUEST_DN"]) || isset($_COOKIE['pageLock']) AND $_COOKIE['pageLock'] == 1)
{
  header("location: /controllers/logoutController.php");
  die();
}

$modulActiu = 'cintesIHdd'; // Variable que pasem a l'script aside.php (linia 83 aprox)

$arrayTipusEstat   				= TipusEstat::loadTipusEsats();
$arrayTipusSistema 				= TipusSistema::loadTipusSistemes();
$arrayHdds 		   				= Hdd::loadHdds();
$arrayOperadors 				= Operador::loadOperadors();
$arrayProjectesNoClassificats 	= Projecte::loadProjectesNoClassificats();
$arrayProjectesClassificats 	= Projecte::loadProjectesClassificats();
$arrayClients 					= Client::loadClients();

$guestDn = $_SESSION['GUEST_DN'];

?>

<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Metropolitana Intrantet | Discs durs de backup</title>
		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.css" />

		<!-- Elusive icons (Taula Hdds) -->
		<link rel="stylesheet" href="/assets/vendor/elusive-icons/css/elusive-icons.css">

		<link rel="stylesheet" href="/assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="/assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="/assets/vendor/pnotify/pnotify.custom.css" />

		<link rel="stylesheet" href="/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css">
		<link rel="stylesheet" href="/assets/vendor/select2/css/select2.css">

		<!-- Specific Page Vendor Tables -->
		<link rel="stylesheet" href="/assets/vendor/select2/css/select2.css">
		<link rel="stylesheet" href="/assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css">
		<link rel="stylesheet" href="/assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css">
		<link rel="stylesheet" href="/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css">

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
			include("../../header.php");
			?>
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<?php 
				include_once("../../aside.php");
				?>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Hdd's Backup</h2>
					</header>

					<!-- start: page -->
					<!-- FORMULARI INGESTA HDD I TAULA DE INFORMACIÓ HDD -->
					<div class="row">
						<!-- NOU HDD -->
						<div class="col-lg-6">
							<section class="panel form-wizard panel-collapsed" id="wizardNouHddBackup">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
									</div>
					
									<h2 class="panel-title">Registrar nou disc d'ur</h2>
								</header>
								<div class="panel-body panel-body-nopadding">
									<div class="wizard-tabs">
										<ul class="wizard-steps">
											<li>
												<a href="#wizardNouHddBackup-confirm" data-toggle="tab" class="text-center">
													<strong>Insertar Disc Dur</strong>
												</a>
											</li>
										</ul>
									</div>
									<form class="form-horizontal" novalidate="novalidate">
										<div class="tab-content">
											<div id="wizardNouHddBackup-confirm"" class="tab-pane">
												<div class="form-group">
													<label class="col-sm-4 control-label" for="newHddMetropolitanaId">metropolitana Id</label>
													<div class="col-sm-8">
														<input type="text" class="form-control input-sm" name="newHddMetropolitanaId" id="newHddMetropolitanaId" required>
														</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="newHddNom">Nom</label>
													<div class="col-sm-8">
														<input type="text" class="form-control input-sm" name="newHddNom" id="newHddNom" required>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="newHddEstat">Estat</label>
													<div class="col-sm-8">
														<select id="newHddEstat" class="form-control" name="exp-month" required>
															<option value="0">-Selecciona tipus d'estat-</option>
															<?php
															foreach($arrayTipusEstat as $index => $tipusEstat)
															{
																?>
																<option value="<?php echo $tipusEstat -> getId(); ?>"><?php echo $tipusEstat -> getNom().' - '.$tipusEstat -> getDescripcio(); ?></option>
																<?php
															}
															?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</form>
								</div>
								<div class="panel-footer">
									<ul class="pager">
										<li class="finish hidden pull-right">
											<a onclick="return insertNewHddBackup();">Insertar</a>
										</li>
									</ul>
								</div>
							</section>
						</div>

						<!-- TAULA INFORMACIÓ HDD -->
						<div class="col-lg-6">
							<section class="panel panel-collapsed">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
									</div>
							
									<h2 class="panel-title">Taula Informació d'HDDs</h2>
								</header>
								<div class="panel-body" style="display: none;">
									<div id="taulaHdd" class="dataTables_wrapper no-footer">
										<div class="table-responsive">
											<table class="table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">
												<thead>
													<tr role="row">
														<th class="sorting_asc" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 20%;">Metropolitana Id
														</th>
														<th class="sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 45%;">Nom
														</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 25%;">Estat
														</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 10%;">Accions</th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach($arrayHdds as $index => $infoHdd)
													{
														// Busquem l'estat del hdd
														$estat = new TipusEstat($infoHdd -> getEstat());
														
														$rutaDelete = '/controllers/hddBackupController/deleteHddBackupController.php?hddId='.$infoHdd -> getId();
														?>
														<tr role="row" class="odd">
															<td class="sorting_1" id="hddId"><?php echo $infoHdd -> getMetropolitanaId(); ?></td>
															<td>
																<strong>Nom: </strong><?php echo $infoHdd -> getNom(); ?>
															</td>
															<td class="center hidden-xs"><?php echo $estat -> getNom(); ?></td>
															
															<!-- Accions -->
															<td class="center hidden-xs">
																<!-- ELIMINAR -->
																<a href="<?php echo $rutaDelete; ?>" onclick="return confirm('Estàs segur/a de que vols esborrar el disc dur?');"><i class="el el-remove" title="Eliminar HDD"></i></a>

																<!-- EDITAR -->
																<a onclick='return loadHddBackupModal("<?php echo $infoHdd-> getId(); ?>")'><i class="el el-edit" title="Editar HDD"></i></a>
															</td>
														</tr>
														<?php
													}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</section>
						</div>
					</div>


					<hr class="tall">


					<!-- WIZARD INSERTAR NOU PROJECTE -->
					<div class="row">
						<div class="col-xs-12">
							<section class="panel form-wizard" id="insertProjectIntoHddBackup">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
									</div>
					
									<h2 class="panel-title">Insertar nou projecte a un disc dur</h2>
								</header>
								<div class="panel-body">
									<div class="wizard-progress wizard-progress-lg">
										<div class="steps-progress">
											<div class="progress-indicator"></div>
										</div>
										<ul class="wizard-steps">
											<li class="active">
												<a href="#insertProjectIntoHddBackup-account" data-toggle="tab"><span>1</span>Info. bàsica</a>
											</li>
											<li>
												<a href="#insertProjectIntoHddBackup-profile" data-toggle="tab"><span>2</span>Info. guardat</a>
											</li>
											<li>
												<a href="#insertProjectIntoHddBackup-confirm" data-toggle="tab"><span>3</span>Info. adicional</a>
											</li>
										</ul>
									</div>
					
									<form class="form-horizontal" method="POST" action="/controllers/hddClientsControllers/insertHddController.php" novalidate="novalidate">
										<div class="tab-content">
											<!-- PESTANYA 1 -->
											<div id="insertProjectIntoHddBackup-account" class="tab-pane active">
												<!-- PROJECTE -->
												<div class="form-group">
													<label class="col-sm-3 control-label" for="nomProjecte">Projecte</label>
													<div class="col-sm-6">
														<input type="text" class="form-control input-sm" id="nomProjecte" required>
													</div>
												</div>

												<!-- OPERADOR -->
												<div class="form-group">
													<label class="col-sm-3 control-label" >Operador</label>
													<div class="col-sm-6">
														<select class="form-control" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;enableCaseInsensitiveFiltering&quot;: true }" id="selectOperador" style="display: none;">
														<?php
														foreach($arrayOperadors as $index => $infoOperador)
														{
															?>
															<option value="<?php echo $infoOperador -> getId(); ?>"><?php echo $infoOperador -> getNom(); ?></option>
															<?php
														}
														?>
													</select>
													</div>
												</div>

												<!-- SISTEMA -->
												<div class="form-group">
													<label class="col-sm-3 control-label" for="nomProjecte">Sistema</label>
													<div class="col-sm-6">
														<select class="form-control" multiple="multiple" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;enableCaseInsensitiveFiltering&quot;: true }" id="selectSistema" style="display: none;">
														<?php
														foreach($arrayTipusSistema as $index => $infoSistema)
														{
															?>
															<option value="<?php echo $infoSistema -> getId(); ?>"><?php echo $infoSistema -> getNom(); ?></option>
															<?php
														}
														?>
														</select>
													</div>
												</div>
											</div>

											<!-- PESTANYA 2 -->
											<div id="insertProjectIntoHddBackup-profile" class="tab-pane">
												<!-- OPERADOR SUPORT -->
												<div class="form-group">
													<label class="col-sm-3 control-label" for="projecte">Operador/a suport</label>
													<div class="col-sm-6">
														<select class="form-control" multiple="multiple" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;enableCaseInsensitiveFiltering&quot;: true }" id="selectSuport" style="display: none;" required="">
														<?php
														foreach($arrayOperadors as $index => $infoOperador)
														{
															?>
															<option value="<?php echo $infoOperador -> getId(); ?>"><?php echo $infoOperador -> getNom(); ?></option>
															<?php
														}
														?>

														</select>
													</div>
												</div>

												<!-- CLIENT -->
												<div class="form-group">
													<label class="col-sm-3 control-label" for="clientProjecte">Client</label>
													<div class="col-sm-6">
														<select class="form-control" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;enableCaseInsensitiveFiltering&quot;: true }" id="clientProjecte" style="display: none;" required="">
														<?php
														foreach($arrayClients as $index => $infoClient)
														{
															?>
															<option value="<?php echo $infoClient -> getId(); ?>"><?php echo $infoClient -> getNom(); ?></option>
															<?php
														}
														?>

														</select>
													</div>
												</div>

												<!-- COMENTARIS -->
												<div class="form-group">
													<label class="col-sm-3 control-label" for="comentarisProjecte">Comentaris</label>
													<div class="col-sm-6">
														<textarea class="form-control input-sm" rows="3" id="comentarisProjecte" name="last-name" style="margin-top: 0px; margin-bottom: 0px; height: 111px;"></textarea>
													</div>
												</div>
											</div>

											<!-- PESTANYA 3 -->
											<div id="insertProjectIntoHddBackup-confirm" class="tab-pane">
												<!-- DISC DUR-->
												<div class="form-group">
													<label class="col-sm-3 control-label" for="hddIdProjecte">Disc dur</label>
													<div class="col-sm-6">
														<select class="form-control" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;enableCaseInsensitiveFiltering&quot;: true }" id="hddIdProjecte" style="display: none;" required="">
														<?php
														foreach($arrayHdds as $index => $infoHdd)
														{
															?>
															<option value="<?php echo $infoHdd -> getId(); ?>"><?php echo $infoHdd -> getNom(); ?></option>
															<?php
														}
														?>

														</select>
													</div>
												</div>

												<!-- RUTA A MASTER -->
												<div class="form-group">
													<label class="col-sm-3 control-label" for="rutaMaster">Ruta al màster</label>
													<div class="col-sm-6">
														<input type="text" class="form-control input-sm" id="rutaMaster" required>
													</div>
												</div>
											</div>
										</div>
									</form>
								</div>
								<div class="panel-footer">
									<ul class="pager">
										<li class="previous disabled">
											<a><i class="fa fa-angle-left"></i> Anterior</a>
										</li>
										<li class="finish hidden pull-right">
											<a onclick="return insertProjectIntoHddBackup();">Insertar</a>
										</li>
										<li class="next">
											<a>Següent <i class="fa fa-angle-right"></i></a>
										</li>
									</ul>
								</div>
							</section>
						</div>
					</div>

					<!-- TAULA PROJECTES NO CLASSIFICAS-->
					<div class="row">
						<div class="col-md-6 col-lg-12 col-xl-6">
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
									</div>
							
									<h2 class="panel-title">Taula Projectes sense HDD</h2>
								</header>
								<div class="panel-body" style="display: block;">
									<div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
										<div class="table-responsive">
											<table class="table table-bordered table-striped mb-none dataTable no-footer" id="datatableClientsUsuaris" role="grid" aria-describedby="datatable-default_info">
												<thead>
													<tr role="row">
														<th class="sorting_asc" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 10%;">Projecte Id
														</th>
														<th class="sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 20%;">Títol
														</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 20%;">Sistema
														</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 20%;">Operador</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 20%;">Client</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 10%;">Accions</th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach($arrayProjectesNoClassificats as $index => $infoProjecte)
													{
														// Busquem el nom del client per no mostrar el id
														$clientProjecte  	= new Client($infoProjecte -> getClientProjecte());
														$sistemesProject 	= $infoProjecte -> getStringSistema();
														$operadorsProject 	= $infoProjecte -> getStringOperador();
														
														$rutaDelete = '/controllers/hddBackupController/deleteProjectController.php?projectId='.$infoProjecte -> getId();
														?>
														<tr role="row" class="odd">
															<!-- ID -->
															<td class="sorting_1" id="projecteId"><?php echo $infoProjecte -> getId(); ?></td>
															<!-- TÍTOL-->
															<td id="titolProjecte">
																<strong><?php echo $infoProjecte -> getProjecte(); ?></strong>
															</td>
															<!-- SISTEMA -->
															<td class="center hidden-xs"><?php echo $sistemesProject; ?></td>
															<!-- OPERADORS -->
															<td class="center hidden-xs"><?php echo $operadorsProject; ?></td>
															<!-- CLIENT -->
															<td class="center hidden-xs"><?php echo $clientProjecte -> getNom(); ?></td>
															<!-- Accions -->
															<td class="center hidden-xs">
																<!-- ELIMINAR -->
																<a href="<?php echo $rutaDelete; ?>" onclick="return confirm('Estàs segur/a de que vols esborrar el projecte?');"><i class="el el-remove" title="Eliminar projecte"></i></a>

																<!-- EDITAR -->
																<a onclick='return loadProjectHddBackupModal("<?php echo $infoProjecte-> getId(); ?>")'><i class="el el-edit" title="Editar projecte"></i></a>

																<!-- RUTA A MASTER -->
																<a onclick=''><i class="el el-folder-open" title="ruta a Master"></i></a>

						
															</td>
														</tr>
														<?php
													}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</section>
						</div>
					</div>

					<!-- TAULA PROJECTES CLASSIFICAS-->
					<div class="row">
						<div class="col-md-6 col-lg-12 col-xl-6">
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
									</div>
							
									<h2 class="panel-title">Taula Projectes classificats a Hdd's</h2>
								</header>
								<div class="panel-body" style="display: block;">
									<div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
										<div class="table-responsive">
											<table class="table table-bordered table-striped mb-none dataTable no-footer" id="datatableClientsUsuaris" role="grid" aria-describedby="datatable-default_info">
												<thead>
													<tr role="row">
														<th class="sorting_asc" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 10%;">Projecte Id
														</th>
														<th class="sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 20%;">Títol
														</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 20%;">Sistema
														</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 20%;">Operador</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 20%;">Client</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 10%;">Accions</th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach($arrayProjectesClassificats as $index => $infoProjecte)
													{
														// Busquem el nom del client per no mostrar el id
														$clientProjecte  	= new Client($infoProjecte -> getClientProjecte());
														$sistemesProject 	= $infoProjecte -> getStringSistema();
														$operadorsProject 	= $infoProjecte -> getStringOperador();
														
														$rutaDelete = '/controllers/hddBackupController/deleteProjectController.php?projectId='.$infoProjecte -> getId();
														?>
														<tr role="row" class="odd">
															<!-- ID -->
															<td class="sorting_1" id="projecteId"><?php echo $infoProjecte -> getId(); ?></td>
															<!-- TÍTOL-->
															<td id="titolProjecte">
																<strong><?php echo $infoProjecte -> getProjecte(); ?></strong>
															</td>
															<!-- SISTEMA -->
															<td class="center hidden-xs"><?php echo $sistemesProject; ?></td>
															<!-- OPERADORS -->
															<td class="center hidden-xs"><?php echo $operadorsProject; ?></td>
															<!-- CLIENT -->
															<td class="center hidden-xs"><?php echo $clientProjecte -> getNom(); ?></td>
															<!-- Accions -->
															<td class="center hidden-xs">
																<!-- ELIMINAR -->
																<a href="<?php echo $rutaDelete; ?>" onclick="return confirm('Estàs segur/a de que vols esborrar el projecte?');"><i class="el el-remove" title="Eliminar projecte"></i></a>

																<!-- EDITAR -->
																<a onclick='return loadProjectHddBackupModal("<?php echo $infoProjecte-> getId(); ?>")'><i class="el el-edit" title="Editar projecte"></i></a>

																<!-- RUTA A MASTER -->
																<a onclick=''><i class="el el-folder-open" title="ruta a Master"></i></a>

						
															</td>
														</tr>
														<?php
													}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</section>
						</div>
					</div>
					<!-- end: page -->
				</section>
			</div>
		</section>

		<!-- MODAL PER EDITAR HDD DE BACKUP -->
		<div class="modal fade" id="modalHddBackup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<form class="form-horizontal" method="POST" action="/controllers/hddBackupController/updateHddBackupController.php">
			    	<div class="modal-header">
			        	<h2 class="panel-title" style="text-align-last: center;">Dades disc dur de backup</h2>
			      	</div>
			      	<div class="modal-body" id="body-uploads">
			      		<div class="row">
				      		<!-- Metropolitana Id -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalHddBackupMetropolitanaId"><strong>Metropolitana Id</strong></label>
								<div class="col-sm-6">
									<input name="modalHddBackupMetropolitanaId" type="text" class="form-control input-sm" id="modalHddBackupMetropolitanaId" readonly="">
								</div>
							</div>

							<!-- Nom -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalNomHddBackup"><strong>Nom</strong></label>
								<div class="col-sm-6">
									<input name="modalNomHddBackup" type="text" class="form-control input-sm" id="modalNomHddBackup" required>
								</div>
							</div>

							<!-- Estat -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" ><strong>Estat</strong></label>
								<div class="col-sm-6">
									<select class="form-control" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;enableCaseInsensitiveFiltering&quot;: true }" style="display: none;" id="modalEstatHddBackup" name="modalEstatHddBackup">
									<option value="0">-Selecciona estat-</option>
									<?php
									foreach($arrayTipusEstat as $index => $estat)
									{
										?>
										<option value="<?php echo $estat -> getId(); ?>"><?php echo $estat -> getNom(); ?></option>
										<?php
									}
									?>

									</select>
								</div>
							</div>

							<!-- Id (hidden) -->
							<div class="form-group">
								<div class="col-sm-6">
									<input name="modalHddBackupId" type="hidden" class="form-control input-sm" id="modalHddBackupId">
								</div>
							</div>
			      		</div>
			      	</div>
				    <div class="modal-footer">
			      		<button type="submit" class="btn btn-default"><strong>Guardar canvis</strong></button>
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Tancar</button>
			      	</div>
			    </form>

		    </div>
		  </div>
		</div>


		<!-- MODAL PER EDITAR PROJECTE -->
		<div class="modal fade" id="loadProjectHddBackupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<form class="form-horizontal" method="POST" action="/controllers/hddBackupController/updateProjectHddBackupController.php">
			    	<div class="modal-header">
			        	<h2 class="panel-title" style="text-align-last: center;">Dades del projecte</h2>
			      	</div>
			      	<div class="modal-body" id="body-uploads">
			      		<div class="row">
				      		<!-- Id -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalProjecteId"><strong>Projecte Id</strong></label>
								<div class="col-sm-6">
									<input name="modalProjecteId" type="text" class="form-control input-sm" id="modalProjecteId" readonly="">
								</div>
							</div>

							<!-- Hdd Id -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalProjecteHddId"><strong>Hdd Backup Id</strong></label>
								<div class="col-sm-6">
									<input name="modalProjecteHddId" type="text" class="form-control input-sm" id="modalProjecteHddId" readonly="">
								</div>
							</div>

							<!-- Operadors -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalProjectOperador"><strong>Operadors</strong></label>
								<div class="col-sm-6">
									<select class="form-control" multiple="multiple" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;enableCaseInsensitiveFiltering&quot;: true }" id="modalProjectOperador" name="modalProjectOperador" style="display: none;">
										<?php
										foreach($arrayOperadors as $index => $infoOperador)
										{
											?>
											<option value="<?php echo $infoOperador -> getId(); ?>"><?php echo $infoOperador -> getNom(); ?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>

							<!-- Sistema -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalProjectSistema"><strong>Sistema</strong></label>
								<div class="col-sm-6">
									<select class="form-control" multiple="multiple" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;enableCaseInsensitiveFiltering&quot;: true }" id="modalProjectSistema" name="modalProjectSistema" style="display: none;">
										<?php
										foreach($arrayTipusSistema as $index => $infoSistema)
										{
											?>
											<option value="<?php echo $infoSistema -> getId(); ?>"><?php echo $infoSistema -> getNom(); ?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>

							<!-- Nom -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalProjectNomProjecte"><strong>Nom projecte</strong></label>
								<div class="col-sm-6">
									<input name="modalProjectNomProjecte" type="text" class="form-control input-sm" id="modalProjectNomProjecte">
								</div>
							</div>

							<!-- Suport -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalProjectOperador"><strong>Operadors suport</strong></label>
								<div class="col-sm-6">
									<select class="form-control" multiple="multiple" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;enableCaseInsensitiveFiltering&quot;: true }" id="modalProjectOperador" name="modalProjectOperador" style="display: none;">
										<?php
										foreach($arrayOperadors as $index => $infoOperador)
										{
											?>
											<option value="<?php echo $infoOperador -> getId(); ?>"><?php echo $infoOperador -> getNom(); ?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>

							<!-- data -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalProjectDataProject"><strong>Data</strong></label>
								<div class="col-sm-6">
									<input name="modalProjectDataProject" type="text" class="form-control input-sm" id="modalProjectDataProject" required>
								</div>
							</div>

							<!-- Comentaris -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalProjectComentaris"><strong>Comentaris</strong></label>
								<div class="col-sm-6">
									<textarea name="modalProjectComentaris" class="form-control input-sm" rows="3" id="modalProjectComentaris" style="margin-top: 0px; margin-bottom: 0px; height: 111px;" required=""></textarea>
								</div>
							</div>

							<!-- Client -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalProjectClientProjecte"><strong>Client</strong></label>
								<div class="col-sm-6">
									<select class="form-control" data-plugin-multiselect="" data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;enableCaseInsensitiveFiltering&quot;: true }" id="modalProjectClientProjecte" name="modalProjectClientProjecte" style="display: none;">
										<?php
										foreach($arrayClients as $index => $infoClient)
										{
											?>
											<option value="<?php echo $infoClient -> getId(); ?>"><?php echo $infoClient -> getNom(); ?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>

							<!-- Ruta a master -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalProjectRutaAMaster"><strong>Ruta a màster</strong></label>
								<div class="col-sm-6">
									<textarea name="modalProjectRutaAMaster" class="form-control input-sm" rows="3" id="modalProjectRutaAMaster" style="margin-top: 0px; margin-bottom: 0px; height: 111px;" required=""></textarea>
								</div>
							</div>
			      		</div>
			      	</div>
				    <div class="modal-footer">
				      		<button type="submit" class="btn btn-default"><strong>Guardar canvis</strong></button>
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Tancar</button>
			      	</div>
			    </form>
		    </div>
		  </div>
		</div>

		<!-- MODAL PER EDITAR CLIENT -->
		<div class="modal fade" id="modalUpdateClient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<form class="form-horizontal" method="POST" action="/controllers/hddClientsController/updateClientController.php">
			    	<div class="modal-header">
			        	<h2 class="panel-title" style="text-align-last: center;">Dades del client</h2>
			      	</div>
			      	<div class="modal-body" id="body-uploads">
			      		<div class="row">
				      		<!-- Id -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalClientId"><strong>Client Id</strong></label>
								<div class="col-sm-6">
									<input name="modalClientId" type="text" class="form-control input-sm" id="modalClientId" readonly="">
								</div>
							</div>
							<!-- Nom -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalNomClient"><strong>Nom</strong></label>
								<div class="col-sm-6">
									<input name="modalNomClient" type="text" class="form-control input-sm" id="modalNomClient" required="">
								</div>
							</div>
							<!-- Adreça -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalAdrecaClient"><strong>Adreça</strong></label>
								<div class="col-sm-6">
									<input name="modalAdrecaClient" type="text" class="form-control input-sm" id="modalAdrecaClient" required="">
								</div>
							</div>
				
							
			      		</div>
			      	</div>
				    <div class="modal-footer">
				      	<button type="submit" class="btn btn-default"><strong>Guardar canvis</strong></button>
				        <button type="button" class="btn btn-default" data-dismiss="modal">Tancar</button>
			      	</div>
			    </form>
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
		<script src="/assets/vendor/jquery-validation/jquery.validate.js"></script>
		<script src="/assets/vendor/bootstrap-wizard/jquery.bootstrap.wizard.js"></script>
		<script src="/assets/vendor/pnotify/pnotify.custom.js"></script>

		<script src="/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
		<script src="/assets/vendor/select2/js/select2.js"></script>

		<!-- Specific Page Vendor Table -->
 		<script src="/assets/vendor/select2/js/select2.js"></script> 
		<script src="/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
		<script src="/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="/assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="/assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="/assets/javascripts/theme.init.js"></script>

		<!-- Examples -->
		<script src="/assets/javascripts/forms/examples.wizard.js"></script>

		<script src="/assets/javascripts/tables/examples.datatables.default.js"></script>
		<script src="/assets/javascripts/tables/examples.datatables.row.with.details.js"></script>
		<script src="/assets/javascripts/tables/examples.datatables.tabletools.js"></script>
		<script src="/js/hdds/hdds.js"></script>

	</body>
</html>