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

    //create config file
    $file_dir = realpath($base_dir . md5(($_REQUEST['user'])) . DIRECTORY_SEPARATOR . ($_REQUEST['file']) . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $file = $file_dir . ($newFilename);

    $input_file_name = $file_dir . 'input.ttl';

    $config_file_name = str_replace('.job', '.ttl', $newFilename);
    $config_file_name = $file_dir . $config_file_name;
    $config_file_handler = @fopen($config_file_name, 'wb');

    $ouput_file_name = str_replace('.job', '.out', $newFilename);
    $ouput_file_name = $file_dir . $ouput_file_name;

    $json = file_get_contents($file);
    $query = json_decode($json, true);
    
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

    $user_file_dir = realpath($base_dir . md5($_REQUEST['user'])) . DS . ($_REQUEST['file']) . '.json';

    $Filejson = file_get_contents($user_file_dir);
    $Filequery = json_decode($Filejson);

    if (!$Filequery->url) {
        if (is_dir($file) || !is_file($file)) {
            die('{"error":"Job not found."}');
        }
        if (is_dir($input_file_name) || !is_file($input_file_name)) {
            die('{"error":"Job not found."}');
        }

        $input_file_name = $input_file_name;

    } else {
        $input_file_name = $Filequery->filename;
    }

    $config_elements = $query['job'];
    
    $dataset_counter = 1;
    $parameter_counter = 1;

    $end = null;
    $next = array();

    $config_data = array();
    $datasets = array();
    $parameters = array();

    foreach ($config_elements as $key => $element) {
        if ('start' == $element['name']) {
            $name = ':d' . $dataset_counter;
            $dataset_counter++;

            $next_id = $element['next'];
            $next[] = $next_id;
            
            $config_data[$next_id] = array(
                ':hasInput' => array(':d1')
            );
            if(!$Filequery->url) {
                $datasets[':d1'] = array(
                    'a' => ':Dataset',
                    ':inputFile' => '"' . $input_file_name . '"'
                );
            } else {
                $datasets[':d1'] = array(
                    'a' => ':Dataset',
                    ':hasUri' => '<' . $input_file_name . '>',
                    ':FromEndPoint' => '<' . $Filequery->endpoint . '>'
                );
            }

            break;
        }
    }

    foreach ($config_elements as $key => $element) {
        if ('end' == $element['name']) {
            $end = $element;
            break;
        }
    }

    while(!empty($next)) {
        $next_id = array_shift($next);

        foreach ($config_elements as $key => $element) {
            if($element['id'] == $next_id && $end['id'] !== $next_id) {
                
                $parameter_type_prefix = ucfirst($element['name']);
                $config_data_type_prefix = ucfirst($element['name']);
                $config_data_label_prefix = ucfirst($element['name']);
                if('nlp' == $element['name']) {
                    $parameter_type_prefix = strtoupper($element['name']);
                    $config_data_type_prefix = strtoupper($element['name']);
                    $config_data_label_prefix = strtoupper($element['name']);
                }

                $parameter_name = ':' . $element['name'] . ucfirst($element['type']) . 'Parameter';
                $parameter_type = ':' . ucfirst($element['type']) . 'Parameter, ' . ':' . $parameter_type_prefix . ucfirst($element['type']) . 'Parameter';
                $config_data_name = ':' . $element['name'] . $element['id'];
                $config_data_type = ':' . ucfirst($element['type']) . ', ' . ':' . $config_data_type_prefix . ucfirst($element['type']);
                $config_data_label = '"' . $config_data_label_prefix . ' ' . ucfirst($element['type']) . '"';

                $next_element_ids = array();
                if(is_array($element['next'])) {
                    $next_element_ids = $element['next'];
                } else if(null != $element['next']) {
                    $next_element_ids[] = $element['next'];
                }

                if(!isset($config_data[$next_id])) {
                    $config_data[$next_id] = array();
                }

                if(!isset($config_data[$next_id]['name'])) {
                    $config_data[$next_id]['name'] = $config_data_name;
                }

                if(!isset($config_data[$next_id]['a'])) {
                    $config_data[$next_id]['a'] = $config_data_type;
                }

                if(!isset($config_data[$next_id]['rdfs:label'])) {
                    $config_data[$next_id]['rdfs:label'] = $config_data_label;
                }

                foreach ($next_element_ids as $next_element_id) {
                    $name = ':d' . $dataset_counter;
                    $dataset_counter++;

                    if($next_element_id != $end['id']) {
                        if(!isset($config_data[$next_element_id])) {
                            $config_data[$next_element_id] = array();
                        }

                        if(!isset($config_data[$next_element_id][':hasInput'])) {
                            $config_data[$next_element_id][':hasInput'] = array();
                        }

                        $config_data[$next_element_id][':hasInput'][] = $name;

                        $datasets[$name] = array(
                            'a' => ':Dataset'
                        );
                    }

                    if(!in_array($next_element_id, $next)) {
                        $next[] = $next_element_id;
                    }

                    if(!isset($config_data[$next_id][':hasOutput'])) {
                        $config_data[$next_id][':hasOutput'] = array();
                    }

                    $config_data[$next_id][':hasOutput'][] = $name;

                    if($end['id'] == $next_element_id) {
                        $datasets[$name] = array(
                            'a' => ':Dataset',
                            ':outputFile' => '"' . $ouput_file_name . '"',
                            ':outputFormat' => '"Turtle"'
                        );
                    } else {
                        $datasets[$name] = array(
                            'a' => ':Dataset'
                        );
                    }
                }

                if(isset($element['properties'])) {
                    if(!isset($config_data[$next_id][':hasParameter'])) {
                        $config_data[$next_id][':hasParameter'] = array();
                    }

                    if('dereferencing' == $element['name']) {
                        if(isset($element['properties']['predicate'], $element['properties']['predicate value'])) {
                            $parameter_key = $element['properties']['predicate'];
                            $parameter_value = $element['properties']['predicate value'];

                            $element['properties'][$parameter_key] = $parameter_value;
                        }
                        unset($element['properties']['predicate']);
                        unset($element['properties']['predicate value']);
                    }

                    foreach ($element['properties'] as $parameter_key => $parameter_value) {
                        //simple match against a URI
                        if(!preg_match('@^\s*([^:]+://.*)$@', $parameter_value, $matches) && false !== strpos($parameter_value, ':')) {
                            $parameter_value = trim($parameter_value);
                            $parameter['a'] = $parameter_type;
                            $parameter[':hasKey'] = '"' . $parameter_key . '"';
                            $parameter[':hasValue'] = $parameter_value;
                        } else if (is_bool($parameter_value)) {
                            $parameter_value = ($parameter_value ? 'true' : 'false');
                            
                            $parameter_value = trim($parameter_value);
                            $parameter['a'] = $parameter_type;
                            $parameter[':hasKey'] = '"' . $parameter_key . '"';
                            $parameter[':hasValue'] = $parameter_value;
                        } else {
                            $parameter_value = trim($parameter_value);
                            $parameter['a'] = $parameter_type;
                            $parameter[':hasKey'] = '"' . $parameter_key . '"';
                            $parameter[':hasValue'] = '"' . $parameter_value . '"';
                        }
                        
                        $config_data[$next_id][':hasParameter'][] = $parameter_name . $parameter_counter;
                        $parameters[$parameter_name . $parameter_counter] = $parameter;
                        $parameter_counter++;
                    }
                }
            }
        }
    }

    //create config file
    //write header
    $newline = PHP_EOL;
    $lines = array(
        "@prefix : <http://geoknow.org/specsontology/> .{$newline}",
        "@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .{$newline}",
        "@prefix geo: <http://w3.org/2003/01/geo/wgs84_pos#> .{$newline}"
    );
    foreach ($lines as $line) {
        fwrite($config_file_handler, $line);
    }

    //write datasets
    foreach ($datasets as $name => $dataset) {
        $i = 0;
        foreach ($dataset as $key => $value) {
            $separator = ';';
            $pre = '';
            if($i >= count($dataset) - 1) {
                $separator = '.';
            }

            if($i == 0) {
                $pre = $name;
            }

            $line = "{$pre} {$key} {$value} {$separator}{$newline}";
            fwrite($config_file_handler, $line);

            $i++;
        }
    }

    //write parameters
    foreach ($parameters as $name => $parameter) {
        $i = 0;
        foreach ($parameter as $key => $value) {
            $separator = ';';
            $pre = '';
            if($i >= count($parameter) - 1) {
                $separator = '.';
            }

            if($i == 0) {
                $pre = $name;
            }

            $line = "{$pre} {$key} {$value} {$separator}{$newline}";
            fwrite($config_file_handler, $line);

            $i++;
        }
    }

    //write modules and operators
    foreach ($config_data as $config_element) {
        $name = $config_element['name'];
        unset($config_element['name']);
        $i = 0;
        foreach ($config_element as $key => $value) {
            $separator = ';';
            $pre = '';
            if($i >= count($config_element) - 1) {
                $separator = '.';
            }

            if($i == 0) {
                $pre = $name;
            }

            if(is_array($value)) {
                $value = implode(', ', $value);
            }

            $line = "{$pre} {$key} {$value} {$separator}{$newline}";
            fwrite($config_file_handler, $line);

            $i++;
        }
    }
    fclose($config_file_handler);

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

    $input_file_name = $file_dir . 'input.ttl';

    $config_file_name = str_replace('.job', '.ttl', $_REQUEST['job']);
    $config_file_name = $file_dir . $config_file_name;

    $ouput_file_name = str_replace('.job', '.out', $_REQUEST['job']);
    $ouput_file_name = $file_dir . $ouput_file_name;

    $pid_file_name = str_replace('.job', '.pid', $_REQUEST['job']);
    $pid_file_name = $file_dir . $pid_file_name;

    $log_file_name = str_replace('.job', '.log', $_REQUEST['job']);
    $log_file_name = $file_dir . $log_file_name;

    $json = file_get_contents($file);
    $query = json_decode($json, true);
    if (!$query) {
        die('{"error": "Job config can not be parsed."}');
    }
    if (!isset($query['job'])) {
        die('{"error": "Job config can not be parsed."}');
    }
    if (!$query['job']) {
        die('{"error": "Job config can not be parsed."}');
    }

    $user_file_dir = realpath($base_dir . md5($_REQUEST['user'])) . DS . ($_REQUEST['file']) . '.json';

    $Filejson = file_get_contents($user_file_dir);
    $Filequery = json_decode($Filejson);
    $query['state'] = 'running';
    file_put_contents($file, json_encode($query));

    if (!$Filequery->url) {
        if (is_dir($file) || !is_file($file)) {
            die('{"error":"Job not foundd."}');
        }
        if (is_dir($input_file_name) || !is_file($input_file_name)) {
            die('{"error":"Job not found."}');
        }

        $input_file_name = $input_file_name;

    } else {
        $input_file_name = $Filequery->filename;
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
    if (is_file($pid_file_name) && !is_writable($pid_file_name)) {
        die('{"error":"PID file can not be written."}');
    }
     if (is_dir($config_file_name)) {
        die('{"error":"Config file is not readable."}');
    }
    if (is_file($config_file_name) && !is_readable($config_file_name)) {
        die('{"error":"Config file is not readable."}');
    }

    //create output and log files if they don't exists already
    touch($ouput_file_name);

    @unlink($log_file_name);
    touch($log_file_name);

    //prepare geolift command
    $command = str_replace('{{CONFIG_FILE}}', $config_file_name, GEOLIFT_RUN_COMMAND);
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
} elseif ($_REQUEST['function'] == 'getConfig') {
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

    $config_file_name = str_replace('.job', '.ttl', $_REQUEST['job']);
    $file_dir = realpath($base_dir . md5(($_REQUEST['user'])) . DIRECTORY_SEPARATOR . ($_REQUEST['file']) . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $file = $file_dir . ($config_file_name);

    if (!file_exists($file)) {
        die('{"error":"Config file could not be found"}');
    }

    header("Content-disposition: attachment; filename=" . $config_file_name);
    readfile($file);
}
?>