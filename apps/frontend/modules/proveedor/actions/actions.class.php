<?php

/**
 * proveedor actions.
 *
 * @package    quesos
 * @subpackage proveedor
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class proveedorActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('Proveedor');

      $pager = new sfDoctrinePager('Proveedor', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('proveedor/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('proveedor/edit?id='.$v->getId());
        $del = $this->getController()->genUrl('proveedor/delete?id='.$v->getId());

	$aaData[] = array(
          "0" => $v->getEmpresaNombre(),
          "1" => $v->getEmpresaRut(),
          "2" => $v->getEmpresaTelefono(),
          "3" => $v->getVentasNombre(),
          "4" => $v->getVentasEmail(),
          "5" => $v->getVentasCelular(),
          "6" => '<a href="'.$ver.'"><img src="/web/images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "7" => '<a href="'.$mod.'"><img src="/web/images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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
    $this->proveedor = Doctrine_Core::getTable('Proveedor')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->proveedor);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ProveedorForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ProveedorForm();
    

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($proveedor = Doctrine_Core::getTable('Proveedor')->find(array($request->getParameter('id'))), sprintf('Object proveedor does not exist (%s).', $request->getParameter('id')));
    $this->form = new ProveedorForm($proveedor);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($proveedor = Doctrine_Core::getTable('Proveedor')->find(array($request->getParameter('id'))), sprintf('Object proveedor does not exist (%s).', $request->getParameter('id')));
    $this->form = new ProveedorForm($proveedor);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($proveedor = Doctrine_Core::getTable('Proveedor')->find(array($request->getParameter('id'))), sprintf('Object proveedor does not exist (%s).', $request->getParameter('id')));
    $proveedor->delete();

    $this->redirect('proveedor/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $proveedor = $form->save();
      $this->redirect('proveedor/index');
    }
  }

    public function executeListActivate($insumo)
  {
    $proveedor = $this->getRoute()->getObject();
    $proveedor->activate();

    // send an email to the affiliate
    $message = $this->getMailer()->compose(
      array('quesos@artisan.cl' => 'Quesos Artisan'),
      $proveedor->getEmail(),
      'Cotización Insumos',
      <<<EOF
Estimado {$proveedor->getContacto()},
Me dirijo a Ud. con motivo de cotizar el insumo {$insumo}.

De antemano muchísimas gracias por su pronta respuesta.

Atentamente,
Encargado O.C.
Quesos Artisan

EOF
    );

    $this->getMailer()->send($message);

    $this->redirect('orden_compra/cotizar');
  }
}
