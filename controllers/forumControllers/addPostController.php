<?php
session_start();

/*
	Controlador que afageix un post nou en un thread al forum
*/
include_once("../../config.php");

// Recepció de les dades
$user 			 = unserialize($_SESSION["USER"]);
$postId 		 = isset($_POST["postId"])?$_POST["postId"]:$_POST["newPostRepliedToId"];
$userName 		 = $_POST["name"];
$userPostComment = isset($_POST["comment"])?$_POST["comment"]:$_POST["commentNew"];
$threadId 		 = $_POST["threadId"];

// Mirem si hi ha una imatge adjunta. Si és el cas la movem al directori 
// premanent
if(isset($_FILES['files']['tmp_name'][0]) AND strlen($_FILES['files']['tmp_name'][0])>1)
{
	$sourcePath 	 = $_FILES['files']['tmp_name'][0];
	$targetPath 	 = "/uploads/forum/".time()."_post_".$postId."_".$_FILES['files']['name'][0];

	echo 'sourcePath: '.$sourcePath.'<br>';
	echo 'targetPath: '.$targetPath.'<br>';

	$moveOk = move_uploaded_file($sourcePath,UPLOAD_PATH.$targetPath);

	if($moveOk)
	{
		$accesLog = new Log('', $user -> getId(), 19, time());
		$accesLog -> saveLog();
	}
	else
	{
		$accesLog = new Log('', $user -> getId(), 20, time());
		$accesLog -> saveLog();
	}
}

// Guardem el post. El constructor guarda el post a bbdd si no li pasem id.
$newPost = new ForumPost('', $threadId, $postId, addslashes($userPostComment), $user -> getId(), 0, 0, time(), $targetPath);

$user = unserialize($_SESSION["USER"]);

if($newPost)
{
	$accesLog = new Log('', $user -> getId(), 16, time());
	$accesLog -> saveLog();

	echo "
            <script type='text/javascript'>alert('Post afegit correctament');
                window.location.href='".URL."/forum/forumPost.php?threadId=".$threadId."';
            </script>";
}
else
{
	$accesLog = new Log('', $user -> getId(), 17, time());
	$accesLog -> saveLog();

	echo "
            <script type='text/javascript'>alert('Error al guardar el Post');
                window.location.href='".URL."';
            </script>";
}
?>