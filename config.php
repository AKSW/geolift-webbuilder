<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
define('GEOLIFT_MAIN_COMMAND', 'java -jar ' . dirname(__FILE__) . DS . 'geolift' . DS . 'geolift.jar');
define('GEOLIFT_SCHEMA_COMMAND', GEOLIFT_MAIN_COMMAND . ' -l');