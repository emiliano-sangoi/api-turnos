<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace APITurnos\Repository;

/**
 * Description of RepoResult
 *
 * @author emiliano
 */
class RepoResult {
    private $msg;
    private $ok;
    private $data;
    
    function __construct($msg = "", $ok = false, $data = array()) {
        $this->msg = $msg;
        $this->ok = $ok;
        $this->data = $data;
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



}
