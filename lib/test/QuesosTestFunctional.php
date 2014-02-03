<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QuesosTestFunctional
 *
 * @author dgomezara
 */
class QuesosTestFunctional extends sfTestFunctional{
    //put your code here
    public function loadData()
    {
      Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures');
      return $this;
    }
}
?>