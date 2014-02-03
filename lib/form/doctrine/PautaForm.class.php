<?php

/**
 * Pauta form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PautaForm extends BasePautaForm
{
  public function configure()
  {
      unset(
            $this['created_at'], $this['updated_at']
      );

      $this->widgetSchema['plantilla_pauta_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Pautas --'=>Doctrine_Core::getTable('PlantillaPauta')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

      $this->widgetSchema->setLabels(array(
            'plantilla_pauta_id' => 'Pauta de Elaboración a Seguir',
            'cultivos_lacticos' => 'Cultivos Lácticos',
            'cantidad_leche' => 'Cantidad de Leche'

        ));

      $this->widgetSchema['fecha'] = new
            sfWidgetFormJQueryDate(array(
          'date_widget'     => new sfWidgetFormI18nDate(array('culture' => 'es')),
            'culture'         => 'es'
             ));

      $this->setDefault('fecha', date('Y/m/d/H:m'));
  }
}
