<?php
session_start();

/*
    Controlador que actualitza els valors dun client
*/

include_once("../../config.php");

$user = unserialize($_SESSION["USER"]);

$client = new Client(addslashes($_POST['modalClientId']));

//Mirem els camps que han canviat
if($client -> getNom() != addslashes($_POST['modalNomClient']))
{
	$client -> setNom(addslashes($_POST['modalNomClient']));
}

if($client -> getAdreca() != addslashes($_POST['modalAdrecaClient']))
{
	$client -> setAdreca(addslashes($_POST['modalAdrecaClient']));
}

// Actualitzem el client
$updateOk = $client -> updateClient();


if($updateOk)
{
	$accesLog = new Log('', $user -> getId(), 41, time());
	$accesLog -> saveLog();

	echo "
     <script type='text/javascript'>
         alert('Client editat correctament');
     </script>
     ";
}
else
{
	$accesLog = new Log('', $user -> getId(), 42, time());
	$accesLog -> saveLog();

	echo "
     <script type='text/javascript'>
         alert('Error al editar l'usuari client);
     </script>
     ";
}

echo "
     <script type='text/javascript'>
         window.location.href='".URL."/manage/hdds/hddsClients.php';
     </script>
     ";

?>