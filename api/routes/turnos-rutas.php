<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * @SWG\Get(
 *      path="/horarios/medico/{id}",
 *      summary="Busca todos los horarios en los que atendera un medico. Se busca a partir de la fecha y hora actual.",
 *      produces={"application/json"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id del medico",
 *         required=true,
 *         type="integer" 
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
$app->get('/horarios/medico/{id}', function (Request $request, Response $response) {


    $repo = new APITurnos\Repository\TurnoRepository($this->db);
    $id_medico = $request->getAttribute('id');
    $from = new DateTime();
    $result = $repo->getHorariosAtencion($id_medico, $from->getTimestamp());
    
    if ($result->isOk()) {
        return $response->withJson($result->getData(), 200);
    }

    return $response->withJson(array(), 500);
});

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



/**
 * @SWG\Post(
 *      path="/turno/nuevo",
 *      summary="Crea un nuevo turno",
 *      produces={"application/json"},
 *     @SWG\Parameter(
 *         name="paciente_id",
 *         in="body",
 *         description="Id del paciente",
 *         required=true,
 *         type="integer" 
 *     ),
 *     @SWG\Parameter(
 *         name="Id de la obra social",
 *         in="path",
 *         description="Obra social del paciente.",
 *         required=false,
 *         type="integer" 
 *     ),
 *     @SWG\Parameter(
 *         name="Id de la obra social",
 *         in="path",
 *         description="Fecha del dia a buscar (Formato Y-m-d).",
 *         required=false,
 *         type="integer" 
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
$app->post('/turno/nuevo', function (Request $request, Response $response) {

    $body = json_decode($request->getBody(), true);
    $repoTurno = new APITurnos\Repository\TurnoRepository($this->db);
    $res = $repoTurno->nuevoTurno($body);
    
    $apiResponse = new APITurnos\Routes\APIResponse();
    
    if(!$res){
        $apiResponse->setMsg($repoTurno->getUltimoError());        
    }else{
        $apiResponse->setOk(true);
        $apiResponse->setStatusCode(200);
        $apiResponse->setData($res);
    }

    
    return $response->withJson( $apiResponse->toArray(), $apiResponse->getStatusCode() );
    
});




