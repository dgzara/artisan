<?php

/**
 * OrdenVentaTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class OrdenVentaTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object OrdenVentaTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('OrdenVenta');
    }

   static public $formas = array(
       NULL=>'-- Seleccione --',
        'Cheque'=>'Cheque',
        'Depósito Bancario'=>'Depósito Bancario',
        'Vale Vista'=>'Vale Vista',
        'Transferencia Electrónica'=>'Transferencia Electrónica',
        'Efectivo'=>'Efectivo',
        'Otra'=>'Otra'  
    );

   static public $bf = array(
        NULL=>'-- Seleccione --',
        'Factura'=>'Factura',
        'Boleta'=>'Boleta',
        'Otra'=>'Otra'
    );


    public function getFormasPago(){
        return self::$formas;
    }

    public function getBf(){
        return self::$bf;
    }

    public function getLastNumero()
    {
        $q = Doctrine_Query::create();
        $q->select("o.id");
        $q->from("OrdenVenta o");
        $q->orderBy("o.id DESC");
        $orden = $q->fetchOne();

        if($orden){
            return $orden->getNumero() + 1;
        }
        else{
            return 1;
        }
    }

    public function getFacturas(){
        $q = Doctrine_Query::create();
        $q->from("OrdenVenta");
        $q->where("boleta_factura = Factura");
        $q->orderBy("n_bf");

        return $q->execute();
    }

    public function getBoletas(){
        $q = Doctrine_Query::create();
        $q->from("OrdenVenta");
        $q->where("boleta_factura = Boleta");
        $q->orderBy("n_bf");

        return $q->execute();
    }



}