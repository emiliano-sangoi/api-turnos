<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace APITurnos\Controller;

/**
 * Description of Util
 *
 * @author emi88
 */
class Util {
    
    function __construct() {
        
    }

        /**
     * 
     * @param mixed $repo_res Respuesta obtenida desde un repositorio.
     * @param string $ultimo_error Ultimo error obtenido.
     * @return APIResponse
     */
    public static function buildApiResponse($repo_res, $ultimo_error = '', $default_success_code = 200, $default_error_code = 500) {

        $apiResponse = new APIResponse();

        if (!$repo_res) {
            $apiResponse->setMsg($ultimo_error);
            $apiResponse->setStatusCode($default_error_code);
        } else {
            $apiResponse->setOk(true);
            $apiResponse->setStatusCode($default_success_code);
            $apiResponse->setData($repo_res);
        }

        return $apiResponse;
    }

}
