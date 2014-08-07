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
