<?php

/**
 * PlantillaIngrediente form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PlantillaIngredienteForm extends BasePlantillaIngredienteForm
{
  public function configure()
  {
      unset(
       $this['created_at'], $this['updated_at'], $this['plantilla_pauta_id']
      );

      $this->widgetSchema['insumo_id'] = new sfWidgetFormChoice(array(
            'choices' => array('-- Seleccione --', '-- Insumos --'=>Doctrine_Core::getTable('Insumo')->getNombresInput()),
            'multiple' => false, 'expanded' => false
        ));

      $this->widgetSchema->setLabels(array(
            'cantidad' => 'Cantidad Requerida'

        ));
  }
}
