<?php

namespace APITurnos\Repository;

use PDO;

class BaseRepository{
    
    /**
     *
     * @var PDO 
     */
    protected $_dbLink;
    protected $_ultimoError;

    public function __construct(PDO $db) {
        $this->_dbLink = $db;
        $this->_ultimoError = '';
    }
    
    public function getDbLink() {
        return $this->_dbLink;
    }

    public function getUltimoError() {
        return $this->_ultimoError;
    }

    public function setDbLink($_dbLink) {
        $this->_dbLink = $_dbLink;
        return $this;
    }

    public function setUltimoError($_ultimoError) {
        $this->_ultimoError = $_ultimoError;
        return $this;
    }
    
    public function resetErrores(){
        $this->_ultimoError = '';
        return $this;
    }


    
}