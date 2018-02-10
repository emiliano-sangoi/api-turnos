<?php

namespace APITurnos\Controller;

/*
 * En este archivo se guardan diferentes rutas para acceder a usuarios, medicos y/o pacientes.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \APITurnos\Repository\EspecialidadesRepository;

/**
 * @SWG\Get(
 *   path="/especialidades/{id}",
 *   summary="Devuelve un listado de todas las especialidades medicas existentes.",
 *   produces={"application/json"},
 *   @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id de la especialidad",
 *         required=false,
 *         type="integer" ,
 *         default=""
 *     ),
 *   @SWG\Response(
 *     response=200,
 *     description="Listado de especialidades medicas."
 *   ),
 *   @SWG\Response(
 *          response=404,
 *          description="No se encontro ningun registro."
 *   ),
 *   @SWG\Response(
 *     response=500,
 *     description="Error interno."
 *   )
 * )
 */
$app->get('/especialidades/[{id}]', function (Request $request, Response $response) {

    $repoEsp = new EspecialidadesRepository($this->db);    
    $id_esp = $request->getAttribute('id');
    $res = $repoEsp->getEspecialidades($id_esp, true);
    
    $apiResponse = Util::buildApiResponse($res, $repoEsp->getUltimoError(), 200);    
    return $response->withJson( $apiResponse->toArray(), $apiResponse->getStatusCode() );

});


