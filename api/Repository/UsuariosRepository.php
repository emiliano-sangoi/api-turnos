<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace APITurnos\Repository;

use \PDO;

/**
 * Esta clase encapsula consultas sobre las tablas relacionadas a los usuarios: 
 *  - usuarios
 *  - medicos
 *  - pacientes
 */
class UsuariosRepository {

    private $_dbLink;

    public function __construct(PDO $db) {
        $this->_dbLink = $db;
    }

    public function getUserData($user, $pwd) {

        $sql = "SELECT u.*, p.id_paciente FROM usuarios u 
            LEFT JOIN pacientes p
            ON u.id_usuario = p.usuario_id
            WHERE u.username = :user AND u.password = :pwd";

        $stmt = $this->_dbLink->prepare($sql);

        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->bindParam(':pwd', $pwd, PDO::PARAM_STR);

        $result = array('ok' => false, 'data' => null, 'msg' => '');
        if ($stmt->execute()) {
            //var_dump($stmt->fetchAll());exit;
            $result['ok'] = true;
            $result['data'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $result['msg'] = $stmt->errorInfo();
        }

        return $result;
    }

    /**
     * Devuelve todos los medicos existentes si no se proporciona un id. En caso de recibir un id distinto 
     * de null devuelvera solo el medico con ese id.
     * 
     * @param type $id
     * @return type
     */
    public function getMedicos($id = null) {
        
        $sql = "SELECT * FROM medicos m INNER JOIN usuarios u ON u.id_usuario = m.usuario_id";
        
        if(!is_null($id)){
            $sql .= " WHERE m.id_medico = $id";                        
        }
        
        $stmt = $this->_dbLink->query($sql);
        $result = array('ok' => false, 'data' => null, 'msg' => '');
        if($stmt){
            $result['ok'] = true;
            $result['data'] = $stmt->fetchAll();
            
        }else{
            $result['msg'] = $stmt->errorInfo();
        }        

        return $result;

    }

    

}
