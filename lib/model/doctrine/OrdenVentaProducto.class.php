<?php

/**
 * OrdenVentaProducto
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    quesos
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class OrdenVentaProducto extends BaseOrdenVentaProducto
{
      public function cambiarInventario($id, $cantidad, $fecha, $bodega, $opcion, $usuario = null, $tipo = null){

        $q = Doctrine_Query::create();
        //$q->select('i.cantidad');
        $q->from('InventarioProductos i');
        $q->where('i.producto_id = ?', $id);
        $q->andWhere('i.bodega_id= ?', $bodega);
        $q->orderBy('i.updated_at DESC');

        $inventario = $q->fetchOne();

        $vieja_cantidad = $inventario->getCantidad();
        
        if($opcion=='aumentar'){
            $nueva_cantidad = $inventario->getCantidad() + $cantidad;
        }

        else if($opcion=='disminuir'){
            $nueva_cantidad = $inventario->getCantidad() - $cantidad;
        }

      //  else{
      //      $nueva_cantidad = $cantidad;
      //  }
         $nuevo_inventario = new InventarioProductos();
         $nuevo_inventario->setFecha($fecha);
         $nuevo_inventario->setCantidad($nueva_cantidad);
         $nuevo_inventario->setProductoId($id);
         $nuevo_inventario->setBodegaId($bodega);
         $nuevo_inventario->save();

         $r = Doctrine_Query::create();
         $r->select('pv.nombre');
         $r->from('Producto pv');
         $r->where('pv.id = ?', $id);
         $ri = $r->fetchOne();
         $nombre = $ri->getNombre(); 

         $s = Doctrine_Query::create();
         $s->select('sv.nombre');
         $s->from('Bodega sv');
         $s->where('sv.id = ?', $bodega);
         $si = $s->fetchOne();
         $bodeganombre = $si->getNombre(); 

         $nuevo_inventario = new Registro();
         $nuevo_inventario->setAccionId(2);
         $nuevo_inventario->setBodegaId($bodega);
         $nuevo_inventario->setBodegaNombre($bodeganombre);
         $nuevo_inventario->setProductoId($id);
         $nuevo_inventario->setNombre($nombre.' '. $codigo);
         $nuevo_inventario->setAccion('Orden de Venta'. $tipo);
         $nuevo_inventario->setUsuarioNombre($usuario);
         $nuevo_inventario->setCantidad($cantidad);
         $nuevo_inventario->setCantidadVieja($vieja_cantidad);
         $nuevo_inventario->setCantidadNueva($nueva_cantidad);
         $nuevo_inventario->save();
    }
}