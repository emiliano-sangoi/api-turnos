<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace APITurnos\Repository;

use \PDO;


class OSRepository{
    private $_dbLink;

    public function __construct(PDO $db) {
        $this->_dbLink = $db;
    }
    
    /**
     * 
     * @param int $id_paciente
     * @return \APITurnos\Repository\RepoResult 
     */
    public function getAfiliaciones($id_paciente){               

//sleep(2);
        $sql = "SELECT
                    os.id_os AS 'idOs',
                    os.nombre,
                    os.cod_os AS 'codOs',
                    os.direccion,
                    os.telefono,
                    a.fecha_ini AS 'fechaIni',
                    p.id_paciente AS 'idPaciente'
                FROM afiliaciones a
                INNER JOIN pacientes p
                ON p.id_paciente = a.paciente_id
                INNER JOIN obras_sociales os
                ON os.id_os = a.os_id
                WHERE a.fecha_fin IS NULL AND p.id_paciente = ?";
        
        $stmt = $this->_dbLink->prepare($sql);
        
        $result = new RepoResult();
        if ($stmt->execute(array($id_paciente))) {
            $result->setOk(true);
            $result->setData($stmt->fetchAll(PDO::FETCH_ASSOC));            
        } else {
            $result->setMsg($stmt->errorInfo());            
        }                

        return $result;
        
    }
    
    /**
     * Devuelve todas las obras sociales existentes.
     * 
     * @param type $id
     * @return \APITurnos\Repository\RepoResult
     */
    public function getObrasSociales($id = null){
        
        $sql = "SELECT * FROM obras_sociales";
        
        if(!is_null($id)){
            $sql .= " WHERE id_obra_social = :id";                        
        }
        
        $stmt = $this->_dbLink->prepare($sql);
        
        if(!is_null($id)){
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        }
        
        $result = new RepoResult();
        if($stmt->execute()){
            $result->setOk(true);
            $result->setData($stmt->fetchAll());            
            
        }else{
            $result->setMsg($stmt->errorInfo());
        }        

        return $result;
    }
    
    /**
     * Devuelve todas las obras sociales existentes.
     * 
     * @param type $id
     * @return \APITurnos\Repository\RepoResult
     */
    public function getConvenios($id_sanatorio){
        
        $sql = "SELECT
                    os.id_os,
                    os.nombre,
                    os.cod_os,
                    os.direccion,
                    os.telefono,
                    c.fecha_ini AS 'fecha_ini_convenio',
                    s.id_sanatorio
                FROM convenios c
                INNER JOIN sanatorios s
                ON c.sanatorio_id = s.id_sanatorio
                INNER JOIN obras_sociales os
                ON c.os_id = os.id_os
                WHERE c.fecha_fin IS NULL AND s.id_sanatorio = ?";
        
               
        $stmt = $this->_dbLink->prepare($sql);
               
        $result = new RepoResult();
        if($stmt->execute(array($id_sanatorio))){
            $result->setOk(true);
            $result->setData($stmt->fetchAll());            
            
        }else{
            $result->setMsg($stmt->errorInfo());
        }        

        return $result;
    }
}
