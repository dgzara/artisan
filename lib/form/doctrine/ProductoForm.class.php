<?php

/**
 * Producto form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProductoForm extends BaseProductoForm {

    public function configure() {
        unset(
                $this['planesde_produccion_list'], $this['created_at'], $this['updated_at'],
                $this['expires_at'], $this['is_activated'], $this['productos_relacionados_list'], $this['codigo']
        );

        $this->widgetSchema['unidad'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --', '-- Unidades --'=>Doctrine_Core::getTable('Producto')->getUnidades()),
                    'multiple' => false, 'expanded' => false
                ));

        $this->widgetSchema['rama_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --', '-- Áreas de Negocio --'=>Doctrine_Core::getTable('Rama')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

        $this->widgetSchema->setLabels(array(
            'codigo' => 'Código*',
            'rama_id' => 'Área de Negocio*',
            'duracion' => 'Ciclo de Vida Comercial*',
            'maduracion' => 'Ciclo de Vida Producción*',
            'stock_critico' => 'Stock Crítico de Inventario*',
            'unidad' => 'Unidad*',
            'nombre' => 'Nombre Producto*',
            'presentacion' => 'Peso o Volumen de Producto*'

        ));

        $this->validatorSchema['nombre'] = new sfValidatorString(array(), array('required' => "Campo Obligatorio."));
        $this->validatorSchema['rama_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Rama')), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar un área de negocio.'));
        $this->validatorSchema['presentacion'] = new sfValidatorInteger(array(), array('required' => "Campo Obligatorio."));
        $this->validatorSchema['unidad'] = new sfValidatorChoice(array('choices' => Doctrine_Core::getTable('Producto')->getUnidades()), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar un campo.'));
        $this->validatorSchema['duracion'] = new sfValidatorInteger(array(), array('required' => "Campo Obligatorio."));
        $this->validatorSchema['maduracion'] = new sfValidatorInteger(array(), array('required' => "Campo Obligatorio."));
        $this->validatorSchema['stock_critico'] = new sfValidatorInteger(array(), array('required' => "Campo Obligatorio."));



    }

}
