<?php

/**
 * AreaDeCostosTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AreaDeCostosTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object AreaDeCostosTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('AreaDeCostos');
    }

    public static function getNombresInput(){
        $areas = Doctrine_Core::getTable('AreaDeCostos')->findAll();
        $nombres = array();

        foreach($areas as $area){
            $nombres[$area->getId()] = $area->getNombre();
        }

        return $nombres;
    }
}