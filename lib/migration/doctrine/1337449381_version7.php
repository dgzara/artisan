<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version7 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('unidad_lote', 'unidad_lote_lote_id_lote_id', array(
             'name' => 'unidad_lote_lote_id_lote_id',
             'local' => 'lote_id',
             'foreign' => 'id',
             'foreignTable' => 'lote',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('unidad_lote', 'unidad_lote_orden_venta_id_orden_venta_id', array(
             'name' => 'unidad_lote_orden_venta_id_orden_venta_id',
             'local' => 'orden_venta_id',
             'foreign' => 'id',
             'foreignTable' => 'orden_venta',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('unidad_lote', 'unidad_lote_lote_id', array(
             'fields' => 
             array(
              0 => 'lote_id',
             ),
             ));
        $this->addIndex('unidad_lote', 'unidad_lote_orden_venta_id', array(
             'fields' => 
             array(
              0 => 'orden_venta_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('unidad_lote', 'unidad_lote_lote_id_lote_id');
        $this->dropForeignKey('unidad_lote', 'unidad_lote_orden_venta_id_orden_venta_id');
        $this->removeIndex('unidad_lote', 'unidad_lote_lote_id', array(
             'fields' => 
             array(
              0 => 'lote_id',
             ),
             ));
        $this->removeIndex('unidad_lote', 'unidad_lote_orden_venta_id', array(
             'fields' => 
             array(
              0 => 'orden_venta_id',
             ),
             ));
    }
}