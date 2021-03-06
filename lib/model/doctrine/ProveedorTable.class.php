<?php

/**
 * ProveedorTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class ProveedorTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object ProveedorTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Proveedor');
    }

    public function getNombresInput(){
        $proveedores = Doctrine_Core::getTable('Proveedor')->findAll();
        $nombres = array();

        foreach($proveedores as $proveedor){
            $nombres[$proveedor->getId()] = $proveedor->getEmpresaNombre();
        }

        return $nombres;
    }
}