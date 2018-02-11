<?php

namespace APITurnos\Controller;

/**
 * @SWG\Swagger(
 *   title="API Restful para sistema de Gestion de Turnos",
 *   info="Este servicio web fue realizado como parte del TP Final del curso: Desarrollo de Aplicaciones Móviles dictado por el Laboratorio Gugler (UADER, FCyT). ",
 *   schemes={"http"},
 *   host="localhost:80",
 *   basePath="/api-turnos/api/index.php"
 * 
 * 
 * 
 *   
 * )
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * @SWG\Get(
 *   path="/info",
 *   summary="Permite consultar información general sobre la API.",
 *   produces={"application/json"},
 *   @SWG\Response(
 *     response=200,
 *     description="Información general sobre la API"
 *   )
 * )
 */
$app->get('/info', function (Request $request, Response $response) {       
    
    $info = array(
        'swagger_docs' => ROOT_URL - '/api',
        'autor' => 'Emiliano Sangoi',
        'licencia' => 'GPL v3. Para mas informacion ver: https://www.gnu.org/licenses/gpl-3.0.en.html'
    );
    return $response->withJson($info, $st);
    
});

/**
 * @SWG\Get(
 *   path="/swagger/json",
 *   summary="Devuelve la documentacion Swagger en formato JSON de la API. Para conocer mas sobre Swagger ir a https://swagger.io/",
 *   produces={"application/xml", "application/json"},
 *   @SWG\Response(
 *     response=200,
 *     description="Documentacion Swagger en formato JSON. "
 *   ),
 *   @SWG\Response(
 *     response=500,
 *     description="Error interno."
 *   )
 * )
 */
$app->get('/swagger/json', function (Request $request, Response $response) {       
    
    $st = 200;
    $res = array();
    
    try{
        $res = \Swagger\scan('Controller/');
    } catch (\Exception $ex){
        $res[] = "Error. " . $ex->getMessage();
        $st = 500;
    }
    return $response->withJson($res, $st);

});

