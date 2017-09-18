<?php

include_once "../../config.php";

ini_set ('display_errors', 0);
ini_set ('display_startup_errors', 0);

/**
 * 
 * Creació de la API per fer consultes sobre el directori de sistema
 * 
 */

/**
 * 
 * @param string $dir Directori sense el path absolut
 * @return array Array amb tota la metadata necessaria del directori
 */
function read_directory ($dir) {
    $dirContent = scandir (USER_DIR . "/" . $dir);
    $arrayJsonInfo = array();

    foreach ($dirContent as $item) {
        if ($item != "." && $item != "..") {
            if (substr ($item, 0, 1) !== '.') {
                $fileitem = USER_DIR . "/" . $dir . "/" . $item;

                $itemInfo = array();
                $itemInfo['name'] = $item;
                $stat = stat ($fileitem);
                $itemInfo['size'] = filesize ($fileitem);
                $itemInfo['fsize'] = FileSizeConvert ($itemInfo['size']);
                $itemInfo['mime'] = mime_content_type ($fileitem);
                $itemInfo['ctime'] = date ('d/m/Y H:i', $stat["ctime"]);
                $itemInfo['mtime'] = date ('d/m/Y H:i', $stat["mtime"]);
                if (is_dir ($fileitem)) {
                    $itemInfo['type'] = "folder";
                } else {

                    $itemInfo['type'] = getFileType ($fileitem);
                }
                $arrayJsonInfo[] = $itemInfo;
            }
        }
    }
    return $arrayJsonInfo;
}

/**
 * 
 * @param string $file Ruta absoluta del fitxer
 * @return string Tipus de fitxer
 */
function getFileType ($file) {
    $mime = mime_content_type ($file);
    $return = "";
    if (preg_match ('/image/', $mime)) {
        $return = "image";
    } else if (preg_match ('/video/', $mime)) {
        $return = "video";
    } else if (preg_match ('/audio/', $mime)) {
        $return = "audio";
    } else if (preg_match ('/compress/', $mime)) {
        $return = "compress";
    } else {
        switch (strtolower (pathinfo ($file, PATHINFO_EXTENSION))) {
            case "doc":
            case "docx":
                $return = "doc";
                break;
            case "xls":
            case "xlsx":
                $return = "xls";
                break;
            case "ppt":
            case "pptx":
                $return = "ppt";
                break;
            case "ai":
            case "c":
            case "cpp":
            case "cs":
            case "css":
            case "deb":
            case "jar":
            case "java":
            case "js":
            case "pdf":
            case "php":
            case "pl":
            case "py":
            case "rb":
            case "rtf":
            case "sh":
            case "xml":
            case "rtf":
                $return = pathinfo ($file, PATHINFO_EXTENSION);
                break;
            case "zip":
            case "rar":
            case "tgz":
            case "gz":
            case "tar":
            case "7z":
                $return = "compress";
                break;
            default:
                $return = "unknown";
                break;
        }
    }

    return $return;
}

function tree_folder () {
    return getDirContents (USER_DIR . "/", "/");
}

function getDirContents ($root = '', $oldpath = '') {

    // if root is a file
    if (is_file ($root)) {
        echo '<li>' . basename ($root) . '</li>';
        return;
    }

    if (!is_dir ($root)) {
        return;
    }

    $dirs = scandir ($root);
    foreach ($dirs as $dir) {
        if ($dir == '.' || $dir == '..') {
            continue;
        }

        $path = $root . '/' . $dir;
        if (is_file ($path)) {
            // if file, create list item tag, and done.
            //echo '<li>' . $dir . '</li>';
        } else if (is_dir ($path)) {
            // if dir, create list item with sub ul tag
            echo '<li class="folder-list-droppable" data-jstree=\'{"icon" : "/img/finder/16x16/places/folder.png" }\' >';
            echo '<a onclick="Finder.changeFolder(\'/' . $oldpath . "/" . $dir . '\')">' . $dir . '</a>';
            echo '<ul>';
            getDirContents ($path, $oldpath . "/" . $dir); // <--- then recursion
            echo '</ul>';
            echo '</li>';
        }
    }
}

