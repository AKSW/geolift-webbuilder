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
 */
if (!isset($_REQUEST["user"]))
    die('{"error":"No User defined"}');
if (!isset($_REQUEST["url"]))
    die('{"error":"No URL defined"}');

$user_dir = $base_dir . md5($_REQUEST['user']) . DIRECTORY_SEPARATOR;
if (!is_dir($user_dir))
    die('{"error":"User not existings"}');

global $user_dir;
$ret = array();

$newFilename = md5($_REQUEST["url"]);

if (!is_dir($user_dir . $newFilename) && !is_file($user_dir . $newFilename)) {
    mkdir($user_dir . $newFilename);
}
$now = new DateTime();
$return=array();
$return["success"] = "URL added.";
$ret["input"] = $newFilename;
$ret["filename"] = $_REQUEST["url"];
$ret['url']=true;
$ret["upload"] = $now->getTimestamp();

file_put_contents($user_dir . $newFilename . ".json", json_encode($ret));
echo json_encode($return);
?>
