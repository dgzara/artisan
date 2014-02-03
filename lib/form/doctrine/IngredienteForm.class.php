<?php

/**
 * Ingrediente form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class IngredienteForm extends BaseIngredienteForm
{
  public function configure()
  {
      unset(
        $this['created_at'], $this['updated_at'], $this['pauta_id']
    );

      $this->widgetSchema['plantilla_ingrediente_id'] = new sfWidgetFormInputHidden();
      $this->validatorSchema['plantilla_ingrediente_id'] = new sfValidatorInteger();

  }
}
