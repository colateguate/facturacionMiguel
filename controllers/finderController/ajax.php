<?php

include_once "apiFunctions.php";

switch ($_GET['action']) {
    case "getDirContents":
        echo json_encode (read_directory ($_POST['dir']));
        break;
    case "getTreeFolder":
        echo tree_folder ();
        break;

    case "downloadFiles":
        $files = json_decode ($_POST['files']);
        downloadFiles ($files);
        break;
    case "newFolder":
        echo createFolder($_POST['dir']);
        break;
    case "deleteSelections":
        $files = json_decode ($_POST['files']);
        echo deleteSelections($files);
        break;
    case "checkOverwrite":
        echo checkOverwrite(json_decode($_POST['files']), $_POST['pasteFolder']);
        break;
    case "actionCopyCut":
        echo actionCopyCut(json_decode($_POST['files']), $_POST['pasteFolder'], $_POST['mode']);
        break;
    case "checkRename":
        echo checkRename($_POST['file']);
        break;
    case "fileRename":
        echo fileRename ($_POST['oldFile'], $_POST['newName']);
        break;
}


