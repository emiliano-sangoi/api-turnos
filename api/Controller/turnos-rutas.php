<?php

namespace APITurnos\Controller;

use APITurnos\Repository\TurnoRepository;
use APITurnos\Controller\Util;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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

    $repoTurno = new TurnoRepository($this->db);
    $id_medico = $request->getAttribute('id');
    $from = new \DateTime();
    $res = $repoTurno->getHorariosAtencion($id_medico, $from->getTimestamp());
        
    
    $apiResponse = Util::buildApiResponse($res, $repoTurno->getUltimoError(), 200);
    return $response->withJson( $apiResponse->toArray(), $apiResponse->getStatusCode() );

});


$app->get('/horarios/medico/{id}/dia/{fecha_Ymd}', function (Request $request, Response $response) {


    $repoTurno = new TurnoRepository($this->db);
    $id_medico = $request->getAttribute('id');
    $fecha_Ymd = $request->getAttribute('fecha_Ymd');
    $res = $repoTurno->getHorariosAtencion($id_medico, $fecha_Ymd);
        
    $apiResponse = Util::buildApiResponse($res, $repoTurno->getUltimoError(), 200);
    return $response->withJson( $apiResponse->toArray(), $apiResponse->getStatusCode() );
    
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
 *         name="horario_atencion_id",
 *         in="body",
 *         description="Id del horario de atencion elegido por el usuario.",
 *         required=true,
 *         type="integer" 
 *     ),
 *     @SWG\Parameter(
 *         name="obra_social_id",
 *         in="body",
 *         description="Id de la obra social elegida por el paciente.",
 *         required=false,
 *         type="integer" 
 *     ),
 *      @SWG\Response(
 *          response=500,
 *          description="Error interno o de validacion"
 *      ),
 *      @SWG\Response(
 *          response=201,
 *          description="Turno creado correctamente."
 * 
 *      )
 * )
 */
$app->post('/turno/nuevo', function (Request $request, Response $response) {

    $body = json_decode($request->getBody(), true);
    $repoTurno = new TurnoRepository($this->db);
    $res = $repoTurno->nuevoTurno($body);

    $apiResponse = Util::buildApiResponse($res, $repoTurno->getUltimoError(), 201);

    return $response->withJson( $apiResponse->toArray(), $apiResponse->getStatusCode() );
    
});

/**
 * @SWG\Post(
 *      path="/turno/baja",
 *      summary="Da de baja un nuevo turno",
 *      produces={"application/json"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="body",
 *         description="Id del turno",
 *         required=true,
 *         type="integer" 
 *     ),
 *      @SWG\Response(
 *          response=500,
 *          description="Error interno o de validacion"
 *      ),
 *      @SWG\Response(
 *          response=201,
 *          description="Turno anulado correctamente."
 * 
 *      )
 * )
 */
$app->post('/turno/baja', function (Request $request, Response $response) {

    $body = json_decode($request->getBody(), true);
    $id_turno = isset($body['id']) ? $body['id'] : null;        
    $repoTurno = new TurnoRepository($this->db);
    $res = $repoTurno->bajaTurno($id_turno);

    $apiResponse = Util::buildApiResponse($res, $repoTurno->getUltimoError(), 200);

    return $response->withJson( $apiResponse->toArray(), $apiResponse->getStatusCode() );
    
});


/**
 * @SWG\Get(
 *      path="/turno/paciente/{id}",
 *      summary="Busca todos los turnos para el paciente especificado.",
 *      produces={"application/json"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id del paciente",
 *         required=true,
 *         type="integer" 
 *     ),
 *      @SWG\Response(
 *          response=500,
 *          description="Error interno"
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="Listado de turnos"
 * 
 *      )
 * )
 */
$app->get('/turno/paciente/{id}', function (Request $request, Response $response) {

    $repoTurno = new TurnoRepository($this->db);
    $id_paciente = $request->getAttribute('id');
    $res = $repoTurno->getTurnosPorPaciente($id_paciente);
            
    $apiResponse = Util::buildApiResponse($res, $repoTurno->getUltimoError(), 200);
    return $response->withJson( $apiResponse->toArray(), $apiResponse->getStatusCode() );

});