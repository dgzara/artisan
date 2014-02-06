<?php

/**
 * rama actions.
 *
 * @package    quesos
 * @subpackage rama
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ramaActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('Rama');

      $pager = new sfDoctrinePager('Rama', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('rama/show?id='.$v);
        $mod = $this->getController()->genUrl('rama/edit?id='.$v);
        $del = $this->getController()->genUrl('rama/delete?id='.$v);

	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getNombre(),
          "2" => '<a href="'.$ver.'"><img src="images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "3" => '<a href="'.$mod.'"><img src="images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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
    $this->rama = Doctrine_Core::getTable('Rama')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->rama);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new RamaForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new RamaForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($rama = Doctrine_Core::getTable('Rama')->find(array($request->getParameter('id'))), sprintf('Object rama does not exist (%s).', $request->getParameter('id')));
    $this->form = new RamaForm($rama);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($rama = Doctrine_Core::getTable('Rama')->find(array($request->getParameter('id'))), sprintf('Object rama does not exist (%s).', $request->getParameter('id')));
    $this->form = new RamaForm($rama);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($rama = Doctrine_Core::getTable('Rama')->find(array($request->getParameter('id'))), sprintf('Object rama does not exist (%s).', $request->getParameter('id')));
    $rama->delete();

    $this->redirect('rama/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $rama = $form->save();
      $this->redirect('rama/index');
    }
  }
}
