<?php

/**
 * ProveedorInsumo form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProveedorInsumoForm extends BaseProveedorInsumoForm
{
  public function configure()
  {
      unset(
      $this['created_at'], $this['updated_at']
    );

    $this->widgetSchema['insumo_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Insumos --'=>Doctrine_Core::getTable('Insumo')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

    $this->widgetSchema['proveedor_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Proveedores --'=>Doctrine_Core::getTable('Proveedor')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

    $this->widgetSchema->setLabels(array(
          'insumo_id' => 'Nombre Insumo*',
          'proveedor_id' => 'Razón Social Proveedor*',
          'precio' => 'Precio Compra Neto'

    ));

    $this->validatorSchema['proveedor_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Proveedor')), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar un campo.'));
    $this->validatorSchema['insumo_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Insumo')), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar un campo.'));
    $this->validatorSchema['precio'] = new sfValidatorInteger(array('required' => false));


  }
}
