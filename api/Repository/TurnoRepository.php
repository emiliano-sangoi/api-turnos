<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace APITurnos\Repository;

use \PDO;

/**
 * Description of TurnoRepository
 *
 * @author emiliano
 */
class TurnoRepository {
    
    private $_dbLink;

    public function __construct(PDO $db) {
        $this->_dbLink = $db;
    }
    
    /**
     * 
     * @param int $id_medico Id del usuario asociado 
     * @param int $dia_tm unix timestamp correspondiente al dia a buscar
     * @return \APITurnos\Repository\RepoResult
     */
    public function getHorariosAtencion($id_medico, $dia_Ymd){
        
        $result = new RepoResult();
        //$fecha = new \DateTime($dia_tm);
        
        if(!ctype_digit($id_medico)){
            return $result->setMsg("El id del medico debe ser un numero entero.");
        }                
        
        //consulta sql que obtiene los datos de cierto medico en cierto dia:
        $sql = "SELECT
                    m.nro_matricula,
                    u.id_usuario,
                    u.apellidos,
                    u.nombres,                    
                    ha.fecha_hora_ini,
                    ha.fecha_hora_fin,
                    s.nombre AS 'sanatorio_nombre',
                    s.direccion AS 'sanatorio_direccion',
                    s.web AS 'sanatorio_web',
                    s.telefono AS 'sanatorio_telefono'
                FROM horarios_atencion ha
                INNER JOIN medicos m
                ON ha.medico_id = m.id_medico
                INNER JOIN usuarios u
                ON u.id_usuario = m.usuario_id
                INNER JOIN sanatorios s
                ON s.id_sanatorio = ha.sanatorio_id
                WHERE m.id_medico = ? AND DATE(ha.fecha_hora_ini)= ? ORDER BY ha.fecha_hora_ini ASC";        
                   
        $stmt = $this->_dbLink->prepare($sql);
        if ($stmt->execute(array($id_medico, $dia_Ymd))) {            
            $result->setData($stmt->fetchAll())->setOk(true);            
        } else {
            $result->setMsg($stmt->errorInfo());
        }

        return $result;        
    }
}
