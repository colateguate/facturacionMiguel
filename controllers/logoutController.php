<?php
session_start();

require_once('../config.php');

$user = unserialize($_SESSION["USER"]);
$accesLog = new Log('', $user -> getId(), 11, time());
$accesLog -> saveLog();

session_unset();
session_destroy();

setcookie('pageLock', 0, 0, '/', 'localhost');


echo "
<script type='text/javascript'>;
    window.location.href='".URL."';
</script>";
die();
