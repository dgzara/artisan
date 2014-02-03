<?php

/**
 * CentroDeCostos form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CentroDeCostosForm extends BaseCentroDeCostosForm
{
  public function configure()
  {
      unset(
                $this['created_at'], $this['updated_at']
        );

      $this->widgetSchema['area_de_costos_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --', '-- Áreas de Costo --'=>Doctrine_Core::getTable('AreaDeCostos')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

      $this->widgetSchema->setLabels(array(
            'monto_default' => 'Monto Predeterminado',
          'descripcion' => 'Descripción'
        ));
  }
}
