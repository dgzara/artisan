<?php

/**
 * insumo actions.
 *
 * @package    quesos
 * @subpackage insumo
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class insumoActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('Insumo');

      $pager = new sfDoctrinePager('Insumo', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('insumo/show?id='.$v);
        $mod = $this->getController()->genUrl('insumo/edit?id='.$v);
        $del = $this->getController()->genUrl('insumo/delete?id='.$v);
        $valor=$v->getUnidad();
        if(valor==0)
        {
          $valor = '';
        }
	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getNombre(),
          "2" => $v->getPresentacion().' '.$valor,
          "3" => $v->getStockCritico(),
          "4" => '<a href="'.$ver.'"><img src="/web/images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "5" => '<a href="'.$mod.'"><img src="/web/images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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
    $this->insumo = Doctrine_Core::getTable('Insumo')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->insumo);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new InsumoForm();
  }

   public function executeAsignar(sfWebRequest $request)
  {
    $this->form = new ProveedorInsumoForm();

  }

   public function executeAsigna(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ProveedorInsumoForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('asignar');
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new InsumoForm();

    $this->processForm($request, $this->form);

    $q = Doctrine_Query::create();
        $q->select('i.id');
        $q->from('Insumo i');
        $q->orderBy('i.created_at DESC');
        $id=$q->fetchOne();

    $nuevo_inventario = new InventarioMateriaPrima();
        $nuevo_inventario->setFecha(date('Y/m/d/H:m'));
        $nuevo_inventario->setCantidad(0);
        $nuevo_inventario->setInsumoId($id);
        $nuevo_inventario->save();

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($insumo = Doctrine_Core::getTable('Insumo')->find(array($request->getParameter('id'))), sprintf('Object insumo does not exist (%s).', $request->getParameter('id')));
    $this->form = new InsumoForm($insumo);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($insumo = Doctrine_Core::getTable('Insumo')->find(array($request->getParameter('id'))), sprintf('Object insumo does not exist (%s).', $request->getParameter('id')));
    $this->form = new InsumoForm($insumo);

    $this->processForm($request, $this->form);

    $this->redirect('insumo/index');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($insumo = Doctrine_Core::getTable('Insumo')->find(array($request->getParameter('id'))), sprintf('Object insumo does not exist (%s).', $request->getParameter('id')));
    $insumo->delete();

    $this->redirect('insumo/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $insumo = $form->save();

      $this->redirect('insumo/index');
      
    }
  }
}
