<?php

/*
 * Copyright 2014 Alrik Hausdorf and Eugen Rein
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
    $json = shell_exec(GEOLIFT_SCHEMA_COMMAND);
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
    if (isset($_REQUEST['job'])) {
        $newFilename = $_REQUEST['job'];
        unlink($file_dir . $newFilename);
    } else {
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
} elseif ($_REQUEST['function'] == "removeJob") {
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
    $file_dir = $base_dir . md5(($_REQUEST['user'])) . DIRECTORY_SEPARATOR . ($_REQUEST['file']) . DIRECTORY_SEPARATOR;
    $newFilename = $_REQUEST['job'];
    unlink($file_dir . $newFilename);

    $return = array(
        'success' => "Removed.");
    echo(json_encode($return));
} elseif ($_REQUEST['function'] == "removeFile") {
    setJsonResponse();
    if (!isset($_REQUEST['user']))
        die('{"error":"user-field required"}');
    if (trim($_REQUEST['user']) == "")
        die('{"error":"user-field required"}');
    if (!isset($_REQUEST['file']))
        die('{"error":"file-field required"}');
    if (trim($_REQUEST['file']) == "")
        die('{"error":"file-field required"}');
    $file_dir = $base_dir . md5(($_REQUEST['user'])) . DIRECTORY_SEPARATOR . ($_REQUEST['file']) . DIRECTORY_SEPARATOR;
    unlink($base_dir . md5(($_REQUEST['user'])) . DIRECTORY_SEPARATOR . ($_REQUEST['file']) . '.json');
    deleteDir($file_dir);

    $return = array(
        'success' => "Removed.");
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
} elseif ($_REQUEST['function'] == 'runJob') {
    setJsonResponse();
    $success = array('success' => true);

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

    $file_dir = realpath($base_dir . md5(($_REQUEST['user'])) . DIRECTORY_SEPARATOR . ($_REQUEST['file']) . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $file = $file_dir . ($_REQUEST['job']);

    $input_file_name = $file_dir . 'input.in';

    $config_file_name = str_replace('.job', '.tsv', $_REQUEST['job']);
    $config_file_name = $file_dir . $config_file_name;
    $config_file_handler = @fopen($config_file_name, 'wb');

    $ouput_file_name = str_replace('.job', '.out', $_REQUEST['job']);
    $ouput_file_name = $file_dir . $ouput_file_name;

    $pid_file_name = str_replace('.job', '.pid', $_REQUEST['job']);
    $pid_file_name = $file_dir . $pid_file_name;

    $log_file_name = str_replace('.job', '.log', $_REQUEST['job']);
    $log_file_name = $file_dir . $log_file_name;

    $json = file_get_contents($file);
    $query = json_decode($json, true);

    $user_file_dir = realpath($base_dir . md5($_REQUEST['user'])) . DS . ($_REQUEST['file']) . '.json';

    $Filejson = file_get_contents($user_file_dir);
    $Filequery = json_decode($Filejson);
    $query['state'] = 'running';
    file_put_contents($file, json_encode($query));
    if (!$query) {
        die('{"error": "Job config can not be parsed."}');
    }
    if (!isset($query['job'])) {
        die('{"error": "Job config can not be parsed."}');
    }
    if (!$query['job']) {
        die('{"error": "Job config can not be parsed."}');
    }
    if (FALSE === $config_file_handler) {
        die('{"error": "Job config can not be written."}');
    }
    if (!$Filequery->url) {
        if (is_dir($file) || !is_file($file)) {
            die('{"error":"Job not found."}');
        }
        if (is_dir($input_file_name) || !is_file($input_file_name)) {
            die('{"error":"Job not found."}');
        }
    } else {
        $input_file_name = '"'+$Filequery->filename+'"';
    }
    if (is_dir($ouput_file_name)) {
        die('{"error":"Output file can not be written."}');
    }
    if (is_file($ouput_file_name) && !is_writable($ouput_file_name)) {
        die('{"error":"Output file can not be written."}');
    }
    if (is_dir($pid_file_name)) {
        die('{"error":"PID file can not be written."}');
    }
    if (is_dir($pid_file_name)) {
        die('{"error":"PID file can not be written."}');
    }
    if (is_file($pid_file_name) && !is_writable($pid_file_name)) {
        die('{"error":"PID file can not be written."}');
    }

    //filter start, end and operator elements from job list
    $modules = array_filter(
            $query['job'], function($element) {
                return (!in_array($element['name'], array('start', 'end')) && 'operator' != $element['type']);
            }
    );

    //sort modules array by element id
    uasort(
            $modules, function($a, $b) {
                if ($a['id'] == $b['id']) {
                    return 0;
                }
                return (($a['id'] < $b['id']) ? -1 : 1);
            }
    );

    //re-index array
    $modules = array_values($modules);

    //create config file
    foreach ($modules as $key => $module) {
        $name = $module['name'];
        $newline = PHP_EOL;
        $key_inc = $key + 1;

        if (!empty($module['properties'])) {
            if ('dereferencing' == $name) {
                $properties = $module['properties'];
                if (isset($properties['input'])) {
                    $properties = $properties['input'];
                    $properties = explode(',', str_replace(array(';', "\t", "\r\n", "\r", "\n"), ',', $properties));
                    $properties = array_map('trim', $properties);
                    $module['properties'] = array();

                    foreach ($properties as $property) {
                        list($n, $v) = explode(' ', str_replace(array('\'', '"'), '', $property));
                        $module['properties'][$n] = $v;
                    }
                }
            }

            foreach ($module['properties'] as $property_name => $property_value) {
                if (is_bool($property_value)) {
                    $property_value = $property_value ? 'true' : 'false';
                }

                $line = "{$key_inc} {$name} {$property_name} {$property_value}{$newline}";
                fwrite($config_file_handler, $line);
            }
        }
    }
    fclose($config_file_handler);

    //create output and log files if they don't exists already
    touch($ouput_file_name);

    @unlink($log_file_name);
    touch($log_file_name);

    //prepare geolift command
    $command = str_replace(
            array('{{INPUT_FILE}}', '{{CONFIG_FILE}}', '{{OUTPUT_FILE}}'), array($input_file_name, $config_file_name, $ouput_file_name), GEOLIFT_RUN_COMMAND
    );
    //start geolift in separate process, suppress all outputs and save PID to pid file
    $command = "nohup {$command} > {$log_file_name} 2>&1 ".PHP_EOL;


    $command.= "php " . GEOLIFT_BASE_PATH . DS . "upload" . DS . "inc" . DS . "sendMail.php " . md5(($_REQUEST['user'])) . " " . (($_REQUEST['file'])) . " " . (($_REQUEST['job'])) . " ";
    //write SH for command and run it
    $run_file_name = str_replace('.job', '.sh', $_REQUEST['job']);

    $run_file_name = $file_dir . $run_file_name;
    file_put_contents($run_file_name, $command);

    exec("sh $run_file_name & echo $!", $out, $return_var);

    //$return_var = 0 : exec started command successfully
    //$out[0] : contains PID
    if (0 === $return_var && isset($out[0])) {
        file_put_contents($pid_file_name, $out[0]);
        echo json_encode($success);
        die('');
    }

    die('{"error":"Unknown error occurred."}');
} elseif ($_REQUEST['function'] == 'getOutput') {
    setJsonResponse();
    $success = array('success' => true);

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

    $ouput_file_name = str_replace('.job', '.out', $_REQUEST['job']);

    $file_dir = realpath($base_dir . md5(($_REQUEST['user'])) . DIRECTORY_SEPARATOR . ($_REQUEST['file']) . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $file = $file_dir . ($ouput_file_name);
    header("Content-disposition: attachment; filename=" . $ouput_file_name);
    readfile($file);
} elseif ($_REQUEST['function'] == 'getLogOutput') {
    setJsonResponse();
    $success = array('success' => true);

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

    $ouput_file_name = str_replace('.job', '.log', $_REQUEST['job']);

    $file_dir = realpath($base_dir . md5(($_REQUEST['user'])) . DIRECTORY_SEPARATOR . ($_REQUEST['file']) . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $file = $file_dir . ($ouput_file_name);
    header("Content-disposition: attachment; filename=" . $ouput_file_name);
    readfile($file);
}
?>
