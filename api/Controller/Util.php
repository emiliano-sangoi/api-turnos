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

            if (is_array($repo_res) && count($repo_res) === 0) {
                $apiResponse->setStatusCode(200);
                $apiResponse->setMsg("No se encontro ningun registro.");  
            }else{
                $apiResponse->setStatusCode($default_error_code);
                $apiResponse->setMsg($ultimo_error);  
            }
            
        } else {
            $apiResponse->setOk(true);
            $apiResponse->setData($repo_res);
            $apiResponse->setStatusCode($default_success_code);
        }

        return $apiResponse;
    }

}
