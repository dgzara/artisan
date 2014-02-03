<?php

/**
 * PlantillaPautaColumna form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PlantillaPautaColumnaForm extends BasePlantillaPautaColumnaForm
{
  public function configure()
  {
    unset(
       $this['created_at'], $this['updated_at'], $this['plantilla_pauta_id']
      );

      $this->widgetSchema['plantilla_columna_id'] = new sfWidgetFormChoice(array(
                'choices'  => Doctrine_Core::getTable('PlantillaColumna')->getNombres(),
                'multiple' => true,
                'expanded' => true,
      ));
  }
}
