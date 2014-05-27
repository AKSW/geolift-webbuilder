<?php

/*
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
 */
if (!isset($_REQUEST['function']))
    die('{"error":"no function selected"}');

if ($_REQUEST['function'] == "getDatasets") {
    
} elseif ($_REQUEST['function'] == "login") {
    global $base_dir;
    if (!isset($_REQUEST['user']))
        die('{"error":"user-field required"}');
     if (trim($_REQUEST['user'])=="")
        die('{"error":"user-field required"}');
    $user_dir = $base_dir . md5($_REQUEST['user']) . DIRECTORY_SEPARATOR;

    if (is_dir($user_dir)) {
        if(is_file($user_dir.DIRECTORY_SEPARATOR."user.json")) {
            $json = file_get_contents($user_dir."user.json");
            echo($json);
            die('');
        }
    } else {
        mkdir($user_dir);
        $json = array('mail' => $_REQUEST['user']);
        file_put_contents($user_dir."user.json", json_encode($json));
        echo($json);
    }
}
?>
