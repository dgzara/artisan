<?php

/**
 * Insumo form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class InsumoForm extends BaseInsumoForm
{
  public function configure()
  {

        $this->widgetSchema['unidad'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --', '-- Unidades --'=>Doctrine_Core::getTable('Insumo')->getUnidades()),
                    'multiple' => false, 'expanded' => false
                ));

        $this->widgetSchema['presentacion'] = new sfWidgetFormInputText();
        $this->widgetSchema['presentacion']->setHidden(false);
        $this->widgetSchema['presentacion']->setDefault(1);

        unset(
      $this['created_at'], $this['updated_at'], $this['orden_compras_list'],
      $this['expires_at'], $this['is_activated']
    );

        $this->widgetSchema->setLabels(array(
            'stock_critico' => 'Stock Crítico (en cantidad de uso)*',
            'nombre' => 'Nombre de Insumo*',
            'unidad' => 'Unidad de Medida',
            'presentacion' => 'Cantidad de Uso por unidad de compra. (ingrese "1" si no existe conversión)*'

        ));


    //$this->validatorSchema['unidad'] = new sfValidatorChoice(array('choices' => Doctrine_Core::getTable('Insumo')->getUnidades()), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar un campo.'));
    $this->validatorSchema['nombre'] = new sfValidatorString(array(), array('required' => "Campo Obligatorio."));
    $this->validatorSchema['presentacion'] = new sfValidatorInteger(array('required' => "Campo Obligatorio."));
    $this->validatorSchema['stock_critico'] = new sfValidatorInteger(array(), array('required' => "Campo Obligatorio."));


  }
}