function downloadFiles ($files) {
    $path = USER_DIR;
    $rndUid = uniqid ();
    if (count ($files) == 1) {
        $files[0] = str_replace ("//", "", $files[0]);
        $file_url = realpath ($path . "/" . $files[0]);

        if (is_dir ($file_url)) {
            $zipString = "cd " . $path . " && zip -rD .download" . $rndUid . ".zip " . $files[0];
            exec ($zipString);
            header ('Content-Type: application/zip');
            header ("Content-Transfer-Encoding: Binary");
            header ("Content-disposition: attachment; filename=\"" . $files[0] . ".zip\"");
            readfile ($path . "/.download" . $rndUid . ".zip");
            unlink ($path . "/" . ".download" . $rndUid . ".zip");
        } else {
            header ('Content-Type: application/octet-stream');
            header ("Content- Transfer-Encoding: Binary");
            header ("Content-disposition: attachment; filename=\"" . basename ($file_url) . "\"");
            readfile ($file_url);
        }
    } else {
        $zipString = "zip -j download" . $rndUid . " ";

        foreach ($files as $file) {
            $strFile = realpath ($path . $file);
            $zipString .= "'" . $strFile . "' ";
        }
        exec ($zipString);
        $file_url = $path . $files[0];
        header ('Content-Type: application/zip');
        header ("Content-Transfer-Encoding: Binary");
        header ("Content-disposition: attachment; filename=\"download.zip\"");
        readfile ("download" . $rndUid . ".zip");
        unlink ("download" . $rndUid . ".zip");
    }
}

function createFolder ($dir) {
    return mkdir (USER_DIR . $dir, 0777);
}

function deleteSelections ($paths) {

    foreach ($paths as $file) {
        $file_url = realpath (USER_DIR . "/" . $file);
        if (!is_dir ($file_url)) {
            unlink ($file_url);
        } else {
            exec ("rm -rf " . $file_url);
        }
    }
    echo "1";
}

function checkOverwrite ($paths, $pastepath) {
    $return = "0";
    foreach ($paths as $file) {
        $file = str_replace ("///", "/", $file);
        $file = str_replace ("//", "/", $file);

        $lastPath = realpath ((USER_DIR . "/" . $pastepath . "/" . $file));
        if (file_exists ($lastPath)) {
            $return = "1";
        }
    }
    return $return;
}

function actionCopyCut ($paths, $pastepath, $mode) {

    foreach ($paths as $file) {

        $filename = basename (realpath (USER_DIR . $file));

        if ($mode === 'copy') {
            copy (realpath (USER_DIR . $file), realpath (USER_DIR . $pastepath) . "/" . $filename);
        } elseif ($mode === "cut") {
            rename (realpath (USER_DIR . $file), realpath (USER_DIR . $pastepath) . "/" . $filename);
        }
        echo "1";
    }
}

function checkRename ($file) {

    $dest = USER_DIR . $file;
    if (file_exists (realpath ($dest))) {
        return "1";
    } else {
        return "0";
    }
}

function fileRename ($oldFile, $newName) {



    $oldPath = (USER_DIR . $oldFile);
    $newPath = (USER_DIR . $newName);

    $oldPath = str_replace ("///", "/", $oldPath);
    $oldPath = str_replace ("//", "/", $oldPath);

    $newPath = str_replace ("///", "/", $newPath);
    $newPath = str_replace ("//", "/", $newPath);


    rename ($oldPath, $newPath);

    return "1";
}

function FileSizeConvert ($bytes) {
    $bytes = floatval ($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow (1024, 4)
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow (1024, 3)
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow (1024, 2)
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1
        ),
    );

    foreach ($arBytes as $arItem) {
        if ($bytes >= $arItem["VALUE"]) {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace (".", ",", strval (round ($result, 2))) . " " . $arItem["UNIT"];
            break;
        }
    }
    return $result;
}
