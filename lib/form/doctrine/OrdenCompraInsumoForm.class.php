<?php

/**
 * OrdenCompraInsumo form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class OrdenCompraInsumoForm extends BaseOrdenCompraInsumoForm
{
  public function configure()
  {
            unset(
          $this['created_at'], $this['updated_at'], $this['orden_compra_id'], $this['insumo_id']
        );

      $this->widgetSchema['insumo_id'] = new sfWidgetFormInputText();
      $this->widgetSchema['orden_compra_id'] = new sfWidgetFormInputText();
      $this->widgetSchema['neto'] = new sfWidgetFormInputText();
      $this->widgetSchema['conversion'] = new sfWidgetFormInputText();
      $this->widgetSchema['insumo_id']->setAttribute('type', 'hidden');
      $this->widgetSchema['orden_compra_id']->setAttribute('type', 'hidden');
      $this->widgetSchema['orden_compra_id']->setHidden(true);
      $this->widgetSchema['detalle']->setHidden(true);
      $this->widgetSchema['neto']->setHidden(true);
      $this->widgetSchema['conversion']->setHidden(true);
      $this->validatorSchema['insumo_id'] = new sfValidatorInteger();
      $this->validatorSchema['orden_compra_id'] = new sfValidatorInteger(array('required' => false));
      $this->widgetSchema['cantidad']->setHidden(true);
      $this->widgetSchema['cantidad']->setDefault(0);
      $this->widgetSchema['neto']->setDefault(0);
      $this->widgetSchema['conversion']->setDefault(1);
  }
}
