<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
// Unpacking the archives takes quite a lot of memory, 4G should be more than enough tho
ini_set('memory_limit','4G');

// TODO: Custom autoloader would be a lot nicer
require_once 'classmap.php';

if(version_compare(PHP_VERSION, '8.0.0', '<')) {
    exit("PHP 8 is required to run this script!\n");
}

if(!function_exists("json_decode")) {
    exit("Your PHP installation does not include the json extension which is necessary for reading and writing the configuration.\n");
}

if(!class_exists('PharData')) {
    exit('The Phar-Extension is missing on your PHP installation. This is required to unpack the archives downloaded from github.');
}

$carbuncle = new Realitaetsverlust\Carbuncle\Carbuncle($argv);