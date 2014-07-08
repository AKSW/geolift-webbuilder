<?php

/*
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
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
