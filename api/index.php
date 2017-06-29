<?php

/**
 * Slim PHP 
 * 
 * https://www.slimframework.com/docs/tutorial/first-app.html
 * 
 * Create Routes
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../config.php';

$app = new \Slim\App(array("settings" => $config));

$container = $app->getContainer();

// ============================================================================================
// DEPENDENCIAS 

//Agregar la dependencia de Monolog:
$container['logger'] = function($c){
    $logger = new Monolog\Logger('my_logger');
    $file_handler = new Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
            
    
};

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};


// FIN DEPENDENCIAS 
// ============================================================================================

require_once 'routes/app-rutas.php';
require_once 'routes/usuarios-rutas.php';




$app->run();