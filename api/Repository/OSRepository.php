<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace APITurnos\Repository;

use PDO;

class OSRepository extends BaseRepository {

    /**
     * Devuelve todas las afiliaciones asociadas al paciente.
     * 
     * @param int $id_paciente
     * @param null|int $id_obra_social
     * @return mixed 
     */
    public function getAfiliaciones($id_paciente, $id_obra_social = null) {

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
                WHERE a.fecha_fin IS NULL";

        $this->resetErrores();

        if (!is_numeric($id_paciente)) {
            $this->_ultimoError = "El id del paciente debe ser un numero entero. Ingresado: " . $id_paciente;
            return false;
        }
        
        if (!is_null($id_obra_social) && !is_numeric($id_obra_social)) {
            $this->_ultimoError = "El id de la obra social debe ser un numero entero. Ingresado: " . $id_obra_social;
            return false;
        }

        $sql .= " AND p.id_paciente = $id_paciente";
        $sql .= $id_obra_social ? " AND os.id_os = $id_obra_social" : "";
        
        $stmt = $this->_dbLink->prepare($sql);        
        if (!$this->ejecutarStmt($stmt)) {
            return false;
        }
        
        if($id_obra_social && $id_paciente){
            return $stmt->fetch();
        }
        
        return $stmt->fetchAll();
    }

    /**
     * Devuelve todas las obras sociales existentes.
     * 
     * @param int $id
     * @return mixed
     */
    public function getObrasSociales($id_os = null) {

        $this->resetErrores();

        if (!is_null($id_os) && !is_numeric($id_os)) {
            $this->_ultimoError = "El id de la obra social debe ser un numero entero. Ingresado: " . $id_os;
            return false;
        }

        $sql = "SELECT * FROM obras_sociales";

        if (!is_null($id_os)) {
            $sql .= " WHERE id_os = :id";
        }

        $stmt = $this->_dbLink->prepare($sql);

        if (!is_null($id_os)) {
            $stmt->bindParam(':id', $id_os, \PDO::PARAM_INT);
        }

        if (!$this->ejecutarStmt($stmt)) {
            return false;
        }

        return $stmt->fetchAll();
    }

    /**
     * Devuelve todas los convenios existentes para el sanatorio especificado.
     * 
     * @param int $id
     * @return mixed
     */
    public function getConvenios($id_sanatorio) {

        $this->resetErrores();

        if (!is_numeric($id_sanatorio)) {
            $this->_ultimoError = "El id del sanatorio debe ser un numero entero.";
            return false;
        }

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
                WHERE c.fecha_fin IS NULL AND s.id_sanatorio = :id_sanatorio";

        $stmt = $this->_dbLink->prepare($sql);
        $stmt->bindParam(':id_sanatorio', $id_sanatorio);

        if (!$this->ejecutarStmt($stmt)) {
            return false;
        }

        return $stmt->fetchAll();
    }

}
