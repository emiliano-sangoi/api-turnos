<?php

namespace APITurnos\Repository;

use \PDO;

/**
 * 
 */
class EspecialidadesRepository {

    private $_dbLink;

    public function __construct(PDO $db) {
        $this->_dbLink = $db;
    }

    /**
     * 
     * @return \APITurnos\Repository\RepoResult
     */
    public function getEspecialidades($id_esp = null, $con_medicos = false) {
        
        if($con_medicos){
            //Selecciona aquellas especialidades que tengan un medico asociado
            $sql = "SELECT e.id_especialidad, e.nom_especialidad FROM medicos m INNER JOIN especialidades e ON m.especialidad_id = e.id_especialidad";
        }else{
            $sql = "SELECT id_especialidad, nom_especialidad FROM especialidades e";
        }
        
        
        if(!is_null($id_esp)){
            $sql .= " WHERE id_especialidad = $id_esp";
        }
        
        $sql .= " GROUP BY e.id_especialidad";
        
        $stmt = $this->_dbLink->prepare($sql);                

        $result = new RepoResult();
        if ($stmt->execute()) {
            
            $stmt->bindColumn(1, $id);
            $stmt->bindColumn(2, $nom);
            //$stmt->bindColumn(3, $desc);
            
            while ($row = $stmt->fetch()) {
                $result->addDataRow(array(
                    "id_especialidad" => $id,
                    "nom_especialidad" => utf8_encode($nom),
                    //"desc_especialidad" => utf8_encode($desc)
                ));
            }
            
            $result->setOk(true);

        } else {
            $result->setMsg($stmt->errorInfo());
        }

        return $result;
    }
    
    
    /**
     * Devuelve true si la especialidad con id existe o false en caso contrario.
     * @param type $id_esp
     * @return boolean
     */
    public function existe($id_esp) {
        if(is_int($id_esp)){
            $stmt = $this->_dbLink->query("SELECT 1 FROM especialidades WHERE id_especialidad = $id_esp");
            return $stmt->rowCount() > 0;            
        }
        return false;
        
        
    }

}
