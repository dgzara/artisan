<?php

/**
 * area_de_costos actions.
 *
 * @package    quesos
 * @subpackage area_de_costos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class area_de_costosActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('AreaDeCostos');

      $pager = new sfDoctrinePager('AreaDeCostos', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('area_de_costos/show?id='.$v);
        $mod = $this->getController()->genUrl('area_de_costos/edit?id='.$v);
        $del = $this->getController()->genUrl('area_de_costos/delete?id='.$v);

	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getNombre(),
          "2" => '<a href="'.$ver.'"><img src="/web/images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "3" => '<a href="'.$mod.'"><img src="/web/images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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
    $this->area_de_costos = Doctrine_Core::getTable('AreaDeCostos')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->area_de_costos);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new AreaDeCostosForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new AreaDeCostosForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($area_de_costos = Doctrine_Core::getTable('AreaDeCostos')->find(array($request->getParameter('id'))), sprintf('Object area_de_costos does not exist (%s).', $request->getParameter('id')));
    $this->form = new AreaDeCostosForm($area_de_costos);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($area_de_costos = Doctrine_Core::getTable('AreaDeCostos')->find(array($request->getParameter('id'))), sprintf('Object area_de_costos does not exist (%s).', $request->getParameter('id')));
    $this->form = new AreaDeCostosForm($area_de_costos);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($area_de_costos = Doctrine_Core::getTable('AreaDeCostos')->find(array($request->getParameter('id'))), sprintf('Object area_de_costos does not exist (%s).', $request->getParameter('id')));
    $area_de_costos->delete();

    $this->redirect('area_de_costos/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $area_de_costos = $form->save();

      $this->redirect('area_de_costos/index');
    }
  }
}
