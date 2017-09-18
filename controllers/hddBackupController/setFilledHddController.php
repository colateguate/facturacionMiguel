<?php
session_start();

/*
	Controlador que marca un disc dur com ple.
*/

include_once("../../config.php");

$hddId = $_POST["hddId"];

$filledHdd = new Hdd($hddId);
$filledHdd -> setIsFull(1);
$filledHdd -> uploadHdd();

$user = unserialize($_SESSION["USER"]);
$accesLog = new Log('', $user -> getId(), 18, time());
$accesLog -> saveLog();

echo "
     <script type='text/javascript'>
         window.location.href='".URL."/manage/hdds/hddsBackups.php';
     </script>
     ";
?>