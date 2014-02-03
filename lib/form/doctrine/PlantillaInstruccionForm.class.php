<?php

/**
 * PlantillaInstruccion form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PlantillaInstruccionForm extends BasePlantillaInstruccionForm
{
  public function configure()
  {
      $this->setWidgets(array(
          'id'                 => new sfWidgetFormInputHidden(),
          'orden'              => new sfWidgetFormInputText(),
          'descripcion'        => new sfWidgetFormTextarea(),
          'plantilla_etapa_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PlantillaEtapa'), 'add_empty' => false)),
          'created_at'         => new sfWidgetFormDateTime(),
          'updated_at'         => new sfWidgetFormDateTime(),
        ));

      unset(
       $this['created_at'], $this['updated_at'], $this['plantilla_etapa_id']
      );

      $this->widgetSchema['descripcion']->setAttribute('size', '100');
      $this->widgetSchema['descripcion']->setAttribute('row', '2');

      $this->widgetSchema['orden']->setAttribute('type', 'hidden');
      $this->widgetSchema['orden']->setHidden(true);
  }
}
