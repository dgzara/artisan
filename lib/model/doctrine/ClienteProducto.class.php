<?php

/**
 * ClienteProducto
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    quesos
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class ClienteProducto extends BaseClienteProducto
{
    public function save(Doctrine_Connection $conn = null) {
        $producto = $this->getProducto();

        // Si es una nueva relación
        if ($this->isNew()) {
            // Revisamos si ya está inserto el Cliente
            if(!$this->existeCliente($this->getClienteId())){
                $locales = $this->getCliente()->getLocales();
                foreach($locales as $local){
                    $local->insertSQL();
                    $producto->insertSQL($local->getId());
                }
            }
            else{
                $locales = $this->getCliente()->getLocales();
                foreach($locales as $local){
                    $producto->insertSQL($local->getId());
                }
            }
        }
        else{
            $locales = $this->getCliente()->getLocales();
            foreach($locales as $local){
                return $producto->updateSQL($local->getId());
            }

        }
        return parent::save($conn);
    }

    public function delete(Doctrine_Connection $conn = null) {
        $this->deleteSQL();
        parent::delete($conn);
    }

    private function deleteSQL(){

        $producto = $this->getProducto();
        $locales = $this->getCliente()->getLocales();

        $sql = '';

        foreach($locales as $local){
            $sql."DELETE FROM productos WHERE codigo = \"".$producto->getCodigo()."\" AND codigoLocal = \"".$local->getId()."\"; ";
            $tabla = new TablaQueries();
            $tabla->setInstruccion($sql);
            $tabla->save();
        }
    }

    public function existeCliente($cliente_id){
        $producto_cliente = Doctrine_Core::getTable('ClienteProducto')->findOneByClienteId($cliente_id);

        if($producto_cliente){
            return true;
        }
        else{
            return false;
        }
    }
}