<?php

/**
 * FormatoQr form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FormatoQrForm extends BaseFormatoQrForm
{
  public function configure()
  {

  	unset(
      $this['created_at'], $this['updated_at'],
      $this['expires_at'], $this['is_activated']
    );

  	//debiera ser float sfValidatorNumber
    $this->validatorSchema['ancho'] = new sfValidatorNumber(array('min' => 3));
    $this->validatorSchema['largo'] = new sfValidatorNumber(array('min' => 3));

  }
}
