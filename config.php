<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ------- DATABASE ------------
define ("USERDB","root");
define ("PASSDB","root");
define ("HOSTDB","localhost");
define ("DATADB","facturacion_miguel");
define ("DEBUG",false);


// ------- ENDPOINT ---------------
define("URL", "http://localhost");

header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Europe/Madrid");
define("CSS_DIR", URL."/css");
define("JS_DIR", URL."/js");
define("IMG_DIR", URL."/img");

define("USER_DIR","/Users/metro/MetroProjects/filesystem");

//------- CONECTAR DB ------------
function connectarDataBase()
{
  $conn = new mysqli(HOSTDB, USERDB, PASSDB, DATADB);
  $conn -> set_charset('utf8');

  return $conn;
}

// CLASSES AUTOLOAD
spl_autoload_register(function ($class_name) {
  require_once 'classes/'.$class_name . '.php';
});
