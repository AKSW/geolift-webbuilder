<?php

/*
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
 */
if (!isset($_REQUEST['function']))
    die('{"error":"no function selected"}');

if ($_REQUEST['function'] == "getDatasets") {
    if (!isset($_REQUEST['user']))
        die('{"error":"user-field required"}');
    if (trim($_REQUEST['user']) == "")
        die('{"error":"user-field required"}');
    $user_dir = $base_dir . md5($_REQUEST['user']) . DIRECTORY_SEPARATOR;
    $results_array = array();
    foreach (glob($user_dir . '*.json') as $file) {
//        echo $file;
        if (!endsWith($file, "user.json")) {
            $result1 = json_decode(file_get_contents($file));
            $result1->jobs = array();
            foreach (glob($user_dir . $result1->input . DIRECTORY_SEPARATOR . '*.job') as $file1) {
                $result1->jobs[] = json_decode(file_get_contents($file1));
            }

            $results_array[] = $result1;
        }
    }
    echo(json_encode($results_array));
} elseif ($_REQUEST['function'] == "login") {
    global $base_dir;
    if (!isset($_REQUEST['user']))
        die('{"error":"user-field required"}');
    if (trim($_REQUEST['user']) == "")
        die('{"error":"user-field required"}');
    $user_dir = $base_dir . md5($_REQUEST['user']) . DIRECTORY_SEPARATOR;

    if (is_dir($user_dir)) {
        if (is_file($user_dir . DIRECTORY_SEPARATOR . "user.json")) {
            setJsonResponse();
            $json = file_get_contents($user_dir . "user.json");
            echo($json);
            die('');
        }
    } else {
        mkdir($user_dir);
        $json = array('mail' => $_REQUEST['user']);
        file_put_contents($user_dir . "user.json", json_encode($json));
        echo(json_encode($json));
    }
} elseif ($_REQUEST['function'] == "getSchema") {
    setJsonResponse();
    $json = file_get_contents("../geolift" . DIRECTORY_SEPARATOR . "schema.json");
    echo($json);
    die('');
} 
?>
