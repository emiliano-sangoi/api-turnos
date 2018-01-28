<?php

namespace APITurnos\Repository;

use PDO;
use Exception;
use PDOException;

class BaseRepository {

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

    public function resetErrores() {
        $this->_ultimoError = '';
        return $this;
    }

    public function ejecutarStmt(\PDOStatement &$stmt) {

        try {

            if (!$stmt->execute()) {
                $this->_ultimoError = $stmt->errorInfo();
                return false;
            }

            return true;
        } catch (PDOException $ex) {
            $this->_ultimoError = "Ocurrio un error al intentar procesar la transaccion.";
        } catch (Exception $ex) {
            $this->_ultimoError = "Ocurrio un error al intentar procesar la transaccion.";
        }

        return false;
    }
    
    public function ejecutarQuery($sql) {

        try {
            
            
            $stmt = $this->_dbLink->query($sql);

            if (!$stmt) {
                $this->_ultimoError = $this->_dbLink->errorInfo();
                return false;
            }

            return $stmt;
        } catch (PDOException $ex) {
            $this->_ultimoError = "Ocurrio un error al intentar procesar la transaccion.";
        } catch (Exception $ex) {
            $this->_ultimoError = "Ocurrio un error al intentar procesar la transaccion.";
        }

        return false;
    }

}
