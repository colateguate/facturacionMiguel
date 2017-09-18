<?php
session_start();

include_once("../../config.php");

/*
	Controlador que afegiex un nou fil a una categoria del forum
*/

$user 			 = unserialize($_SESSION["USER"]);
$categoryId 	 = $_POST["categoryId"];
$newThreadTitle	 = $_POST["newThreadTitle"];
$threadComment 	 = $_POST["comment"];

// Guardem el post. El constructor guarda el post a bbdd si no li pasem id.
$newThread = new ForumThreat('', $categoryId, addslashes($newThreadTitle), addslashes($threadComment), $user -> getId(), time(), 0, 0, 0);

$user = unserialize($_SESSION["USER"]);

if($newThread)
{
	$accesLog = new Log('', $user -> getId(), 14, time());
	$accesLog -> saveLog();

	echo "
            <script type='text/javascript'>alert('Fil afegit correctament');
                window.location.href='".URL."/forum/forumIndex.php?catId=".$categoryId."';
            </script>";
}
else
{
	$accesLog = new Log('', $user -> getId(), 15, time());
	$accesLog -> saveLog();

	echo "
            <script type='text/javascript'>alert('Error al guardar el nou fil');
                window.location.href='".URL."';
            </script>";
}
?>