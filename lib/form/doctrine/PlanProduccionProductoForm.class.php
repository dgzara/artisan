<?php

/**
 * PlanProduccionProducto form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PlanProduccionProductoForm extends BasePlanProduccionProductoForm
{
  public function configure()
  {
      unset(
          $this['created_at'], $this['updated_at'], $this['plan_id'], $this['producto_id']
        );

      $this->widgetSchema['producto_id'] = new sfWidgetFormInputText();
      $this->widgetSchema['plan_id'] = new sfWidgetFormInputText();
      $this->widgetSchema['producto_id']->setAttribute('type', 'hidden');
      $this->widgetSchema['plan_id']->setAttribute('type', 'hidden');
      $this->widgetSchema['producto_id']->setHidden(true);
      $this->widgetSchema['plan_id']->setHidden(true);
      $this->validatorSchema['producto_id'] = new sfValidatorInteger();
      $this->validatorSchema['plan_id'] = new sfValidatorInteger(array('required' => false));
      $this->widgetSchema['cantidad']->setDefault(0);
  }

  public function getProductoId(){
      return $this->widgetSchema['producto_id']->getAttribute('value');
  }
}
