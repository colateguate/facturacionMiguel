<?php
session_start();

include_once('../config.php');

if (!isset($_SESSION["GUEST_DN"]) || isset($_COOKIE['pageLock']) AND $_COOKIE['pageLock'] == 1)
{
  header("location: /controllers/logoutController.php");
  die();
}

$modulActiu = 'controlsQualitat';

$arrayProjectes = Projecte::loadProjectes();

?>

<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Metropolitana Intrantet | Controls de qualitat</title>
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

		<!-- Specific Page Vendor Tables -->
		<link rel="stylesheet" href="/assets/vendor/select2/css/select2.css">
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
			include("../header.php");
			?>
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<?php 
				include_once("../aside.php");
				?>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Controls de qualitat</h2>
					</header>

					<!-- start: page -->

					<!-- TAULA DE INFORMACIÓ DE PROJECTES -->
					<div class="row">
						<div class="col-md-6 col-lg-12 col-xl-6">
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
									</div>
							
									<h2 class="panel-title">Taula Informació de projectes</h2>
								</header>
								<div class="panel-body" style="display: block;">
									<div id="taulaHdd" class="dataTables_wrapper no-footer">
										<div class="table-responsive">
											<table class="table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">
												<thead>
													<tr role="row">
														<th class="sorting_asc" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 15%;">Projecte Id
														</th>
														<th class="sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 30%;">Projecte
														</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 30%;">Operador
														</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 15%;">Sistema</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 10%;">Accions</th>
													</tr>
												</thead>
												<tbody>
													<!-- <?php
													foreach($arrayProjectes as $index => $infoProjecte)
													{
														// Busquem el nom del client per no mostrar el id
														$clientProjecte  = new Client($infoProjecte -> getClient());
														$rutaDelete = '/controllers/hddClientsController/deleteUserController.php?hddId='.$infoProjecte -> getId();
														?> -->
														<tr role="row" class="odd">
															<td class="sorting_1" id="hddId"><?php echo $infoProjecte -> getId(); ?></td>
															<td>
																<strong>Nom: </strong><?php echo $clientProjecte -> getNom(); ?>
																<br>
																<strong>Adreça: </strong><?php echo $clientProjecte -> getAdreca(); ?>
															</td>
															<td class="center hidden-xs"><?php echo $infoProjecte -> getProjecteDepartament(); ?></td>
															<td class="center hidden-xs"><?php echo $infoProjecte -> getLocalitzacio(); ?></td>
															<!-- Accions -->
															<td class="center hidden-xs">
																<!-- ELIMINAR -->
																<a href="<?php echo $rutaDelete; ?>" onclick="return confirm('Estàs segur/a de que vols esborrar el disc dur?');"><i class="el el-remove"></i></a>

																<!-- EDITAR -->
																<a onclick='return loadHddModal("<?php echo $infoProjecte-> getId(); ?>")'><i class="el el-edit"></i></a>

						
															</td>
														</tr>
													<!-- 	<?php
													}
													?> -->
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</section>
						</div>
					</div>

					<hr class="tall">

					<!-- WIZARD INSERTAR USUARI i NOU CLIENT -->
					<!-- NOU USUARI -->
					<div class="row">
						<div class="col-lg-6">
							<section class="panel form-wizard panel-collapsed" id="registreUsuari">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
									</div>
					
									<h2 class="panel-title">Registrar nou Usuari</h2>
								</header>
								<div class="panel-body panel-body-nopadding">
									<div class="wizard-tabs">
										<ul class="wizard-steps">
											<li class="active">
												<a href="#registreUsuari-account" data-toggle="tab" class="text-center">
													<span class="badge hidden-xs">1</span>
													Informació personal
												</a>
											</li>
											<li>
												<a href="#registreUsuari-profile" data-toggle="tab" class="text-center">
													<span class="badge hidden-xs">2</span>
													Informació de contacte
												</a>
											</li>
											<li>
												<a href="#registreUsuari-confirm" data-toggle="tab" class="text-center">
													<span class="badge hidden-xs">3</span>
													Informació d'usuari
												</a>
											</li>
										</ul>
									</div>
									<form class="form-horizontal" novalidate="novalidate" method="POST" action="/controllers/hddClientsController/insertUsuariController.php">
										<div class="tab-content">
											<div id="registreUsuari-account" class="tab-pane active">
												<div class="form-group">
													<label class="col-sm-4 control-label" for="registreUsuari-username">Nom</label>
													<div class="col-sm-8">
														<input type="text" class="form-control input-sm" name="username" id="registreUsuariNom" required>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="registreUsuari-password">Primer Cognom</label>
													<div class="col-sm-8">
														<input type="text" class="form-control input-sm" name="password" id="registreUsuariCognom1" minlength="6" required>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="registreUsuari-password">Segon Cognom</label>
													<div class="col-sm-8">
														<input type="text" class="form-control input-sm" name="password" id="registreUsuaricognom2" minlength="6" required>
													</div>
												</div>
											</div>
											<div id="registreUsuari-profile" class="tab-pane">
												<div class="form-group">
													<label class="col-sm-4 control-label" for="registreUsuari-first-name">Email</label>
													<div class="col-sm-8">
														<input type="text" class="form-control input-sm" name="first-name" id="registreUsuariEmail" required>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="registreUsuari-last-name">Telèfon</label>
													<div class="col-sm-8">
														<input type="text" class="form-control input-sm" name="last-name" id="registreUsuariTelf" required>
													</div>
												</div>
											</div>
											<div id="registreUsuari-confirm" class="tab-pane">
												<div class="form-group">
													<label class="col-sm-4 control-label" for="registreUsuari-email">Client</label>
													<div class="col-sm-8">
														<select id="selectClientUserClient" class="form-control" name="exp-month" required onchange="carregaClients(this);" required>
														
														<option value="0">-Selecciona client-</option>
														<?php
														foreach($arrayClients as $index => $client)
														{
															?>
															<option value="<?php echo $client -> getId(); ?>"><?php echo $client -> getNom(); ?></option>
															<?php
														}
														?>

														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="registreUsuari-email">Tipus d'usuari</label>
													<div class="col-sm-8">
														<select id="selectTipusUsuari" class="form-control" name="exp-month" required onchange="carregaTipusUsuari(this);" required>
														
														<option value="0">-Selecciona tipus d'usuari-</option>
														<?php
														foreach($arrayTipusUsuari as $index => $tipusUsuari)
														{
															?>
															<option value="<?php echo $tipusUsuari -> getId(); ?>"><?php echo $tipusUsuari -> getDescripcio(); ?></option>
															<?php
														}
														?>

														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="registreUsuari-email">Idioma</label>
													<div class="col-sm-8">
														<select id="selectIdiomes" class="form-control" name="exp-month" required onchange="carregaIdiomes(this);">
														
														<option value="0">-Selecciona idioma-</option>
														<?php
														
														foreach($arrayIdiomes as $index => $idioma)
														{
															?>
															<option value="<?php echo $idioma -> getId(); ?>"><?php echo $idioma -> getIdioma(); ?></option>
															<?php
														}
														?>

														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="registreUsuari-email">Notificació</label>
													<div class="col-sm-8">
														<input type="checkbox" name="registreUsuariNotificacio" id="registreUsuariNotificacio">
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
											<a onclick="return insertUsuariClient();">Insertar</a>
										</li>
										<li class="next">
											<a>Següent <i class="fa fa-angle-right"></i></a>
										</li>
									</ul>
								</div>
							</section>
						</div>

						<!-- NOU CLIENT -->
						<div class="col-lg-6">
							<section class="panel form-wizard panel-collapsed" id="wizardClient">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
									</div>
					
									<h2 class="panel-title">Registrar nou client</h2>
								</header>
								<div class="panel-body panel-body-nopadding">
									<div class="wizard-tabs">
										<ul class="wizard-steps">
											<li>
												<a href="#wizardClient-confirm" data-toggle="tab" class="text-center">
													<strong>Insertar Client</strong>
												</a>
											</li>
										</ul>
									</div>
									<form class="form-horizontal" novalidate="novalidate">
										<div class="tab-content">
											<div id="wizardClient-confirm"" class="tab-pane">
												<div class="form-group">
													<label class="col-sm-4 control-label" for="newClientNom">Nom client</label>
													<div class="col-sm-8">
														<input type="text" class="form-control input-sm" name="newClientNom" id="newClientNom" required>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="newClientAdreca">Adreça</label>
													<div class="col-sm-8">
														<input type="text" class="form-control input-sm" name="newClientAdreca" id="newClientAdreca" required>
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
											<a onclick="return insertNewClient();">Insertar</a>
										</li>
										<li class="next">
											<a>Següent <i class="fa fa-angle-right"></i></a>
										</li>
									</ul>
								</div>
							</section>
						</div>
					</div>

					<!-- TAULA USUARIS CLIENTS -->
					<div class="row">
						<div class="col-md-6 col-lg-12 col-xl-6">
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
									</div>
							
									<h2 class="panel-title">Taula Informació d'usuaris clients</h2>
								</header>
								<div class="panel-body" style="display: block;">
									<div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
										<div class="table-responsive">
											<table class="table table-bordered table-striped mb-none dataTable no-footer" id="datatableClientsUsuaris" role="grid" aria-describedby="datatable-default_info">
												<thead>
													<tr role="row">
														<th class="sorting_asc" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 15%;">usari client Id
														</th>
														<th class="sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 30%;">Nom i cognoms
														</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 20%;">Info. contacte
														</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 25%;">Client</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 10%;">Accions</th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach($arrayUsuarisClients as $index => $infoUserClient)
													{
														// Busquem el nom del client per no mostrar el id
														$client = new Client($infoUserClient -> getClient());
														$rutaDelete = '/controllers/hddClientsController/deleteUserClientController.php?userClientId='.$infoUserClient -> getId();
														?>
														<tr role="row" class="odd">
															<td class="sorting_1" id="userClientId"><?php echo $infoUserClient -> getId(); ?></td>
															<td>
																<?php echo $infoUserClient -> getNom().' '.$infoUserClient -> getPrimerCognom().' '.$infoUserClient -> getSegonCognom(); ?>
															</td>
															<td class="center hidden-xs">
																<strong>Email: </strong>
																<?php
																	echo $infoUserClient -> getEmail();
																?>
																<br>
																<strong>Telèfon: </strong>
																<?php
																	echo $infoUserClient -> getTelefon();
																?>
															</td>
															<td class="center hidden-xs">
																<strong>Nom: </strong><?php echo $client -> getNom(); ?>
																<br>
																<strong>Adreça: </strong><?php echo $client -> getAdreca(); ?>
															</td>
															<!-- Accions -->
															<td class="center hidden-xs">
																<!-- ELIMINAR -->
																<a href="<?php echo $rutaDelete; ?>" onclick="confirm('Estàs segur/a de que vols esborrar a l\'usuari seleccionat?');"><i class="el el-remove"></i></a>

																<!-- EDITAR -->
																<a onclick='loadUserClientModal("<?php echo $infoUserClient-> getId(); ?>")'><i class="el el-edit"></i></a>
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

					<!-- TAULA CLIENTS -->
					<div class="row">
						<div class="col-md-6 col-lg-12 col-xl-6">
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
									</div>
							
									<h2 class="panel-title">Taula Informació de clients</h2>
								</header>
								<div class="panel-body" style="display: block;">
									<div id="divTaulaClients" class="dataTables_wrapper no-footer">
										<div class="table-responsive">
											<table class="table table-bordered table-striped mb-none dataTable no-footer" id="datatableTaulaClients" role="grid" aria-describedby="datatable-default_info">
												<thead>
													<tr role="row">
														<th class="sorting_asc" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 10%;">client Id
														</th>
														<th class="sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 40%;">Nom
														</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 40%;">Adreça
														</th>
														<th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 10%;">Accions</th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach($arrayClients as $index => $infoClient)
													{
														$rutaDelete = '/controllers/hddClientsController/deleteClientController.php?clientId='.$infoClient -> getId();
														?>
														<tr role="row" class="odd">
															<td class="sorting_1" id="userClientId"><?php echo $infoClient -> getId(); ?></td>
															<td>
																<?php echo $infoClient -> getNom()?>
															</td>
															<td class="center hidden-xs">
																<?php
																	echo $infoClient -> getAdreca();
																?>
															</td>

															<!-- Accions -->
															<td class="center hidden-xs">
																<!-- ELIMINAR -->
																<a href="<?php echo $rutaDelete; ?>" onclick="confirm('Estàs segur/a de que vols esborrar al client seleccionat?');"><i class="el el-remove"></i></a>

																<!-- EDITAR -->
																<a onclick='loadClientModal("<?php echo $infoClient-> getId(); ?>")'><i class="el el-edit"></i></a>
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

		<!-- MODAL PER EDITAR HDD DE CLIENT -->
		<div class="modal fade" id="modalUpdateHdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<form class="form-horizontal" method="POST" action="/controllers/hddClientsController/updateHddClientController.php">
			    	<div class="modal-header">
			        	<h2 class="panel-title" style="text-align-last: center;">Dades disc dur de client</h2>
			      	</div>
			      	<div class="modal-body" id="body-uploads">
			      		<div class="row">
				      		<!-- Id -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalHddId"><strong>Hdd Id</strong></label>
								<div class="col-sm-6">
									<input name="modalHddId" type="text" class="form-control input-sm" id="modalHddId" readonly="">
								</div>
							</div>
							<!-- Client -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" ><strong>Client</strong></label>
								<div class="col-sm-6">
									<select name="modalSelectClient" id="modalSelectClient" class="form-control" name="exp-month" required onchange="modalCarregaAdrecaClient(this);">
									
									<option value="0">-Selecciona client-</option>
									<?php
									foreach($arrayClients as $index => $client)
									{
										?>
										<option value="<?php echo $client -> getId(); ?>"><?php echo $client -> getNom(); ?></option>
										<?php
									}
									?>

									</select>
								</div>
							</div>
						
							<!-- Adreça -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalAdrecaClientHdd"><strong>Adreça</strong></label>
								<div class="col-sm-6">
									<input name="modalAdrecaClientHdd" type="text" class="form-control input-sm" id="modalAdrecaClientHdd" required readonly="">
								</div>
							</div>

							<!-- Descripcio -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalDescripcio"><strong>Descripció</strong></label>
								<div class="col-sm-6">
									<textarea name="modalDescripcio" class="form-control input-sm" rows="3" id="modalDescripcio" style="margin-top: 0px; margin-bottom: 0px; height: 111px;" required=""></textarea>
								</div>
							</div>

							<!-- Projecte / Departament -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalProjecteDepartament"><strong>Poecte / Departament</strong></label>
								<div class="col-sm-6">
									<input name="modalProjecteDepartament" type="text" class="form-control input-sm" id="modalProjecteDepartament" required>
								</div>
							</div>

							<!-- Localitzacio -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalLocalitzacio"><strong>Localització</strong></label>
								<div class="col-sm-6">
									<input name="modalLocalitzacio" type="text" class="form-control input-sm" id="modalLocalitzacio" required>
								</div>
							</div>

							<!-- Data Inserció -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalDataInsercio"><strong>Data inserció</strong></label>
								<div class="col-sm-6">
									<input name="modalDataInsercio" type="text" class="form-control input-sm" id="modalDataInsercio" required>
								</div>
							</div>

							<!-- Estat -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalEstat"><strong>Estat</strong></label>
								<div class="col-sm-6">
									<input name="modalEstat" type="text" class="form-control input-sm" id="modalEstat" required>
								</div>
							</div>

							<!-- Data retorn -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalDataRetorn"><strong>Data Retorn</strong></label>
								<div class="col-sm-6">
									<input name="modalDataRetorn" type="text" class="form-control input-sm" id="modalDataRetorn">
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


		<!-- MODAL PER EDITAR USUARI CLIENT -->
		<div class="modal fade" id="modalUpdateUserClient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<form class="form-horizontal" method="POST" action="/controllers/hddClientsController/updateUserClientController.php">
			    	<div class="modal-header">
			        	<h2 class="panel-title" style="text-align-last: center;">Dades del usuari client</h2>
			      	</div>
			      	<div class="modal-body" id="body-uploads">
			      		<div class="row">
				      		<!-- Id -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalUsuariClientId"><strong>Usuari client Id</strong></label>
								<div class="col-sm-6">
									<input name="modalUsuariClientId" type="text" class="form-control input-sm" id="modalUsuariClientId" readonly="">
								</div>
							</div>
							<!-- Nom -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalNomUserClient"><strong>Nom</strong></label>
								<div class="col-sm-6">
									<input name="modalNomUserClient" type="text" class="form-control input-sm" id="modalNomUserClient" required="">
								</div>
							</div>
						
							<!-- Cognom1 -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalCognom1UserClient"><strong>Primer cognom</strong></label>
								<div class="col-sm-6">
									<input name="modalCognom1UserClient" type="text" class="form-control input-sm" id="modalCognom1UserClient" required="">
								</div>
							</div>

							<!-- Cognom2 -->
				      		<div class="form-group">
								<label class="col-sm-3 control-label" for="modalCognom2UserClient"><strong>Segon cognom</strong></label>
								<div class="col-sm-6">
									<input name="modalCognom2UserClient" type="text" class="form-control input-sm" id="modalCognom2UserClient">
								</div>
							</div>

							<!-- Email -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalEmailUserClient"><strong>Email</strong></label>
								<div class="col-sm-6">
									<input name="modalEmailUserClient" type="text" class="form-control input-sm" id="modalEmailUserClient" required>
								</div>
							</div>

							<!-- Telèfon -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalTelefonUserClient"><strong>Telèfon</strong></label>
								<div class="col-sm-6">
									<input name="modalTelefonUserClient" type="text" class="form-control input-sm" id="modalTelefonUserClient" required>
								</div>
							</div>

							<!-- Client -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalClientUserClient"><strong>Client</strong></label>
								<div class="col-sm-6">
									<select name="modalClientUserClient" id="modalClientUserClient" class="form-control" name="exp-month" required onchange="modalCarregaClients(this);">
									
										<option value="0">-Selecciona client-</option>
										<?php
										foreach($arrayClients as $index => $client)
										{
											?>
											<option value="<?php echo $client -> getId(); ?>"><?php echo $client -> getNom(); ?></option>
											<?php
										}
										?>

									</select>
								</div>
							</div>

							<!-- Tipus usuari -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalClientUserTipusUsuari"><strong>Tipus d'usuari</strong></label>
								<div class="col-sm-6">
									<select name="modalClientUserTipusUsuari" id="modalClientUserTipusUsuari" class="form-control" name="exp-month" required onchange="modalCarregaClients(this);">
									
										<option value="0">-Selecciona tipus d'usuari-</option>
										<?php
										foreach($arrayTipusUsuari as $index => $usuari)
										{
											?>
											<option value="<?php echo $usuari -> getId(); ?>"><?php echo $usuari -> getDescripcio(); ?></option>
											<?php
										}
										?>

									</select>
								</div>
							</div>

							<!-- Idioma -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalClientUserIdioma"><strong>Idioma</strong></label>
								<div class="col-sm-6">
									<select name="modalClientUserIdioma" id="modalClientUserIdioma" class="form-control" name="exp-month" required onchange="modalCarregaIdiomes(this);">
									
										<option value="0">-Selecciona idioma-</option>
										<?php
										foreach($arrayIdiomes as $index => $idioma)
										{
											?>
											<option value="<?php echo $idioma -> getId(); ?>"><?php echo $idioma -> getIdioma(); ?></option>
											<?php
										}
										?>

									</select>
								</div>
							</div>
							
							<!-- Notifiacció -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="modalUserClientNotificacio"><strong>Notifiacció</strong></label>
								<div class="col-sm-6">
									<input name="modalUserClientNotificacio" type="text" class="form-control input-sm" id="modalUserClientNotificacio">
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