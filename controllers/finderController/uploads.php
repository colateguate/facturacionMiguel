<?php

include_once "../../config.php";

ini_set ('display_errors', 0);
ini_set ('display_startup_errors', 0);

$dirContent = USER_DIR . "/" . $_POST['path']."/";

if(isset($_FILES["file"]))
{
  //Filter the file types , if you want.
  if ($_FILES["file"]["error"] > 0)
  {
    //echo "Error: " . $_FILES["file"]["error"];
  }
  else
  {
    move_uploaded_file($_FILES["file"]["tmp_name"],$dirContent. $_FILES["file"]["name"]);

    //echo "Uploaded File :".$_FILES["file"]["name"];
  }

}
