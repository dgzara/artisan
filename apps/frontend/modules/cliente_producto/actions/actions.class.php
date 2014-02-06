<?php

/**
 * cliente_producto actions.
 *
 * @package    quesos
 * @subpackage cliente_producto
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cliente_productoActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $formato = new sfNumberFormat('es_CL');

    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('ClienteProducto');

      $pager = new sfDoctrinePager('ClienteProducto', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('cliente_producto/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('cliente_producto/edit?id='.$v->getId());
        $del = $this->getController()->genUrl('cliente_producto/delete?id='.$v->getId());

	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getProducto()->getNombreCompleto(),
          "2" => $v->getCliente()->getName(),
          "3" => '$'.$formato->format($v->getPrecio(), 'd', 'CLP'),
          "4" => $v->getStockCritico(),
          "5" => '<a href="'.$ver.'"><img src="images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "6" => '<a href="'.$mod.'"><img src="images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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
    $this->cliente_producto = Doctrine_Core::getTable('ClienteProducto')->find(array($request->getParameter('id')));
    $this->formato = new sfNumberFormat('es_CL');
    $this->forward404Unless($this->cliente_producto);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ClienteProductoForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ClienteProductoForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($cliente_producto = Doctrine_Core::getTable('ClienteProducto')->find(array($request->getParameter('id'))), sprintf('Object cliente_producto does not exist (%s).', $request->getParameter('id')));
    $this->form = new ClienteProductoForm($cliente_producto);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($cliente_producto = Doctrine_Core::getTable('ClienteProducto')->find(array($request->getParameter('id'))), sprintf('Object cliente_producto does not exist (%s).', $request->getParameter('id')));
    $this->form = new ClienteProductoForm($cliente_producto);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($cliente_producto = Doctrine_Core::getTable('ClienteProducto')->find(array($request->getParameter('id'))), sprintf('Object cliente_producto does not exist (%s).', $request->getParameter('id')));
    $cliente_producto->delete();

    $this->redirect('cliente_producto/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $cliente_producto = $form->save();

      $this->redirect('cliente_producto/index');
    }
  }
}
