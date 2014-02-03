<?php

/**
 * AspectoCalidad form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AspectoCalidadForm extends BaseAspectoCalidadForm
{
  public function configure()
  {
    $this->widgetSchema['captura_id'] = new sfWidgetFormInputText();
    $this->validatorSchema['captura_id'] = new sfValidatorInteger(array('required' => false));

     unset(
           $this['captura_id'], $this['created_at'], $this['updated_at']
        );

  }
}
