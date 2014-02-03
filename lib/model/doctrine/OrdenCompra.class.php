<?php

/**
 * OrdenCompra
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    quesos
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class OrdenCompra extends BaseOrdenCompra
{
    public function getInsumos(){
        $q = Doctrine_Query::create();
        $q->select('i.*, oi.neto as precio, oi.cantidad as cantidad, oi.detalle as detalle');
        $q->from('Insumo i');
        $q->innerJoin('i.OrdenCompraInsumo oi');
        $q->innerJoin('i.ProveedorInsumo p');
        $q->where('oi.orden_compra_id = ?', $this->getId());
        return $q->execute();
    }

//    public function getTotal(){
//        $ordenes_insumos = Doctrine_Core::getTable('OrdenCompraInsumo')->findByOrdenCompraId($this->getId());
//        $monto = 0;
//
//        foreach($ordenes_insumos as $orden_insumo){
//            $monto += $orden_insumo->getNeto() * $orden_insumo->getCantidad();
//        }
//
//        $n = new sfNumberFormat('es_CL');
//        return '$'.$n->format($monto, 'd', 'CLP');
//
//    }

    public function getTotalInsumo($insumo_id){
        $ordenes_insumos = Doctrine_Core::getTable('OrdenCompraInsumo')->findByDql('orden_compra_id = ? AND insumo_id = ?', array($this->getId(), $insumo_id));
        $monto = 0;

        foreach($ordenes_insumos as $orden_insumo){
            $neto = $orden_insumo->getNeto() * $orden_insumo->getCantidad();
            $iva = intval($neto * 0.19);
            $monto += ($neto + $iva);
        }

        $n = new sfNumberFormat('es_CL');
        return '$'.$n->format($monto, 'd', 'CLP');
    }

    public function getNeto(){
        $insumos = $this->getInsumos();
        $total = 0;
        $unidades =0;

        foreach($insumos as $insumo){
            $total+= $insumo->getPrecio() * $insumo->getCantidad();
        }

        return $total;
    }

    public function getIVA(){
        return round($this->getNeto() * 0.19);
    }

    public function getTotal(){
        return $this->getNeto() + $this->getIVA();
    }
}