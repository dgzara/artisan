<?php

/**
 * descriptor_producto actions.
 *
 * @package    quesos
 * @subpackage descriptor_producto
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class descriptor_productoActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->descriptor_de_productos = Doctrine_Core::getTable('DescriptorDeProducto')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->descriptor_de_producto = Doctrine_Core::getTable('DescriptorDeProducto')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->descriptor_de_producto);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new DescriptorDeProductoForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new DescriptorDeProductoForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($descriptor_de_producto = Doctrine_Core::getTable('DescriptorDeProducto')->find(array($request->getParameter('id'))), sprintf('Object descriptor_de_producto does not exist (%s).', $request->getParameter('id')));
    $this->form = new DescriptorDeProductoForm($descriptor_de_producto);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($descriptor_de_producto = Doctrine_Core::getTable('DescriptorDeProducto')->find(array($request->getParameter('id'))), sprintf('Object descriptor_de_producto does not exist (%s).', $request->getParameter('id')));
    $this->form = new DescriptorDeProductoForm($descriptor_de_producto);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($descriptor_de_producto = Doctrine_Core::getTable('DescriptorDeProducto')->find(array($request->getParameter('id'))), sprintf('Object descriptor_de_producto does not exist (%s).', $request->getParameter('id')));
    $descriptor_de_producto->delete();

    $this->redirect('descriptor_producto/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $descriptor_de_producto = $form->save();

      $this->redirect('descriptor_producto/edit?id='.$descriptor_de_producto->getId());
    }
  }
}
