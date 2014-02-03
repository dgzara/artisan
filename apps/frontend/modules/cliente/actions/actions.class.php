<?php

/**
 * cliente actions.
 *
 * @package    quesos
 * @subpackage cliente
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class clienteActions extends sfActions
{  
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('Cliente');

      $pager = new sfDoctrinePager('Cliente', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('cliente/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('cliente/edit?id='.$v->getId());
        
	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getTipo(),
          "2" => $v->getName(),
          "3" => $v->getRazonSocial(),
          "4" => $v->getRut(),
          "5" => $v->getTelefono(),
          "6" => $v->getContacto(),
          "7" => $v->getEmail(),
          "8" => $v->getCel(),
          "9" => '<a href="'.$ver.'"><img src="/web/images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "10" => '<a href="'.$mod.'"><img src="/web/images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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


  public function executeAyuda(sfWebRequest $request)
  {
      return $this->renderPartial('cliente/ayuda');
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->cliente = Doctrine_Core::getTable('Cliente')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->cliente);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ClienteForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ClienteForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($cliente = Doctrine_Core::getTable('Cliente')->find(array($request->getParameter('id'))), sprintf('Object cliente does not exist (%s).', $request->getParameter('id')));
    $this->form = new ClienteForm($cliente);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($cliente = Doctrine_Core::getTable('Cliente')->find(array($request->getParameter('id'))), sprintf('Object cliente does not exist (%s).', $request->getParameter('id')));
    $this->form = new ClienteForm($cliente);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($cliente = Doctrine_Core::getTable('Cliente')->find(array($request->getParameter('id'))), sprintf('Object cliente does not exist (%s).', $request->getParameter('id')));
    $cliente->delete();

    $this->redirect('cliente/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $cliente = $form->save();
      $this->redirect('cliente/index');
    }
  }
}
