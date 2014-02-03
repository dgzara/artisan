<?php

/**
 * AspectoCalidad
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    quesos
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class AspectoCalidad extends BaseAspectoCalidad
{
    public function save(Doctrine_Connection $conn = null) {
        if ($this->isNew()) {
            $this->insertSQL();
            return parent::save($conn);
        }
        else{
            return $this->updateSQL($conn);
        }
        
    }

    public function delete(Doctrine_Connection $conn = null) {
        $this->deleteSQL();
        return parent::delete($conn);
    }


    public function asArray($host) {
        return array(
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion()
        );
    }

    protected function insertSQL(){
        $sql = "INSERT INTO calidades (nombre, descripcion) VALUES (\":nombre\", \":descripcion\")";
        $this->generarSQL($sql);
    }

    protected function updateSQL(Doctrine_Connection $conn = null){
        $sql = "UPDATE calidades SET nombre = \":nombre\", descripcion = \":descripcion\" WHERE nombre = \":nombre\" AND descripcion = \":descripcion\"";
        $this->generarSQL($sql);
        return parent::save($conn);
    }

    protected function deleteSQL(){
        $sql = "DELETE from calidades where nombre = \":nombre\"";
        $this->generarSQL($sql);
    }

    protected function generarSQL($sql){
        $parametros = array(':nombre', ':descripcion');
        $reemplazo = array($this->getNombre(), $this->getDescripcion());
        $instruccion = str_replace($parametros, $reemplazo, $sql);

        $tabla = new TablaQueries();
        $tabla->setInstruccion($instruccion);
        $tabla->save();
    }
}