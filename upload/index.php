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
error_reporting(E_ALL );//| E_STRICT);
$base_dir = "files".DIRECTORY_SEPARATOR;
include_once './inc/functions.php';
include_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'config.php';

if (isset($_FILES["files"])) {//upload new File
    include 'inc/upload.php';
} elseif (isset($_REQUEST["url"])) {//save new url
    include 'inc/url.php';
} elseif (isset($_REQUEST["function"])) {//all other functions
    include 'inc/misc.php';
} else {
    die("{'error':'Don\'t understand what you want. \n\rBest, \n\rYour Server'}");
}
?>