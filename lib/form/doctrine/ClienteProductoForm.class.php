<?php

/**
 * ClienteProducto form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ClienteProductoForm extends BaseClienteProductoForm
{
  public function configure()
  {
            unset(
      $this['created_at'], $this['updated_at']
    );

              $this->widgetSchema['producto_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --', '-- Productos --'=>Doctrine_Core::getTable('Producto')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

    $this->widgetSchema['cliente_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Clientes --'=>Doctrine_Core::getTable('Cliente')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

    $this->widgetSchema->setLabels(array(
          'cliente_id' => 'Cliente*',
          'producto_id' => 'Producto*',
          'precio' => 'Precio Venta Neto',
          'stock_critico' => 'Stock Crítico*'
    ));

    $this->validatorSchema['cliente_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cliente')), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar un campo.'));
    $this->validatorSchema['producto_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Producto')), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar un campo.'));
    $this->validatorSchema['precio'] = new sfValidatorInteger(array('required' => false));
    $this->validatorSchema['stock_critico'] = new sfValidatorInteger(array('required' => false));



  }

}
