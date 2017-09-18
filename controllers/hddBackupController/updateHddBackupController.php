<?php
session_start();

/*
	Controlador que marca edita les dades d'un disc dur de backup.
*/

include_once("../../config.php");

$user = unserialize($_SESSION["USER"]);

$hdd = new Hdd(addslashes($_POST['modalHddBackupId']));

//Mirem els camps que han canviat

//metropolitana Id
if($hdd -> getMetropolitanaId() != addslashes($_POST['modalHddBackupMetropolitanaId']))
{
	$hdd -> setMetropolitanaId(addslashes($_POST['modalHddBackupMetropolitanaId']));
}

//nom
if($hdd -> getNom() != addslashes($_POST['modalNomHddBackup']))
{
	$hdd -> setNom(addslashes($_POST['modalNomHddBackup']));
}

//estat
if($hdd -> getEstat() != addslashes($_POST['modalEstatHddBackup']))
{
	$hdd -> setEstat(addslashes($_POST['modalEstatHddBackup']));
}


// Actualitzem el hdd
$updateOk = $hdd -> updateHdd();


if($updateOk)
{
	$accesLog = new Log('', $user -> getId(), 47, time());
	$accesLog -> saveLog();

	echo "
     <script type='text/javascript'>
         alert('Disc editat correctament');
     </script>
     ";
}
else
{
	$accesLog = new Log('', $user -> getId(), 48, time());
	$accesLog -> saveLog();

	echo "
     <script type='text/javascript'>
         alert('Error al editar el disc');
     </script>
     ";
}

echo "
     <script type='text/javascript'>
         window.location.href='".URL."/manage/hdds/hddsBackups.php';
     </script>
     ";

?>