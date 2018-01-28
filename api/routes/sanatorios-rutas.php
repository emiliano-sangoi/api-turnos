<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/sanatorios/convenios/os/', function (Request $request, Response $response) {       
    
    $pdo = $this->db;
    $result = $pdo->query("SELECT * FROM obras_sociales");
    $obras_sociales = $result->fetchAll();
        
    /*return $response->wiwithStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->getBody()->write(json_encode($obras_sociales));*/
    return $response->withJson($obras_sociales, 201);
    
    

});