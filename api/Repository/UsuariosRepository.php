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
class UsuariosRepository extends BaseRepository {

    /**
     * Devuelve el registro asociado a un usuario.
     * 
     * @param string $user
     * @param string $pwd
     * @return mixed
     */
    public function getUserData($user, $pwd) {

        $this->resetErrores();

        $sql = "SELECT u.id_usuario,
                    p.id_paciente,
                    u.username,
                    u.password,
                    u.apellidos,
                    u.nombres,
                    u.tipo_doc,
                    u.nro_doc,
                    u.email,
                    u.direccion,
                    u.fecha_nac 
                FROM usuarios u 
                LEFT JOIN pacientes p
                    ON u.id_usuario = p.usuario_id
                WHERE u.username = :user AND u.password = :pwd LIMIT 1";

        $stmt = $this->_dbLink->prepare($sql);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->bindParam(':pwd', $pwd, PDO::PARAM_STR);

        if (!$this->ejecutarStmt($stmt)) {
            return false;
        }
        
        if($stmt->rowCount() == 0){
            $this->_ultimoError = "No se encontro el ningun usuario con las credenciales suministradas";
            return false;
        }

        return $stmt->fetchAll();
    }

    /**
     * Devuelve todos los medicos existentes si no se proporciona un id. En caso de recibir un id distinto 
     * de null devuelvera solo el medico con ese id.
     * 
     * @param int $id_medico
     * @return mixed
     */
    public function getMedicos($id_medico = null) {

        $this->resetErrores();

        if (!is_null($id_medico) && !is_numeric($id_medico)) {
            $this->_ultimoError = "El id del medico debe ser un numero entero. Ingresado: " . $id_medico;
            return false;
        }

        $sql = "SELECT u.id_usuario,"
                . " m.id_medico, "
                . " m.nro_matricula, "
                . " m.especialidad_id,"
                . " u.username,"
                . " u.password,"
                . " u.apellidos,"
                . " u.nombres,"
                . " u.tipo_doc,"
                . " u.nro_doc,"
                . " u.email,"
                . " u.direccion,"
                . " u.fecha_nac  "
                . "FROM medicos m INNER JOIN usuarios u ON u.id_usuario = m.usuario_id";

        if (!is_null($id_medico)) {
            $sql .= " WHERE m.id_medico = $id_medico";
        }

        $stmt = $this->ejecutarQuery($sql);
        if (!$stmt) {
            return false;
        }

        return $stmt->fetchAll();
    }
    
    public function getMedicoPorMatricula($matricula) {

        $this->resetErrores();

        if (!is_string($matricula)) {
            $this->_ultimoError = "EL numero de matricula debe ser un string. Ingresado: " . $matricula;
            return false;
        }

        $sql = "SELECT u.id_usuario,"
                . " m.id_medico, "
                . " m.nro_matricula, "
                . " m.especialidad_id,"
                . " u.username,"
                . " u.password,"
                . " u.apellidos,"
                . " u.nombres,"
                . " u.tipo_doc,"
                . " u.nro_doc,"
                . " u.email,"
                . " u.direccion,"
                . " u.fecha_nac  "
                . "FROM medicos m "
                . "INNER JOIN usuarios u "
                . " ON u.id_usuario = m.usuario_id "
                . "WHERE m.nro_matricula = :nro_matricula ";

        $stmt = $this->_dbLink->prepare($sql);
        $stmt->bindParam(':nro_matricula', $matricula);
        
        if (!$this->ejecutarStmt($stmt)) {
            return false;
        }

        return $stmt->fetch();
    }

    /**
     * Devuelve un listado con todos los medicos con la especialidad indicada.
     * 
     * @param int $id_esp
     * @return mixed
     */
    public function getMedicosConEspecialidad($id_esp) {

        $this->resetErrores();

        if (!is_numeric($id_esp)) {
            $this->_ultimoError = "El id de la especialidad debe ser un numero entero.";
            return false;
        }

        $sql = "SELECT u.id_usuario, m.nro_matricula, u.username, u.password, u.apellidos, u.nombres, u.tipo_doc, u.nro_doc, u.email, u.direccion, u.fecha_nac "
                . "FROM medicos m "
                . "INNER JOIN especialidades e "
                . "ON m.especialidad_id = id_especialidad "
                . "INNER JOIN usuarios u "
                . "ON u.id_usuario = m.usuario_id "
                . "WHERE e.id_especialidad = :id_esp";


        $stmt = $this->_dbLink->prepare($sql);
        $stmt->bindParam(':id_esp', $id_esp);

        if (!$this->ejecutarStmt($stmt)) {
            return false;
        }

        return $stmt->fetchAll();
    }

    /**
     * Devuelve un listado con todos los pacientes. Si se especifica id filtra los pacientes por id de paciente
     * 
     * @param int $id_usuario
     * @return mixed
     */
    public function getPacientes($id_paciente = null) {
        
        $this->resetErrores();

        if (!is_null($id_paciente) && !is_numeric($id_paciente)) {
            $this->_ultimoError = "El id del paciente debe ser un numero entero. Ingresado: " . $id_paciente;
            return false;
        }

        $sql = "SELECT u.id_usuario,"
                . " p.id_paciente,"
                . " u.username,"
                . " u.password,"
                . " u.apellidos,"
                . " u.nombres "
                . "FROM pacientes p "
                . "INNER JOIN usuarios u "
                . "ON u.id_usuario = p.usuario_id";

        if (!is_null($id_paciente)) {
            $sql .= " WHERE p.id_paciente = $id_paciente";
        }

        $stmt = $this->ejecutarQuery($sql);
        if (!$stmt) {
            return false;
        }

        if($id_paciente){
            return $stmt->fetch();
        }
        
        return $stmt->fetchAll();
    }

}
