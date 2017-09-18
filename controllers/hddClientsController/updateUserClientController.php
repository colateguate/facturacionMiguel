<?php
session_start();

/*
	Controlador que actualitza els valors dun usuariClient
*/

include_once("../../config.php");

$user = unserialize($_SESSION["USER"]);

$userClient = new UsersClient(addslashes($_POST['modalUsuariClientId']));

//Mirem els camps que han canviat
if($userClient -> getNom() != addslashes($_POST['modalNomUserClient']))
{
	$userClient -> setNom(addslashes($_POST['modalNomUserClient']));
}

if($userClient -> getPrimerCognom() != addslashes($_POST['modalCognom1UserClient']))
{
	$userClient -> setPrimerCognom(addslashes($_POST['modalCognom1UserClient']));
}

if($userClient -> getSegonCognom() != addslashes($_POST['modalCognom2UserClient']))
{
	$userClient -> setSegonCognom(addslashes($_POST['modalCognom2UserClient']));
}

if($userClient -> getEmail() != addslashes($_POST['modalEmailUserClient']))
{
	$userClient -> setEmail(addslashes($_POST['modalEmailUserClient']));
}

if($userClient -> getTelefon() != addslashes($_POST['modalTelefonUserClient']))
{
	$userClient -> setTelefon(addslashes($_POST['modalTelefonUserClient']));
}

if($userClient -> getClient() != addslashes($_POST['modalClientUserClient']))
{
	$userClient -> setClient(addslashes($_POST['modalClientUserClient']));
}

if($userClient -> getTipusUsuari() != addslashes($_POST['modalClientUserTipusUsuari']))
{
	$userClient -> setTipusUsuari(addslashes($_POST['modalClientUserTipusUsuari']));
}

if($userClient -> getIdioma() != addslashes($_POST['modalClientUserIdioma']))
{
	$userClient -> setIdioma(addslashes($_POST['modalClientUserIdioma']));
}

if($userClient -> getNotificacio() != addslashes($_POST['modalUserClientNotificacio']))
{
	$userClient -> setNotificacio(addslashes($_POST['modalUserClientNotificacio']));
}

// Actualitxem un usuari client
$updateOk = $userClient -> updateUserClient();


if($updateOk)
{
	$accesLog = new Log('', $user -> getId(), 35, time());
	$accesLog -> saveLog();

	echo "
     <script type='text/javascript'>
         alert('Usuari client editat correctament');
     </script>
     ";
}
else
{
	$accesLog = new Log('', $user -> getId(), 36, time());
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