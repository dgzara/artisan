<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
/**
 * sfWidgetFormoctrineJQueryAutocompleter represents an autocompleter input widget rendered by JQuery
 * optimized for foreign key lookup.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormPropelJQueryAutocompleter.class.php 12130 2008-10-10 14:51:07Z fabien $
 */
class sfWidgetFormRut extends sfWidgetFormInput
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    dm::getResponse()->addJavascript('/sfRutPlugin/js/jquery.Rut.min.js');

    return $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value, "class" => "rut_input"), $attributes)) .

<<<EOHTML
<script type="text/javascript">
  $(window).load(function(){
    $("#{$this->generateId($name)}").Rut({
      on_error: function(){
        alert('Rut incorrecto');
  //      $("#{$this->generateId($name)}").select();
      },
      format_on: 'blur'
    });
    $('#{$this->generateId($name)}').trigger('blur');
  });
</script>
EOHTML;
  }
}
