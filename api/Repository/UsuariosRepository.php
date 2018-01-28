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

    /**
     * Devuelve el registro asociado a un usuario.
     * 
     * @param type $user
     * @param type $pwd
     * @return type
     */
    public function getUserData($user, $pwd) {

        $sql = "SELECT u.id_usuario, p.id_paciente, u.username, u.password, u.apellidos, u.nombres, u.tipo_doc, u.nro_doc, u.email, u.direccion, u.fecha_nac 
            FROM usuarios u 
            LEFT JOIN pacientes p
            ON u.id_usuario = p.usuario_id
            WHERE u.username = :user AND u.password = :pwd LIMIT 1";

        $stmt = $this->_dbLink->prepare($sql);

        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->bindParam(':pwd', $pwd, PDO::PARAM_STR);
          
        $result = new RepoResult();
        if ($stmt->execute()) {
            $result->setOk(true);
            $result->setData($stmt->fetchAll());
        } else {
            $result->setMsg($stmt->errorInfo());
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
        
        $sql = "SELECT u.id_usuario, m.nro_matricula, u.username, u.password, u.apellidos, u.nombres, u.tipo_doc, u.nro_doc, u.email, u.direccion, u.fecha_nac  "
                . "FROM medicos m INNER JOIN usuarios u ON u.id_usuario = m.usuario_id";
        
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
    

    /**
     * Devuelve un listado con todos los medicos con la especialidad indicada.
     * 
     * @param type $id
     * @return \APITurnos\Repository\RepoResult
     */
    public function getMedicosConEspecialidad($id) {
        
        $sql = "SELECT u.id_usuario, m.nro_matricula, u.username, u.password, u.apellidos, u.nombres, u.tipo_doc, u.nro_doc, u.email, u.direccion, u.fecha_nac "
                . "FROM medicos m "
                . "INNER JOIN especialidades e "
                . "ON m.especialidad_id = id_especialidad "
                . "INNER JOIN usuarios u "
                . "ON u.id_usuario = m.usuario_id "
                . "WHERE e.id_especialidad = ?";
        
        
        $stmt = $this->_dbLink->prepare($sql);
        $result = new RepoResult();
        if($stmt->execute(array($id))){
            $result->setData($stmt->fetchAll(PDO::FETCH_ASSOC));
            $result->setOk(true);
            
        }else{
            $result->setMsg($stmt->errorInfo());
        }        

        return $result;

    }
    
    /**
     * Devuelve un listado con todos los pacientes.
     * @param type $id
     * @return type
     */
    public function getPacientes($id = null) {
        
        $sql = "SELECT u.id_usuario, u.username, u.password, u.apellidos, u.nombres FROM pacientes p INNER JOIN usuarios u ON u.id_usuario = p.usuario_id";
        
        if(!is_null($id)){
            $sql .= " WHERE p.id_paciente = $id";                        
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
