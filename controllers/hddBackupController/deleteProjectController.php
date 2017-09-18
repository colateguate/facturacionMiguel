<?php
session_start();

/*
	Controlador que elimina un hdd de backup de la base de dades
*/

include_once("../../config.php");

$projectId = $_GET["projectId"];

$deletedProject = new Projecte($projectId);
$deletedOk 		= $deletedProject -> deleteProject();

$user = unserialize($_SESSION["USER"]);

if($deletedOk)
{
	$accesLog = new Log('', $user -> getId(), 49, time());
	$accesLog -> saveLog();
}
else
{
	$accesLog = new Log('', $user -> getId(), 50, time());
	$accesLog -> saveLog();
}

echo "
     <script type='text/javascript'>
         window.location.href='".URL."/manage/hdds/hddsBackups.php';
     </script>
     ";
?>