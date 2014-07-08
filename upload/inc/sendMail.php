<?php

/*
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
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


$json = json_decode(file_get_contents($user_dir . "user.json"));
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'gurkware.de';                          // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                              // Enable SMTP authentication
$mail->port = 25;
$mail->Username = 'mpi@gurkware.de';                 // SMTP username
$mail->Password = 'uvCqrSrrdQ53Y2jx';                           // SMTP password

$mail->From = 'Query@GeoLift.morulia.de';
$mail->FromName = 'GeoLiftQuery-Mailer';
$mail->addAddress($json->mail);               // Name is optional

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
$log_path = $user_dir . $argv[2] . DIRECTORY_SEPARATOR . str_replace('.job', '.log', $argv[3]);

$out_path = $user_dir . $argv[2] . DIRECTORY_SEPARATOR . str_replace('.job', '.out', $argv[3]);

$mail->addAttachment($log_path);    // Optional name
$mail->addAttachment($out_path);    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Your GeoLift-Query ('.$job->name.') is done';
$mail->Body = 'Hey there,<br>I will inform you that your Query <b>"'.$job->name.'"</b> is done. <br>The Log ans your Outpufile is located as Attachment and can be downloaded at the Website.';
$mail->AltBody = 'Hey there,
    I will inform you that your Query 
    "'.$job->name.'"
    is done. 
    
The Log ans your Outpufile is located as Attachment and can be downloaded at the Website.';

if (!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
    $job['state'] = 'done';
    file_put_contents($job_path, json_encode($job));
}
?>
