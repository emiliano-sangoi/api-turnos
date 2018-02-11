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

/**
 * IMPORTANTE:
 * 
 * Los valores de host y port tambien deben ser configurados en la anotacion de Swagger, archivo: 
 *  api/Controller/app-rutas.php
 * Lineas 10 y 11
 * 
 * De lo contrario se estarn generando mal las rutas que se utilizaran en Swagger UI
 */
$config['host'] = "localhost";
$config['port'] = 80;
$config['basePath'] = '/api-turnos/api/index.php';


/*
 * Ejemplo:
 * http://localhost/api-turnos/api/index.php/swagger/json
 */


define('ROOT_URL', "http://" . $config['host'] . ':' . $config['port'] . $config['basePath']);
define('SWAGGER_DOCS_URL_JSON', ROOT_URL . "/swagger/json");
