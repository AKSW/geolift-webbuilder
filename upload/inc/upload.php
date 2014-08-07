<?php
/*
 * Copyright 2014 Alrik Hausdorf
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
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
        mkdir($user_dir . $newFilename);

        move_uploaded_file($tmpName, $user_dir . $newFilename . DIRECTORY_SEPARATOR . "input.in");
    }
    $ret["input"] = $newFilename;
    $ret["filename"] = $fileName;
    $ret["upload"] = $now->getTimestamp();
    $ret['url'] = false;
    file_put_contents($user_dir . $newFilename . ".json", json_encode($ret));
    return $ret;
}

//<start> BASE Script </start>
if (!isset($_REQUEST["user"]))
    die('{"error":"No User defined"}');

$user_dir = $base_dir . md5($_REQUEST['user']) . DIRECTORY_SEPARATOR;
if (!is_dir($user_dir))
    die('{"error":"User not existings"}');



if (!is_array($_FILES["files"]["name"])) {
    $return[0] = handleUpload($_FILES["files"]);
} else {
    for ($i = 0; $i < sizeof($_FILES["files"]["name"]); $i++) {
        $return[$i] = handleUpload($_FILES["files"], $i);
    }
}
echo json_encode($return);
