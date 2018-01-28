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
 *      /var/www/html/api-turnos/api/routes/app-rutas.php, linea 8
 * 
 * ///////////////////////////////////////////////////////////////////////////////////////////////////////////
 * Create Routes
 */

namespace APITurnos;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \PDO;
use Monolog;

require_once '../vendor/autoload.php';
require_once '../config.php';
require_once 'routes/APIResponse.php';
require_once 'Repository/BaseRepository.php';
require_once 'Repository/UsuariosRepository.php';
require_once 'Repository/OSRepository.php';
require_once 'Repository/EspecialidadesRepository.php';
require_once 'Repository/TurnoRepository.php';

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
require_once 'routes/os-rutas.php';
require_once 'routes/especialidades-rutas.php';
require_once 'routes/turnos-rutas.php';




$app->run();