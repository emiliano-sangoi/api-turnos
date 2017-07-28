<?php

/*
 * En este archivo se guardan diferentes rutas para acceder a usuarios, medicos y/o pacientes.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * @SWG\Get(
 *   path="/especialidades",
 *   summary="Devuelve un listado de todas las especialidades medicas existentes.",
 *   produces={"application/json"},
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
$app->get('/especialidades', function (Request $request, Response $response) {


    $repo = new APITurnos\Repository\EspecialidadesRepository($this->db);

    $result = $repo->getEspecialidades();
    if ($result->isOk()) {
        return $response->withJson(
                        $result->getData(), count($result->getData()) > 0 ? 200 : 404);
    }


    return $response->withJson('', 500);
});


