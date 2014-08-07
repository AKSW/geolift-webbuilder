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
include_once dirname(__FILE__) . '/../../config.php';
if (!isset($argv[1]))
    die('{"error":"user-field required"}');
if (trim($argv[1]) == "")
    die('{"error":"user-field required"}');
if (!isset($argv[2]))
    die('{"error":"file-field required"}');
if (trim($argv[2]) == "")
    die('{"error":"file-field required"}');
if (!isset($argv[3]))
    die('{"error":"job-field required"}');
if (trim($argv[3]) == "")
    die('{"error":"job-field required"}');
$user_dir = GEOLIFT_BASE_PATH . DS . 'upload' . DS . 'files' . DS . $argv[1] . DIRECTORY_SEPARATOR;
$job_path = $user_dir . $argv[2] . DIRECTORY_SEPARATOR . $argv[3];
$job = json_decode(file_get_contents($job_path));

$job->state = 'done';
file_put_contents($job_path, json_encode($job));

$json = json_decode(file_get_contents($user_dir . "user.json"));
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = MAIL_HOST;                          // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                              // Enable SMTP authentication
$mail->port = MAIL_PORT;
$mail->Username = MAIL_USERNAME;                 // SMTP username
$mail->Password = MAIL_PASSWORD;                           // SMTP password

$mail->From = MAIL_FROM;
$mail->FromName = MAIL_FROM_NAME;
$mail->addAddress($json->mail);               // Name is optional

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
$log_path = $user_dir . $argv[2] . DIRECTORY_SEPARATOR . str_replace('.job', '.log', $argv[3]);

$out_path = $user_dir . $argv[2] . DIRECTORY_SEPARATOR . str_replace('.job', '.out', $argv[3]);

$mail->addAttachment($log_path);    // Optional name
$mail->addAttachment($out_path);    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Your GeoLift-Query (' . $job->name . ') is done';
$mail->Body = 'Hey there,<br>I will inform you that your Query <b>"' . $job->name . '"</b> is done. <br>The Log ans your Outpufile is located as Attachment and can be downloaded at the Website.';
$mail->AltBody = 'Hey there,
    I will inform you that your Query 
    "' . $job->name . '"
    is done. 
    
The Log ans your Outpufile is located as Attachment and can be downloaded at the Website.';

if (!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>
