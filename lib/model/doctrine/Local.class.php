<?php

/**
 * Local
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    quesos
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Local extends BaseLocal
{
    public function save(Doctrine_Connection $conn = null) {
        if ($this->isNew()) {
            $local = parent::save($conn);

            if($this->getCliente()->existeClienteProducto()){
                $this->insertSQL();
                $clientes_productos = Doctrine_Core::getTable('ClienteProducto')->findByClienteId($this->getClienteId());

                foreach($clientes_productos as $cliente_producto){
                    $producto = $cliente_producto->getProducto();
                    $producto->insertSQL($this->getId());
                }
            }
        }
        else{            
            return $this->updateSQL();
        }
        return $local;
    }

    public function delete(Doctrine_Connection $conn = null) {
        $this->deleteSQL();
        parent::delete($conn);
    }

    public function asArray($host) {
        return array(
            'cliente' => $this->getCliente()->getName(),
            'cliente_id' => $this->getCliente()->getId(),
            'nombre' => $this->getNombre(),
            'codigo' => $this->getCodigo(),
            'region' => $this->getRegion(),
            'direccion' => $this->getDireccion()
        );
    }

    public function getProductos(){
        $capturas = $this->getProductoLocal();
        $productos = array();
        foreach($capturas as $captura){
            $productos[] = $captura->getProducto();
        }
        return $productos;
    }

    public function getNombreCompleto(){
        $nombre = $this->getCliente()->getNombre();
        return $nombre.' '.$this->getNombre();
    }

    public function insertSQL(){
        $sql = "INSERT INTO locales (tipoCliente, cliente, cliente_id, nombre, codigo, region, direccion) VALUES (\":tipoCliente\", \":cliente\", \":idCliente\", \":nombre\", \":codigo\", \":region\", \":direccion\")";
        $this->generarSQL($sql);

    }

    public function updateSQL(Doctrine_Connection $conn = null){
        $sql = "UPDATE locales SET tipoCliente = \":tipoCliente\", cliente = \":cliente\", cliente_id = \":idCliente\", nombre = \":nombre\", codigo = \":codigo\", region = \":region\", direccion = \":direccion\" WHERE codigo = \":codigo\"";
        $this->generarSQL($sql);
        return parent::save($conn);
    }

    public function deleteSQL(){
        $sql = "DELETE FROM locales WHERE codigo = \"".$this->getId()."\";";
        $sql2 = "DELETE FROM productos WHERE codigoLocal = \"".$this->getId()."\";";

        $tabla = new TablaQueries();
        $tabla->setInstruccion($sql);
        $tabla->save();

        $tabla2 = new TablaQueries();
        $tabla2->setInstruccion($sql2);
        $tabla2->save();
    }

    protected function generarSQL($sql){
        $parametros = array(':tipoCliente', ':cliente', ':idCliente', ':nombre', ':codigo', ':region', ':direccion');
        $reemplazo = array($this->getCliente()->getTipo(), $this->getCliente()->getName(), $this->getCliente()->getId(), $this->getNombre(), $this->getId(), $this->getRegion(), $this->getDireccion());
        $instruccion = str_replace($parametros, $reemplazo, $sql);

        $tabla = new TablaQueries();
        $tabla->setInstruccion($instruccion);
        $tabla->save();
    }
}