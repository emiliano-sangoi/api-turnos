<?php

/**
 * Variables de configuracion
 */

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = "localhost";
$config['db']['user']   = "root";
$config['db']['pass']   = "";
$config['db']['dbname'] = "";

define('ROOT_URL', "http://" . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']);
define('SWAGGER_DOCS_URL_JSON', ROOT_URL . "/api/swagger/json");
