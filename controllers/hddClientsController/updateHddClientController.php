<?php
session_start();

/*
	Controlador que marca un disc dur com ple.
*/

include_once("../../config.php");

$user = unserialize($_SESSION["USER"]);

$hddClient = new HddClient(addslashes($_POST['modalHddId']));

//Mirem els camps que han canviat
if($hddClient -> getClient() != addslashes($_POST['modalSelectClient']))
{
	$hddClient -> setClient(addslashes($_POST['modalSelectClient']));
}

if($hddClient -> getDescripcio() != addslashes($_POST['modalDescripcio']))
{
	$hddClient -> setDescripcio(addslashes($_POST['modalDescripcio']));
}

if($hddClient -> getProjecteDepartament() != addslashes($_POST['modalProjecteDepartament']))
{
	$hddClient -> setProjecteDepartament(addslashes($_POST['modalProjecteDepartament']));
}

if($hddClient -> getLocalitzacio() != addslashes($_POST['modalLocalitzacio']))
{
	$hddClient -> setLocalitzacio(addslashes($_POST['modalLocalitzacio']));
}

if($hddClient -> getDataInsercio() != addslashes($_POST['modalDataInsercio']))
{
	$hddClient -> setDataInsercio(addslashes($_POST['modalDataInsercio']));
}

if($hddClient -> getEstat() != addslashes($_POST['modalEstat']))
{
	$hddClient -> setEstat(addslashes($_POST['modalEstat']));
}

if($hddClient -> getDataRetorn() != addslashes($_POST['modalDataRetorn']))
{
	$hddClient -> setDataRetorn(addslashes($_POST['modalDataRetorn']));
}

// Actualitzem el hddClient
$updateOk = $hddClient -> updateHddClient();


if($updateOk)
{
	$accesLog = new Log('', $user -> getId(), 29, time());
	$accesLog -> saveLog();

	echo "
     <script type='text/javascript'>
         alert('Disc editat correctament');
     </script>
     ";
}
else
{
	$accesLog = new Log('', $user -> getId(), 30, time());
	$accesLog -> saveLog();

	echo "
     <script type='text/javascript'>
         alert('Error al editat el disc');
     </script>
     ";
}

echo "
     <script type='text/javascript'>
         window.location.href='".URL."/manage/hdds/hddsClients.php';
     </script>
     ";

?>