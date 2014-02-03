<?php

/**
 * Rama form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RamaForm extends BaseRamaForm
{
  public function configure()
  {
        unset(
                 $this['created_at'], $this['updated_at'], $this['expires_at']
        );

        $this->widgetSchema->setLabels(array(
          'nombre' => 'Nombre Área de Negocio*'
        ));

        $this->validatorSchema['nombre'] = new sfValidatorString(array(), array('required' => "Campo Obligatorio."));

  }
}
