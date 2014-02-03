<?php

/**
 * ClienteTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class ClienteTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object ClienteTable
     */

    static public $tipos = array(
        'HORECA' => 'HORECA',
        'Supermercado' => 'Supermercado',
        'Persona Natural' => 'Persona Natural'
    );

    public static function getInstance()
    {
        return Doctrine_Core::getTable('Cliente');
    }

    public function getNombresInput(){
        $clientes = Doctrine_Core::getTable('Cliente')->findAll();
        $nombres = array();
        foreach($clientes as $cliente){
            $nombres[$cliente->getId()] = $cliente->getName();
        }
        return $nombres;
    }

    public function getTipos()
    {
//        $q = Doctrine_Query::create()->select("c.tipo");
//        $q->from("Cliente c");
//        $q->groupBy("c.tipo");
//
//        return $q->execute();
        return self::$tipos;
    }

    public function getForTipo(array $parameters) {
        if (key_exists('tipo', $parameters)) {
            $cliente = Doctrine_Core::getTable('Cliente')->findByTipo($parameters['tipo']);
            if (count($cliente) == 0) {
                throw new sfError404Exception(sprintf('El cliente del tipo "%s" no existe', $parameters['tipo']));
            } else {
                if (key_exists('codigo', $parameters)) {
                    return $cliente->getProductosPorCodigo($parameters['codigo']);
                } else {
                    return $cliente;
                }
            }
        } else {
            return Doctrine_Core::getTable('Cliente')->findAll();
        }
    }

    public function getProductos(array $parameters) {
        $cliente = Doctrine_Core::getTable('Cliente')->findOneById($parameters['id']);
        if (!$cliente) {
            throw new sfError404Exception(sprintf('El cliente con el id "%s" no existe', $parameters['id']));
        } else {
            return $cliente->getProductos();
        }
    }
}