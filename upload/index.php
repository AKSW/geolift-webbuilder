<?php

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