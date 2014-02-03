<?php

/**
 * OrdenVentaProducto form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class OrdenVentaProductoForm extends BaseOrdenVentaProductoForm
{
  public function configure()
  {

      unset($this['created_at'], $this['updated_at'], $this['orden_venta_id'], $this['producto_id']);

      $this->widgetSchema['producto_id'] = new sfWidgetFormInputText();
      $this->widgetSchema['orden_venta_id'] = new sfWidgetFormInputText();
      $this->widgetSchema['neto'] = new sfWidgetFormInputText();
      $this->widgetSchema['producto_id']->setAttribute('type', 'hidden');
      $this->widgetSchema['orden_venta_id']->setAttribute('type', 'hidden');
      $this->widgetSchema['producto_id']->setHidden(true);
      $this->widgetSchema['orden_venta_id']->setHidden(true);
      $this->widgetSchema['detalle']->setHidden(true);
      $this->widgetSchema['neto']->setHidden(true);
      $this->validatorSchema['producto_id'] = new sfValidatorInteger();
      $this->validatorSchema['orden_venta_id'] = new sfValidatorInteger(array('required' => false));
      $this->widgetSchema['cantidad']->setDefault(0);
      $this->widgetSchema['descuento']->setDefault(0);
      $this->widgetSchema['neto']->setDefault(0);
      $this->validatorSchema['descuento'] = new sfValidatorInteger(array('min'=>0, 'max'=>100));

  }

  public function getStockDisponible(){
      
      $producto_id = $this->widgetSchema['producto_id']->getAttribute('value');
      
      if(!is_null($producto_id)){
          $producto = Doctrine_Core::getTable('Producto')->findOneById($producto_id);
          return $producto->getStockSantiago() + $producto->getTransito();
      }
      else{
          return null;
      }
  }
}
