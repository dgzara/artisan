<?php

/**
 * CentroDeCostosTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CentroDeCostosTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object CentroDeCostosTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CentroDeCostos');
    }

    public static function getNombresInput(){
        $costos = Doctrine_Core::getTable('CentroDeCostos')->findAll();
        $nombres = array();

        foreach($costos as $costo){
            $nombres[$costo->getId()] = $costo->getNombre();
        }

        return $nombres;
    }
}