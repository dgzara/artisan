<?php

require_once dirname(__FILE__).'/../lib/sfGuardPermissionGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sfGuardPermissionGeneratorHelper.class.php';

/**
 * sfGuardUser actions.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardUser
 * @author     Fabien Potencier
 * @version    SVN: $Id: actions.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardPermissionActions extends autoSfGuardPermissionActions
{
      public function executeIndex(sfWebRequest $request)
          {
            $this->permisos = Doctrine_Core::getTable('SfGuardPermission')
              ->createQuery('a')
              ->orderBy('description ASC')
              ->execute();
          }

      protected function processForm(sfWebRequest $request, sfForm $form)
          {
            $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
            if ($form->isValid())
            {
              $cliente = $form->save();
              $this->redirect('guard/permissions');
            }
          }
       
       
}
