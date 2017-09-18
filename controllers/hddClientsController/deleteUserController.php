<?php
session_start();

/*
	Controlador que marca un disc dur com ple.
*/

include_once("../../config.php");

$hddId = $_GET["hddId"];

$user = unserialize($_SESSION["USER"]);

$deletedOk = HddClient::deleteHddClient($hddId);

if($deletedOk)
{
	$accesLog = new Log('', $user -> getId(), 27, time());
	$accesLog -> saveLog();
}
else
{
	$accesLog = new Log('', $user -> getId(), 28, time());
	$accesLog -> saveLog();
}

echo "
     <script type='text/javascript'>
         window.location.href='".URL."/manage/hdds/hddsClients.php';
     </script>
     ";
?>