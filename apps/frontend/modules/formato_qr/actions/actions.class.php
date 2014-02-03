<?php

/**
 * formato_qr actions.
 *
 * @package    quesos
 * @subpackage formato_qr
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class formato_qrActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    

  }



   public function executeEdit()
  {
    $this->forward404Unless($formato = Doctrine_Core::getTable('FormatoQr')->find(1), sprintf('Object cliente does not exist (%s).', 1 ));
    $this->form = new FormatoQrForm($formato);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($formato = Doctrine_Core::getTable('FormatoQr')->find(array($request->getParameter('id'))), sprintf('Object cliente does not exist (%s).', $request->getParameter('id')));
    $this->form = new FormatoQrForm($formato);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

   protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $formato = $form->save();
      $this->redirect('cliente/index');
    }
  }


}
