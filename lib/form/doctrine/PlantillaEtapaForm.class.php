<?php

/**
 * PlantillaEtapa form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PlantillaEtapaForm extends BasePlantillaEtapaForm
{
  public function configure()
  {
       unset(
               $this['created_at'], $this['updated_at'], $this['plantilla_pauta_id']
              );
       $this->widgetSchema['nombre']->setAttribute('size', '100');

       $this->widgetSchema['orden'] = new sfWidgetFormInputText();
      $this->widgetSchema['orden']->setAttribute('type', 'hidden');
      $this->widgetSchema['orden']->setHidden(true);
  }
}
