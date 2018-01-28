<?php

namespace APITurnos\Controller;

/*
 * En este archivo se guardan diferentes rutas para acceder a usuarios, medicos y/o pacientes.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * @SWG\Get(
 *   path="/os/{id}",
 *   summary="Listado de todas las obras sociales existentes.",
 *   produces={"application/json"},
 *  @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id de la obra social",
 *         required=false,
 *         type="integer" 
 *     ),
 *   @SWG\Response(
 *     response=200,
 *     description="Listado de obras sociales."
 *   ),
 *   @SWG\Response(
 *     response=500,
 *     description="Error interno."
 *   )
 * )
 */
$app->get('/os[/{id}]', function (Request $request, Response $response) {

    $repoOs = new \APITurnos\Repository\OSRepository($this->db);

    $id_os = $request->getAttribute("id");
    $res = $repoOs->getObrasSociales($id_os);
    
    $apiResponse = Util::buildApiResponse($res, $repoOs->getUltimoError(), 200);
    return $response->withJson( $apiResponse->toArray(), $apiResponse->getStatusCode() );
        
});


/**
 * @SWG\Get(
 *   path="/os/afiliaciones/paciente/{id_paciente}",
 *   summary="Devuelve un listado de las obras sociales a las que se encuentra afiliado un usuario.",
 *   produces={"application/json"},
 *   @SWG\Parameter(
 *         name="id_paciente",
 *         in="path",
 *         description="Id del paciente",
 *         required=true,
 *         type="integer" 
 *     ),
 *   @SWG\Response(
 *     response=200,
 *     description="Listado de obras sociales a las que se encuentra afiliado un usuario."
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
$app->get('/os/afiliaciones/paciente/{id_paciente}', function (Request $request, Response $response) {


    $repo = new APITurnos\Repository\OSRepository($this->db);

    $id_paciente = $request->getAttribute('id_paciente');
    if (is_int((int) $id_paciente)) {
        $result = $repo->getAfiliaciones($id_paciente);
        if ($result->isOk()) {
            return $response->withJson(
                            $result->getData(), count($result->getData()) > 0 ? 200 : 404);
        }
    }

    return $response->withJson('', 500);
});


/**
 * @SWG\Get(
 *   path="/os/convenios/sanatorio/{id}",
 *   summary="Devuelve un listado de todas las obras sociales con las cuales el sanatorio especificado mantiene convenio.",
 *   produces={"application/json"},
 *   @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id del sanatorio",
 *         required=true,
 *         type="integer" 
 *     ),
 *   @SWG\Response(
 *     response=200,
 *     description="Listado de obras sociales."
 *   ),
 *   @SWG\Response(
 *     response=500,
 *     description="Error interno."
 *   )
 * )
 */
$app->get('/os/convenios/sanatorio/{id}', function (Request $request, Response $response) {

    $repo = new APITurnos\Repository\OSRepository($this->db);

    $id = $request->getAttribute('id');
    $result = $repo->getConvenios($id);
    if ($result->isOk()) {
        return $response->withJson($result->getData(), 200);
    }

    return $response->withJson('', 500);
});

