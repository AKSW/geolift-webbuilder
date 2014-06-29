<?php

/*
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
 * 
 */
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
