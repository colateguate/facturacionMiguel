<?php
session_start();

/*
	Controlador que crea una nova categoria.
*/
include_once("../../config.php");

$newCategory = $_POST["newCategory"];

$newCategory = new ForumCategory('', addslashes($newCategory));

$user = unserialize($_SESSION["USER"]);

if($newCategory)
{
	$accesLog = new Log('', $user -> getId(), 12, time());
	$accesLog -> saveLog();

	echo "1";
}
else
{
	$accesLog = new Log('', $user -> getId(), 13, time());
	$accesLog -> saveLog();

	echo "
            <script type='text/javascript'>alert('Error al guardar la categoria');
                window.location.href='".URL."';
            </script>";
}
?>
