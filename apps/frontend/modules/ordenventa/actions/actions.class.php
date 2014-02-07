<?php

/**
 * ordenventa actions.
 *
 * @package    quesos
 * @subpackage ordenventa
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ordenventaActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $page = ($request->getParameter('page')) ? $request->getParameter('page') : 0;
    $rows_per_click = 20;
    $this->ordenes_venta = Doctrine::getTable('OrdenVenta')
      ->createQuery('a')
      ->orderBy('a.id DESC')
      ->limit($rows_per_click)
      ->offset($page*$rows_per_click)
      ->execute();

    if($request->isXmlHttpRequest())
    {
        $this->setTemplate('load_more');
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
        $this->redirect('ordenventa/index');
    }

    if($this->accion == "Validar")
    {
        $this->titulo = "Validar Orden de Venta para Despachar";
        $this->link = lcfirst($request->getParameter('accion'));
    }
    
    else if($this->accion == "Despachar")
    {
        $this->titulo = "Despachar Orden de Venta";
        $this->link = lcfirst($request->getParameter('accion'));
    }

    else if($this->accion == "Registrar Recepción")
    {
        $this->titulo = "Registrar Recepción Orden de Venta";
        $this->link = "recepcion";
    }

    else if($this->accion == "Cobrar")
    {
        $this->titulo = "Cobrar Orden de Venta";
        $this->link = lcfirst($request->getParameter('accion'));
    }

    else
    {
        $this->link = lcfirst($request->getParameter('accion'));
    }

    if($this->accion == "Registrar Devolución")
    {
        $this->titulo = "Registrar Devolución de una Orden de Venta";
        $this->link = "devolucion";
        $this->orden_ventas = Doctrine_Core::getTable('OrdenVenta')
      ->createQuery('a')
      ->where('a.accion = ?', 'Registrar Recepción')
      ->orderBy('a.created_at DESC')
      ->execute();
    }

    else{
    $this->orden_ventas = Doctrine_Core::getTable('OrdenVenta')
      ->createQuery('a')
      ->where('a.accion = ?', $request->getParameter('accion'))
      ->orderBy('a.created_at DESC')
      ->execute();
    }
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->orden_venta);
  }

  public function executePreview(sfWebRequest $request)
  {
    $this->orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->orden_venta);
  }



  public function executeUpload(sfWebRequest $request)
{
        


    
}

public function executeImportar()
{
    $ruta = __DIR__.'/../../../../../../public_html/web/importar/server/php/files/';
    $archivos =  glob($ruta."{*.xml,*.txt}",GLOB_BRACE);

    foreach ($archivos as $a){
        @$nombres[]=array_pop(split("/",$a));
    }
    
    foreach ($nombres as $nom) {
        $xml = simplexml_load_file($ruta.$nom);
        if(!$xml)
        {
            unlink($ruta.$nom);
            continue;
        }
        try
        {
            $resultado= $xml->xpath('body/eanucc:transaction/command/eanucc:documentCommand/documentCommandOperand/eanucc:order/typedEntityIdentification/entityIdentification/uniqueCreatorIdentification');
        }
        catch(Exception $e)
        {
            unlink($ruta.$nom);
            continue;
        }

        $nOC=substr($resultado[0], -11);
        try
        {
            $local_ICN= $xml->xpath('body/eanucc:transaction/command/eanucc:documentCommand/documentCommandOperand/eanucc:order/shipParty/gln');

        }
        catch(Exception $e)
        {
            unlink($ruta.$nom);
            continue;
        }
        $local= Doctrine_Core::getTable('Local')->findOneByIdComercioNet($local_ICN);
        $orden= new OrdenVenta();
        $orden->setFecha(date('Y/m/d H:m'));
        $orden->setClienteId(12);
        $orden->setLocalId($local->getId());
        $orden->setNumero(Doctrine_Core::getTable('OrdenVenta')->getLastNumero());
        $orden->setNOc($nOC);
        $orden->setAccion('Validar');

        try{$fecha= $xml->xpath('body/eanucc:transaction/command/eanucc:documentCommand/documentCommandOperand/eanucc:order/movementDate');}
        catch(Exception $e)
        {
            unlink($ruta.$nom);
            continue;
        }
        $orden->setCondiciones('Fecha de Entrega: '.$fecha[0]);
            
        try{$unidades= $xml->xpath('body/eanucc:transaction/command/eanucc:documentCommand/documentCommandOperand/eanucc:order/lineItem');}
        catch(Exception $e)
        {
            unlink($ruta.$nom);
            continue;
        }
        $orden->save(); 
            
        foreach ($unidades as $u) {
            try
            {
                $idComercioNet=(string)$u->itemIdentification->gtin;
                $cantidad=($u->requestedQuantity)*($u->containedUnits);
                $valorNeto=($u->netPrice->amount)/($u->containedUnits);
            }
            catch(Exception $e)
            {
                unlink($ruta.$nom);
                continue;
            } 
            try 
            {
                $descuento=$u->allowanceCharge->monetaryAmountOrPercentage->percentage;

            } 
            catch (Exception $e) {
                $descuento=0;      
            }          
            $producto= Doctrine_Core::getTable('Producto')->findOneByIdComercioNet($idComercioNet);
                        
            $ordenVentaProducto= new OrdenVentaProducto();
            $ordenVentaProducto->setOrdenVentaId($orden->getId());
            $ordenVentaProducto->setCantidad($cantidad);
            $ordenVentaProducto->setNeto($valorNeto);
            $ordenVentaProducto->setProductoId($producto->getId());
            $ordenVentaProducto->setDescuento($descuento);
            $ordenVentaProducto->save();
        } 
        
        $archivosCorrectos[]= array('nombre' =>$nom , 'orden' =>$nOC );
        unlink($ruta.$nom);
       
    }

    $this->nombres=$archivosCorrectos;

    

     
}
  
  public function executeNew(sfWebRequest $request)
  {
  

    $this->form = new OrdenVentaForm();
    $this->widgetSchema = $this->form->getWidgetSchema();
    $this->widgetSchema['cliente_id']->setAttribute("onchange", "cambiarLocal()");
    $this->widgetSchema['numero']->setDefault(Doctrine_Core::getTable('OrdenVenta')->getLastNumero());
    $this->widgetSchema['numero']->setAttribute('disabled', 'disabled');

      

  }

public function executeCreate(sfWebRequest $request)
{
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->form = new OrdenVentaForm();

    $this->widgetSchema = $this->form->getWidgetSchema();
    $this->widgetSchema['cliente_id']->setAttribute("onchange", "cambiarLocal()");
    $this->widgetSchema['numero']->setAttribute('disabled', 'disabled');


    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
    if ($this->form->isValid())
    {
        $orden_venta = $this->form->save();

        $this->orden_venta_productos = array();
    $this->cliente = Doctrine_Core::getTable('Cliente')->findOneById($orden_venta->getClienteId());
    $this->productos = $this->cliente->getProductos();

    foreach($this->productos as $producto){
        $this->orden_venta_productos[$producto->getId()] = new OrdenVentaProductoForm();
        $widgetSchema = $this->orden_venta_productos[$producto->getId()]->getWidgetSchema();
        $validatorSchema = $this->orden_venta_productos[$producto->getId()]->getValidatorSchema();
        $widgetSchema->setNameFormat('orden_venta_producto_'.$producto->getId().'[%s]');
    }

    foreach($this->productos as $producto){
        $this->processFormProducto($request, $this->orden_venta_productos[$producto->getId()], $orden_venta);
    }

    $this->redirect('ordenventa/index');
    }
    else{
       // echo $this->form.'fail';
    }

    $this->setTemplate('new');
}

public function executeEdit(sfWebRequest $request)
{
        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

        $this->form = new OrdenVentaForm($orden_venta);
        $widgetSchema = $this->form->getWidgetSchema();
        if($orden_venta->getFecha_Envio() != NULL)
        {
            $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho");
        }
        if($orden_venta->getN_Bf() != NULL)
        {
            $widgetSchema['n_bf']->setAttribute('type', 'text');
            $widgetSchema['n_bf']->setHidden(false);
            $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta");
        }
        if($orden_venta->getFecha_Bf() != NULL)
        {
            $widgetSchema['fecha_bf'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_bf']->setLabel("Fecha de Facturación/Boleta");

        }
        if($orden_venta->getBoleta_Factura() != NULL)
        {
            $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
            $widgetSchema['boleta_factura']->setHidden(false);
            $widgetSchema['boleta_factura']->setLabel("Factura/Boleta");
        }
        if($orden_venta->getGuia_Despacho() != NULL)
        {
            $widgetSchema['guia_despacho']->setAttribute('type', 'text');
            $widgetSchema['guia_despacho']->setHidden(false);
            $widgetSchema['guia_despacho']->setLabel("Guía de Despacho");
        }
        if($orden_venta->getEncargado_Despacho() != NULL)
        {
            $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
            $widgetSchema['encargado_despacho']->setHidden(false);
            $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho");
        }
        if($orden_venta->getFecha_Recepcion() != NULL)
        {
            $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción");
        }
        if($orden_venta->getEncargado_Recepcion() != NULL)
        {
            $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
            $widgetSchema['encargado_recepcion']->setHidden(false);
            $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local");
        }
        if($orden_venta->getFecha_Pago() != NULL)
        {
            $widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_pago']->setLabel("Fecha de Pago");
        }
        if($orden_venta->getForma_Pago() != NULL)
        {
            $widgetSchema['forma_pago'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getFormasPago(),'multiple' => false, 'expanded' => false));
            $widgetSchema['forma_pago']->setHidden(false);
            $widgetSchema['forma_pago']->setLabel("Forma de Pago");
        }
        if($orden_venta->getN_Documento() != NULL)
        {
            $widgetSchema['n_documento']->setAttribute('type', 'text');
            $widgetSchema['n_documento']->setHidden(false);
            $widgetSchema['n_documento']->setLabel("N° de Documento");
        }
        $this->orden_venta_productos = array();

        foreach($orden_venta_productos as $orden_venta_producto){
            $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
            $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
            $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
        }
}

public function executeUpdate(sfWebRequest $request)
{
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

        $this->form = new OrdenVentaForm($orden_venta);

        // Guardamos la orden de venta
        $this->processForm($request, $this->form);

        $this->orden_venta_productos = array();

        // Guardamos las órdenes
        foreach($orden_venta_productos as $orden_venta_producto){
            $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
            $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
            $this->processFormProducto($request, $this->orden_venta_productos[$orden_venta_producto->getProductoId()], $orden_venta);
        }

         $this->redirect('ordenventa/index');

}




public function executeDelete(sfWebRequest $request)
{
        $request->checkCSRFProtection();

        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

        if($orden_venta->getAccion() == 'Registrar Recepción' || $orden_venta->getAccion() == 'Cobrar' || $orden_venta->getAccion()=='Cobrada')
        {
            foreach($orden_venta_productos as $orden_venta_producto){
                $orden_venta_producto->cambiarInventario($orden_venta_producto->getProductoId(), $orden_venta_producto->getCantidad(), date('Y/m/d/H:m'), '2', 'aumentar', $this->getUser()->getGuardUser()->getName());
            }
        }

        $orden_venta->delete();

        $this->redirect('ordenventa/index');
}

   

  public function executeValidar(sfWebRequest $request)
{
    $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

    $this->form = new OrdenVentaForm($orden_venta);
    $this->orden_venta_productos = array();
    $widgetSchema = $this->form->getWidgetSchema();
    $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['numero']->setAttribute('disabled', 'disabled');
    $widgetSchema['archivo_adjunto']->setAttribute('disabled', 'disabled');

    foreach($orden_venta_productos as $orden_venta_producto){
        $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
        $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
        $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
        $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
        $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
    }
}

  public function executeValidate(sfWebRequest $request)
{
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

        
        $this->form = new OrdenVentaForm($orden_venta);

        $widgetSchema = $this->form->getWidgetSchema();
        $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['numero']->setAttribute('disabled', 'disabled');

        $this->processForm($request, $this->form);

        if ($this->form->isValid()){

            $this->orden_venta_productos = array();

            foreach($orden_venta_productos as $orden_venta_producto){
                $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
                $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
                $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
                $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
                $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
                $this->processFormProducto($request, $this->orden_venta_productos[$orden_venta_producto->getProductoId()], $orden_venta);
            }

            $orden_venta->setAccion('Despachar');
            $orden_venta->save();
            $this->redirect('ordenventa/index');
      }
        else
            $this->setTemplate('validar');
}

 

  public function executeRechazar(sfWebRequest $request)
  {
        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $orden_venta->setAccion('Rechazada');
        $orden_venta->save();
        $this->redirect('ordenventa/index');
  }

  public function executeDeshacer(sfWebRequest $request)
  {
        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

        if($orden_venta->getAccion()=='Rechazada')
        {
        $orden_venta->setAccion('Validar');
        $orden_venta->save();
        }

        else if($orden_venta->getAccion()=='Despachar')
        {
        $orden_venta->setAccion('Validar');
        $orden_venta->save();
        }

        else if($orden_venta->getAccion()=='Registrar Recepción')
        {
        $this->orden_venta_productos = array();

        // Guardamos las órdenes
        foreach($orden_venta_productos as $orden_venta_producto){
            $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
            $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
            $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
            $this->processFormProducto($request, $this->orden_venta_productos[$orden_venta_producto->getProductoId()], $orden_venta);
            $orden_venta_producto->cambiarInventario($orden_venta_producto->getProductoId(), $orden_venta_producto->getCantidad(), date('Y/m/d/H:m'), '2', 'aumentar', $this->getUser()->getGuardUser()->getName(), ' Deshacer');
        }


        $orden_venta->setBoleta_Factura(NULL);
        $orden_venta->setFecha_Bf(NULL);
        $orden_venta->setFecha_Envio(NULL);
        $orden_venta->setEncargado_Recepcion(NULL);
        $orden_venta->setGuia_Despacho(NULL);
        $orden_venta->setEncargado_Despacho(NULL);
        $orden_venta->setN_Bf(NULL);
        $orden_venta->setAccion('Despachar');
        $orden_venta->save();
        }

        else if($orden_venta->getAccion()=='Cobrar')
        {
        $orden_venta->setFecha_Recepcion(NULL);
        $orden_venta->setEncargado_Recepcion(NULL);
        $orden_venta->setAccion('Registrar Recepción');
        $orden_venta->save();
        }

        else if($orden_venta->getAccion()=='Devuelta')
        {
        $this->orden_venta_productos = array();

        // Guardamos las órdenes
        foreach($orden_venta_productos as $orden_venta_producto){
            $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
            $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
            $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
            $this->processFormProducto($request, $this->orden_venta_productos[$orden_venta_producto->getProductoId()], $orden_venta);
            $orden_venta_producto->cambiarInventario($orden_venta_producto->getProductoId(), $orden_venta_producto->getCantidad(), date('Y/m/d/H:m'), '2', 'disminuir', $this->getUser()->getGuardUser()->getName(), 'Deshacer');
        }
        $orden_venta->setAccion('Registrar Recepción');
        $orden_venta->save();
        }

        else if($orden_venta->getAccion()=='Cobrada')
        {
        $orden_venta->setBoleta_Factura(NULL);
        $orden_venta->setFecha_Bf(NULL);
        $orden_venta->setFecha_Pago(NULL);
        $orden_venta->setN_Bf(NULL);
        $orden_venta->setForma_pago(NULL);
        $orden_venta->setN_Documento(NULL);
        $orden_venta->setAccion('Cobrar');
        $orden_venta->save();
        }

        $this->redirect('ordenventa/index');
  }

  public function executeDespachar(sfWebRequest $request)
  {
        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

        $this->form = new OrdenVentaForm($orden_venta);
        $widgetSchema = $this->form->getWidgetSchema();
        $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho*");
        $widgetSchema['fecha_envio']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['guia_despacho']->setAttribute('type', 'text');
        $widgetSchema['guia_despacho']->setHidden(false);
        $widgetSchema['guia_despacho']->setLabel("N° Guía de Despacho");
        $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
        $widgetSchema['encargado_despacho']->setHidden(false);
        $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho*");
        $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
        $widgetSchema['encargado_recepcion']->setHidden(false);
        $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local");
        $widgetSchema['fecha_bf'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_bf']->setLabel("Fecha de Emisión de Factura/Boleta");
        $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
        $widgetSchema['boleta_factura']->setHidden(false);
        $widgetSchema['boleta_factura']->setLabel("Factura/Boleta");
        $widgetSchema['n_bf']->setAttribute('type', 'text');
        $widgetSchema['n_bf']->setHidden(false);
        $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta");
        $widgetSchema['fecha'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
        $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['numero']->setAttribute('disabled', 'disabled');
        $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
        $widgetSchema['archivo_adjunto']->setAttribute('disabled', 'disabled');

        $this->orden_venta_productos = array();

        foreach($orden_venta_productos as $orden_venta_producto){
            $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
            $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
            $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
            $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
            $widgetSchema['detalle']->setAttribute('disabled', 'disabled');
            $widgetSchema['neto']->setAttribute('disabled', 'disabled');
            $widgetSchema['descuento']->setAttribute('disabled', 'disabled');
        }
  }

  public function executeDeliver(sfWebRequest $request)
  {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

        $this->form = new OrdenVentaForm($orden_venta);
        $widgetSchema = $this->form->getWidgetSchema();
        $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho*");
        $widgetSchema['fecha_envio']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['guia_despacho']->setAttribute('type', 'text');
        $widgetSchema['guia_despacho']->setHidden(false);
        $widgetSchema['guia_despacho']->setLabel("N° Guía de Despacho");
        $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
        $widgetSchema['encargado_despacho']->setHidden(false);
        $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho*");
        $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
        $widgetSchema['encargado_recepcion']->setHidden(false);
        $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local (Cliente)");
        $widgetSchema['fecha_bf'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_bf']->setLabel("Fecha de Emisión de Factura/Boleta");
        $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
        $widgetSchema['boleta_factura']->setHidden(false);
        $widgetSchema['boleta_factura']->setLabel("Factura/Boleta");
        $widgetSchema['n_bf']->setAttribute('type', 'text');
        $widgetSchema['n_bf']->setHidden(false);
        $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta");
        $widgetSchema['fecha'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
        $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['numero']->setAttribute('disabled', 'disabled');
        $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
        $widgetSchema['archivo_adjunto']->setAttribute('disabled', 'disabled');

        $validatorSchema = $this->form->getValidatorSchema();
        $validatorSchema['encargado_despacho'] = new sfValidatorString();
        $validatorSchema['fecha_envio'] = new sfValidatorDate();

        $this->processForm($request, $this->form);
        
        $this->orden_venta_productos = array();

        foreach($orden_venta_productos as $orden_venta_producto){
            $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
            $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
            $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
            $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
            $widgetSchema['detalle']->setAttribute('disabled', 'disabled');
            $widgetSchema['neto']->setAttribute('disabled', 'disabled');
            $widgetSchema['descuento']->setAttribute('disabled', 'disabled');
            //$this->processFormProducto($request, $this->orden_venta_productos[$orden_venta_producto->getProductoId()], $orden_venta);
        }

        if($this->form->isValid())
        {
            foreach($orden_venta_productos as $orden_venta_producto)
            {
            $orden_venta_producto->cambiarInventario($orden_venta_producto->getProductoId(), $orden_venta_producto->getCantidad(), $orden_venta->getFecha_Envio(), '2', 'disminuir', $this->getUser()->getGuardUser()->getName(), ' Despachar');
            }
        $orden_venta->setAccion('Registrar Recepción');
        $orden_venta->save();

        $this->redirect('ordenventa/index');
        }

        $this->setTemplate('despachar');
  }

  public function executeRecepcion(sfWebRequest $request)
  {
        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

        $this->form = new OrdenVentaForm($orden_venta);
        $widgetSchema = $this->form->getWidgetSchema();
        $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho");
        $widgetSchema['fecha_envio']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['guia_despacho']->setAttribute('type', 'text');
        $widgetSchema['guia_despacho']->setHidden(false);
        $widgetSchema['guia_despacho']->setLabel("N° Guía de Despacho");
        $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
        $widgetSchema['encargado_despacho']->setHidden(false);
        $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho");
        $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Local (Cliente)*");
        $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
        $widgetSchema['encargado_recepcion']->setHidden(false);
        $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local (Cliente)*");
        $widgetSchema['fecha_bf'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_bf']->setLabel("Fecha de Emisión de Factura/Boleta");
        $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
        $widgetSchema['boleta_factura']->setHidden(false);
        $widgetSchema['boleta_factura']->setLabel("Factura/Boleta");
        $widgetSchema['n_bf']->setAttribute('type', 'text');
        $widgetSchema['n_bf']->setHidden(false);
        $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta");
        $widgetSchema['fecha'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
        $widgetSchema['fecha_envio'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha_envio']->setAttribute('disabled', 'disabled');
        $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['numero']->setAttribute('disabled', 'disabled');
        $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
        $widgetSchema['guia_despacho']->setAttribute('disabled', 'disabled');
        $widgetSchema['encargado_despacho']->setAttribute('disabled', 'disabled');
        $widgetSchema['archivo_adjunto']->setAttribute('disabled', 'disabled');


        $this->orden_venta_productos = array();

        foreach($orden_venta_productos as $orden_venta_producto){
            $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
            $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
            $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
            $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
            $widgetSchema['detalle']->setAttribute('disabled', 'disabled');
            $widgetSchema['neto']->setAttribute('disabled', 'disabled');
            $widgetSchema['descuento']->setAttribute('disabled', 'disabled');
        }
  }

  public function executeReception(sfWebRequest $request)
  {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

        $this->form = new OrdenVentaForm($orden_venta);
        $widgetSchema = $this->form->getWidgetSchema();
        $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho");
        $widgetSchema['fecha_envio']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['guia_despacho']->setAttribute('type', 'text');
        $widgetSchema['guia_despacho']->setHidden(false);
        $widgetSchema['guia_despacho']->setLabel("N° Guía de Despacho");
        $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
        $widgetSchema['encargado_despacho']->setHidden(false);
        $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho");
        $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Local (Cliente)*");
        $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
        $widgetSchema['encargado_recepcion']->setHidden(false);
        $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local (Cliente)*");
        $widgetSchema['fecha_bf'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_bf']->setLabel("Fecha de Emisión de Factura/Boleta");
        $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
        $widgetSchema['boleta_factura']->setHidden(false);
        $widgetSchema['boleta_factura']->setLabel("Factura/Boleta");
        $widgetSchema['n_bf']->setAttribute('type', 'text');
        $widgetSchema['n_bf']->setHidden(false);
        $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta");
        $widgetSchema['fecha'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
        $widgetSchema['fecha_envio'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha_envio']->setAttribute('disabled', 'disabled');
        $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['numero']->setAttribute('disabled', 'disabled');
        $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
        $widgetSchema['guia_despacho']->setAttribute('disabled', 'disabled');
        $widgetSchema['encargado_despacho']->setAttribute('disabled', 'disabled');
        $widgetSchema['archivo_adjunto']->setAttribute('disabled', 'disabled');

        $validatorSchema = $this->form->getValidatorSchema();
        $validatorSchema['encargado_recepcion'] = new sfValidatorString();
        $validatorSchema['fecha_recepcion'] = new sfValidatorDate();

        $this->processForm($request, $this->form);

        $this->orden_venta_productos = array();

        foreach($orden_venta_productos as $orden_venta_producto){
            $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
            $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
            $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
            $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
            $widgetSchema['detalle']->setAttribute('disabled', 'disabled');
            $widgetSchema['neto']->setAttribute('disabled', 'disabled');
            $widgetSchema['descuento']->setAttribute('disabled', 'disabled');
            //$this->processFormProducto($request, $this->orden_venta_productos[$orden_venta_producto->getProductoId()], $orden_venta);
            //$orden_venta_producto->cambiarInventario($orden_venta_producto->getProductoId(), $orden_venta_producto->getCantidad(), $orden_venta->getFecha_Envio(), '2', 'disminuir');
        }

        if($this->form->isValid())
        {
        $orden_venta->setAccion('Cobrar');
        $orden_venta->save();
        $this->redirect('ordenventa/index');
        }

        $this->setTemplate('recepcion');
  }

  public function executeCobrar(sfWebRequest $request)
  {

        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));
        $this->form = new OrdenVentaForm($orden_venta);
        $widgetSchema = $this->form->getWidgetSchema();
        $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho");
        $widgetSchema['fecha_envio']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['guia_despacho']->setAttribute('type', 'text');
        $widgetSchema['guia_despacho']->setHidden(false);
        $widgetSchema['guia_despacho']->setLabel("N° Guía de Despacho");
        $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
        $widgetSchema['encargado_despacho']->setHidden(false);
        $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho");
        $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Local (Cliente)");
        $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
        $widgetSchema['encargado_recepcion']->setHidden(false);
        $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local (Cliente)");
        $widgetSchema['fecha_bf'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_bf']->setLabel("Fecha de Emisión de Factura/Boleta*");
        $widgetSchema['fecha_bf']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
        $widgetSchema['boleta_factura']->setHidden(false);
        $widgetSchema['boleta_factura']->setLabel("Factura/Boleta*");
        $widgetSchema['n_bf']->setAttribute('type', 'text');
        $widgetSchema['n_bf']->setHidden(false);
        $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta*");
        $widgetSchema['fecha'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
        $widgetSchema['fecha_envio'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha_envio']->setAttribute('disabled', 'disabled');
        $widgetSchema['fecha_recepcion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha_recepcion']->setAttribute('disabled', 'disabled');
        $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['numero']->setAttribute('disabled', 'disabled');
        $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
        $widgetSchema['guia_despacho']->setAttribute('disabled', 'disabled');
        $widgetSchema['encargado_despacho']->setAttribute('disabled', 'disabled');
        $widgetSchema['encargado_recepcion']->setAttribute('disabled', 'disabled');
        $widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_pago']->setLabel("Fecha de Pago*");
        $widgetSchema['fecha_pago']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['forma_pago'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getFormasPago(),'multiple' => false, 'expanded' => false));
        $widgetSchema['forma_pago']->setHidden(false);
        $widgetSchema['forma_pago']->setLabel("Forma de Pago*");
        $widgetSchema['n_documento']->setAttribute('type', 'text');
        $widgetSchema['n_documento']->setHidden(false);
        $widgetSchema['n_documento']->setLabel("N° de Documento de Pago");
        $widgetSchema['archivo_adjunto']->setAttribute('disabled', 'disabled');

        $this->orden_venta_productos = array();

        foreach($orden_venta_productos as $orden_venta_producto){
            $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
            $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
            $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
            $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
            $widgetSchema['detalle']->setAttribute('disabled', 'disabled');
            $widgetSchema['neto']->setAttribute('disabled', 'disabled');
            $widgetSchema['descuento']->setAttribute('disabled', 'disabled');
        }

  }

  public function executeCharge(sfWebRequest $request)
  {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

        $this->form = new OrdenVentaForm($orden_venta);
        $this->form = new OrdenVentaForm($orden_venta);
        $widgetSchema = $this->form->getWidgetSchema();
        $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho");
        $widgetSchema['fecha_envio']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['guia_despacho']->setAttribute('type', 'text');
        $widgetSchema['guia_despacho']->setHidden(false);
        $widgetSchema['guia_despacho']->setLabel("N° Guía de Despacho");
        $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
        $widgetSchema['encargado_despacho']->setHidden(false);
        $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho");
        $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Local (Cliente)");
        $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
        $widgetSchema['encargado_recepcion']->setHidden(false);
        $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local (Cliente)");
        $widgetSchema['fecha_bf'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_bf']->setLabel("Fecha de Emisión de Factura/Boleta*");
        $widgetSchema['fecha_bf']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
        $widgetSchema['boleta_factura']->setHidden(false);
        $widgetSchema['boleta_factura']->setLabel("Factura/Boleta*");
        $widgetSchema['n_bf']->setAttribute('type', 'text');
        $widgetSchema['n_bf']->setHidden(false);
        $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta*");
        $widgetSchema['fecha'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
        $widgetSchema['fecha_envio'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha_envio']->setAttribute('disabled', 'disabled');
        $widgetSchema['fecha_recepcion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha_recepcion']->setAttribute('disabled', 'disabled');
        $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['numero']->setAttribute('disabled', 'disabled');
        $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
        $widgetSchema['guia_despacho']->setAttribute('disabled', 'disabled');
        $widgetSchema['encargado_despacho']->setAttribute('disabled', 'disabled');
        $widgetSchema['encargado_recepcion']->setAttribute('disabled', 'disabled');
        $widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_pago']->setLabel("Fecha de Pago*");
        $widgetSchema['fecha_pago']->setDefault(date('Y/m/d/H:m'));
        $widgetSchema['forma_pago'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getFormasPago(),'multiple' => false, 'expanded' => false));
        $widgetSchema['forma_pago']->setHidden(false);
        $widgetSchema['forma_pago']->setLabel("Forma de Pago*");
        $widgetSchema['n_documento']->setAttribute('type', 'text');
        $widgetSchema['n_documento']->setHidden(false);
        $widgetSchema['n_documento']->setLabel("N° de Documento de Pago");
        $widgetSchema['archivo_adjunto']->setAttribute('disabled', 'disabled');

        $validatorSchema = $this->form->getValidatorSchema();
        $validatorSchema['n_bf'] = new sfValidatorInteger();
        $validatorSchema['boleta_factura'] = new sfValidatorString();
        $validatorSchema['fecha_bf'] = new sfValidatorDate();
        $validatorSchema['fecha_pago'] = new sfValidatorDate();
        $validatorSchema['forma_pago'] = new sfValidatorString();

        $this->processForm($request, $this->form);

        $this->orden_venta_productos = array();

        foreach($orden_venta_productos as $orden_venta_producto){
            $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
            $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
            $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
            $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
            $widgetSchema['detalle']->setAttribute('disabled', 'disabled');
            $widgetSchema['neto']->setAttribute('disabled', 'disabled');
            $widgetSchema['descuento']->setAttribute('disabled', 'disabled');
        }

        if($this->form->isValid())
        {
        $orden_venta->setAccion('Cobrada');
        $orden_venta->save();
        $this->redirect('ordenventa/index');
        }
        $this->setTemplate('cobrar');

  }

  public function executeDevolucion(sfWebRequest $request)
  {
        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

        $this->form = new OrdenVentaForm($orden_venta);
        $widgetSchema = $this->form->getWidgetSchema();
        if($orden_venta->getFecha_Envio() != NULL)
        {
            $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho");
            $widgetSchema['fecha_envio']->setAttribute('disabled', 'disabled');
        }
        if($orden_venta->getN_Bf() != NULL)
        {
            $widgetSchema['n_bf']->setAttribute('type', 'text');
            $widgetSchema['n_bf']->setHidden(false);
            $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta");
            $widgetSchema['n_bf']->setAttribute('disabled', 'disabled');
        }
        if($orden_venta->getFecha_Bf() != NULL)
        {
            $widgetSchema['fecha_bf'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_bf']->setLabel("Fecha de Facturación/Boleta");
            $widgetSchema['fecha_bf']->setAttribute('disabled', 'disabled');

        }
        if($orden_venta->getBoleta_Factura() != NULL)
        {
            $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
            $widgetSchema['boleta_factura']->setHidden(false);
            $widgetSchema['boleta_factura']->setLabel("Factura/Boleta");
            $widgetSchema['boleta_factura']->setAttribute('disabled', 'disabled');
        }
        if($orden_venta->getGuia_Despacho() != NULL)
        {
            $widgetSchema['guia_despacho']->setAttribute('type', 'text');
            $widgetSchema['guia_despacho']->setHidden(false);
            $widgetSchema['guia_despacho']->setLabel("Guía de Despacho");
            $widgetSchema['guia_despacho']->setAttribute('disabled', 'disabled');
        }
        if($orden_venta->getEncargado_Despacho() != NULL)
        {
            $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
            $widgetSchema['encargado_despacho']->setHidden(false);
            $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho");
            $widgetSchema['encargado_despacho']->setAttribute('disabled', 'disabled');
        }
        if($orden_venta->getFecha_Recepcion() != NULL)
        {
            $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción (Cliente)");
            $widgetSchema['fecha_recepcion']->setAttribute('disabled', 'disabled');
        }
        if($orden_venta->getEncargado_Recepcion() != NULL)
        {
            $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
            $widgetSchema['encargado_recepcion']->setHidden(false);
            $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local (Cliente)");
            $widgetSchema['encargado_recepcion']->setAttribute('disabled', 'disabled');
        }
        if($orden_venta->getFecha_Pago() != NULL)
        {
            $widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_pago']->setLabel("Fecha de Pago");
            $widgetSchema['fecha_pago']->setAttribute('disabled', 'disabled');
        }
        if($orden_venta->getForma_Pago() != NULL)
        {
            $widgetSchema['forma_pago'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getFormasPago(),'multiple' => false, 'expanded' => false));
            $widgetSchema['forma_pago']->setHidden(false);
            $widgetSchema['forma_pago']->setLabel("Forma de Pago");
            $widgetSchema['forma_pago']->setAttribute('disabled', 'disabled');
        }
        if($orden_venta->getN_Documento() != NULL)
        {
            $widgetSchema['n_documento']->setAttribute('type', 'text');
            $widgetSchema['n_documento']->setHidden(false);
            $widgetSchema['n_documento']->setLabel("N° de Documento");
            $widgetSchema['n_documento']->setAttribute('disabled', 'disabled');
        }
        $widgetSchema['fecha'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
        $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
        $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
        $widgetSchema['numero']->setAttribute('disabled', 'disabled');
        $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
        $widgetSchema['archivo_adjunto']->setAttribute('disabled', 'disabled');
        $this->orden_venta_productos = array();

        foreach($orden_venta_productos as $orden_venta_producto){
            $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
            $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
            $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
            $widgetSchema['neto']->setAttribute('disabled', 'disabled');
        }
  }

  public function executeReturn(sfWebRequest $request)
  {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('id'))), sprintf('Object orden_venta does not exist (%s).', $request->getParameter('id')));
        $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden_venta->getId()));

        $this->form = new OrdenVentaForm($orden_venta);
        $this->processForm($request, $this->form);

        $this->orden_venta_productos = array();

        foreach($orden_venta_productos as $orden_venta_producto){
            $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);
            $widgetSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$orden_venta_producto->getProductoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$orden_venta_producto->getProductoId().'[%s]');
            $widgetSchema['producto_id']->setLabel($orden_venta_producto->getProducto()->getNombreCompleto());
            $this->processFormProducto($request, $this->orden_venta_productos[$orden_venta_producto->getProductoId()], $orden_venta);
            $orden_venta_producto->cambiarInventario($orden_venta_producto->getProductoId(), $orden_venta_producto->getCantidad(), date('Y/m/d/H:m'), '2', 'aumentar', $this->getUser()->getGuardUser()->getName(), ' Devolver');
        }

        $orden_venta->setAccion('Devuelta');
        $orden_venta->save();

        $this->redirect('ordenventa/index');
  }

  public function executeCargarOrdenProducto(sfWebRequest $request)
  {
        $cliente_id = $request->getParameter('cliente_id');
        $this->orden_venta_productos = array();
        $this->cliente = Doctrine_Core::getTable('Cliente')->findOneById($cliente_id);
        if($this->cliente!=NULL)
            $this->productos = $this->cliente->getProductos();

        foreach($this->productos as $producto){
            $this->orden_venta_productos[$producto->getId()] = new OrdenVentaProductoForm();
            $widgetSchema = $this->orden_venta_productos[$producto->getId()]->getWidgetSchema();
            $validatorSchema = $this->orden_venta_productos[$producto->getId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_venta_producto_'.$producto->getId().'[%s]');
            $widgetSchema['producto_id']->setAttribute('value', $producto->getId());
            $widgetSchema['producto_id']->setLabel($producto->getNombreCompleto());
            $widgetSchema['neto']->setAttribute('value', $producto->getClienteProductoByCliente($cliente_id));
        }

        return $this->renderPartial('listaProductos', array('orden_venta_productos' => $this->orden_venta_productos));
  }



  public function executeCargarClienteLocales(sfWebRequest $request)
  {
        $cliente_id = $request->getParameter('cliente_id');
        $this->locales = array();
        $this->cliente = Doctrine_Core::getTable('Cliente')->findOneById($cliente_id);
        if($this->cliente!=NULL)
            $this->locales = $this->cliente->getLocales();

        return $this->includePartial('listaLocales', array('locales' => $this->locales));
  }

  public function executePdf(sfWebRequest $request)
    {
      $config = sfTCPDFPluginConfigHandler::loadConfig();
      //$config = sfTCPDFPluginConfigHandler::loadConfig('my_config');
      sfTCPDFPluginConfigHandler::includeLangFile($this->getUser()->getCulture());

      $doc_title    = "Orden de compra";
      $doc_subject  = "Detalles del orden de compra";
      $doc_keywords = "Artisan";
      //$htmlcontent  = "<br /><h1>heading 1</h1><h2>heading 2</h2><h3>heading 3</h3><h4>heading 4</h4><h5>heading 5</h5><h6>heading 6</h6>ordered list:<br /><ol><li><b>bold text</b></li><li><i>italic text</i></li><li><u>underlined text</u></li><li><a href="http://www.tecnick.com">link to http://www.tecnick.com</a></li><li>test break<br />second line<br />third line</li><li><font size="+3">font + 3</font></li><li><small>small text</small></li><li>normal <sub>subscript</sub> <sup>superscript</sup></li></ul><hr />table:<br /><table border="1" cellspacing="1" cellpadding="1"><tr><th>#</th><th>A</th><th>B</th></tr><tr><th>1</th><td bgcolor="#cccccc">A1</td><td>B1</td></tr><tr><th>2</th><td>A2</td><td>B2</td></tr><tr><th>3</th><td>A3</td><td><font color="#FF0000">B3</font></td></tr></table><hr />image:<br /><img src="sfTCPDFPlugin/images/logo_example.png" alt="test alt attribute" width="100" height="100" border="0" />";

      //create new PDF document (document units are set by default to millimeters)
      $pdf = new sfTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

      // set document information
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor(PDF_AUTHOR);
      $pdf->SetTitle($doc_title);
      $pdf->SetSubject($doc_subject);
      $pdf->SetKeywords($doc_keywords);

      $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, PDF_FOOTER_STRING);

      //set margins
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

      //set auto page breaks
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

      //initialize document
      $pdf->AliasNbPages();
      $pdf->AddPage();

      // set barcode
      //$pdf->SetBarcode(date("Y-m-d H:i:s", time()));

      $orden_venta_id = $request->getParameter('orde_venta_id');
      $orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($request->getParameter('orden_venta_id')));

        // output some HTML code
          //echo  $this->renderPartial('ordencompra/pdf',array('orden_compra'=>$this->orden_compra));
          sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');


          $htmlcontent  = get_partial('ordenventa/pdf',array('orden_venta'=>$orden_venta));
          $pdf->writeHTML($htmlcontent , true, 0);

          // output two html columns
          //$first_column_width = 80;
          //$current_y_position = $pdf->getY();
          //$pdf->writeHTMLCell($first_column_width, 0, 0, $current_y_position, "<b>hello</b>", 0, 0, 0);
          //$pdf->writeHTMLCell(0, 0, $first_column_width, $current_y_position, "<i>world</i>", 0, 1, 0);

          // output some content
          //$pdf->Cell(0,10,"TEST Bold-Italic Cell",1,1,'C');

          // output some UTF-8 test content
          //$pdf->AddPage();
          //$pdf->SetFont("FreeSerif", "", 12);

          //$utf8text = file_get_contents(K_PATH_CACHE. "utf8test.txt", false); // get utf-8 text form file
          //$pdf->SetFillColor(230, 240, 255, true);
          //$pdf->Write(5,$utf8text, '', 1);

          // remove page header/footer
          $pdf->setPrintHeader(false);
          $pdf->setPrintFooter(false);

        //   Two HTML columns test
        //   $pdf->AddPage();
        //   $right_column = "<b>right column</b> right column right column right column right column
        //   right column right column right column right column right column right column
        //   right column right column right column right column right column right column";
        //   $left_column = "<b>left column</b> left column left column left column left column left
        //   column left column left column left column left column left column left column
        //   left column left column left column left column left column left column left
        //   column";
        //   $first_column_width = 80;
        //   $second_column_width = 80;
        //   $column_space = 20;
        //   $current_y_position = $pdf->getY();
        //   $pdf->writeHTMLCell($first_column_width, 0, 0, 0, $left_column, 1, 0, 0);
        //   $pdf->Cell(0);
        //   $pdf->writeHTMLCell($second_column_width, 0, $first_column_width+$column_space, $current_y_position, $right_column, 0, 0, 0);

          // add page header/footer
          $pdf->setPrintHeader(true);
          $pdf->setPrintFooter(true);

          // Multicell test
          /*$pdf->MultiCell(40, 5, "A test multicell line 1\ntest multicell line 2\ntest multicell line 3", 1, 'J', 0, 0);
          $pdf->MultiCell(40, 5, "B test multicell line 1\ntest multicell line 2\ntest multicell line 3", 1, 'J', 0);
          $pdf->MultiCell(40, 5, "C test multicell line 1\ntest multicell line 2\ntest multicell line 3", 1, 'J', 0, 0);
          $pdf->MultiCell(40, 5, "D test multicell line 1\ntest multicell line 2\ntest multicell line 3", 1, 'J', 0, 2);
          $pdf->MultiCell(40, 5, "F test multicell line 1\ntest multicell line 2\ntest multicell line 3", 1, 'J', 0);
        */
          // Close and output PDF document
          $pdf->Output();

          // Stop symfony process
          throw new sfStopException();
    }

    public function executeCambiarCliente(sfWebRequest $request){
      $clientes = Doctrine::getTable('Cliente')->findAll();
      return $this->renderPartial('global/select', array('list' => $clientes, 'name' => false, 'id' => false, 'selected_id' => 0));
      
    }

    public function executeCambiarLocal(sfWebRequest $request){
        $cliente_id = $request->getParameter('cliente_id');
        
        if(!is_null($cliente_id) && $cliente_id != 0 ) {
            $locales = Doctrine::getTable('Local')->findByClienteId($cliente_id);
        }
        else {
            $locales = null;
        }
        return $this->renderPartial('global/select', array('list' => $locales, 'name' => false, 'id' => false, 'selected_id' => 0));
    }

    public function executeFactura(sfWebRequest $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $q = Doctrine_Query::create();
            $q->from("OrdenVenta o");
            $q->where("o.boleta_factura = 'Factura'");

            $asc_desc = $request->getParameter('sSortDir_0');
            $col = $request->getParameter('iSortCol_0');

            switch($col)
            {
              case 0:
                $q->orderBy('n_bf '.$asc_desc);
                break;
              case 1:
                $q->orderBy('numero '.$asc_desc);
                break;
              case 2:
                $q->orderBy('accion '.$asc_desc);
                break;
              case 3:
                $q->orderBy('fecha_bf '.$asc_desc);
                break;
              case 4:
                $q->from('OrdenVenta o, o.Cliente c')
                  ->orderBy('c.name '.$asc_desc);
                break;
              case 5:
                $q->from('OrdenVenta o, o.Local l')
                  ->orderBy('l.nombre '.$asc_desc);
                break;
            }

            $pager = new sfDoctrinePager('OrdenVenta', $request->getParameter('iDisplayLength'));
            $pager->setQuery($q);
            $req_page = ((int)$request->getParameter('iDisplayStart') / (int)$request->getParameter('iDisplayLength')) + 1;
            $pager->setPage($req_page);
            $pager->init();

            $aaData = array();
            $list = $pager->getResults();
            foreach ($list as $v)
            {
                $ver = $this->getController()->genUrl('ordenventa/show?id='.$v->getId());
                $mod = $this->getController()->genUrl('ordenventa/edit?id='.$v->getId());

                $aaData[] = array(
                  "0" => $v->getNBf(),
                  "1" => '<a href="'.$ver.'">'.$v->getNumero().'</a>',
                  "2" => $v->getAccion(),
                  "3" => $v->getDateTimeObject('fecha_bf')->format('d-m-Y'),
                  "4" => $v->getCliente()->getName(),
                  "5" => $v->getLocal()->getNombre(),
                  "6" => $v->getValorNeto(),
                  "7" => $v->getIVA(),
                  "8" => $v->getValorTotal(),
                  "9" => '<a href="'.$ver.'"><img src="images/tools/icons/event_icons/ico-story.png" border="0"></a>',
                  "10" => '<a href="'.$mod.'"><img src="images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
                );
            }

            $output = array(
                "iTotalRecords" => count($pager),
                "iTotalDisplayRecords" => count($pager),
                "aaData" => $aaData,
                "sEcho" => $request->getParameter('sEcho'),
            );

          return $this->renderText(json_encode($output));
       }
    }

    public function executeGet_data(sfWebRequest $request)
    {
      $formato = new sfNumberFormat('es_CL');
      
      if ($request->isXmlHttpRequest())
      {
        $q = Doctrine_Query::create()
             ->from('OrdenVenta')
             ->limit(1);

        $pager = new sfDoctrinePager('OrdenVenta', $request->getParameter('iDisplayLength'));
        $pager->setQuery($q);
        $pager->setPage($request->getParameter('page', 1));
        $pager->init();

        $aaData = array();
        $list = $pager->getResults();
        foreach ($list as $v)
        {
          $ver = $this->getController()->genUrl('ordenventa/show?id='.$v->getId());
          $mod = $this->getController()->genUrl('ordenventa/edit?id='.$v->getId());

          // Elaboramos los productos
          $productos = $v->getProductos();
          $list_productos = '';

          if($productos){
              $list_productos = '<ul>';
              foreach($productos as $producto){
                  $ver_producto = $this->getController()->genUrl('producto/show?id='.$producto->getId());
                  $list_productos = $list_productos.'<li><a href="'.$ver_producto. '" target="_blank">' . $producto->getNombreCompleto(). '</a> (' . $producto->getCantidad() . ')</li>';
              }
              $list_productos = $list_productos.'</ul>';
          }

          // Vemos el deshacer
          $deshacer = '';
          if($v->getAccion() != 'Validar'){
            $des = $this->getController()->genUrl('ordenventa/deshacer?id='.$v->getId());
            $deshacer = '<a href="'.$des.'"><img src="images/tools/icons/event_icons/ico-undo.png" border="0"></a>';
          }

            $estado_accion = $v->getAccion();
          if($v->getAccion() == 'Despachar') {
            $link = 'ordenventa/despachar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Despachar</a>";
          }
          elseif ($v->getAccion()=='Validar') {
            $link = 'ordenventa/validar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Validar</a>";
          }
          elseif ($v->getAccion()=='Cobrar') {
            $link = 'ordenventa/cobrar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Cobrar</a>";
          }
          elseif ($v->getAccion()=='Registrar Recepción') {
            $link = 'ordenventa/recepcion/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Registrar Recepción</a>";
          }
          elseif ($v->getAccion()=='Registrar Devolución') {
            $link = 'ordenventa/devolucion/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Registrar Devolución</a>";
          }
          $aaData[] = array(
            "0" => $v->getNumero(),
            "1" => $v->getDateTimeObject('fecha')->format('d-m-Y'),
            "2" => $v->getCliente()->getName(),
            "3" => $v->getNOc(),
            "4" => $v->getLocal()->getNombre(),
            "5" => $v->getValorNeto(),
            "6" => $estado_accion,
            "7" => $deshacer,
            "8" => '<a class="jt" rel="/quesosar/web/ordenventa/preview/'.$v->getId().'" title="Orden '.$v->getNumero().'" href="'.$ver.'"><img src="images/tools/icons/event_icons/ico-story.png" border="0" /></a>',
            //"8" => '<a href="'.$ver.'"><img src="images/tools/icons/event_icons/ico-story.png" border="0"></a>',
            "9" => '<a href="'.$mod.'"><img src="images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
            "10" => '<input type="checkbox" class="checkbox1" value="'.$v->getId().'" accion="'.$v->getAccion().'"> <br>',
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

    //esta funcion sirve para hacer la siguiente accion (validar, despachar, pagar, etc) varias ordenes juntas
    public function executeGrupo(sfWebRequest $request)
    {
        $grupo = $request->getParameter('grupo');
        $ordenes = array();
        //guardo la lista de ordenes q se pueden accionar
        foreach($grupo as $id)
        {
            $ordenes[] =Doctrine::getTable('OrdenVenta')->find($id);
        }
        $this->ordenes = $ordenes;
        $this->ordenes2=array();
        $i=0;
        $estado=$ordenes[0]->getAccion();

        foreach ($ordenes as $orden) {
        $this->ordenes2[$i] = new OrdenVentaForm($orden);
        $widgetSchema = $this->ordenes2[$i]->getWidgetSchema();
        $validatorSchema = $this->ordenes2[$i]->getValidatorSchema();
        $widgetSchema->setNameFormat('ordenes_'.$i.'[%s]');

        if($estado=="Validar")
        {
            $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
            $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
            $widgetSchema['numero']->setAttribute('disabled', 'disabled'); 
            $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Local (Cliente)*");
            $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
            $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
            $widgetSchema['encargado_recepcion']->setHidden(false);
            $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local (Cliente)*");
            $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
            $widgetSchema['boleta_factura']->setHidden(false);
            $widgetSchema['boleta_factura']->setLabel("Factura/Boleta*");
            $widgetSchema['n_bf']->setAttribute('type', 'text');
            $widgetSchema['n_bf']->setHidden(false);
            $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta*");

        }
        elseif($estado=="Despachar")
        {
            $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
            $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
            $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
            $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
            $widgetSchema['numero']->setAttribute('disabled', 'disabled');

            $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho*");
            $widgetSchema['fecha_envio']->setDefault(date('Y/m/d/H:m'));
            $widgetSchema['fecha_envio']->setHidden(false);
            $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
            $widgetSchema['encargado_despacho']->setHidden(false);
            $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho*");
            $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Local (Cliente)*");
            $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
            $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
            $widgetSchema['encargado_recepcion']->setHidden(false);
            $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local (Cliente)*");
            $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
            $widgetSchema['boleta_factura']->setHidden(false);
            $widgetSchema['boleta_factura']->setLabel("Factura/Boleta*");
            $widgetSchema['n_bf']->setAttribute('type', 'text');
            $widgetSchema['n_bf']->setHidden(false);
            $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta*");

        }
        elseif($estado=="Registrar Recepción")
        {
            $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
            $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
            $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
            $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
            $widgetSchema['numero']->setAttribute('disabled', 'disabled');   
           
            $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Local (Cliente)*");
            $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
            $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
            $widgetSchema['encargado_recepcion']->setHidden(false);
            $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local (Cliente)*");
            $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
            $widgetSchema['boleta_factura']->setHidden(false);
            $widgetSchema['boleta_factura']->setLabel("Factura/Boleta*");
            $widgetSchema['n_bf']->setAttribute('type', 'text');
            $widgetSchema['n_bf']->setHidden(false);
            $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta*");
    
        }
        elseif($estado=="Registrar Devolución")
        {
            $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
            $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
            $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
            $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
            $widgetSchema['numero']->setAttribute('disabled', 'disabled');    
            $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Local (Cliente)*");
            $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
            $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
            $widgetSchema['encargado_recepcion']->setHidden(false);
            $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local (Cliente)*");
            $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
            $widgetSchema['boleta_factura']->setHidden(false);
            $widgetSchema['boleta_factura']->setLabel("Factura/Boleta*");
            $widgetSchema['n_bf']->setAttribute('type', 'text');
            $widgetSchema['n_bf']->setHidden(false);
            $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta*");

        }
        elseif($estado=="Cobrar")
        {
            $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
            $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
            $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
            $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
            $widgetSchema['numero']->setAttribute('disabled', 'disabled');

            $widgetSchema['fecha_bf'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_bf']->setLabel("Fecha de Emisión de Factura/Boleta*");
            $widgetSchema['fecha_bf']->setDefault(date('Y/m/d/H:m'));
            $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Local (Cliente)*");
            $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
            $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
            $widgetSchema['encargado_recepcion']->setHidden(false);
            $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local (Cliente)*");
            $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
            $widgetSchema['boleta_factura']->setHidden(false);
            $widgetSchema['boleta_factura']->setLabel("Factura/Boleta*");
            $widgetSchema['n_bf']->setAttribute('type', 'text');
            $widgetSchema['n_bf']->setHidden(false);
            $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta*");
            $widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
            $widgetSchema['fecha_pago']->setLabel("Fecha de Pago*");
            $widgetSchema['fecha_pago']->setDefault(date('Y/m/d/H:m'));
            $widgetSchema['forma_pago'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getFormasPago(),'multiple' => false, 'expanded' => false));
            $widgetSchema['forma_pago']->setHidden(false);
            $widgetSchema['forma_pago']->setLabel("Forma de Pago*");
        }
        $widgetSchema['archivo_adjunto']->setAttribute('hidden', 'hidden');
        $widgetSchema['archivo_adjunto2']->setAttribute('hidden', 'hidden');
        $widgetSchema['archivo_adjunto3']->setAttribute('hidden', 'hidden');
        $widgetSchema['condiciones']->setAttribute('hidden', 'hidden');
        $widgetSchema['archivo_adjunto']->setHidden(true);
        $widgetSchema['archivo_adjunto2']->setHidden(true);
        $widgetSchema['archivo_adjunto3']->setHidden(true);
        $widgetSchema['condiciones']->setHidden(true);
        $i++;
        }
    }

    //Validar un grupo completo de ordenes de compra
    public function executeValidategrupal(sfWebRequest $request)
    {
        $keys = array_keys($request->getPostParameters());

        // Contamos cuantas órdenes hay
        $ordenes_id = array();
        $consulta = $request->getPostParameters();

        foreach($keys as $key=>$val){
            if(substr_count($val, 'ordenes_')){
                $ordenes_id[] = $consulta[$val]['id'];
            }
        }
        $estado = $consulta[$val]['accion'];

        // Variable del mensaje
        $this->mensajes = array();

        // Entramos a validar los formularios
        for($i=0 ; $i < count($ordenes_id) ;$i++)
        {
            $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
            $this->forward404Unless($orden_venta = Doctrine_Core::getTable('OrdenVenta')->find(array($ordenes_id[$i])), sprintf('Object orden_venta does not exist (%s).', $ordenes_id[$i]));
            $this->forward404Unless($orden_venta_productos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($ordenes_id[$i]));

            $this->form= new OrdenVentaForm($orden_venta);
            $widgetSchema = $this->form->getWidgetSchema();
            $widgetSchema->setNameFormat('ordenes_'.$i.'[%s]');
            $validatorSchema = $this->form->getValidatorSchema();
            
            $this->orden_venta_productos = array();
            foreach($orden_venta_productos as $orden_venta_producto)
            {
                $this->orden_venta_productos[$orden_venta_producto->getProductoId()] = new OrdenVentaProductoForm($orden_venta_producto);    
            }

            if($estado=="Validar")
            {
                $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
                $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
                $widgetSchema['numero']->setAttribute('disabled', 'disabled');    
            }
            elseif($estado=="Despachar")
            {
                $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
                $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
                $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
                $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
                $widgetSchema['numero']->setAttribute('disabled', 'disabled'); 
                $widgetSchema['fecha_envio'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
                $widgetSchema['fecha_envio']->setLabel("Fecha de Despacho*");
                $widgetSchema['fecha_envio']->setDefault(date('Y/m/d/H:m'));
                $widgetSchema['fecha_envio']->setHidden(false);
                $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
                $widgetSchema['encargado_despacho']->setHidden(false);
                $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho*");
   
            }
            elseif($estado=="Registrar Recepción")
            {
                $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
                $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
                $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
                $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
                $widgetSchema['numero']->setAttribute('disabled', 'disabled');  
                $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
                $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Local (Cliente)*");
                $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
                $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
                $widgetSchema['encargado_recepcion']->setHidden(false);
                $widgetSchema['encargado_recepcion']->setLabel("Recepcionista en Local (Cliente)*");
             
            }
            elseif($estado=="Registrar Devolución")
            {
                $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
                $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
                $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
                $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
                $widgetSchema['numero']->setAttribute('disabled', 'disabled');    
            }
            elseif($estado=="Cobrar")
            {
                $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
                $widgetSchema['cliente_id']->setAttribute('disabled', 'disabled');
                $widgetSchema['local_id']->setAttribute('disabled', 'disabled');
                $widgetSchema['n_oc']->setAttribute('disabled', 'disabled');
                $widgetSchema['numero']->setAttribute('disabled', 'disabled');    
                $widgetSchema['fecha_bf'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
                $widgetSchema['fecha_bf']->setLabel("Fecha de Emisión de Factura/Boleta*");
                $widgetSchema['fecha_bf']->setDefault(date('Y/m/d/H:m'));
                $widgetSchema['boleta_factura'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getBf(),'multiple' => false, 'expanded' => false));
                $widgetSchema['boleta_factura']->setHidden(false);
                $widgetSchema['boleta_factura']->setLabel("Factura/Boleta*");
                $widgetSchema['n_bf']->setAttribute('type', 'text');
                $widgetSchema['n_bf']->setHidden(false);
                $widgetSchema['n_bf']->setLabel("N° de Factura/Boleta*");
                $widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
                $widgetSchema['fecha_pago']->setLabel("Fecha de Pago*");
                $widgetSchema['fecha_pago']->setDefault(date('Y/m/d/H:m'));
                $widgetSchema['forma_pago'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenVenta')->getFormasPago(),'multiple' => false, 'expanded' => false));
                $widgetSchema['forma_pago']->setHidden(false);
                $widgetSchema['forma_pago']->setLabel("Forma de Pago*");
            }
            $widgetSchema['archivo_adjunto']->setAttribute('hidden', 'hidden');
            $widgetSchema['archivo_adjunto2']->setAttribute('hidden', 'hidden');
            $widgetSchema['archivo_adjunto3']->setAttribute('hidden', 'hidden');
            $widgetSchema['condiciones']->setAttribute('hidden', 'hidden');
            $widgetSchema['archivo_adjunto']->setHidden(true);
            $widgetSchema['archivo_adjunto2']->setHidden(true);
            $widgetSchema['archivo_adjunto3']->setHidden(true);
            $widgetSchema['condiciones']->setHidden(true);

            $this->processForm($request, $this->form);
            if ($this->form->isValid())
            {
                $orden_venta = $this->form->updateObject();
                //Se agrega la siguiente acción
                if($orden_venta->getAccion()=="Validar") $orden_venta->setAccion('Despachar');
                elseif($orden_venta->getAccion()=="Despachar")
                    {
                        foreach($orden_venta_productos as $orden_venta_producto)
                        {
                            $orden_venta_producto->cambiarInventario($orden_venta_producto->getProductoId(), $orden_venta_producto->getCantidad(), $orden_venta->getFecha_Envio(), '2', 'disminuir', $this->getUser()->getGuardUser()->getName());
                        }
                        $orden_venta->setAccion('Registrar Recepción');
                    } 
                elseif($orden_venta->getAccion()=="Registrar Recepción") $orden_venta->setAccion('Cobrar');
                elseif($orden_venta->getAccion()=="Registrar Devolucion") $orden_venta->setAccion('Devuelta');
                elseif($orden_venta->getAccion()=="Cobrar") $orden_venta->setAccion('Cobrada');
                $orden_venta->save();
                $this->mensajes[$i]="Se actualizó la orden de venta número ".$orden_venta->getNumero().". ";
            } 
            else
            {
                $this->mensajes[$i]="Hubo un error al actualizar la orden de venta número ".$orden_venta->getNumero().". ";
            }  
            
        }
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid())
        {  
          $orden_venta = $form->save();
        }
        else{
            echo 'fail';
        }
    }

  protected function processFormProducto(sfWebRequest $request, sfForm $form, $orden_venta)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if($form->isValid()){
            $p = $form->updateObject();
            $p->setOrdenVentaId($orden_venta->getId());
            if($p->getCantidad() != 0){
                $p->save();
            }
        }
        else
        {
            echo 'fail';
        }
    }
}
