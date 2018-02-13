<?php

namespace APITurnos\Repository;

use \PDO;

/**
 * 
 */
class EspecialidadesRepository extends BaseRepository {

    /**
     * 
     * @param int $id_esp
     * @param boolean $con_medicos
     * @return mixed
     */
    public function getEspecialidades($id_esp = null, $con_medicos = false) {
        
        $this->resetErrores();

        if (!is_null($id_esp) && !is_numeric($id_esp)) {
            $this->_ultimoError = "El id de la especialidad debe ser un numero entero.";
            return false;
        }        

        if ($con_medicos) {
            //Selecciona aquellas especialidades que tengan un medico asociado
            $sql = "SELECT e.id_especialidad, LCASE(e.nom_especialidad) FROM medicos m INNER JOIN especialidades e ON m.especialidad_id = e.id_especialidad";
        } else {
            $sql = "SELECT id_especialidad, LCASE(nom_especialidad) FROM especialidades e";
        }

        $sql .= is_null($id_esp) ? '' : " WHERE id_especialidad = $id_esp";
        $sql .= " GROUP BY e.id_especialidad";

        $stmt = $this->_dbLink->prepare($sql);
        if (!$this->ejecutarStmt($stmt)) {
            return false;
        }

        $stmt->bindColumn(1, $id);
        $stmt->bindColumn(2, $nom);

        $especialidades = array();
        while ($row = $stmt->fetch()) {
            
            $nom = ucwords($nom);

            $especialidades[] = array(
                "id_especialidad" => $id,
                "nom_especialidad" => utf8_encode($nom),
                    //"desc_especialidad" => utf8_encode($desc)
            );
            
            if($id_esp){
                return $especialidades[0];
            }
        }

        dump($especialidades);exit;
        return $especialidades;
    }

    /**
     * Devuelve true si la especialidad con id existe o false en caso contrario.
     * 
     * @param type $id_esp
     * @return boolean
     */
    public function existe($id_esp) {
        
        $this->resetErrores();
        
        if (!is_numeric($id_esp)) {
            $this->_ultimoError = "El id de la especialidad debe ser un numero entero.";
            return false;
        }
                
        $sql = "SELECT 1 FROM especialidades WHERE id_especialidad = $id_esp";      
        $stmt = $this->ejecutarQuery($sql);
        if(!$stmt){
            return false;
        }
                        
        return $stmt->rowCount() > 0;        
    }

}
