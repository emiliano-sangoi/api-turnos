<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace APITurnos\Repository;

use Exception;
use PDOException;

/**
 * Description of TurnoRepository
 *
 * @author emiliano
 */
class TurnoRepository extends BaseRepository {
    

    /**
     * Guarda en la base de datos un nuevo turno.
     * 
     * @param type $data
     * @return boolean
     */
    public function nuevoTurno($data) {
        
        $required_params = array('paciente_id', 'obra_social_id', 'horario_atencion_id');
        
        if( ! $this->isTurnoValido( $required_params, $data ) ){
            return false;
        }
        
        $this->resetErrores();

        $sql = "INSERT INTO turnos (id_turnos,"
                . " paciente_id,"
                . " id_horario_atencion,"
                . " obras_social_id,"
                . " fecha_creacion,"
                . " fecha_cancelacion) "
                . "VALUES (NULL, :paciente_id, :horario_atencion_id, :obra_social_id, NOW(), NULL)";

        try {
            $stmt = $this->_dbLink->prepare($sql);
            foreach ($required_params as $param){
                $stmt->bindParam( ':' . $param, $data[$param] );
            }                        

            if (!$stmt->execute()) {
                $this->_ultimoError = $stmt->errorInfo();                
            }
            
            
            $id = $this->_dbLink->lastInsertId();
            $turno = $this->getTurnoPorId($id);               
            if(!$turno){
                return false;
            }
            
            return $turno;
            
        } catch (PDOException $ex) {
            $this->_ultimoError = "Problema al guardar el registro en la base de datos.";            
        } catch (Exception $ex) {
            $this->_ultimoError = "No se pudo guardar el registro en la base de datos.";
        }

        return false;
    }
    
    /**
     * Varifica que los datos ingresados validen los formatos y reglas de negocio requeridas.
     * 
     * @param type $required_params
     * @param type $data
     * @return boolean
     */
    public function isTurnoValido($required_params, $data){
        
        $this->resetErrores();
        
        //Validacion
        // Todas las claves fueron definidas y son valores numericos:
        foreach ( $required_params as $param ) {
            if (!isset($param, $data) || !is_numeric($data[$param])) {
                $this->_ultimoError = "El parametro: $param no esta definido o no posee un formato valido.";
                return false;
            }
        }

        // Horario disponible. El horario de atencion no se encuentra reservado:
        if ( ! $this->isHorarioDisponible( $data['horario_atencion_id'] ) ) {
            $this->_ultimoError = "El horario seleccionado no se encuentra disponible.";
            return false;
        }

        // Si se definio una obra social, ¿el paciente tiene convenio vigente con la obra social que selecciono?:
        $repoOs = new OSRepository($this->_dbLink);
        $afiliaciones = $repoOs->getAfiliaciones($data['paciente_id'], true);        
        $flag = false;
        foreach ($afiliaciones as $afiliacion) {
            if ($afiliacion['idOs'] == $data['obra_social_id'] ) {
                $flag = true;
                break;
            }
        }
        if(!$flag){
            $this->_ultimoError = "El paciente no se encuentra afiliado a la obra social elegida.";            
            return false;
        }
        
        
        return true;
    }

    /**
     * Verifica si un horario de atencion esta disponible o no.
     * 
     * @param type $id_horario
     * @return type
     */
    public function isHorarioDisponible($id_horario, &$result) {

        $this->_ultimoError = '';

        try {

            $sql = "SELECT 1 FROM turnos WHERE id_horario_atencion = $id_horario";
            $stmt = $this->_dbLink->query($sql);
            return $stmt->rowCount() === 0;

        } catch (PDOException $ex) {
            $this->_ultimoError = "Problema al guardar el registro en la base de datos.";
        } catch (Exception $ex) {
            $this->_ultimoError = "No se pudo guardar el registro en la base de datos.";
        }

        return false;
    }
    
    /**
     * Busca un turno por su id
     * 
     * @param int $id_turno
     * @return boolean
     */
    public function getTurnoPorId($id_turno){
        
        $this->resetErrores();

        try {

            $sql = "SELECT * FROM turnos WHERE id_turnos = $id_turno";
            $stmt = $this->_dbLink->query($sql);
            return $stmt->fetch();

        } catch (PDOException $ex) {
            $this->_ultimoError = "Problema al intentar recuperar el registro de la base de datos.";
        } catch (Exception $ex) {
            $this->_ultimoError = "No se pudo recuperar el registro de la base de datos.";
        }

        return false;
        
    }

    /**
     * 
     * @param int $id_medico Id del usuario asociado 
     * @param int $dia_tm unix timestamp correspondiente al dia a buscar
     * @return RepoResult
     */
    public function getHorariosAtencion($id_medico, $from) {

        $result = new APIResponse();

        if (!is_numeric($from)) {
            return $result;
        }

        if (!ctype_digit($id_medico)) {
            return $result->setMsg("El id del medico debe ser un numero entero.");
        }

        //consulta sql que obtiene los datos de cierto medico en cierto dia:
        $sql = "SELECT
                    id_horario_atencion,
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
                WHERE m.id_medico = ? AND UNIX_TIMESTAMP(ha.fecha_hora_ini) >= ? ORDER BY ha.fecha_hora_ini ASC";

        $stmt = $this->_dbLink->prepare($sql);
        if ($stmt->execute(array($id_medico, $from))) {
            $result->setData($stmt->fetchAll())->setOk(true);
        } else {
            $result->setMsg($stmt->errorInfo());
        }

        return $result;
    }

}
