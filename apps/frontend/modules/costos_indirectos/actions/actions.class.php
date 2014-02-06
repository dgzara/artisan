<?php

/**
 * costos_indirectos actions.
 *
 * @package    quesos
 * @subpackage costos_indirectos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class costos_indirectosActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->formato = new sfNumberFormat('es_CL');

    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('CostosIndirectos');

      $pager = new sfDoctrinePager('CostosIndirectos', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('costos_indirectos/show?id='.$v);
        $mod = $this->getController()->genUrl('costos_indirectos/edit?id='.$v);
        $del = $this->getController()->genUrl('costos_indirectos/delete?id='.$v);

	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getNombre(),
          "2" => $v->getAreaDeCostos()->getNombre(),
          "3" => $v->getCentroDeCostos()->getNombre(),
          "4" => $v->getBodega()->getNombre(),
          "5" => '$'.$this->formato->format($v->getMonto(),'d','CLP'),
          "6" => date('d-m-Y', strtotime($v->getFecha())),
          "7" => '<a href="'.$ver.'"><img src="../images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "8" => '<a href="'.$mod.'"><img src="../images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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
    $this->costos_indirectos = Doctrine_Core::getTable('CostosIndirectos')->find(array($request->getParameter('id')));
    $this->formato = new sfNumberFormat('es_CL');
    $this->forward404Unless($this->costos_indirectos);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new CostosIndirectosForm();
    $this->area_de_costos_id = 0;
    $this->centro_de_costos_id = 0;
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    // Vemos el Area de Costo Seleccionado
    $postParams = $request->getPostParameter('costos_indirectos');
    $this->area_de_costos_id = $postParams['area_de_costos_id'];
    $this->centro_de_costos_id = $postParams['centro_de_costos_id'];

    // Generamos el form
    $this->form = new CostosIndirectosForm();
 
    $this->processForm($request, $this->form);
    
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($costos_indirectos = Doctrine_Core::getTable('CostosIndirectos')->find(array($request->getParameter('id'))), sprintf('Object costos_indirectos does not exist (%s).', $request->getParameter('id')));
    $this->form = new CostosIndirectosForm($costos_indirectos);
    $this->area_de_costos_id = $costos_indirectos->getAreaDeCostosId();
    $this->centro_de_costos_id = $costos_indirectos->getCentroDeCostosId();
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($costos_indirectos = Doctrine_Core::getTable('CostosIndirectos')->find(array($request->getParameter('id'))), sprintf('Object costos_indirectos does not exist (%s).', $request->getParameter('id')));
    $this->form = new CostosIndirectosForm($costos_indirectos);
    
    // Vemos el Area de Costo Seleccionado
    $postParams = $request->getPostParameter('costos_indirectos');
    $this->area_de_costos_id = $postParams['area_de_costos_id'];
    $this->centro_de_costos_id = $postParams['centro_de_costos_id'];

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($costos_indirectos = Doctrine_Core::getTable('CostosIndirectos')->find(array($request->getParameter('id'))), sprintf('Object costos_indirectos does not exist (%s).', $request->getParameter('id')));
    $costos_indirectos->delete();

    $this->redirect('costos_indirectos/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    print_r($form->getName());
    if ($form->isValid())
    {
      $costos_indirectos = $form->save();

      $this->redirect('costos_indirectos/index');
    }
  }

  public function executeCambiarAreaDeCosto(sfWebRequest $request){
      $area_de_costos_id = $request->getPostParameter('area_de_costos_id');
      $area_de_costos = Doctrine::getTable('AreaDeCostos')->findAll();
      
      return $this->renderPartial('global/select', array('nombre' => 'area_de_costos', 'list' => $area_de_costos, 'name' => false, 'selected_id' => $area_de_costos_id));
  }

  public function executeCambiarCentroDeCosto(sfWebRequest $request){
      $area_de_costos_id = $request->getParameter('area_de_costos_id');
      $centro_de_costos_id = $request->getPostParameter('centro_de_costos_id');

      if(!is_null($area_de_costos_id) && $area_de_costos_id != 0 ) {
          $centro_de_costos = Doctrine::getTable('CentroDeCostos')->findByAreaDeCostosId($area_de_costos_id);
      }
      else {
          $centro_de_costos = null;
      }
      return $this->renderPartial('global/select', array('list' => $centro_de_costos, 'name' => false, 'id' => false, 'selected_id' => $centro_de_costos_id));
  }

  public function executeCambiarMonto(sfWebRequest $request){
      $centro_de_costos_id = $request->getPostParameter('centro_de_costos_id');

      if(!is_null($centro_de_costos_id) && $centro_de_costos_id != 0 ) {
          $centro_de_costos = Doctrine::getTable('CentroDeCostos')->findOneById($centro_de_costos_id);
          $monto=$centro_de_costos->getMontoDefault();
	  $desc=$centro_de_costos->getDescripcion();
      }
      else {
          $monto = null;
          $desc=null;
      }
      //echo $centro_de_costos;
      $arr=array('monto'=>$monto, 'desc'=>$desc);
      print json_encode($arr);
      return sfView::NONE;
  }
}
