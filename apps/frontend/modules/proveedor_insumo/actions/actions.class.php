<?php

/**
 * proveedor_insumo actions.
 *
 * @package    quesos
 * @subpackage proveedor_insumo
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class proveedor_insumoActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $formato = new sfNumberFormat('es_CL');
    
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('ProveedorInsumo');

      $pager = new sfDoctrinePager('ProveedorInsumo', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('proveedor_insumo/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('proveedor_insumo/edit?id='.$v->getId());

	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getInsumo()->getNombre(),
          "2" => $v->getProveedor()->getEmpresaNombre(),
          "3" => '$'.$formato->format($v->getPrecio(), 'd', 'CLP'),
          "4" => '<a href="'.$ver.'"><img src="../images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "5" => '<a href="'.$mod.'"><img src="../images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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
    $this->proveedor_insumo = Doctrine_Core::getTable('ProveedorInsumo')->find(array($request->getParameter('id')));
    $this->formato = new sfNumberFormat('es_CL');
    $this->forward404Unless($this->proveedor_insumo);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ProveedorInsumoForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ProveedorInsumoForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
    
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($proveedor_insumo = Doctrine_Core::getTable('ProveedorInsumo')->find(array($request->getParameter('id'))), sprintf('Object proveedor_insumo does not exist (%s).', $request->getParameter('id')));
    $this->form = new ProveedorInsumoForm($proveedor_insumo);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($proveedor_insumo = Doctrine_Core::getTable('ProveedorInsumo')->find(array($request->getParameter('id'))), sprintf('Object proveedor_insumo does not exist (%s).', $request->getParameter('id')));
    $this->form = new ProveedorInsumoForm($proveedor_insumo);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($proveedor_insumo = Doctrine_Core::getTable('ProveedorInsumo')->find(array($request->getParameter('id'))), sprintf('Object proveedor_insumo does not exist (%s).', $request->getParameter('id')));
    $proveedor_insumo->delete();

    $this->redirect('proveedor_insumo/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $proveedor_insumo = $form->save();

      $this->redirect('proveedor_insumo/index');
    }
  }

  public function executeCotizar(sfWebRequest $request)
  {
    $this->insumo = $request->getParameter('insumo');

    $this->todos = Doctrine_Core::getTable('ProveedorInsumo')
      ->createQuery('a')
      ->orderBy('a.insumo_id')
      ->execute();

    $this->proveedor_insumos = Doctrine_Core::getTable('ProveedorInsumo')
      ->createQuery('a')
      ->where('a.insumo_id = ?', $request->getParameter('insumo'))
      ->execute();
  }

  public function executeCotiza(sfWebRequest $request)
  {
    $this->forward404Unless($proveedor_insumo = Doctrine_Core::getTable('ProveedorInsumo')->find(array($request->getParameter('id'))), sprintf('Object proveedor_insumo does not exist (%s).', $request->getParameter('id')));
   // $proveedor = $this->getRoute()->getObject();
  //  $proveedor->activate();

    // send an email to the affiliate
    $message = $this->getMailer()->compose(
      array('ikleiman@uc.cl' => 'Quesos Artisan'),
      //$proveedor_insumo->getProveedor()->getEmail(),
            'ikleimans@gmail.com',
      'Cotización Insumos',
      <<<EOF
Estimado {$proveedor_insumo->getProveedor()->getContacto()},

Me dirijo a Ud. con motivo de cotizar el insumo {$proveedor_insumo->getInsumo()->getNombre()}.

De antemano muchísimas gracias por su pronta respuesta.

Atentamente,
Encargado Cotizaciones
Quesos Artisan

EOF
    );

    $this->getMailer()->send($message);
    $this->redirect('proveedor_insumo/cotizar');
  }
}
