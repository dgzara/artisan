<?php

/**
 * lote actions.
 *
 * @package    quesos
 * @subpackage lote
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class loteActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
//
//    $filtrando = $request->getParameter('value');
//    $desde = $this->dateadd($request->getParameter('desde'),0,0,0,0,0,0);
//    $hasta = $this->dateadd($request->getParameter('hasta'),1,0,0,0,0,0);
//
//    if($desde == NULL)
//        $desde = '2000/01/01';
//
//    if($request->getParameter('hasta') == NULL)
//        $hasta = '2100/01/01';
//
//    if($filtrando != NULL){
//    $this->lotes = Doctrine_Core::getTable('Lote')
//      ->createQuery('a')
//      ->from('Lote a, a.Pauta pp, a.Producto p')
//      ->where('pp.id like "%"?"%"', $filtrando)
//      ->orWhere('p.nombre like "%"?"%"', $filtrando)
//      ->orWhere('p.presentacion like "%"?"%"', $filtrando)
//      ->orWhere('p.unidad like "%"?"%"', $filtrando)
//      ->orWhere('comentarios like "%"?"%"', $filtrando)
//      ->orWhere('numero like "%"?"%"', $filtrando)
//      ->orWhere('accion like "%"?"%"', $filtrando)
//      ->andWhere('fecha_elaboracion >= ?', $desde)
//      ->andWhere('fecha_elaboracion <= ?', $hasta)
//      ->orderBy('a.fecha_elaboracion DESC')
//      ->execute();
//    }
//
//    else{
//    $this->lotes = Doctrine_Core::getTable('Lote')
//      ->createQuery('a')
//            ->where('fecha_elaboracion >= ?', $desde)
//      ->andWhere('fecha_elaboracion <= ?', $hasta)
//            ->orderBy('a.fecha_elaboracion DESC')
//      ->execute();
//    }

  }

  public function executeGet_data(sfWebRequest $request)
    {
      $formato = new sfNumberFormat('es_CL');

      if ($request->isXmlHttpRequest())
      {
        $q = Doctrine_Query::create()
             ->from('Lote');

        $asc_desc = $request->getParameter('sSortDir_0');
        $col = $request->getParameter('iSortCol_0');

        switch($col)
        {
          case 0:
            $q->orderBy('numero '.$asc_desc);
            break;
          case 1:
            $q->orderBy('fecha_elaboracion '.$asc_desc);
            break;
          case 2:
            $q->orderBy('pauta_id '.$asc_desc);
            break;
          case 3:
            $q->from('Lote l, l.Producto p, p.Rama r')
              ->orderBy('r.nombre '.$asc_desc);
            break;
          case 4:
            $q->orderBy('comentarios '.$asc_desc);
            break;
          case 5:
            $q->orderBy('cantidad '.$asc_desc);
            break;
          case 6:
            $q->orderBy('cantidad_actual '.$asc_desc);
            break;
        }

#        $q = ($request->getParameter('iSortCol_0') == 0) ? $q->orderBy('numero '.$asc_desc) : $q;
#        $q = ($request->getParameter('iSortCol_0') == 1) ? $q->orderBy('fecha_elaboracion '.$asc_desc) : $q;
#        $q = ($request->getParameter('iSortCol_0') == 2) ? $q->orderBy('pauta_id '.$asc_desc) : $q;

        $pager = new sfDoctrinePager('Lote', $request->getParameter('iDisplayLength'));
        $pager->setQuery($q);
        //Paginación por partes
        $req_page = ((int)$request->getParameter('iDisplayStart') / (int)$request->getParameter('iDisplayLength')) + 1;
        //$pager->setPage($request->getParameter('page', 1));
        $pager->setPage($req_page);
        $pager->init();

        $aaData = array();
        $list = $pager->getResults();
        foreach ($list as $v)
        {
          $ver = $this->getController()->genUrl('lote/show?id='.$v->getId());
          $mod = $this->getController()->genUrl('lote/edit?id='.$v->getId());
          $link_producto = $this->getController()->genUrl('producto/edit?id='.$v->getProductoId());
          $link_pauta = $this->getController()->genUrl('pauta/edit?id='.$v->getPautaId());

          // Vemos el deshacer
          $deshacer = '';
          if($v->getAccion() != 'A Madurar' && $v->getAccion() != 'Rechazado'){
            $des = $this->getController()->genUrl('lote/deshacer?id='.$v->getId());
            $deshacer = '<a href="'.$des.'"><img src="images/tools/icons/event_icons/ico-back.png" border="0"></a>';
          }
          
          $estado_accion = $v->getAccion();
          if($v->getAccion() == 'Despachar') {
            $link = 'lote/despachar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Despachar</a>";
          }
          elseif ($v->getAccion()=='A Madurar') {
            $link = 'lote/madurar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Ingresar a Maduración</a>";
          }
          elseif ($v->getAccion()=='Empacar') {
            $link = 'lote/empacar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Empacar</a>";
          }
          elseif ($v->getAccion()=='En Maduración') {
            $link = 'lote/retirar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>En Maduración</a>";
          }


          $aaData[] = array(
            "0" => $v->getNumero(),
            "1" => $v->getDateTimeObject('fecha_elaboracion')->format('d-m-Y'),
            "2" => '<a href="'.$link_pauta.'" target="_blank">'.$v->getPautaId().'</a>',
            "3" => '<a href="'.$link_producto.'" target="_blank">'.$v->getProducto()->getNombreCompleto().'</a>',
            "4" => $v->getComentarios(),
            "5" => $v->getCantidad(),
            "6" => $v->getCantidadActualIndex(),
            "7" => $v->getRendimiento().'%',
            "8" => $estado_accion,
            "9" => $deshacer,
            "10" => '<a class="jt" rel="/web/lote/preview/'.$v->getId().'" title="Lote '.$v->getNumero().'" href="'.$ver.'"><img src="images/tools/icons/event_icons/ico-story.png" border="0" /></a>',
            "11" => '<a href="'.$mod.'"><img src="images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
            "12" => '<input type="checkbox" class="checkbox1" value="'.$v->getId().'" accion="'.$v->getAccion().'"> <br>',
          );
       }
	   

        $output = array(
          "iTotalRecords" => count($pager),
          "iTotalDisplayRecords" => count($pager),
          //"iTotalDisplayRecords" => $request->getParameter('iDisplayLength'),
          "aaData" => $aaData,
          "sEcho" => $request->getParameter('sEcho'),
        );

        return $this->renderText(json_encode($output));
       }
    }

  public function dateadd($date, $dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0) {
        $date_r = getdate(strtotime($date));
        $date_result = date("Y/m/d", mktime(($date_r["hours"] + $hh), ($date_r["minutes"] + $mn), ($date_r["seconds"] + $ss), ($date_r["mon"] + $mm), ($date_r["mday"] + $dd), ($date_r["year"] + $yy)));
        return $date_result;
    }

  public function executeFilter(sfWebRequest $request)
  {
    $this->accion = $request->getParameter('accion');

    if($request->getParameter('accion') == ""){
        $this->redirect('lote/index');
    }

    if($this->accion == "A Madurar")
    {
        $this->accion = "Ingresar a Maduración";
        $this->link = "madurar";
        $this->titulo = "Lotes Elaborados Listos Para Ingresar a Cámara de Maduración";
    }

    else if($this->accion == "En Maduración")
    {
        $this->accion = "Ver Estado Maduración";
        $this->link = "show";
    }

    else if($this->accion == "Empacar")
    {
        $this->titulo = "Lotes Fuera de Cámara de Maduración (En Condiciones de Empacar)";
        $this->link = lcfirst($request->getParameter('accion'));
    }

    else if($this->accion == "Despachar")
    {
        $this->titulo = "Lotes Empacados Listos para Despacho a Central de Distribución";
        $this->link = lcfirst($request->getParameter('accion'));
    }

    else
    {
        $this->link = lcfirst($request->getParameter('accion'));
    }

    if($this->accion == "Retirar")
    {
        $q = Doctrine_Query::create()
        ->from('Lote l, l.Producto p')
        // ->where('DATEDIFF(CURRENT_DATE, l.fecha_entrada ) >= p.maduracion')
        // ->andWhere('l.producto_id = p.id')
         ->andWhere('l.accion = ?', 'En Maduración')
         ->orderBy('p.maduracion - DATEDIFF(CURRENT_DATE, l.fecha_entrada )');
       $this->lotes = $q->execute();
       $this->titulo = "Retirar Lote de Cámara de Maduración";
       
    }
    
    else{
    $this->lotes = Doctrine_Core::getTable('Lote')
      ->createQuery('a')
      ->where('a.accion = ?', $request->getParameter('accion'))
              ->orderBy('a.fecha_elaboracion DESC')
      ->execute();
    }
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->lote);

    $this->vencimiento = $this->lote->dateadd($this->lote->getFecha_Salida(), $this->lote->getProducto()->getDuracion(),0,0,0,0,0 );
  }
  public function executePreview(sfWebRequest $request)
  {
    $this->lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->lote);

  }

  public function executeCierrelote(sfWebRequest $request)
  {

  }

  public function executeCierre_get_data(sfWebRequest $request)
  {
      $fecha= new DateTime("23-06-2011");
      if ($request->isXmlHttpRequest())
      {        
        $q = Doctrine_Query::create()
             ->from('Lote')
             ->where('accion != "Cerrado"')
             ->andWhere('fecha_elaboracion > ?', $fecha);

        $asc_desc = $request->getParameter('sSortDir_0');
        $col = $request->getParameter('iSortCol_0');

        switch($col)
        {
          case 0:
            $q->orderBy('numero '.$asc_desc);
            break;
          case 1:
            $q->orderBy('fecha_elaboracion '.$asc_desc);
            break;
          case 2:
            //$q->orderBy('pauta_id '.$asc_desc);
            break;
          case 3:
            $q->from('Lote l, l.Producto p')
              ->orderBy('p.nombre '.$asc_desc);
            break;
        }

        $pager = new sfDoctrinePager('Lote', $request->getParameter('iDisplayLength'));
        $pager->setQuery($q);
        //Paginación por partes
        $req_page = ((int)$request->getParameter('iDisplayStart') / (int)$request->getParameter('iDisplayLength')) + 1;
        $pager->setPage($req_page);
        $pager->init();

        $aaData = array();
        $list = $pager->getResults();

        foreach ($list as $lote) {
          if(($lote->getFecha_Salida())!=null)
          {
            $vencimiento= $lote->dateadd($lote->getFecha_Salida(), $lote->getProducto()->getDuracion(),0,0,0,0,0 );
          }
          else
          {
            $vencimiento = date("d M Y");
          }
          $cantidadA = $lote->getCantidad_Actual() - $lote->getVendidas();
          $fecha_elaboracion = $lote->getDateTimeObject('fecha_elaboracion');
          if($vencimiento<date("d M Y") && ($lote->getAccion())!='Cerrado' && $cantidadA>0 && $fecha_elaboracion>$fecha)
          {
            $link = 'close/id/'.$lote->getId();
            $aaData[]= array(
              '0' =>$lote-> getNumero(),  
              '1' =>$fecha_elaboracion->format('d-m-Y'),
              '2' =>$vencimiento,
              '3' =>$lote-> getProducto()->getNombreCompleto(),
              '4' =>$lote-> getCantidad(),
              '5' =>$cantidadA,
              '6' =>$lote-> getVendidas(),
              '7' =>$lote-> getRendimiento().'%',
              '8' =>$lote-> getAccion(),
              '9' => '<a href="'.$link.'">Cerrar</a>'
            );
          }
        }
        
        $output = array(
          "iTotalRecords" => count($pager),
          "iTotalDisplayRecords" => count($pager),
          "aaData" => $aaData,
          "sEcho" => $request->getParameter('sEcho'),
        );
        
        return $this->renderText(json_encode($output));
      }
      return $this->renderText(json_encode(array('status' => 'fail')));
  }

  public function executeClose(sfWebRequest $request)
  {
    $lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id')));
    $lote->setAccion("Cerrado");
    $cantidad=$lote-> getCantidad_Actual() - $lote->getVendidas();
    $cantidad = ($cantidad>=0) ? $cantidad : 0 ;
    $lote->cambiarInventario($lote->getProductoId(), $cantidad, date('Y/m/d/H:m'), '2', 'disminuir', $this->getUser()->getGuardUser()->getName(),'Cerrar');
    $lote->save();
    $this->redirect('lote/cierrelote');
  }
  
  public function executeGenerarqr(sfWebRequest $request)
  {
	  $this->lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->lote);
    $this->formato=Doctrine_Core::getTable('FormatoQr')->find(1);
  }
  
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new LoteForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new LoteForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));
    $this->form = new LoteForm($lote);

    $widgetSchema = $this->form->getWidgetSchema();
    if($lote->getFecha_Entrada() != NULL){
        $widgetSchema['fecha_entrada'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es' ));;
        $widgetSchema['fecha_entrada']->setLabel('Fecha de Ingreso a Cámara de Maduración');
    }
    if($lote->getFecha_Salida() != NULL){
        $widgetSchema['fecha_salida'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es' ));;
        $widgetSchema['fecha_salida']->setLabel('Fecha de Retiro de Cámara de Maduración');
    }
    if($lote->getFecha_Empaque() != NULL){
        $widgetSchema['fecha_empaque'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es' ));;
        $widgetSchema['fecha_empaque']->setLabel('Fecha de Empaque');
    }
    if($lote->getFecha_Envio() != NULL)
    {
        $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es' ));;
        $widgetSchema['fecha_envio']->setLabel('Fecha de Despacho a Centro de Distribución');
    }
    if($lote->getFecha_Recepcion() != NULL)
    {
        $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es' ));;
        $widgetSchema['fecha_recepcion']->setLabel('Fecha de Recepción');
    }
    if($lote->getCantidad_Danada() != NULL)
    {
        $widgetSchema['cantidad_danada']->setAttribute('type', 'text');
        $widgetSchema['cantidad_danada']->setHidden(false);
    }
    if($lote->getCantidad_Ff() != NULL)
    {
        $widgetSchema['cantidad_ff']->setAttribute('type', 'text');
        $widgetSchema['cantidad_ff']->setHidden(false);
        $widgetSchema['cantidad_ff']->setLabel('Unidades Fuera de Formato Valdivia');
    }
    if($lote->getCC_Valdivia() != NULL)
    {
        $widgetSchema['cc_valdivia']->setAttribute('type', 'text');
        $widgetSchema['cc_valdivia']->setHidden(false);
    }
    if($lote->getCC_Santiago() != NULL)
    {
        $widgetSchema['cc_santiago']->setAttribute('type', 'text');
        $widgetSchema['cc_santiago']->setHidden(false);
    }
    if($lote->getMedio_Transporte() != NULL)
    {
        $widgetSchema['medio_transporte']->setAttribute('type', 'text');
        $widgetSchema['medio_transporte']->setHidden(false);
    }
    if($lote->getN_Documento() != NULL)
    {
        $widgetSchema['n_documento']->setAttribute('type', 'text');
        $widgetSchema['n_documento']->setHidden(false);
    }
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));
    $this->form = new LoteForm($lote);

    $this->processForm($request, $this->form);

    $this->redirect('lote/index');
  }

  public function executeRechazar(sfWebRequest $request)
  {
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));

    if($lote->getAccion()=='Despachar')
        $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), date('Y/m/d/H:m'), '1', 'disminuir', $this->getUser()->getGuardUser()->getName(),'Despachar Rechazar');

    else if($lote->getAccion()=='Recepcionar')
        $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), date('Y/m/d/H:m'), '1', 'aumentar', $this->getUser()->getGuardUser()->getName(),'Recepcionar Rechazar');

    else if($lote->getAccion()=='Recepcionado')
        $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), date('Y/m/d/H:m'), '2', 'disminuir', $this->getUser()->getGuardUser()->getName(),'Recepcionado Rechazar');

   // $lote->setCantidad_Danada($lote->getCantidad()+$lote->getCc_Valdivia()+$lote->getCantidad_Ff()+$lote->getCantidad_Danada()+
   //         $lote->getCc_Santiago()+$lote->getCantidad_Ff_Stgo()+$lote->getCantidad_Danada_Stgo());
    $lote->setCantidad_Actual(0);
//    $lote->setCantidad_Danada(0);
//        $lote->setCantidad_Ff(0);
//        $lote->setCc_Valdivia(0);

    $lote->setAccion('Rechazado');
    $lote->save();
    $this->redirect('lote/index');
  }

  public function executeDeshacer(sfWebRequest $request)
  {
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));

    if($lote->getAccion()=='En Maduración')
    {
        $lote->setFecha_Entrada(NULL);
        $lote->setAccion('A Madurar');
        $lote->save();
    }

    else if($lote->getAccion()=='Empacar')
    {
        $lote->setCantidad_Actual($lote->getCantidad_Actual() + $lote->getCantidad_Danada() + $lote->getCantidad_Ff());
        $lote->setFecha_Salida(NULL);
        $lote->setCantidad_Danada(NULL);
        $lote->setCantidad_Ff(NULL);
        $lote->setAccion('En Maduración');
        $lote->save();
    }

    else if($lote->getAccion()=='Despachar')
    {
	 $descriptores = Doctrine_Core::getTable("DescriptorDeProducto")->findBy('producto_id', $lote->getProductoId());
	 foreach($descriptores as $descriptor)
        {
	   $lote->cambiarInventarioDescriptor($descriptor->getInsumoId(), ($lote->getCantidadActual() + $lote->getCcValdivia())*$descriptor->getCantidad(), date('Y/m/d/H:m'), 'aumentar', $this->getUser()->getGuardUser()->getName(),'Despachar');
	 }
        $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), date('Y/m/d/H:m'), '1', 'disminuir', $this->getUser()->getGuardUser()->getName(),'Despachar Deshacer');
        $lote->setCantidad_Actual($lote->getCantidad_Actual() + $lote->getCc_Valdivia());
        $lote->setFecha_Empaque(NULL);
        $lote->setCc_Valdivia(NULL);
        $lote->setAccion('Empacar');
        Doctrine_Core::getTable("CostosDirectos")->EliminarCostosSecos($lote->getId());
        $lote->save();
    }

    else if($lote->getAccion()=='Recepcionar')
    {
        $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), date('Y/m/d/H:m'), '1', 'aumentar', $this->getUser()->getGuardUser()->getName(),'Recepcionar Deshacer');
        $lote->setFecha_Envio(NULL);
        $lote->setMedio_Transporte(NULL);
        $lote->setN_Documento(NULL);
        $lote->setAccion('Despachar');
        $lote->save();
    }

    else if($lote->getAccion()=='Recepcionado')
    {
        $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), date('Y/m/d/H:m'), '2', 'disminuir', $this->getUser()->getGuardUser()->getName(),'Recepcionado Deshacer');
        $lote->setCantidad_Actual($lote->getCantidad() - $lote->getCantidad_Danada_Stgo() - $lote->getCantidad_Ff_Stgo() - $lote->getCc_Santiago());
        $lote->setFecha_Recepcion(NULL);
        $lote->setCantidad_Recibida(NULL);
        $lote->setCantidad_Danada_Stgo(NULL);
        $lote->setCantidad_Ff_Stgo(NULL);
        $lote->setCc_Santiago(NULL);
        $lote->setAccion('Recepcionar');
        $lote->save();
    }

    $this->redirect('lote/index');
  }

  public function executeMadurar(sfWebRequest $request)
  {
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));
    $this->form = new LoteForm($lote);

    $widgetSchema = $this->form->getWidgetSchema();
    $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
    $widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');

    $widgetSchema['cantidad_actual'] = new sfWidgetFormInputText();
    $widgetSchema['cantidad_actual']->setAttribute('type', 'hidden');
    $widgetSchema['cantidad_actual']->setHidden(true);

    $widgetSchema['fecha_entrada'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
    $widgetSchema['fecha_entrada']->setLabel('Fecha de Ingreso a Cámara de Maduración*');
    $widgetSchema['fecha_entrada']->setDefault(date('Y/m/d/H:m'));
    
    $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
    $widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['numero']->setAttribute('disabled', 'disabled');
  }

  public function executeRipen(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));
    $this->form = new LoteForm($lote);

    $this->processForm($request, $this->form);

    $lote->setCantidad_Actual($lote->getCantidad());
    $lote->setAccion('En Maduración');
    $lote->save();
    $this->redirect('lote/index');
  }

  public function executeRetirar(sfWebRequest $request)
  {
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));
    $this->form = new LoteForm($lote);
    $widgetSchema = $this->form->getWidgetSchema();
    $widgetSchema['fecha_salida'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
    $widgetSchema['fecha_salida']->setLabel('Fecha de Retiro de Cámara de Maduración*');
    $widgetSchema['fecha_salida']->setDefault(date('Y/m/d/H:m'));
    $widgetSchema['cantidad_danada']->setAttribute('type', 'text');
    $widgetSchema['cantidad_danada']->setHidden(false);
    $widgetSchema['cantidad_danada']->setDefault(0);
    $widgetSchema['cantidad_ff']->setAttribute('type', 'text');
    $widgetSchema['cantidad_ff']->setHidden(false);
    $widgetSchema['cantidad_ff']->setDefault(0);
    $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
    $widgetSchema['cantidad_actual'] = new sfWidgetFormInputText();
    $widgetSchema['cantidad_actual']->setAttribute('type', 'hidden');
    $widgetSchema['cantidad_actual']->setHidden(true);
    $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
    $widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
    $widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['numero']->setAttribute('disabled', 'disabled');

    
  }

  public function executeRetrieve(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));
    $this->form = new LoteForm($lote);

    $widgetSchema = $this->form->getWidgetSchema();
    $widgetSchema['fecha_salida'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
    $widgetSchema['fecha_salida']->setLabel('Fecha de Retiro de Cámara de Maduración*');
    $widgetSchema['fecha_salida']->setDefault(date('Y/m/d/H:m'));
    $widgetSchema['cantidad_danada']->setAttribute('type', 'text');
    $widgetSchema['cantidad_danada']->setHidden(false);
    $widgetSchema['cantidad_ff']->setAttribute('type', 'text');
    $widgetSchema['cantidad_ff']->setHidden(false);
    $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
    $widgetSchema['cantidad_actual'] = new sfWidgetFormInputText();
    $widgetSchema['cantidad_actual']->setAttribute('type', 'hidden');
    $widgetSchema['cantidad_actual']->setHidden(true);
    $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
    $widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
    $widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['numero']->setAttribute('disabled', 'disabled');


    $this->validatorSchema=$this->form->getValidatorSchema();
    $this->validatorSchema['cantidad_danada'] = new sfValidatorInteger();
    $this->validatorSchema['cantidad_ff'] = new sfValidatorInteger();
    $this->validatorSchema['fecha_salida'] = new sfValidatorDate();
    $this->validatorSchema['fecha_elaboracion'] = new sfValidatorDate();

    $this->processForm($request, $this->form);

    if ($this->form->isValid()){
        $lote->setCantidad_Actual($lote->getCantidad_Actual() - $lote->getCantidad_Danada() - $lote->getCantidad_Ff());
        $lote->setAccion('Empacar');
        $lote->save();
        $this->redirect('lote/index');
    }

    else{
        //echo "NO VALIDO";
        //$this->setTemplate('validar');
    }
    //$this->redirect("ordencompra/validar?" . $qryString);
    $this->setTemplate('retirar');

    //$this->redirect('lote/index');
  }

  public function executeEmpacar(sfWebRequest $request)
  {
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));
    $this->form = new LoteForm($lote);

    $widgetSchema = $this->form->getWidgetSchema();
    $widgetSchema['cantidad_actual']->setAttribute('type', 'text');
    $widgetSchema['cantidad_actual']->setHidden(false);
    $widgetSchema['cantidad_actual']->setLabel("Unidades Útiles para Empaque");
    $widgetSchema['fecha_empaque'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
    $widgetSchema['fecha_empaque']->setLabel("Fecha de Empaque*");
    $widgetSchema['fecha_empaque']->setDefault(date('Y/m/d/H:m'));
    $widgetSchema['cc_valdivia']->setAttribute('type', 'text');
    $widgetSchema['cc_valdivia']->setHidden(false);
    $widgetSchema['cc_valdivia']->setDefault(1);
    $widgetSchema['_csrf_token']->setHidden(true);
    $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
    $widgetSchema['cantidad_actual']->setAttribute('disabled', 'disabled');
    $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
    $widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
    $widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['numero']->setAttribute('disabled', 'disabled');
  }

  public function executePack(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));
    $this->form = new LoteForm($lote);

    $widgetSchema = $this->form->getWidgetSchema();
    $widgetSchema['cantidad_actual']->setAttribute('type', 'text');
    $widgetSchema['cantidad_actual']->setHidden(false);
    $widgetSchema['cantidad_actual']->setLabel("Unidades Útiles para Empaque");
    $widgetSchema['fecha_empaque'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
    $widgetSchema['fecha_empaque']->setLabel("Fecha de Empaque*");
    $widgetSchema['fecha_empaque']->setDefault(date('Y/m/d/H:m'));
    $widgetSchema['cc_valdivia']->setAttribute('type', 'text');
    $widgetSchema['cc_valdivia']->setHidden(false);
    $widgetSchema['_csrf_token']->setHidden(true);
    $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
    $widgetSchema['cantidad_actual']->setAttribute('disabled', 'disabled');
    $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
    $widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
    $widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['numero']->setAttribute('disabled', 'disabled');

    $this->validatorSchema=$this->form->getValidatorSchema();
    $this->validatorSchema['cc_valdivia'] = new sfValidatorInteger();
    $this->validatorSchema['fecha_empaque'] = new sfValidatorDate();
    $this->validatorSchema['fecha_elaboracion'] = new sfValidatorDate();

    $this->processForm($request, $this->form);

    if ($this->form->isValid()){
        $lote->setCantidad_Actual($lote->getCantidad_Actual() - $lote->getCc_Valdivia());
        $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), $lote->getFecha_Empaque(), '1', 'aumentar', $this->getUser()->getGuardUser()->getName(),'Producto Empacado');
        $lote->setAccion('Despachar');
        $lote->save();
        
        //aqui se ingresa el costodirecto o costo seco.
        $descriptores = Doctrine_Core::getTable("DescriptorDeProducto")->findBy('producto_id', $lote->getProductoId());  
        
        //ESTO ES PA TESTING
        
//        $costodirecto= new CostosDirectos();
//        $costodirecto->setLoteId($lote->getId());
//        $costodirecto->setInsumoId(1);
//        $costodirecto->setTipoCosto('empa');
//        $costodirecto->setFecha($lote->getFechaEmpaque());
//        $costodirecto->setProductoId($lote->getProductoId());
//        
//        $preciou = Doctrine_Core::getTable('OrdenCompraInsumo')->getUltimoPrecioUnitario(1);
//        $costodirecto->setPrecioUnitario($preciou);
//        //El costo seco es el (costo_insumo/unidad * unidades_insumo/unidad_producto * (unidades_producto_empacadas)
//        $unidades_empacadas = $lote->getCantidadActual() + $lote->getCcValdivia();
//        $valor = $preciou * 10 * $unidades_empacadas;
//        $costodirecto->setValor($valor);
//        $costodirecto->save();
        
        
        //FIN DE TESTING
        
        
        foreach($descriptores as $descriptor)
        {

         $insumo_id = $descriptor->getInsumoId();

	$lote->cambiarInventarioDescriptor($descriptor->getInsumoId(), ($lote->getCantidadActual() + $lote->getCcValdivia())*$descriptor->getCantidad(), $lote->getFecha_Empaque(), 'disminuir', $this->getUser()->getGuardUser()->getName(),'Empacar Insumo '.$insumo_id);

        $costodirecto= new CostosDirectos();
        $costodirecto->setLoteId($lote->getId());
        $costodirecto->setInsumoId($descriptor->getInsumoId());
        $costodirecto->setTipoCosto('empa');
        $costodirecto->setFecha($lote->getFechaEmpaque());
        $costodirecto->setProductoId($lote->getProductoId());
        
        $preciou = Doctrine_Core::getTable('OrdenCompraInsumo')->getUltimoPrecioUnitario($descriptor->getInsumoId());
        $costodirecto->setPrecioUnitario($preciou);
        //El costo seco es el (costo_insumo/unidad * unidades_insumo/unidad_producto * (unidades_producto_empacadas)
        $unidades_empacadas = $lote->getCantidadActual() + $lote->getCcValdivia();
        $valor = $preciou * $descriptor->getCantidad() * $unidades_empacadas;
        $costodirecto->setValor($valor);
        $costodirecto->save();
        }
        
        $this->redirect('lote/index');
    }

    else{
        //echo "NO VALIDO";
        //$this->setTemplate('validar');
    }
    //$this->redirect("ordencompra/validar?" . $qryString);
    $this->setTemplate('empacar');

  }

    public function executeDespachar(sfWebRequest $request)
  {
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));
    $this->form = new LoteForm($lote);

    $widgetSchema = $this->form->getWidgetSchema();
    $widgetSchema['cantidad_actual']->setAttribute('type', 'text');
    $widgetSchema['cantidad_actual']->setHidden(false);
    $widgetSchema['cantidad_actual']->setLabel("Unidades Empacadas para Despacho");
    $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
    $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho a Centro de Distribución*");
    $widgetSchema['fecha_envio']->setDefault(date('Y/m/d/H:m'));
    $widgetSchema['medio_transporte']->setAttribute('type', 'text');
    $widgetSchema['medio_transporte']->setHidden(false);
    $widgetSchema['n_documento']->setAttribute('type', 'text');
    $widgetSchema['n_documento']->setHidden(false);
    $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
    $widgetSchema['cantidad_actual']->setAttribute('disabled', 'disabled');
    $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
    $widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
    $widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['numero']->setAttribute('disabled', 'disabled');
    }

  public function executeDeliver(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));
    $this->form = new LoteForm($lote);

    $widgetSchema = $this->form->getWidgetSchema();
    $widgetSchema['cantidad_actual']->setAttribute('type', 'text');
    $widgetSchema['cantidad_actual']->setHidden(false);
    $widgetSchema['cantidad_actual']->setLabel("Unidades Empacadas para Despacho");
    $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
    $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho a Centro de Distribución*");
    $widgetSchema['fecha_envio']->setDefault(date('Y/m/d/H:m'));
    $widgetSchema['medio_transporte']->setAttribute('type', 'text');
    $widgetSchema['medio_transporte']->setHidden(false);
    $widgetSchema['n_documento']->setAttribute('type', 'text');
    $widgetSchema['n_documento']->setHidden(false);
    $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
    $widgetSchema['cantidad_actual']->setAttribute('disabled', 'disabled');
    $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
    $widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
    $widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['numero']->setAttribute('disabled', 'disabled');
    
    $this->validatorSchema=$this->form->getValidatorSchema();
    $this->validatorSchema['medio_transporte'] = new sfValidatorString();
    $this->validatorSchema['fecha_envio'] = new sfValidatorDate();
    $this->validatorSchema['fecha_elaboracion'] = new sfValidatorDate();

    $this->processForm($request, $this->form);
    
    if ($this->form->isValid()){
        $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), $lote->getFecha_Envio(), '1', 'disminuir', $this->getUser()->getGuardUser()->getName(),'Recepcionar Deliver');
        $lote->setAccion('Recepcionar');
        $lote->save();
        $this->redirect('lote/index');
    }
    else{
        //echo "NO VALIDO";
        //$this->setTemplate('validar');
    }
    //$this->redirect("ordencompra/validar?" . $qryString);
    $this->setTemplate('despachar');


  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));

    if($lote->getAccion()=='Despachar')
    {
        $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), date('Y/m/d/H:m'), '1', 'disminuir', $this->getUser()->getGuardUser()->getName(),'Despachar Eliminar');
    }

    else if($lote->getAccion()=='Recepcionado')
    {
        $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), date('Y/m/d/H:m'), '2', 'disminuir', $this->getUser()->getGuardUser()->getName(),'Recepcionado Eliminar');
    }

    $lote->delete();

    $this->redirect('lote/index');
  }

  public function executeTrazabilidad(sfWebRequest $request){
    //$this->productos = Doctrine_Core::getTable('Producto')->findAll();
    //$this->fechas = Doctrine_Core::getTable('Lote')->getTodasFechas();
    $this->ramas = Doctrine_Core::getTable('Rama')->findAll();
    $this->productos = array();

    foreach($this->ramas as $rama){
        $this->productos[$rama->getId()] = Doctrine_Core::getTable('Producto')->findByRama($rama->getId());
    }
  }

  public function executeTrazabilidadProducto(sfWebRequest $request){
      $producto_id = $request->getParameter('producto_id');
      $this->producto = Doctrine_Core::getTable('Producto')->findOneById($producto_id);

      return $this->renderPartial('listaTrazabilidad', array('producto' =>$this->producto));
  }


  public function executeProductosElaborados(sfWebRequest $request){
    //$this->lotes = Doctrine_Core::getTable('Lote')->getCodigos();
    $this->fechas = Doctrine_Core::getTable('Lote')->getTodasFechas();
    $this->ramas = Doctrine_Core::getTable('Rama')->findAll();
    $this->lotes = array();

    foreach($this->ramas as $rama){
        $this->lotes[] = Doctrine_Core::getTable('Lote')->getCodigosPorRama($rama->getId());
    }
  }

  public function executeProductosElaborados2(sfWebRequest $request){
    //$this->lotes = Doctrine_Core::getTable('Lote')->getCodigos();
    $this->fechas = Doctrine_Core::getTable('Lote')->getTodasFechas();
    $this->ramas = Doctrine_Core::getTable('Rama')->findAll();
    $this->lotes = array();

    foreach($this->ramas as $rama){
        $this->lotes[] = Doctrine_Core::getTable('Lote')->getCodigosPorRama($rama->getId());
    }
  }

  public function executeBackupProductosElaborados(sfWebRequest $request){
    $this->lotes = Doctrine_Core::getTable('Lote')->getCodigos();
    $this->fechas = Doctrine_Core::getTable('Lote')->getTodasFechas();
    $this->arreglos = array();

    foreach($this->lotes as $lote){
        $this->arreglos[] = $lote->getProduccion();
    }
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $lote = $form->save();

    }
  }
 public function executeGrupo(sfWebRequest $request)
  {
      $grupo = $request->getParameter('grupo');
      $lotes = array();
      //guardo la lista de ordenes q se pueden accionar
      foreach($grupo as $id)
      {
          $lotes[] =Doctrine::getTable('Lote')->find($id);
      }
      $this->lotes = $lotes;
      $this->lotes2=array();
      $i=0;
      $estado=$lotes[0]->getAccion();

      foreach ($lotes as $lote) {
      $this->lotes2[$i] = new LoteForm($lote);
      $widgetSchema = $this->lotes2[$i]->getWidgetSchema();
      $widgetSchema->setNameFormat('lotes_'.$i.'[%s]');
      $validatorSchema = $this->lotes2[$i]->getValidatorSchema();
      unset($widgetSchema['comentarios']);
      unset($widgetSchema['padre']);
          if($estado=="A Madurar")
          {
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
              $widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
              $widgetSchema['cantidad_actual'] = new sfWidgetFormInputText();
              $widgetSchema['cantidad_actual']->setAttribute('type', 'hidden');
              $widgetSchema['cantidad_actual']->setHidden(true);
              $widgetSchema['fecha_entrada'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_entrada']->setLabel('Fecha de Ingreso a Cámara de Maduración*');
              $widgetSchema['fecha_entrada']->setDefault(date('Y/m/d/H:m'));
              //$widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['numero']->setAttribute('disabled', 'disabled');

              $widgetSchema['cantidad'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['producto_id'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['numero'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['cantidad_actual'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 

          }
          elseif($estado=="En Maduración")
          {
              $widgetSchema['fecha_salida'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_salida']->setLabel('Fecha de Retiro de Cámara de Maduración*');
              $widgetSchema['fecha_salida']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['cantidad_danada']->setAttribute('type', 'text');
              $widgetSchema['cantidad_danada']->setHidden(false);
              $widgetSchema['cantidad_danada']->setDefault(0);
              $widgetSchema['cantidad_ff']->setAttribute('type', 'text');
              $widgetSchema['cantidad_ff']->setHidden(false);
              $widgetSchema['cantidad_ff']->setDefault(0);
              $widgetSchema['cantidad_actual'] = new sfWidgetFormInputText();
              $widgetSchema['cantidad_actual']->setAttribute('type', 'hidden');
              $widgetSchema['cantidad_actual']->setHidden(true);
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
              //$widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
              //$widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
              //$widgetSchema['numero']->setAttribute('disabled', 'disabled'); 

              $widgetSchema['producto_id'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['numero'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly'));
              $widgetSchema['cantidad'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['cantidad_actual'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 

          }
          elseif($estado=="Empacar")
          {
              $widgetSchema['cantidad_actual']->setAttribute('type', 'text');
              $widgetSchema['cantidad_actual']->setHidden(false);
              $widgetSchema['cantidad_actual']->setLabel("Unidades Útiles para Empaque");
              $widgetSchema['fecha_empaque'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_empaque']->setLabel("Fecha de Empaque*");
              $widgetSchema['fecha_empaque']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['cc_valdivia']->setAttribute('type', 'text');
              $widgetSchema['cc_valdivia']->setHidden(false);
              $widgetSchema['cc_valdivia']->setDefault(1);
              $widgetSchema['_csrf_token']->setHidden(true);
              //$widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
              //$widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
              //$widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
              //$widgetSchema['numero']->setAttribute('disabled', 'disabled'); 
              $validatorSchema['cc_valdivia'] = new sfValidatorInteger();
              $validatorSchema['fecha_empaque'] = new sfValidatorDate();
              $validatorSchema['fecha_elaboracion'] = new sfValidatorDate();

              $widgetSchema['producto_id'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['numero'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['cantidad'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['cantidad_actual'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
          }
          elseif($estado=="Despachar")
          {
              $widgetSchema['cantidad_actual']->setAttribute('type', 'text');
              $widgetSchema['cantidad_actual']->setHidden(false);
              $widgetSchema['cantidad_actual']->setLabel("Unidades Empacadas para Despacho");
              $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho a Centro de Distribución*");
              $widgetSchema['fecha_envio']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['medio_transporte']->setAttribute('type', 'text');
              $widgetSchema['medio_transporte']->setHidden(false);
              $widgetSchema['n_documento']->setAttribute('type', 'text');
              $widgetSchema['n_documento']->setHidden(true);
              unset($widgetSchema['n_documento']);
              //$widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
              //$widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
              //$widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
              //$widgetSchema['numero']->setAttribute('disabled', 'disabled');

              $validatorSchema['medio_transporte'] = new sfValidatorString();
              $validatorSchema['fecha_envio'] = new sfValidatorDate();
              $validatorSchema['fecha_elaboracion'] = new sfValidatorDate();

              $widgetSchema['producto_id'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['numero'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['cantidad'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['cantidad_actual'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
          }
          
      $i++;
      }
  }
  
  public function executeValidategrupal(sfWebRequest $request)
  {
      $keys = array_keys($request->getPostParameters());

      // Contamos cuantas órdenes hay
      $lotes_id = array();
      $consulta = $request->getPostParameters();

      foreach($keys as $key=>$val){
          if(substr_count($val, 'lotes_')){
              $lotes_id[] = $consulta[$val]['id'];
          }
      }
      $estado = $consulta[$val]['accion'];

      // Variable del mensaje
      $this->mensajes = array();

      // Entramos a validar los formularios
      for($i=0 ; $i < count($lotes_id) ;$i++)
      {
          $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
          $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($lotes_id[$i])), sprintf('Object lote does not exist (%s).', $lotes_id[$i]));
          
          $this->form= new LoteForm($lote);
          $widgetSchema = $this->form->getWidgetSchema();
          $widgetSchema->setNameFormat('lotes_'.$i.'[%s]');
          
          unset($widgetSchema['comentarios']);
          unset($widgetSchema['padre']);
          if($estado=="A Madurar")
          {
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
              //$widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
              $widgetSchema['cantidad_actual'] = new sfWidgetFormInputText();
              $widgetSchema['cantidad_actual']->setAttribute('type', 'hidden');
              $widgetSchema['cantidad_actual']->setHidden(true);
              $widgetSchema['fecha_entrada'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_entrada']->setLabel('Fecha de Ingreso a Cámara de Maduración*');
              $widgetSchema['fecha_entrada']->setDefault(date('Y/m/d/H:m'));
              //$widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
              //$widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
              //$widgetSchema['numero']->setAttribute('disabled', 'disabled');

              $widgetSchema['cantidad'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['producto_id'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['numero'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 


          }
          elseif($estado=="En Maduración")
          {
              $widgetSchema['fecha_salida'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_salida']->setLabel('Fecha de Retiro de Cámara de Maduración*');
              $widgetSchema['fecha_salida']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['cantidad_danada']->setAttribute('type', 'text');
              $widgetSchema['cantidad_danada']->setHidden(false);
              $widgetSchema['cantidad_danada']->setDefault(0);
              $widgetSchema['cantidad_ff']->setAttribute('type', 'text');
              $widgetSchema['cantidad_ff']->setHidden(false);
              $widgetSchema['cantidad_ff']->setDefault(0);
              $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
              $widgetSchema['cantidad_actual'] = new sfWidgetFormInputText();
              $widgetSchema['cantidad_actual']->setAttribute('type', 'hidden');
              $widgetSchema['cantidad_actual']->setHidden(true);
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
              //$widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
              //$widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
              //$widgetSchema['numero']->setAttribute('disabled', 'disabled'); 

              $widgetSchema['producto_id'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 
              $widgetSchema['numero'] = new sfWidgetFormInput(array(), array('readonly'=>'readonly')); 

          }
          elseif($estado=="Empacar")
          {
              $widgetSchema['cantidad_actual']->setAttribute('type', 'text');
              $widgetSchema['cantidad_actual']->setHidden(false);
              $widgetSchema['cantidad_actual']->setLabel("Unidades Útiles para Empaque");
              $widgetSchema['fecha_empaque'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_empaque']->setLabel("Fecha de Empaque*");
              $widgetSchema['fecha_empaque']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['cc_valdivia']->setAttribute('type', 'text');
              $widgetSchema['cc_valdivia']->setHidden(false);
              $widgetSchema['cc_valdivia']->setDefault(1);
              $widgetSchema['_csrf_token']->setHidden(true);
              //$widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
              //$widgetSchema['cantidad_actual']->setAttribute('disabled', 'disabled');
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
              //$widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
              //$widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
              //$widgetSchema['numero']->setAttribute('disabled', 'disabled'); 

              $validatorSchema['cc_valdivia'] = new sfValidatorInteger();
              $validatorSchema['fecha_empaque'] = new sfValidatorDate();
              $validatorSchema['fecha_elaboracion'] = new sfValidatorDate();
          }
          elseif($estado=="Despachar")
          {
              $widgetSchema['cantidad_actual']->setAttribute('type', 'text');
              $widgetSchema['cantidad_actual']->setHidden(false);
              $widgetSchema['cantidad_actual']->setLabel("Unidades Empacadas para Despacho");
              $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho a Centro de Distribución*");
              $widgetSchema['fecha_envio']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['medio_transporte']->setAttribute('type', 'text');
              $widgetSchema['medio_transporte']->setHidden(false);
              $widgetSchema['n_documento']->setAttribute('type', 'text');
              $widgetSchema['n_documento']->setHidden(true);
              unset($widgetSchema['n_documento']);
              //$widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
              //$widgetSchema['cantidad_actual']->setAttribute('disabled', 'disabled');
              $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
              //$widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
              //$widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
              //$widgetSchema['numero']->setAttribute('disabled', 'disabled');
              $validatorSchema['medio_transporte'] = new sfValidatorString();
              $validatorSchema['fecha_envio'] = new sfValidatorDate();
              $validatorSchema['fecha_elaboracion'] = new sfValidatorDate();

          }
          $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

          if ($this->form->isValid())
          {
              $lote = $this->form->updateObject();
              //Se agrega la siguiente acción
              if($lote->getAccion()=="A Madurar")
              {
                $lote->setAccion('En Maduración');
                $lote->setCantidad_Actual($lote->getCantidad());
              }
              elseif($lote->getAccion()=="En Maduración")
              {
                $lote->setAccion('Empacar');
                $lote->setCantidad_Actual($lote->getCantidad_Actual() - $lote->getCantidad_Danada() - $lote->getCantidad_Ff());
                
                
              } 
              elseif($lote->getAccion()=="Empacar")
              {
                $lote->setAccion('Despachar');
                $lote->setCantidad_Actual($lote->getCantidad_Actual() - $lote->getCc_Valdivia());
                $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), $lote->getFecha_Empaque(), '1', 'aumentar', $this->getUser()->getGuardUser()->getName(),'Despachar Grupal');

                $descriptores = Doctrine_Core::getTable("DescriptorDeProducto")->findBy('producto_id', $lote->getProductoId());  

                foreach($descriptores as $descriptor)
                {

                $insumo_id = $descriptor->getInsumoId();
                $lote->cambiarInventarioDescriptor($descriptor->getInsumoId(), ($lote->getCantidadActual() + $lote->getCcValdivia())*$descriptor->getCantidad(), $lote->getFecha_Empaque(), 'disminuir', $this->getUser()->getGuardUser()->getName(),'Empacar Insumo '.$insumo_id);

                $costodirecto= new CostosDirectos();
                $costodirecto->setLoteId($lote->getId());
                $costodirecto->setInsumoId($descriptor->getInsumoId());
                $costodirecto->setTipoCosto('empa');
                $costodirecto->setFecha($lote->getFechaEmpaque());
                $costodirecto->setProductoId($lote->getProductoId());
                
                $preciou = Doctrine_Core::getTable('OrdenCompraInsumo')->getUltimoPrecioUnitario($descriptor->getInsumoId());
                $costodirecto->setPrecioUnitario($preciou);
                
                //El costo seco es el (costo_insumo/unidad * unidades_insumo/unidad_producto * (unidades_producto_empacadas)
                $unidades_empacadas = $lote->getCantidadActual() + $lote->getCcValdivia();
                $valor = $preciou * $descriptor->getCantidad() * $unidades_empacadas;
                $costodirecto->setValor($valor);
                $costodirecto->save();
                }
              } 
              elseif($lote->getAccion()=="Despachar")
              {
                $lote->setAccion('Recepcionar');
                $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), $lote->getFecha_Envio(), '1', 'disminuir', $this->getUser()->getGuardUser()->getName(),'Recepcionar Grupal');
              }
              elseif($lote->getAccion()=="Recepcionar")
              {
                $lote->setAccion('Recepcionado');
              }
              $lote->save();
              $this->mensajes[$i]="Se actualizó el lote número ".$lote->getNumero().". ".$lote->getCantidad();
          } 
          else
          {
              $this->mensajes[$i]="Hubo un error al actualizar el lote número ".$lote->getNumero().". ";
          }  
          
      }
  }

}
