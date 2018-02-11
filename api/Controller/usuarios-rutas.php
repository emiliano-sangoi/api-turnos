<?php

namespace APITurnos\Controller;

/*
 * En este archivo se guardan diferentes rutas para acceder a usuarios, medicos y/o pacientes.
 */

use APITurnos\Repository\UsuariosRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @SWG\Post(
 *     path="/login",
 *     operationId="login",
 *     description="Permite determinar si el usuario y contraseña ingresado existen en la base de datos.",
 *     produces={"application/json"},
 *     @SWG\Parameter(
 *         name="username",
 *         in="body",
 *         description="Nombre de usuario",
 *         required=true,
 *         type="string",
 *         @SWG\Schema(type="string")
 *     ),
 *     @SWG\Parameter(
 *         name="password",
 *         in="body",
 *         description="Contraseña",
 *         required=true,
 *         type="string",
 *         @SWG\Schema(type="string")
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="Usuario y/o contraseña son validos."
 *     ),
 *     @SWG\Response(
 *         response=401,
 *         description="Usuario y/o contraseña son incorrectos.",
 *     ),
 *     @SWG\Response(
 *         response="default",
 *         description="unexpected error"
 *     )
 * )
 */
$app->post('/login', function (Request $request, Response $response) {

    $body = json_decode($request->getBody(), true);
    $repoUsuarios = new UsuariosRepository($this->db);

    $res = $repoUsuarios->getUserData($body["username"], $body["password"]);
    
    $apiResponse = Util::buildApiResponse($res, $repoUsuarios->getUltimoError(), 200);    
    return $response->withJson( $apiResponse->toArray(), $apiResponse->getStatusCode() );
    
});

/**
 * @SWG\Get(
 *      path="/pacientes/{id}",
 *      summary="Busca el paciente con el id indicado. Si no se especifica id, devuelve todos los pacientes",
 *      produces={"application/json"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id del paciente",
 *         required=false,
 *         type="integer",
 *         default="" 
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
$app->get('/pacientes/[{id}]', function (Request $request, Response $response) {
    
    $repoUsuarios = new UsuariosRepository($this->db);
    $id_paciente = $request->getAttribute('id');
    $res = $repoUsuarios->getPacientes($id_paciente);
    
    $apiResponse = Util::buildApiResponse($res, $repoUsuarios->getUltimoError(), 200);    
    return $response->withJson( $apiResponse->toArray(), $apiResponse->getStatusCode() );
    
});

/**
 * @SWG\Get(
 *      path="/medicos/{id}",
 *      summary="Busca el medico con el id indicado",
 *      produces={"application/json"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id del medico",
 *         required=false,
 *         type="integer",
 *         default="" 
 *     ),
 *      @SWG\Response(
 *          response=500,
 *          description="Error interno"
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="Devuelve informacion del medico"
 * 
 *      )
 * )
 */
$app->get('/medicos/[{id}]', function (Request $request, Response $response) {    
        
    $repoUsuarios = new UsuariosRepository($this->db);
    $id_medico = $request->getAttribute('id');
    $res = $repoUsuarios->getMedicos($id_medico);
    
    $apiResponse = Util::buildApiResponse($res, $repoUsuarios->getUltimoError(), 200);    
    return $response->withJson( $apiResponse->toArray(), $apiResponse->getStatusCode() );
    
});

/**
 * @SWG\Get(
 *      path="/medicos/especialidad/{id}",
 *      summary="Busca todos los medicos con cierta especialidad",
 *      produces={"application/json"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id de la especialidad",
 *         required=true,
 *         type="integer" 
 *     ),
 *      @SWG\Response(
 *          response=500,
 *          description="Error interno"
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="Listado de medicos"
 * 
 *      )
 * )
 */
$app->get('/medicos/especialidad/{id}', function (Request $request, Response $response) {
    
    $repoMedicos = new UsuariosRepository($this->db);
    $id_medico = $request->getAttribute('id');
    $res = $repoMedicos->getMedicosConEspecialidad($id_medico);
    
    $apiResponse = Util::buildApiResponse($res, $repoMedicos->getUltimoError(), 200);    
    return $response->withJson( $apiResponse->toArray(), $apiResponse->getStatusCode() );    
    
});
