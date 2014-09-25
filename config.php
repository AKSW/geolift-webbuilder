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

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
define('GEOLIFT_FILE_ENCODING', 'UTF-8');
define('GEOLIFT_MAIN_COMMAND', 'java -Dfile.encoding=' . GEOLIFT_FILE_ENCODING . ' -jar ' . dirname(__FILE__) . DS . 'geolift' . DS . 'geolift.jar');
define('GEOLIFT_SCHEMA_COMMAND', GEOLIFT_MAIN_COMMAND . ' -l');
define('GEOLIFT_BASE_PATH', dirname(__FILE__) );

/*
 * NOTE: {{PLACEHOLDERS}} are replaced in upload/inc/misc.php
 *
 * available placeholders: 
 *	CONFIG_FILE	- the path and name of the config file
 */
define('GEOLIFT_RUN_COMMAND', GEOLIFT_MAIN_COMMAND . ' {{CONFIG_FILE}}');


define('MAIL_HOST','');
define('MAIL_PORT',25);
define('MAIL_USERNAME','');
define('MAIL_PASSWORD','');
define('MAIL_FROM','Query@GeoLift.de');
define('MAIL_FROM_NAME','GeoLiftQuery-Mailer');

