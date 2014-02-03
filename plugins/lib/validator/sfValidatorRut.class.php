<?php

/* Copyright (c) 2008 José Joaquín Núñez (josejnv@gmail.com) http://joaquinnunez.cl
 * Licensed under GPL (http://www.opensource.org/licenses/gpl-2.0.php)
 * Use only for non-commercial usage.
 *
 * Version : 0.1
*/
class sfValidatorRut extends sfValidatorBase
{

  protected function configure($options = array(), $messages = array())
  {
    $this->setMessage('invalid', 'El rut ingresado es inválido');
  }

  protected function doClean($values)
  {
    $r = strtoupper(str_replace(array(".", "-"), "", $values));
    $sub_rut = substr($r, 0, strlen($r) - 1);
    $sub_dv = substr($r,  - 1);
    $x = 2;
    $s = 0;
    for ($i = strlen($sub_rut) - 1;$i >= 0;$i--)
    {
      if ($x > 7)
      {
        $x = 2;
      }
      $s += $sub_rut[$i] * $x;
      $x++;
    }
    $dv = 11 - ($s % 11);
    if ($dv == 10)
    {
      $dv = 'K';
    }
    if ($dv == 11)
    {
      $dv = '0';
    }
    if ($dv == $sub_dv)
    {
      return $values;
    }
    else
    {
      throw new sfValidatorError($this, 'invalid', array('values' => $values));
    }
  }
}


