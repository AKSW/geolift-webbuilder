<?php

/*
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
 * 
 */
/**
 * thanks to: http://stackoverflow.com/questions/3349753/delete-directory-with-files-in-it/3349792#3349792
 */

function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

function startsWith($haystack, $needle)
{
    return $needle === "" || strpos(trim($haystack), $needle) === 0;
}
function endsWith($haystack, $needle)
{
    return $needle === "" || substr(trim($haystack), -strlen($needle)) === $needle;
}
function setJsonResponse() {
    header("Content-type: application/json");
}
?>
