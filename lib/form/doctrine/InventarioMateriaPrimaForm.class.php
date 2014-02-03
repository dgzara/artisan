<?php

/**
 * InventarioMateriaPrima form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class InventarioMateriaPrimaForm extends BaseInventarioMateriaPrimaForm
{
  public function configure()
  {
unset(
      $this['created_at'], $this['updated_at'], $this['fecha']
    );

    $this->widgetSchema['insumo_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Insumos --'=>Doctrine_Core::getTable('Insumo')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

;

  $this->validatorSchema['insumo_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Insumo')), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar un campo.'));

  }
}
