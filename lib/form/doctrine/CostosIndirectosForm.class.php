<?php

/**
 * CostosIndirectos form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CostosIndirectosForm extends BaseCostosIndirectosForm
{
  public function configure()
  {
      unset(
                $this['created_at'], $this['updated_at']
        );
      $this->widgetSchema['descripcion'] = new sfWidgetFormTextarea();

      $this->widgetSchema['area_de_costos_id'] = new sfWidgetFormChoice(array(
            'choices' => array('-- Seleccione --', '-- Areas --' => Doctrine_Core::getTable('AreaDeCostos')->getNombresInput()),
            'multiple' => false, 'expanded' => false
        ));

      $this->widgetSchema['centro_de_costos_id'] = new sfWidgetFormChoice(array(
            'choices' => array('-- Seleccione --', '-- Centros --' => Doctrine_Core::getTable('CentroDeCostos')->getNombresInput()),
            'multiple' => false, 'expanded' => false
        ));

      $this->widgetSchema['bodega_id'] = new sfWidgetFormChoice(array(
            'choices'=> array('-- Seleccione --','-- Lugares --' => Doctrine_Core::getTable('Bodega')->getNombresInput()),
            'multiple' => false, 'expanded' => false
        ));

      $this->widgetSchema->setLabels(array(
            'area_de_costos_id' => 'Área de Costos *',
            'centro_de_costos_id' => 'Centro de Costos *',
            'descripcion' => 'Descripcion ',
            'nombre' => 'Nombre *',
            'bodega_id' => 'Lugar *',
            'monto' => 'Monto *'
        ));

      $this->widgetSchema['archivo_adjunto'] = new sfWidgetFormInputFile(array(
          'label' => 'Archivo Adjunto',
        ));

      $this->widgetSchema->setPositions(array('id','area_de_costos_id', 'centro_de_costos_id', 'descripcion', 'nombre', 'bodega_id', 'monto', 'fecha', 'detalle', 'archivo_adjunto'));

      $this->setDefault('fecha', date('Y/m/d/H:m'));
      $this->widgetSchema['fecha'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
      $this->widgetSchema['fecha']->setLabel("Fecha *");

      $this->validatorSchema['fecha'] = new sfValidatorDate();

      $this->validatorSchema['archivo_adjunto'] = new sfValidatorFile(array(
          'required'   => false,
          'path'       => sfConfig::get('sf_upload_dir').'/costosindirectos'
        ));
  }
}
