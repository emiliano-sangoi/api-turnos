<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace APITurnos\Controller;

/**
 * Description of RepoResult
 *
 * @author emiliano
 */
class APIResponse {
    private $msg;
    private $ok;
    private $data;
    private $statusCode;
            
    function __construct($msg = "", $ok = false, $data = array(), $statusCode = 500) {
        $this->msg = $msg;
        $this->ok = $ok;
        $this->data = $data;
        $this->statusCode = $statusCode;
    }
    
    public function toArray(){
        return array(
            "msg" => $this->msg,
            "ok" => $this->ok,
            "data" => $this->data,
            "status_code" => $this->statusCode
        );
    }
            
    function getMsg() {
        return $this->msg;
    }

    function isOk() {
        return $this->ok;
    }

    function getData() {
        return $this->data;
    }

    function setMsg($msg) {
        $this->msg = $msg;
        
        return $this;
    }

    function setOk($ok) {
        $this->ok = $ok;
        
        return $this;
    }

    function setData($data) {
        $this->data = $data;
        
        return $this;
    }
    
    function addDataRow($row){
        $this->data[] = $row;
        
        return $this;
    }

    function getStatusCode() {
        return $this->statusCode;
    }

    function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
    }



}
