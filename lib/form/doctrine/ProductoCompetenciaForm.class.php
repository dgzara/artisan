<?php

/**
 * ProductoCompetencia form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProductoCompetenciaForm extends BaseProductoCompetenciaForm
{
  public function configure()
  {
      unset(
                $this['created_at'], $this['updated_at'], $this['productos_competencia_relacionados_list'],
                $this['productos_relacionados_list'], $this['codigo'], $this['descripcion']
        );


        $this->widgetSchema['marca_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Marcas --'=>Doctrine_Core::getTable('Marca')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

        $this->widgetSchema['unidad'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --', '-- Unidades --'=>Doctrine_Core::getTable('ProductoCompetencia')->getUnidades()),
                    'multiple' => false, 'expanded' => false
                ));

        $this->widgetSchema->setLabels(array(
            'presentacion' => 'Peso/Volumen*',
            'nombre' => 'Nombre*',
            'marca_id' => 'Marca*',
            'unidad' => 'Unidades*'
        ));

        $this->validatorSchema['nombre'] = new sfValidatorString(array(), array('required' => "Campo Obligatorio."));
        $this->validatorSchema['marca_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Marca')), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar una marca de producto.'));
        $this->validatorSchema['presentacion'] = new sfValidatorInteger(array(), array('required' => "Campo Obligatorio."));
        $this->validatorSchema['unidad'] = new sfValidatorInteger(array(), array('required' => "Campo Obligatorio."));
        $this->validatorSchema['unidad'] = new sfValidatorChoice(array('choices' => Doctrine_Core::getTable('ProductoCompetencia')->getUnidades()), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar un campo.'));
  }
}
