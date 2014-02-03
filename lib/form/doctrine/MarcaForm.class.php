<?php

/**
 * Marca form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MarcaForm extends BaseMarcaForm
{
  public function configure()
  {
      unset(
                $this['created_at'], $this['updated_at']
        );

      $this->widgetSchema->setLabels(array(
            'nombre' => 'Nombre*',
            'rubro' => 'Rubro*'
        ));

      $this->validatorSchema['nombre'] = new sfValidatorString(array(), array('required' => "Campo Obligatorio."));
      $this->validatorSchema['rubro'] = new sfValidatorString(array(), array('required' => "Campo Obligatorio."));
  }
}