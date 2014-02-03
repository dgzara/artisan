<?php

/**
 * PlantillaPauta form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PlantillaPautaForm extends BasePlantillaPautaForm
{
  public function configure()
  {
      unset(
               $this['created_at'], $this['updated_at'], $this['codigo']
              );

      $this->widgetSchema['rama_id'] = new sfWidgetFormChoice(array(
          'choices' => array('-- Seleccione --', '-- Áreas de Negocios --'=>Doctrine_Core::getTable('Rama')->getNombresInput()),
          'expanded' => false,
          'multiple' => false,
        ));


      $this->widgetSchema['columnas_list'] = new sfWidgetFormChoice(array(
                'choices'  => Doctrine_Core::getTable('PlantillaColumna')->getNombres(),
                'multiple' => true,
                'expanded' => true,
      ));
      $this->widgetSchema->setLabels(array(
          'rama_id'    => 'Área de Negocios*',
          'nombre'    => 'Nombre*',
          'columnas_list'    => 'Lista Columnas*'
        ));

      $this->validatorSchema['nombre'] = new sfValidatorString(array(), array('required' => "Campo Obligatorio."));
      $this->validatorSchema['rama_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Rama')), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar un área de negocio.'));
      $this->validatorSchema['columnas_list'] = new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'PlantillaColumna'), array('required' => 'Debe seleccionar una o más columnas.'));

  }
}
