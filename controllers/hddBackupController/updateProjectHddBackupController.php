<?php
session_start();

/*
	Controlador que marca edita les dades d'un disc dur de backup.
*/

include_once("../../config.php");

$user = unserialize($_SESSION["USER"]);

$projecte = new Projecte(addslashes($_POST['modalProjecteId']));

//Mirem els camps que han canviat

// Operador
if($projecte -> getOperadors() != addslashes($_POST['modalProjectOperador']))
{
    $projecte -> setOperador(addslashes($_POST['modalProjectOperador']));
} 

// Sistema
if($projecte -> getSistema() != addslashes($_POST['modalProjectSistema']))
{
    $projecte -> setOperador(addslashes($_POST['modalProjectSistema']));
}

// Nom projecte
if($projecte -> getProjecte() != addslashes($_POST['modalProjectNomProjecte']))
{
    $projecte -> setProjecte(addslashes($_POST['modalProjectNomProjecte']));
}

// Operadors suport
if($projecte -> getSuport() != addslashes($_POST['modalProjectOperador']))
{
    $projecte -> setSuport(addslashes($_POST['modalProjectOperador']));
}

// Data
if($projecte -> getData() != addslashes($_POST['modalProjectDataProject']))
{
    $projecte -> setData(addslashes($_POST['modalProjectDataProject']));
}

// Comentaris
if($projecte -> getData() != addslashes($_POST['modalProjectComentaris']))
{
    $projecte -> setData(addslashes($_POST['modalProjectComentaris']));
}

// Client
if($projecte -> getClient() != addslashes($_POST['modalProjectClientProjecte']))
{
    $projecte -> setClient(addslashes($_POST['modalProjectClientProjecte']));
}

// Ruta a master
if($projecte -> getRutaMaster() != addslashes($_POST['modalProjectRutaAMaster']))
{
    $projecte -> setRutaMaster(addslashes($_POST['modalProjectRutaAMaster']));
}


// Actualitzem el hdd
$updateOk = $hdd -> updateHdd();


if($updateOk)
{
	$accesLog = new Log('', $user -> getId(), 51, time());
	$accesLog -> saveLog();

	echo "
     <script type='text/javascript'>
         alert('projecte editat correctament');
     </script>
     ";
}
else
{
	$accesLog = new Log('', $user -> getId(), 52, time());
	$accesLog -> saveLog();

	echo "
     <script type='text/javascript'>
         alert('Error al editar el projecte');
     </script>
     ";
}

echo "
     <script type='text/javascript'>
         window.location.href='".URL."/manage/hdds/hddsBackups.php';
     </script>
     ";

?>