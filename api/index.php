<?php

/**
 * Slim PHP 
 * 
 * https://www.slimframework.com/docs/tutorial/first-app.html
 * 
 * ///////////////////////////////////////////////////////////////////////////////////////////////////////////
 * API | SET UP & RUN
 * ///////////////////////////////////////////////////////////////////////////////////////////////////////////
 * Instrucciones para utilizar la API:
 * 
 * - Conocer la IP publica:
 *      $ hostname -I
 * - Correr un servidor built-in en la ip publica con un puerto que se encuentre libre:
 *      $ php -S 192.168.1.108:8000
 * - Actualizar la IP y puerto en la app movil.
 * - Actualizar la IP y el puerto en el script que genera el JSON de la documentacion Swagger: 
 *      /var/www/html/api-turnos/api/Controller/app-rutas.php, linea 8
 * 
 * ///////////////////////////////////////////////////////////////////////////////////////////////////////////
 * Create Routes
 */

namespace APITurnos;

use \PDO;
use Monolog;

require_once '../vendor/autoload.php';
require_once '../config.php';


$app = new \Slim\App(array("settings" => $config));

$container = $app->getContainer();

// ============================================================================================
// DEPENDENCIAS 

//Agregar la dependencia de Monolog:
$container['logger'] = function($c){
    $logger = new Monolog\Logger('api-turnos_logger');
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

require_once 'Controller/APIResponse.php';
require_once 'Controller/Util.php';
require_once 'Controller/app-rutas.php';
require_once 'Controller/usuarios-rutas.php';
require_once 'Controller/os-rutas.php';
require_once 'Controller/especialidades-rutas.php';
require_once 'Controller/turnos-rutas.php';




$app->run();