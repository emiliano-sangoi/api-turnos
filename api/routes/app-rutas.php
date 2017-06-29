<?php


/**
 * @SWG\Swagger(
 *   title="API Restful para sistema de Gestion de Turnos",
 *   description="Este servicio web fue realizado como parte del TP Final del curso: Desarrollo de Aplicaciones Móviles dictado por el Laboratorio Gugler (UADER, FCyT). ",
 *   schemes={"http"},
 *   host="127.0.0.1:8080",
 *   basePath="/api"
 * )
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/hello/{name}', function (Request $request, Response $response) {       
    
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");
    
    $this->logger->addInfo("$name escribio un mensaje.");

    return $response;
});


$app->get('/swagger/json', function (Request $request, Response $response) {       
    
    $swagger = \Swagger\scan('routes/');
    
    return $response->withJson($swagger, 201);

});

