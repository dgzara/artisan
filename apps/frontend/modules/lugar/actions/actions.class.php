<?php

/**
 * lugar actions.
 *
 * @package    quesos
 * @subpackage lugar
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class lugarActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('Bodega');

      $pager = new sfDoctrinePager('Bodega', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('lugar/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('lugar/edit?id='.$v->getId());

	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getNombre(),
          "2" => '<a href="'.$ver.'"><img src="../images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "3" => '<a href="'.$mod.'"><img src="../images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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
    $this->bodega = Doctrine_Core::getTable('Bodega')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->bodega);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new BodegaForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new BodegaForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($bodega = Doctrine_Core::getTable('Bodega')->find(array($request->getParameter('id'))), sprintf('Object bodega does not exist (%s).', $request->getParameter('id')));
    $this->form = new BodegaForm($bodega);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($bodega = Doctrine_Core::getTable('Bodega')->find(array($request->getParameter('id'))), sprintf('Object bodega does not exist (%s).', $request->getParameter('id')));
    $this->form = new BodegaForm($bodega);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($bodega = Doctrine_Core::getTable('Bodega')->find(array($request->getParameter('id'))), sprintf('Object bodega does not exist (%s).', $request->getParameter('id')));
    $bodega->delete();

    $this->redirect('lugar/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $bodega = $form->save();

      $this->redirect('lugar/index');
    }
  }
}
