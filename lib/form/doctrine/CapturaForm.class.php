<?php

/**
 * Captura form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CapturaForm extends BaseCapturaForm
{
  public function configure()
  {
      unset(
                $this['created_at'], $this['updated_at'] 
              /**, $this['producto_id'], $this['local_id'] */
        );
  }
}
