<?php

/*
 * En este archivo se guardan diferentes rutas para acceder a usuarios, medicos y/o pacientes.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Devuelve todos los pacientes existentes.
 */
/**
 * @SWG\Get(
 *   path="/pacientes",
 *   summary="Listado de todos los pacientes existentes",
 *   produces={"application/xml", "application/json"},
 *   @SWG\Response(
 *     response=200,
 *     description="Listado de todos los pacientes existentes"
 *   ),
 *   @SWG\Response(
 *     response=500,
 *     description="Error interno."
 *   )
 * )
 */
$app->get('/pacientes', function (Request $request, Response $response) {

    $pdo = $this->db;
    $result = $pdo->query("SELECT * FROM pacientes p INNER JOIN usuarios u ON u.id_usuario = p.usuario_id");
    $pacientes = $result->fetchAll();

    /* return $response->wiwithStatus(200)
      ->withHeader('Content-Type', 'application/json')
      ->getBody()->write(json_encode($obras_sociales)); */
    return $response->withJson($pacientes, 200);
});

/**
 * @SWG\Get(
 *      path="/pacientes/{id}",
 *      summary="Busca el paciente con el id indicado",
 *      produces={"application/xml", "application/json"},
 *      @SWG\Response(
 *          response=200,
 *          description="Listado de todos los pacientes existentes"
 *      ),
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id del paciente",
 *         required=false,
 *         type="integer" 
 *     ),
 *      @SWG\Response(
 *          response=500,
 *          description="Error interno"
 *      ),
 *      @SWG\Response(
 *          response=404,
 *          description="No se encontro paciente solicitado."
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="Se encontro al paciente solicitado."
 * 
 *      )    
 * )
 */
$app->get('/pacientes/{id}', function (Request $request, Response $response) {

    $paciente = array();
    $st_code = 404;
    
    $id = $request->getAttribute('id');
    if ($id) {
        $sql = "SELECT * FROM pacientes p INNER JOIN usuarios u ON u.id_usuario = p.usuario_id  WHERE p.id_paciente = $id";
        $pdo = $this->db;
        $result = $pdo->query($sql);
        $paciente = $result->fetchAll();
        $st_code = 200;
    }

    return $response->withJson($paciente, $st_code);
});



$app->get('/medicos', function (Request $request, Response $response) {

    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    $this->logger->addInfo("$name escribio un mensaje.");

    return $response;
});

$app->get('/medicos/id', function (Request $request, Response $response) {

    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    $this->logger->addInfo("$name escribio un mensaje.");

    return $response;
});
