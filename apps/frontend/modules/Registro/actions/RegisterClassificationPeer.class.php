  <?php

/**
 * Registro actions.
 *
 * @package    quesos
 * @subpackage Registro
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
public static function doSelectPager($page=1, $item_per_page = 10, Criteria $criteria = null)

{

  if ($criteria === null)

 {

    $criteria = new Criteria();

 }

  $pager = new sfPropelPager('RegisterClassification', $item_per_page);

  $pager->setCriteria($criteria);

  $pager->setPage($page);

  $pager->setPeerMethod('doSelect');

  $pager->init();

  return $pager;

}

}
