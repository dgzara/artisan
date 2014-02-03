<?php

/**
 * PlanProduccion form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PlanProduccionForm extends BasePlanProduccionForm
{
  public function configure()
  {
      unset(
      $this['created_at'], $this['updated_at'],
      $this['expires_at'], $this['is_activated']
    );

 //       $this->widgetSchema['fecha'] = new sfWidgetFormJQueryDate(array(
 //   'image'=> '/images/icons/calendar_view_month.gif'));

          $this->widgetSchema['fecha'] = new
            sfWidgetFormJQueryDate(array(
          'date_widget'     => new sfWidgetFormI18nDate(array('culture' => 'es')),
            'culture'         => 'es'
             ));

      $this->setDefault('fecha', date('Y/m/d/H:m'));
      
      
      $this->widgetSchema->setLabels(array(
            'fecha' => 'Fecha*'
        ));


  }
}
