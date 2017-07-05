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
 *         name="user",
 *         in="body",
 *         description="Nombre de usuario",
 *         required=true
 *     ),
 *     @SWG\Parameter(
 *         name="pwd",
 *         in="body",
 *         description="Contraseña",
 *         required=true
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="Usuario y/o contraseña son validos."
 *     ),
 *     @SWG\Response(
 *         response=405,
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

    $st = 405;
    $response_body = array("msg" => "No se encontro el ningun usuario con las credenciales suministradas");
    $result = $repo->getUserData($body["username"], $body["password"]);
    if ($result['ok']) {
        $st = 200;
        $response_body = $result['data'];
    }

    return $response->withJson($response_body, $st);
    
});



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


/**
 * @SWG\Get(
 *   path="/medicos",
 *   summary="Devuelve un listado de todos los medicos existentes",
 *   produces={"application/xml", "application/json"},
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
