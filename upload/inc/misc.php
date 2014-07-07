<?php

/*
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
 */
global $base_dir;
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
                $json = json_decode(file_get_contents($file1));
                $json->file = substr(strrchr($file1, DIRECTORY_SEPARATOR), 1);
                $result1->jobs[] = $json;
            }

            $results_array[] = $result1;
        }
    }
    echo(json_encode($results_array));
} elseif ($_REQUEST['function'] == "login") {
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
} elseif ($_REQUEST['function'] == "saveData") {
    setJsonResponse();
    if (!isset($_REQUEST['user']))
        die('{"error":"user-field required"}');
    if (trim($_REQUEST['user']) == "")
        die('{"error":"user-field required"}');
    if (!isset($_REQUEST['file']))
        die('{"error":"file-field required"}');
    if (trim($_REQUEST['file']) == "")
        die('{"error":"file-field required"}');
    if (!isset($_REQUEST['data']))
        die('{"error":"data-field required"}');
    if (trim($_REQUEST['data']) == "")
        die('{"error":"data-field required"}');
    $file_dir = $base_dir . md5(($_REQUEST['user'])) . DIRECTORY_SEPARATOR . ($_REQUEST['file']) . DIRECTORY_SEPARATOR;
    if (isset($_REQUEST['job'])){
        $newFilename = $_REQUEST['job'];
        unlink($file_dir.$newFilename);
    }else {
        $newFilename = "query_0.job";
        $run = 0;
        while (is_dir($file_dir . $newFilename) or is_file($file_dir . $newFilename)) {
            $newFilename = "query_" . $run++ . ".job";
        }
    }
    file_put_contents($file_dir . $newFilename, urldecode($_REQUEST['data']));
    $return = array(
        'success' => "Saved.");
    echo(json_encode($return));
} elseif ($_REQUEST['function'] == "loadJob") {
    setJsonResponse();
    if (!isset($_REQUEST['user']))
        die('{"error":"user-field required"}');
    if (trim($_REQUEST['user']) == "")
        die('{"error":"user-field required"}');
    if (!isset($_REQUEST['file']))
        die('{"error":"file-field required"}');
    if (trim($_REQUEST['file']) == "")
        die('{"error":"file-field required"}');
    if (!isset($_REQUEST['job']))
        die('{"error":"job-field required"}');
    if (trim($_REQUEST['job']) == "")
        die('{"error":"job-field required"}');
    $file = $base_dir . md5(($_REQUEST['user'])) . DIRECTORY_SEPARATOR . ($_REQUEST['file']) . DIRECTORY_SEPARATOR . ($_REQUEST['job']);
    if (!is_dir($file) && is_file($file)) {
        $json = file_get_contents($file);
        echo($json);
        die('');
    } else {
        die('{"error":"Job not found."}');
    }
}
?>
