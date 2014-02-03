<?php

/**
 * inventario_materia_prima actions.
 *
 * @package    quesos
 * @subpackage inventario_materia_prima
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class inventario_materia_primaActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->inventario_materia_primas = Doctrine_Core::getTable('InventarioMateriaPrima')->getLastItems();
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->inventario_materia_prima = Doctrine_Core::getTable('InventarioMateriaPrima')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->inventario_materia_prima);
  }

  public function executeGet_data(sfWebRequest $request)
  {
      $formato = new sfNumberFormat('es_CL');
      
      if ($request->isXmlHttpRequest())
      {
        $q = Doctrine_Query::create()
             ->from('inventario_materia_prima')
             ->limit(1);

        $pager = new sfDoctrinePager('inventario_materia_prima', $request->getParameter('iDisplayLength'));
        $pager->setQuery($q);
        $pager->setPage($request->getParameter('page', 1));
        $pager->init();

        $aaData = array();
        $list = $pager->getResults();

        
        foreach ($list as $v)
        {
          $ver = $this->getController()->genUrl('inventario_materia_prima/show?id='.$v->getId());
          $mod = $this->getController()->genUrl('inventario_materia_prima/edit?id='.$v->getId());

          
          

          $aaData[] = array(
            "0" => $v->getInsumoId(),
            "1" => $v->getCantidad(),
            "2" => $v->getFecha(),
            "3" => $v->getDateTimeObject('updated_at')->format('d M Y H:i:s'),
            "4" => $v->getCantidad(),
            "5" => $v->getCantidad(),

            "6" => $v->getDateTimeObject('created_at')->format('d M Y H:i:s'),

            "7" => $v->getCantidad(),

            "8" => $v->getCantidad(),

            "9" => $v->getCantidad(),
            );

          $output = array(
          "iTotalRecords" => count($pager),
          "iTotalDisplayRecords" => $request->getParameter('iDisplayLength'),
          "aaData" => $aaData,
        );
    }

          }
        return $this->renderText(json_encode($output));
}

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new InventarioMateriaPrimaForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new InventarioMateriaPrimaForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($inventario_materia_prima = Doctrine_Core::getTable('InventarioMateriaPrima')->find(array($request->getParameter('id'))), sprintf('Object inventario_materia_prima does not exist (%s).', $request->getParameter('id')));
    $this->form = new InventarioMateriaPrimaForm($inventario_materia_prima);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($inventario_materia_prima = Doctrine_Core::getTable('InventarioMateriaPrima')->find(array($request->getParameter('id'))), sprintf('Object inventario_materia_prima does not exist (%s).', $request->getParameter('id')));
    $this->form = new InventarioMateriaPrimaForm($inventario_materia_prima);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($inventario_materia_prima = Doctrine_Core::getTable('InventarioMateriaPrima')->find(array($request->getParameter('id'))), sprintf('Object inventario_materia_prima does not exist (%s).', $request->getParameter('id')));
    $inventario_materia_prima->delete();

    $this->redirect('inventario_materia_prima/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {

      $q = Doctrine_Query::create();
      $q-> select('i.nombre');
      $q->from('Insumo i');
      $q->where('i.id = ?', $request->getPostParameter("inventario_materia_prima[insumo_id]"));
      $qi = $q->fetchOne();
      $nombre = $qi -> getNombre();

        $r = Doctrine_Query::create();
        $r->from('InventarioMateriaPrima i');
        $r->where('i.insumo_id = ?', $request->getPostParameter("inventario_materia_prima[insumo_id]"));
        $r->orderBy('i.created_at DESC');
        $ri = $r ->fetchOne();
        $cantidad_vieja = $ri -> getCantidad();
        $usuarioAlgo = $this->getUser()->getGuardUser()->getName();


      $nuevo_inventario = new Registro();
      $nuevo_inventario->setAccionId(6);
      $nuevo_inventario->setBodegaId(0);
      $nuevo_inventario->setBodegaNombre('Común');
      $nuevo_inventario->setProductoId($request->getPostParameter("inventario_materia_prima[insumo_id]"));
      $nuevo_inventario->setNombre($nombre);
      $nuevo_inventario->setAccion('Editar Inventario Materia Prima');
      $nuevo_inventario->setUsuarioNombre($usuarioAlgo);
      $nuevo_inventario->setCantidad($request->getPostParameter("inventario_materia_prima[cantidad]"));
      $nuevo_inventario->setCantidadVieja($cantidad_vieja);
      $nuevo_inventario->setCantidadNueva($request->getPostParameter("inventario_materia_prima[cantidad]"));
      $nuevo_inventario->save();

      $inventario_materia_prima = $form->save();

      $this->redirect("inventario_materia_prima/index");

    }
  }
}
