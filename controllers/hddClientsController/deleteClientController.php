<?php
session_start();

/*
	Controlador que elimina un client de la taula clients
*/

include_once("../../config.php");

$clientId = $_GET["clientId"];

$user = unserialize($_SESSION["USER"]);

$deletedOk = Client::deleteClient($clientId);

if($deletedOk)
{
	$accesLog = new Log('', $user -> getId(), 39, time());
	$accesLog -> saveLog();
}
else
{
	$accesLog = new Log('', $user -> getId(), 40, time());
	$accesLog -> saveLog();
}

echo "
     <script type='text/javascript'>
         window.location.href='".URL."/manage/hdds/hddsClients.php';
     </script>
     ";
?>