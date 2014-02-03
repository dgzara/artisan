<?php

/**
 * Cliente
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    quesos
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Cliente extends BaseCliente
{
    public function getProductos(){
        $relaciones = $this->getClienteProducto();
        $productos = array();

        foreach($relaciones as $relacion){
            $productos[$relacion->getProducto()->getId()] = $relacion->getProducto();
        }
        return $productos;
    }

    public function getProductosPorCodigo($codigo){
        $q = Doctrine_Query::create();
        $q->from("Cliente c");
        $q->leftJoin("c.Local l");
        $q->leftJoin("l.ProductoLocal pl");
        $q->leftJoin("pl.Producto p");
        $q->where("p.codigo = ?", $codigo);
        $q->groupBy("p.codigo");

        return $q->execute();

    }

    public function asArray($host) {
        return array(
            'tipo' => $this->getTipo(),
            'nombre' => $this->getName(),
            'rut' => $this->getRut(),
            'ciudad' => $this->getCiudad(),
            'contacto' => $this->getContacto()
        );
    }

    public function asArray2($host) {
        return array(
            'tipo' => $this->getTipo()
        );
    }

    public function getLocales(){
        $q = Doctrine_Query::create();
        $q->select('l.*');
        $q->from('Local l');
        $q->where('l.cliente_id = ?', $this->getId());
        return $q->execute();
    }

    public function getNombre(){
        return $this->getName();
    }

    public function existeClienteProducto(){
        $producto_cliente = Doctrine_Core::getTable('ClienteProducto')->findOneByClienteId($this->getId());

        if($producto_cliente){
            return true;
        }

        return false;
    }
}