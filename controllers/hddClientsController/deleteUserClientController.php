<?php
session_start();

/*
	Controlador que elimina un usuari Client de la taula users_clients
*/

include_once("../../config.php");

$userClientId = $_GET["userClientId"];

$user = unserialize($_SESSION["USER"]);

$deletedOk = UsersClient::deleteUserClient($userClientId);

if($deletedOk)
{
	$accesLog = new Log('', $user -> getId(), 33, time());
	$accesLog -> saveLog();
}
else
{
	$accesLog = new Log('', $user -> getId(), 34, time());
	$accesLog -> saveLog();
}

echo "
     <script type='text/javascript'>
         window.location.href='".URL."/manage/hdds/hddsClients.php';
     </script>
     ";
?>