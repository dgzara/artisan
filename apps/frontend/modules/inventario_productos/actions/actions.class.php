<?php

/**
 * inventario_productos actions.
 *
 * @package    quesos
 * @subpackage inventario_productos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class inventario_productosActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ramas = Doctrine_Core::getTable('Rama')->findAll();
    $this->bodega = Doctrine_Core::getTable('Bodega')->findOneById(1);

    $this->inventario_productos = array();
    
    foreach($this->ramas as $rama){
        $this->inventario_productos[] = Doctrine_Core::getTable('InventarioProductos')->getTodasPorRama('1', $rama->getId());
    }
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->inventario_productos = Doctrine_Core::getTable('InventarioProductos')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->inventario_productos);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new InventarioProductosForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new InventarioProductosForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($inventario_productos = Doctrine_Core::getTable('InventarioProductos')->find(array($request->getParameter('id'))), sprintf('Object inventario_productos does not exist (%s).', $request->getParameter('id')));
    $this->form = new InventarioProductosForm($inventario_productos);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($inventario_productos = Doctrine_Core::getTable('InventarioProductos')->find(array($request->getParameter('id'))), sprintf('Object inventario_productos does not exist (%s).', $request->getParameter('id')));
    $this->form = new InventarioProductosForm($inventario_productos);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($inventario_productos = Doctrine_Core::getTable('InventarioProductos')->find(array($request->getParameter('id'))), sprintf('Object inventario_productos does not exist (%s).', $request->getParameter('id')));
    $inventario_productos->delete();

    $this->redirect('inventario_productos/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $inventario_productos = $form->save();

      $this->redirect('inventario_productos/edit?id='.$inventario_productos->getId());
    }
  }
}
