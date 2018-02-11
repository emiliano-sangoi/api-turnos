<?php

/**
 * Variables de configuracion
 * 
 * Template generador a partir del archivo config.tpl.php
 */

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = "localhost";
$config['db']['user']   = "";
$config['db']['pass']   = "";
$config['db']['dbname'] = "";
$config['host'] = $_SERVER['SERVER_NAME'];
$config['port'] = $_SERVER['SERVER_PORT'];
$config['path'] = '/api-turnos/api/index.php';

/*
 * Ejemplo:
 * http://localhost/api-turnos/api/index.php/swagger/json
 */


define('ROOT_URL', "http://" . $config['host'] . ':' . $config['port'] . $config['path']);
define('SWAGGER_DOCS_URL_JSON', ROOT_URL . "/swagger/json");
