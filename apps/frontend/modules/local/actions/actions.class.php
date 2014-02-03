<?php

/**
 * local actions.
 *
 * @package    quesos
 * @subpackage local
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class localActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('Local');

      $pager = new sfDoctrinePager('Local', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('local/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('local/edit?id='.$v->getId());
        $del = $this->getController()->genUrl('local/delete?id='.$v->getId());

	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getCliente()->getName(),
          "2" => $v->getNombre(),
          "3" => $v->getDireccion(),
          "4" => $v->getContacto_1(),
          "5" => $v->getEmail_1(),
          "6" => $v->getCel_1(),
          "7" => '<a href="'.$ver.'"><img src="/web/images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "8" => '<a href="'.$mod.'"><img src="/web/images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
        );
      }

      $output = array(
     	"iTotalRecords" => count($pager),
     	"iTotalDisplayRecords" => $request->getParameter('iDisplayLength'),
    	"aaData" => $aaData,
      );

      return $this->renderText(json_encode($output));
   }
  }


  public function executeShow(sfWebRequest $request)
  {
    $this->local = Doctrine_Core::getTable('Local')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->local);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new LocalForm();

  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new LocalForm();

    $this->processForm($request, $this->form);

    //$this->redirect('local/index');
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($local = Doctrine_Core::getTable('Local')->find(array($request->getParameter('id'))), sprintf('Object local does not exist (%s).', $request->getParameter('id')));
    $this->form = new LocalForm($local);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($local = Doctrine_Core::getTable('Local')->find(array($request->getParameter('id'))), sprintf('Object local does not exist (%s).', $request->getParameter('id')));
    $this->form = new LocalForm($local);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($local = Doctrine_Core::getTable('Local')->find(array($request->getParameter('id'))), sprintf('Object local does not exist (%s).', $request->getParameter('id')));
    $local->delete();

    $this->redirect('local/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $local = $form->save();
      $this->redirect('local/index');
    }

    //if (!$form->isValid())
    //{
    //  $orden_compra = $form->save();
    //}
    //else{
    //    echo $form;
    //}


  }
}
