<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
define('GEOLIFT_MAIN_COMMAND', 'java -jar ' . dirname(__FILE__) . DS . 'geolift' . DS . 'geolift.jar');
define('GEOLIFT_SCHEMA_COMMAND', GEOLIFT_MAIN_COMMAND . ' -l');

/*
 * NOTE: {{PLACEHOLDERS}} are replaced in upload/inc/misc.php
 *
 * available placeholders: 
 *	INPUT_FILE 	- the path and name of the input file
 *	CONFIG_FILE	- the path and name of the config file
 *	OUTPUT_FILE	- the path and name of the output file 
 */
define('GEOLIFT_RUN_COMMAND', GEOLIFT_MAIN_COMMAND . ' -i {{INPUT_FILE}} -c {{CONFIG_FILE}} -o {{OUTPUT_FILE}}');