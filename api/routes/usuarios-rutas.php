<?php

/*
 * En este archivo se guardan diferentes rutas para acceder a usuarios, medicos y/o pacientes.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

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
 *         required=true
 *     ),
 *     @SWG\Parameter(
 *         name="password",
 *         in="body",
 *         description="Contraseña",
 *         required=true
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
    $repo = new APITurnos\Repository\UsuariosRepository($this->db);

    $result = $repo->getUserData($body["username"], $body["password"]);
    global $container;
    $container['logger']->info("lala");    
    if ($result->isOk()) {
        $response_body = $result->getData();
        $user = array_pop($response_body);
	if($user){
            return $response->withJson($user, 200);	
	}else{
            return $response->withJson(array("msg" => "No se encontro el ningun usuario con las credenciales suministradas"), 401);	
	}
    }
    return $response->withJson("", 500);
    
});



/**
 * @SWG\Get(
 *   path="/pacientes",
 *   summary="Listado de todos los pacientes existentes",
 *   produces={"application/json"},
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

    $repo = new APITurnos\Repository\UsuariosRepository($this->db);

    $result = $repo->getPacientes();
    if ($result['ok']) {
        return $response->withJson($result['data'], 200);
    }

    return $response->withJson(array(), 405);
});

/**
 * @SWG\Get(
 *      path="/pacientes/{id}",
 *      summary="Busca el paciente con el id indicado",
 *      produces={"application/json"},
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

    $repo = new APITurnos\Repository\UsuariosRepository($this->db);

    $id = $request->getAttribute('id');
    $result = $repo->getPacientes($id);
    if ($result['ok']) {
        return $response->withJson($result['data'], 200);
    }

    return $response->withJson(array(), 405);
    
});


/**
 * @SWG\Get(
 *   path="/medicos",
 *   summary="Devuelve un listado de todos los medicos existentes",
 *   produces={"application/json"},
 *   @SWG\Response(
 *     response=200,
 *     description="Listado de todos los medicos existentes"
 *   ),
 *   @SWG\Response(
 *     response=500,
 *     description="Error interno."
 *   )
 * )
 */
$app->get('/medicos', function (Request $request, Response $response) {
    
    $repo = new APITurnos\Repository\UsuariosRepository($this->db);

    $result = $repo->getMedicos();
    if ($result['ok']) {
        return $response->withJson($result['data'], 200);
    }

    return $response->withJson(array(), 405);

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
 *         type="integer" 
 *     ),
 *      @SWG\Response(
 *          response=500,
 *          description="Error interno"
 *      ),
 *      @SWG\Response(
 *          response=404,
 *          description="No se encontro ningún medico con el id pasado como parametro."
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="Devuelve informacion del medico"
 * 
 *      )
 * )
 */
$app->get('/medicos/{id}', function (Request $request, Response $response) {
    
    $repo = new APITurnos\Repository\UsuariosRepository($this->db);

    $id = $request->getAttribute('id');
    $result = $repo->getMedicos($id);
    if ($result['ok']) {
        return $response->withJson($result['data'], 200);
    }

    return $response->withJson(array(), 405);
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
    
    $repo = new APITurnos\Repository\UsuariosRepository($this->db);

    $id = $request->getAttribute('id');
    $result = $repo->getMedicosConEspecialidad($id);
    if ($result->isOk()) {
        return $response->withJson($result->getData(), 200);
    }

    return $response->withJson(array(), 500);
});
