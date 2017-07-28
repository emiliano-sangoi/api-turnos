<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * @SWG\Get(
 *      path="/horarios/medico/{id}/dia/{fecha_Ymd}",
 *      summary="Busca todos los horarios en los que atendera un medico. Se busca a partir de la fecha y hora actual.",
 *      produces={"application/json"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id del medico",
 *         required=true,
 *         type="integer" 
 *     ),
 *     @SWG\Parameter(
 *         name="fecha_Ymd",
 *         in="path",
 *         description="Fecha del dia a buscar (Formato Y-m-d).",
 *         required=true,
 *         type="string" 
 *     ),
 *      @SWG\Response(
 *          response=500,
 *          description="Error interno"
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="Listado de horarios"
 * 
 *      )
 * )
 */
$app->get('/horarios/medico/{id}/dia/{fecha_Ymd}', function (Request $request, Response $response) {


    $repo = new APITurnos\Repository\TurnoRepository($this->db);
    $id_medico = $request->getAttribute('id');
    $fecha_Ymd = $request->getAttribute('fecha_Ymd');
    $result = $repo->getHorariosAtencion($id_medico, $fecha_Ymd);
    
    if ($result->isOk()) {
        return $response->withJson($result->getData(), 200);
    }

    return $response->withJson(array(), 500);
});




