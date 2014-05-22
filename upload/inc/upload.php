<?php

/**
 * file to handle Uploads
 */
$return = array();

//print_r($_FILES["files"]);

function handleUpload($file, $i = -1) {
    global $user_dir;
    $ret = array();
    $now = new DateTime();
    
    if ($i != -1)
        $fileName = $file["name"][$i];
    else
        $fileName = $file["name"];
    
    if ($i != -1)
        $tmpName = $file["tmp_name"][$i];
    else
        $tmpName = $file["tmp_name"];

    $newFilename = md5_file($tmpName);

    if (!is_dir($user_dir . $newFilename) && !is_file($user_dir . $newFilename)) {

        move_uploaded_file($tmpName, $user_dir . $newFilename);
    }
    $ret["filename"] = $fileName;
    $ret["upload"] = $now->getTimestamp();

    file_put_contents($user_dir . $newFilename . ".json", json_encode($ret));
    return $ret;
}
//<start> BASE Script </start>
if (!isset($_REQUEST["user"]))
    die("{'error':'No User defined'}");

$user_dir = $base_dir . $_REQUEST['user'] . DIRECTORY_SEPARATOR;
if (!is_dir($user_dir))
    die("{'error':'User not existings'}");



if (!is_array($_FILES["files"]["name"])) {
    $return[0] = handleUpload($_FILES["files"]);
} else {
    for ($i = 0; $i < sizeof($_FILES["files"]["name"]); $i++) {
        $return[$i] = handleUpload($_FILES["files"], $i);
    }
}
echo json_encode($return);