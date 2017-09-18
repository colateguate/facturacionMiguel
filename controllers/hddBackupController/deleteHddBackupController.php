<?php
session_start();

/*
	Controlador que elimina un hdd de backup de la base de dades
*/

include_once("../../config.php");

$hddId = $_GET["hddId"];

$deletedHdd = new Hdd($hddId);
$deletedOk 	= $deletedHdd -> deleteHdd();

$user = unserialize($_SESSION["USER"]);

if($deletedOk)
{
	$accesLog = new Log('', $user -> getId(), 45, time());
	$accesLog -> saveLog();
}
else
{
	$accesLog = new Log('', $user -> getId(), 46, time());
	$accesLog -> saveLog();
}

echo "
     <script type='text/javascript'>
         window.location.href='".URL."/manage/hdds/hddsBackups.php';
     </script>
     ";
?>