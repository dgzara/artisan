<?php
/**
 * ordencompra actions.
 *
 * @package    quesos
 * @subpackage ordencompra
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ordencompraActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    //echo $this->renderPartial('ordencompra/pdf',array('orden_compra'=>3));
    $filtrando = $request->getParameter('value');
    $desde = $this->dateadd($request->getParameter('desde'),0,0,0,0,0,0);
    $hasta = $this->dateadd($request->getParameter('hasta'),1,0,0,0,0,0);
    if($desde == NULL)
       $desde = '2000/01/01';
    if($request->getParameter('hasta') == NULL)
        $hasta = '2100/01/01';
    if($filtrando != NULL){
    $this->orden_compras = Doctrine_Core::getTable('OrdenCompra')
      ->createQuery('a')
      ->from('OrdenCompra a, a.Proveedor p')
      ->where('p.empresa_nombre like "%"?"%"', $filtrando)
      ->orWhere('numero like "%"?"%"', $filtrando)
      ->orWhere('accion like "%"?"%"', $filtrando)
      ->andWhere('fecha >= ?', $desde)
      ->andWhere('fecha <= ?', $hasta)
      ->orderBy('a.created_at DESC')
      ->execute();
        /*
        $this->orden_compras = Doctrine_Core::getTable('OrdenCompra')
        ->createQuery('a')
                ->from('OrdenCompra oc')
            ->where('empresa_nombre like "%"?"%" and proveedor_id=oc.Proveedor.id', $filtrando)
                //->orWhere('oc.numero like "%"?"%', $filtrando)
               // ->orWhere('oc.accion like "%"?"%', $filtrando)
         // ->orWhere('insumo.nombre like "%"?"%" and OrdenCompra.id=oc.OrdenCompraInsumo.orden_compra_id and Insumo.id=oc.OrdenCompraInsumo.insumo_id', $filtrando)
                  ->andWhere('fecha >= ?', $desde)
                  ->andWhere('fecha <= ?', $hasta)
                ->orderBy('oc.fecha DESC')
             ->execute();
         */
        /*
      $q = Doctrine_Query::create();
        $q->select('oc.*, p.*, i.*, oci.*');
        $q->from('OrdenCompra oc, oc.OrdenCompraInsumo oci');
        //$q->leftJoin('oc.OrdenCompraInsumo oci');
        $q->innerJoin('oci.Insumo i');
        $q->innerJoin('oc.Proveedor p');
        $q->where('p.empresa_nombre = ?', $filtrando);
        $q->orWhere('oc.numero = ?', $filtrando);
        $q->
        $this->orden_compras = $q->execute();
        /*
          ->createQuery('a')
            //->where('name like "%"?"%" and proveedor_id=proveedor.id', $filtrando)
        //    ->orWhere('producto.nombre like "%"?"%" and planproduccion.id=planproduccionproducto.plan_id and producto.id=planproduccionproducto.producto_id', $filtrando)
                  ->andWhere('fecha >= ?', $desde)
                  ->andWhere('fecha <= ?', $hasta)
                ->orderBy('a.fecha DESC')
             ->execute();
             
        $q = Doctrine_Query::create();
        $q->select('oc.numero, oci.id');
        $q->from('OrdenCompraInsumo oci, oci.OrdenCompra oc');
        //$q->innerJoin('oci.OrdenCompra oc');
        $q->innerJoin('oci.Insumo i');
        $q->innerJoin('oc.Proveedor p');
        $q->where('oc.numero = ?', $filtrando);
        $this->orden_compras = $q->execute();
        */
      
    }





    else{

    $this->orden_compras = Doctrine_Core::getTable('OrdenCompra')

      ->createQuery('a')
      ->where('fecha >= ?', $desde)
      ->andWhere('fecha <= ?', $hasta)
      ->orderBy('a.fecha DESC')
      ->execute();
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
        $this->redirect('ordencompra/index');
    }
    $this->orden_compras = Doctrine_Core::getTable('OrdenCompra')

      ->createQuery('a')
      ->where('a.accion = ?', $request->getParameter('accion'))
      ->orderBy('a.fecha DESC')
      ->execute();
  }



  public function executeShow(sfWebRequest $request)

  {
    $this->orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('id')));
    $this->formato = new sfNumberFormat('es_CL');
    $this->forward404Unless($this->orden_compra);
  }



  public function executeNew(sfWebRequest $request)

  {

    $this->form = new OrdenCompraForm();

    //Numero correlativo no editable.
    $this->widgetSchema = $this->form->getWidgetSchema();
    $this->widgetSchema['proveedor_id']->setAttribute("onchange", "cargarProductosPorProveedor()");


    $this->widgetSchema['numero']->setDefault(Doctrine_Core::getTable('OrdenCompra')->getLastNumero());

    $this->widgetSchema['numero']->setAttribute('disabled', 'disabled');


  }



  public function executeNewProveedor(sfWebRequest $request)

  {

    $this->form = new OrdenCompraForm();

    $this->widgetSchema = $this->form->getWidgetSchema();

    $this->widgetSchema['proveedor_id']->setAttribute("onchange", "cargarProductosPorProveedor()");

  }



  public function executeCreate(sfWebRequest $request)

  {

    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));



    $this->form = new OrdenCompraForm();

    //$this->vacio = 'true';

    //Numero debe ser correlativo, no editable.

    $this->widgetSchema = $this->form->getWidgetSchema();

    $this->widgetSchema['numero']->setAttribute('disabled', 'disabled');

    $this->widgetSchema['proveedor_id']->setAttribute("onchange", "cargarProductosPorProveedor()");

    // Guardamos la orden de compra

    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

    if ($this->form->isValid())

    {

         $orden_compra = $this->form->save();

            // Buscamos los objetos de un proveedor específico

            $this->orden_compra_insumos = array();

            $this->proveedor = Doctrine_Core::getTable('Proveedor')->findOneById($orden_compra->getProveedorId());

            $this->insumos = $this->proveedor->getInsumos();



            foreach($this->insumos as $insumo){

                $this->orden_compra_insumos[$insumo->getId()] = new OrdenCompraInsumoForm();

                $widgetSchema = $this->orden_compra_insumos[$insumo->getId()]->getWidgetSchema();

                $validatorSchema = $this->orden_compra_insumos[$insumo->getId()]->getValidatorSchema();

                $widgetSchema->setNameFormat('orden_compra_insumo_'.$insumo->getId().'[%s]');

            }



            // Guardamos todos los insumos



            foreach($this->insumos as $insumo){

                $this->processFormInsumo($request, $this->orden_compra_insumos[$insumo->getId()], $orden_compra);

            }



            $message = $this->getMailer()->compose( array('info@quesosartisan.cl' => 'Sistema de Información Artisan'),

        'ordendecompra@quesosartisan.cl',

      'Quesos Artisan: Emisión de Orden de Compra',

      <<<EOF

Estimados,



{$this->getUser()->getGuardUser()->getName()} ha emitido una Orden de Compra. Por favor revisar y completar procedimiento.



De antemano muchas gracias.



Atentamente,

Administrador Artisan



EOF

    );

        // Adjuntamos el PDF
        $data = $this->getPDF($orden_compra);
        $attachment = Swift_Attachment::newInstance($data, 'artisan_oc_'.$orden_compra->getId().'.pdf', 'application/pdf');
        $message->attach($attachment);

        // Enviamos el correo
        $this->getMailer()->send($message);



//            if($this->vacio=='false')

//            {

                $this->redirect('ordencompra/index');

//            }

//

//            else

//               $this->setTemplate('new');

    }

    else{

        //echo 'fail';

    }

    // Redirigimos al Index

    $this->setTemplate('new');

  }



  public function executeEdit(sfWebRequest $request)

  {

    $this->forward404Unless($orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('id'))), sprintf('Object orden_compra does not exist (%s).', $request->getParameter('id')));

    //$this->forward404Unless($orden_compra_insumos = Doctrine_Core::getTable('OrdenCompraInsumo')->findByOrdenCompraId($orden_compra->getId()));



    $this->form = new OrdenCompraForm($orden_compra);

    $widgetSchema = $this->form->getWidgetSchema();



    $this->proveedor = Doctrine_Core::getTable('Proveedor')->findOneById($orden_compra->getProveedorId());

    $this->insumos = $this->proveedor->getInsumos();



    //$widgetSchema['fecha']->setAttribute('disabled', 'disabled');

    $widgetSchema['proveedor_id']->setAttribute('disabled', 'disabled');

    $widgetSchema['lugar_id']->setAttribute('disabled', 'disabled');

    $widgetSchema['numero']->setAttribute('disabled', 'disabled');



    if($orden_compra->getFecha_Recepcion() != NULL)
    {
        $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción");
    }
    if($orden_compra->getEncargado_Recepcion() != NULL)
    {
        $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
        $widgetSchema['encargado_recepcion']->setHidden(false);
        $widgetSchema['encargado_recepcion']->setLabel("Recepcionista Artisan");
    }
    if($orden_compra->getGuia_Despacho() != NULL)
    {
        $widgetSchema['guia_despacho']->setAttribute('type', 'text');
        $widgetSchema['guia_despacho']->setHidden(false);
        $widgetSchema['guia_despacho']->setLabel("Guía de Despacho");
    }
    if($orden_compra->getEncargado_Despacho() != NULL)
    {
        $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
        $widgetSchema['encargado_despacho']->setHidden(false);
        $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho");
    }

    if($orden_compra->getFecha_Factura() != NULL)

    {

        $widgetSchema['fecha_factura'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));

        $widgetSchema['fecha_factura']->setLabel("Fecha de Facturación");

    }
    if($orden_compra->getN_Factura() != NULL)
    {
        $widgetSchema['n_factura']->setAttribute('type', 'text');
        $widgetSchema['n_factura']->setHidden(false);
        $widgetSchema['n_factura']->setLabel("N° de Factura");
    }
    if($orden_compra->getFecha_Pago() != NULL)
    {
        $widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
        $widgetSchema['fecha_pago']->setLabel("Fecha de Pago");
    }

    if($orden_compra->getForma_Pago() != NULL)

    {

        $widgetSchema['forma_pago'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenCompra')->getFormasPago(),'multiple' => false, 'expanded' => false));

        $widgetSchema['forma_pago']->setHidden(false);

        $widgetSchema['forma_pago']->setLabel("Forma de Pago");



    }

    if($orden_compra->getN_Documento() != NULL)

    {

        $widgetSchema['n_documento']->setAttribute('type', 'text');

        $widgetSchema['n_documento']->setHidden(false);

        $widgetSchema['n_documento']->setLabel("N° de Documento");

    }

    $this->orden_compra_insumos = array();



    // Para todos los insumos, genero el form para guardar la orden de compra.

    foreach($this->insumos as $insumo){



        // Busco el registro, si no esta alamacenado es null.

        $orden_compra_insumo = Doctrine_Core::getTable('OrdenCompraInsumo')->findByDql('orden_compra_id = ? AND insumo_id = ?', array($orden_compra->getId(), $insumo->getId()))->getFirst();



        $this->orden_compra_insumos[$insumo->getId()] = new OrdenCompraInsumoForm($orden_compra_insumo);

        $widgetSchema = $this->orden_compra_insumos[$insumo->getId()]->getWidgetSchema();

        $validatorSchema = $this->orden_compra_insumos[$insumo->getId()]->getValidatorSchema();

        $widgetSchema->setNameFormat('orden_compra_insumo_'.$insumo->getId().'[%s]');

        $widgetSchema['insumo_id']->setAttribute('value', $insumo->getId());

        $widgetSchema['insumo_id']->setLabel($insumo->getNombreCompleto());

        //$widgetSchema['neto']->setAttribute('value', $insumo->getProveedorInsumoByProveedor($orden_compra->getProveedorId()));

    }

  }



  public function executeUpdate(sfWebRequest $request)

  {

    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));

    $this->forward404Unless($orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('id'))), sprintf('Object orden_compra does not exist (%s).', $request->getParameter('id')));

    

    $this->form = new OrdenCompraForm($orden_compra);

    $this->proveedor = Doctrine_Core::getTable('Proveedor')->findOneById($orden_compra->getProveedorId());

    $this->insumos = $this->proveedor->getInsumos();

    $this->orden_compra_insumos = array();



    // Guardamos la orden de compra

    $this->processForm($request, $this->form);



    // Guardamos las órdenes

    foreach($this->insumos as $insumo){



        // Busco el registro, si no esta alamacenado es null.

        $orden_compra_insumo = Doctrine_Core::getTable('OrdenCompraInsumo')->findByDql('orden_compra_id = ? AND insumo_id = ?', array($orden_compra->getId(), $insumo->getId()))->getFirst();



        $this->orden_compra_insumos[$insumo->getId()] = new OrdenCompraInsumoForm($orden_compra_insumo);

        $widgetSchema = $this->orden_compra_insumos[$insumo->getId()]->getWidgetSchema();

        $validatorSchema = $this->orden_compra_insumos[$insumo->getId()]->getValidatorSchema();

        $widgetSchema->setNameFormat('orden_compra_insumo_'.$insumo->getId().'[%s]');

        $widgetSchema['insumo_id']->setAttribute('value', $insumo->getId());

        $widgetSchema['insumo_id']->setLabel($insumo->getNombreCompleto());

        //$widgetSchema['neto']->setAttribute('value', $insumo->getProveedorInsumoByProveedor($orden_compra->getProveedorId()));

        $this->processFormInsumo($request, $this->orden_compra_insumos[$insumo->getId()], $orden_compra);

    }



    $this->redirect('ordencompra/index');



  }



  public function executeDelete(sfWebRequest $request)

{

            $request->checkCSRFProtection();



            $this->forward404Unless($orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('id'))), sprintf('Object orden_compra does not exist (%s).', $request->getParameter('id')));

            $this->forward404Unless($orden_compra_insumos = Doctrine_Core::getTable('OrdenCompraInsumo')->findByOrdenCompraId($orden_compra->getId()));



            if($orden_compra->getAccion()=='Pagar' || $orden_compra->getAccion()=='Pagada')

            {

                foreach($orden_compra_insumos as $orden_compra_insumo)
                {
        		

        	if($orden_compra_insumo->getConversion()!=0)
                	$orden_compra_insumo->cambiarInventario($orden_compra_insumo->getInsumoId(), $orden_compra_insumo->getCantidad()*$orden_compra_insumo->getConversion(), date('Y/m/d/H:m'), 'disminuir', $this->getUser()->getGuardUser()->getName());

        	else
        		$orden_compra_insumo->cambiarInventario($orden_compra_insumo->getInsumoId(), $orden_compra_insumo->getCantidad(), date('Y/m/d/H:m'), 'disminuir', $this->getUser()->getGuardUser()->getName());
                    
        	//$orden_compra_insumo->cambiarInventario($orden_compra_insumo->getInsumoId(), $orden_compra_insumo->getCantidad(), date('Y/m/d/H:m'), 'disminuir');

                }

            }



            $orden_compra->delete();



            $this->redirect('ordencompra/index');

}



  public function executeValidar(sfWebRequest $request){

    $this->forward404Unless($orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('id'))), sprintf('Object orden_compra does not exist (%s).', $request->getParameter('id')));

    $this->forward404Unless($orden_compra_insumos = Doctrine_Core::getTable('OrdenCompraInsumo')->findByOrdenCompraId($orden_compra->getId()));

    

    $this->form = new OrdenCompraForm($orden_compra);

    $this->orden_compra_insumos = array();

    $widgetSchema = $this->form->getWidgetSchema();

    //$widgetSchema['fecha']->setAttribute('disabled', 'disabled');

    $widgetSchema['proveedor_id']->setAttribute('disabled', 'disabled');

    $widgetSchema['lugar_id']->setAttribute('disabled', 'disabled');

    $widgetSchema['numero']->setAttribute('disabled', 'disabled');

    //$widgetSchema['condiciones']->setAttribute('disabled', 'disabled');

    

    foreach($orden_compra_insumos as $orden_compra_insumo){

        $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()] = new OrdenCompraInsumoForm($orden_compra_insumo);

        $widgetSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getWidgetSchema();

        $validatorSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getValidatorSchema();

        $widgetSchema->setNameFormat('orden_compra_insumo_'.$orden_compra_insumo->getInsumoId().'[%s]');

        $widgetSchema['insumo_id']->setLabel($orden_compra_insumo->getInsumo()->getNombreCompleto());

        

    }

  }



  public function executeValidate(sfWebRequest $request)

  {



    //$getVars = $request->getGetParameters();

    //$qryString = http_build_query($getVars);



    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));

    $this->forward404Unless($orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('id'))), sprintf('Object orden_compra does not exist (%s).', $request->getParameter('id')));

    $this->forward404Unless($orden_compra_insumos = Doctrine_Core::getTable('OrdenCompraInsumo')->findByOrdenCompraId($orden_compra->getId()));



    $this->form = new OrdenCompraForm($orden_compra);

    //$this->vacio = 'true';

    $widgetSchema = $this->form->getWidgetSchema();

    $widgetSchema['proveedor_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['lugar_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['numero']->setAttribute('disabled', 'disabled');
    // Guardamos la orden de compra
    $this->processForm($request, $this->form);

    $this->orden_compra_insumos = array();
    foreach($orden_compra_insumos as $orden_compra_insumo){
            $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()] = new OrdenCompraInsumoForm($orden_compra_insumo);
            $widgetSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getWidgetSchema();
            $validatorSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getValidatorSchema();
            $widgetSchema->setNameFormat('orden_compra_insumo_'.$orden_compra_insumo->getInsumoId().'[%s]');
        }

    if ($this->form->isValid()){

        // Guardamos las órdenes

        foreach($orden_compra_insumos as $orden_compra_insumo){

            $this->processFormInsumo($request, $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()], $orden_compra);

        }

//        if($this->vacio=='false')

//            {

            $orden_compra->setAccion('Recepcionar');

            $orden_compra->save();

            $this->redirect('ordencompra/index');

//            }

//

//            else

//               $this->setTemplate('new');

            

    }

    else{

        //echo "NO VALIDO";

        //$this->setTemplate('validar');

    }

    //$this->redirect("ordencompra/validar?" . $qryString);

    $this->setTemplate('validar');

    

  }



  public function executeRechazar(sfWebRequest $request)

  {

    $this->forward404Unless($orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('id'))), sprintf('Object orden_compra does not exist (%s).', $request->getParameter('id')));

    $orden_compra->setAccion('Rechazada');

    $orden_compra->save();

    $this->redirect('ordencompra/index');

  }



  public function executeDeshacer(sfWebRequest $request)

  {

    $this->forward404Unless($orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('id'))), sprintf('Object orden_compra does not exist (%s).', $request->getParameter('id')));

    $this->forward404Unless($orden_compra_insumos = Doctrine_Core::getTable('OrdenCompraInsumo')->findByOrdenCompraId($orden_compra->getId()));



    if($orden_compra->getAccion()=='Rechazada')

    {

    $orden_compra->setAccion('Validar');

    $orden_compra->save();

    }



    else if($orden_compra->getAccion()=='Recepcionar')

    {

    $orden_compra->setAccion('Validar');

    $orden_compra->save();

    }



    else if($orden_compra->getAccion()=='Pagar')

    {
    $this->orden_compra_insumos = array();
    // Guardamos las órdenes
    foreach($orden_compra_insumos as $orden_compra_insumo){
        $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()] = new OrdenCompraInsumoForm($orden_compra_insumo);
        $widgetSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getWidgetSchema();
        $validatorSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getValidatorSchema();
        $widgetSchema->setNameFormat('orden_compra_insumo_'.$orden_compra_insumo->getInsumoId().'[%s]');
        $this->processFormInsumo($request, $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()], $orden_compra);
 	  if($orden_compra_insumo->getConversion()!=0)
        	$orden_compra_insumo->cambiarInventario($orden_compra_insumo->getInsumoId(), $orden_compra_insumo->getCantidad()*$orden_compra_insumo->getConversion(), date('Y/m/d/H:m'), 'disminuir', $this->getUser()->getGuardUser()->getName());
	  else
		$orden_compra_insumo->cambiarInventario($orden_compra_insumo->getInsumoId(), $orden_compra_insumo->getCantidad(), date('Y/m/d/H:m'), 'disminuir', $this->getUser()->getGuardUser()->getName());
        //$orden_compra_insumo->cambiarInventario($orden_compra_insumo->getInsumoId(), $orden_compra_insumo->getCantidad(), date('Y/m/d/H:m'), 'disminuir');
    }
    $orden_compra->setFecha_Factura(NULL);
    $orden_compra->setFecha_Recepcion(NULL);
    $orden_compra->setEncargado_Recepcion(NULL);
    $orden_compra->setGuia_Despacho(NULL);
    $orden_compra->setEncargado_Despacho(NULL);
    $orden_compra->setN_Factura(NULL);
    $orden_compra->setAccion('Recepcionar');
    $orden_compra->save();
    }

    else if($orden_compra->getAccion()=='Pagada')
    {
      $orden_compra->setFecha_Factura(NULL);
      $orden_compra->setFecha_Pago(NULL);
      $orden_compra->setN_Factura(NULL);
      $orden_compra->setForma_pago(NULL);
      $orden_compra->setN_Documento(NULL);
      $orden_compra->setAccion('Pagar');
      $orden_compra->save();
      // si hay costos indirectos redirigir a esto:
      $this->redirect('costos_indirectos/index');
    }
    $this->redirect('ordencompra/index');
  }

  public function executeRecepcionar(sfWebRequest $request)

  {

    $this->forward404Unless($orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('id'))), sprintf('Object orden_compra does not exist (%s).', $request->getParameter('id')));

    $this->forward404Unless($orden_compra_insumos = Doctrine_Core::getTable('OrdenCompraInsumo')->findByOrdenCompraId($orden_compra->getId()));



    $this->form = new OrdenCompraForm($orden_compra);

    $widgetSchema = $this->form->getWidgetSchema();

    $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));

    $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción*");

    $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));

    $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');

    $widgetSchema['encargado_recepcion']->setHidden(false);

    $widgetSchema['encargado_recepcion']->setLabel("Recepcionista Artisan*");

    $widgetSchema['guia_despacho']->setAttribute('type', 'text');

    $widgetSchema['guia_despacho']->setHidden(false);

    $widgetSchema['guia_despacho']->setLabel("Guía de Despacho");

    $widgetSchema['encargado_despacho']->setAttribute('type', 'text');

    $widgetSchema['encargado_despacho']->setHidden(false);

    $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho*");

    $widgetSchema['fecha_factura'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));

    $widgetSchema['fecha_factura']->setLabel("Fecha de Facturación");

    $widgetSchema['n_factura']->setAttribute('type', 'text');

    $widgetSchema['n_factura']->setHidden(false);

    $widgetSchema['n_factura']->setLabel("N° de Factura");

    $widgetSchema['fecha'] = new sfWidgetFormI18nDate(array('culture' => 'es'));

    $widgetSchema['fecha']->setAttribute('disabled', 'disabled');

    $widgetSchema['proveedor_id']->setAttribute('disabled', 'disabled');

    $widgetSchema['lugar_id']->setAttribute('disabled', 'disabled');

    $widgetSchema['numero']->setAttribute('disabled', 'disabled');

    



    $this->orden_compra_insumos = array();



    foreach($orden_compra_insumos as $orden_compra_insumo){

        $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()] = new OrdenCompraInsumoForm($orden_compra_insumo);

        $widgetSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getWidgetSchema();

        $validatorSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getValidatorSchema();

        $widgetSchema->setNameFormat('orden_compra_insumo_'.$orden_compra_insumo->getInsumoId().'[%s]');

        $widgetSchema['insumo_id']->setLabel($orden_compra_insumo->getInsumo()->getNombreCompleto());

        $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');

        $widgetSchema['detalle']->setAttribute('disabled', 'disabled');

        $widgetSchema['neto']->setAttribute('disabled', 'disabled');

    }

  }



  public function executeRecieve(sfWebRequest $request)

  {

    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));

    $this->forward404Unless($orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('id'))), sprintf('Object orden_compra does not exist (%s).', $request->getParameter('id')));

    $this->forward404Unless($orden_compra_insumos = Doctrine_Core::getTable('OrdenCompraInsumo')->findByOrdenCompraId($orden_compra->getId()));



    $this->form = new OrdenCompraForm($orden_compra);

    $widgetSchema = $this->form->getWidgetSchema();

    $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));

    $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción*");

    $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));

    $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');

    $widgetSchema['encargado_recepcion']->setHidden(false);

    $widgetSchema['encargado_recepcion']->setLabel("Recepcionista Artisan*");

    $widgetSchema['guia_despacho']->setAttribute('type', 'text');

    $widgetSchema['guia_despacho']->setHidden(false);

    $widgetSchema['guia_despacho']->setLabel("Guía de Despacho");

    $widgetSchema['encargado_despacho']->setAttribute('type', 'text');

    $widgetSchema['encargado_despacho']->setHidden(false);

    $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho*");

    $widgetSchema['fecha_factura'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));

    $widgetSchema['fecha_factura']->setLabel("Fecha de Facturación");

    $widgetSchema['n_factura']->setAttribute('type', 'text');

    $widgetSchema['n_factura']->setHidden(false);

    $widgetSchema['n_factura']->setLabel("N° de Factura");

    $widgetSchema['fecha'] = new sfWidgetFormI18nDate(array('culture' => 'es'));

    $widgetSchema['fecha']->setAttribute('disabled', 'disabled');

    $widgetSchema['proveedor_id']->setAttribute('disabled', 'disabled');

    $widgetSchema['lugar_id']->setAttribute('disabled', 'disabled');

    $widgetSchema['numero']->setAttribute('disabled', 'disabled');



    $validatorSchema = $this->form->getValidatorSchema();

    $validatorSchema['encargado_despacho'] = new sfValidatorString();

    $validatorSchema['encargado_recepcion'] = new sfValidatorString();

    $validatorSchema['fecha_recepcion'] = new sfValidatorDate();

    

    // Guardamos la orden de compra

    $this->processForm($request, $this->form);



    $this->orden_compra_insumos = array();



    // Guardamos las órdenes

    foreach($orden_compra_insumos as $orden_compra_insumo){
        $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()] = new OrdenCompraInsumoForm($orden_compra_insumo);
        $widgetSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getWidgetSchema();
        $validatorSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getValidatorSchema();
        $widgetSchema->setNameFormat('orden_compra_insumo_'.$orden_compra_insumo->getInsumoId().'[%s]');
        $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
        $widgetSchema['detalle']->setAttribute('disabled', 'disabled');
        $widgetSchema['neto']->setAttribute('disabled', 'disabled');

        //$this->processFormInsumo($request, $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()], $orden_compra);

    }



    if($this->form->isValid())

    {

    foreach($orden_compra_insumos as $orden_compra_insumo){
	if($orden_compra_insumo->getConversion()!=0)
        	$orden_compra_insumo->cambiarInventario($orden_compra_insumo->getInsumoId(), $orden_compra_insumo->getCantidad()*$orden_compra_insumo->getConversion(), $orden_compra->getFecha_Recepcion(), 'aumentar', $this->getUser()->getGuardUser()->getName());
	else
		$orden_compra_insumo->cambiarInventario($orden_compra_insumo->getInsumoId(), $orden_compra_insumo->getCantidad(), $orden_compra->getFecha_Recepcion(), 'aumentar', $this->getUser()->getGuardUser()->getName());

        //$orden_compra_insumo->cambiarInventario($orden_compra_insumo->getInsumoId(), $orden_compra_insumo->getCantidad(), $orden_compra->getFecha_Recepcion(), 'aumentar');

    }



    $orden_compra->setAccion('Pagar');

    $orden_compra->save();

    $this->redirect('ordencompra/index');

    }



    $this->setTemplate('recepcionar');

  }

  public function executePagar(sfWebRequest $request){
    $this->forward404Unless($orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('id'))), sprintf('Object orden_compra does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($orden_compra_insumos = Doctrine_Core::getTable('OrdenCompraInsumo')->findByOrdenCompraId($orden_compra->getId()));
    $this->form = new OrdenCompraForm($orden_compra);
    $this->formCosto = new CostosIndirectosForm();
    $this->area_de_costos_id = 0;
    $this->centro_de_costos_id = 0;
    $this->widgetSchema = $this->form->getWidgetSchema();
    $this->widgetSchema2 = $this->formCosto->getWidgetSchema();
    unset($this->widgetSchema2['descripcion']);
    $this->widgetSchema['proveedor_id']->setAttribute("onchange", "cargarProductosPorProveedor()");
    $widgetSchema = $this->form->getWidgetSchema();
    $widgetSchema['proveedor_id']->setAttributes(array('disabled' => 'disabled'));
    $widgetSchema['lugar_id']->setAttribute(array('disabled' => 'disabled'));
    $widgetSchema['numero']->setAttributes(array('disabled' => 'disabled'));
    $widgetSchema['fecha'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
    $widgetSchema['fecha']->setAttributes(array('disabled' => 'disabled'));
    $widgetSchema['encargado_recepcion']->setAttributes(array('disabled' => 'disabled'));
    $widgetSchema['encargado_despacho']->setAttributes(array('disabled' => 'disabled'));
    $widgetSchema['fecha_recepcion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
    $widgetSchema['fecha_recepcion']->setAttributes(array('disabled' => 'disabled'));
    $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción*");
    $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
    $widgetSchema['encargado_recepcion']->setHidden(false);
    $widgetSchema['encargado_recepcion']->setLabel("Recepcionista Artisan");
    $widgetSchema['guia_despacho']->setAttribute('type', 'text');
    $widgetSchema['guia_despacho']->setHidden(false);
    $widgetSchema['guia_despacho']->setLabel("Guía de Despacho");
    $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
    $widgetSchema['encargado_despacho']->setHidden(false);
    $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho");
    $widgetSchema['fecha_factura'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
    $widgetSchema['fecha_factura']->setLabel("Fecha de Facturación*");
    $widgetSchema['fecha_factura']->setDefault(date('Y/m/d/H:m'));
    $widgetSchema['n_factura']->setAttribute('type', 'text');
    $widgetSchema['n_factura']->setHidden(false);
    $widgetSchema['n_factura']->setLabel("N° de Factura*");
    $widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
    $widgetSchema['fecha_pago']->setLabel("Fecha de Pago*");
    $widgetSchema['fecha_pago']->setDefault(date('Y/m/d/H:m'));
    $widgetSchema['forma_pago'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenCompra')->getFormasPago(),'multiple' => false, 'expanded' => false));
    $widgetSchema['forma_pago']->setHidden(false);
    $widgetSchema['forma_pago']->setLabel("Forma de Pago*");
    $widgetSchema['n_documento']->setAttribute('type', 'text');
    $widgetSchema['n_documento']->setHidden(false);
    $widgetSchema['n_documento']->setLabel("N° de Documento");

    $this->orden_compra_insumos = array();



    foreach($orden_compra_insumos as $orden_compra_insumo){

        $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()] = new OrdenCompraInsumoForm($orden_compra_insumo);

        $widgetSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getWidgetSchema();

        $validatorSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getValidatorSchema();

        $widgetSchema->setNameFormat('orden_compra_insumo_'.$orden_compra_insumo->getInsumoId().'[%s]');

        $widgetSchema['insumo_id']->setLabel($orden_compra_insumo->getInsumo()->getNombreCompleto());

        $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');

        $widgetSchema['detalle']->setAttribute('disabled', 'disabled');

        $widgetSchema['neto']->setAttribute('disabled', 'disabled');

    }

  }



  public function executePay(sfWebRequest $request)

  {

    $this->forward404Unless($orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('id'))), sprintf('Object orden_compra does not exist (%s).', $request->getParameter('id')));

    $this->forward404Unless($orden_compra_insumos = Doctrine_Core::getTable('OrdenCompraInsumo')->findByOrdenCompraId($orden_compra->getId()));

    $this->form = new OrdenCompraForm($orden_compra);

    $this->formCosto = new CostosIndirectosForm();

    $this->widgetSchema2 = $this->formCosto->getWidgetSchema();
    unset($this->widgetSchema2['descripcion']);
    $this->area_de_costos_id = 0;
    $this->centro_de_costos_id = 0;

    $widgetSchema = $this->form->getWidgetSchema();

    $widgetSchema['proveedor_id']->setAttributes(array('disabled' => 'disabled'));

    $widgetSchema['lugar_id']->setAttribute(array('disabled' => 'disabled'));

    $widgetSchema['numero']->setAttributes(array('disabled' => 'disabled'));

    $widgetSchema['fecha'] = new sfWidgetFormI18nDate(array('culture' => 'es'));

    $widgetSchema['fecha']->setAttributes(array('disabled' => 'disabled'));

    $widgetSchema['encargado_recepcion']->setAttributes(array('disabled' => 'disabled'));

    $widgetSchema['encargado_despacho']->setAttributes(array('disabled' => 'disabled'));

    $widgetSchema['fecha_recepcion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));

    $widgetSchema['fecha_recepcion']->setAttributes(array('disabled' => 'disabled'));

    $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción");

    $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');

    $widgetSchema['encargado_recepcion']->setHidden(false);

    $widgetSchema['encargado_recepcion']->setLabel("Recepcionista Artisan");

    $widgetSchema['guia_despacho']->setAttribute('type', 'text');

    $widgetSchema['guia_despacho']->setHidden(false);

    $widgetSchema['guia_despacho']->setLabel("Guía de Despacho");

    $widgetSchema['encargado_despacho']->setAttribute('type', 'text');

    $widgetSchema['encargado_despacho']->setHidden(false);

    $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho");

    $widgetSchema['fecha_factura'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));

    $widgetSchema['fecha_factura']->setLabel("Fecha de Facturación*");

    $widgetSchema['fecha_factura']->setDefault(date('Y/m/d/H:m'));

    $widgetSchema['n_factura']->setAttribute('type', 'text');

    $widgetSchema['n_factura']->setHidden(false);

    $widgetSchema['n_factura']->setLabel("N° de Factura*");

    $widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));

    $widgetSchema['fecha_pago']->setLabel("Fecha de Pago*");

    $widgetSchema['fecha_pago']->setDefault(date('Y/m/d/H:m'));

    $widgetSchema['forma_pago'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenCompra')->getFormasPago(),'multiple' => false, 'expanded' => false));

    $widgetSchema['forma_pago']->setHidden(false);

    $widgetSchema['forma_pago']->setLabel("Forma de Pago*");

    $widgetSchema['n_documento']->setAttribute('type', 'text');

    $widgetSchema['n_documento']->setHidden(false);

    $widgetSchema['n_documento']->setLabel("N° de Documento");



    $validatorSchema = $this->form->getValidatorSchema();

    $validatorSchema['n_factura'] = new sfValidatorInteger();

    $validatorSchema['forma_pago'] = new sfValidatorString();

    $validatorSchema['fecha_factura'] = new sfValidatorDate();

    $validatorSchema['fecha_pago'] = new sfValidatorDate();



    // Guardamos la orden de compra

    $this->processForm($request, $this->form);



    $this->orden_compra_insumos = array();

    foreach($orden_compra_insumos as $orden_compra_insumo){

        $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()] = new OrdenCompraInsumoForm($orden_compra_insumo);

        $widgetSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getWidgetSchema();

        $validatorSchema = $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()]->getValidatorSchema();

        $widgetSchema->setNameFormat('orden_compra_insumo_'.$orden_compra_insumo->getInsumoId().'[%s]');

        $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');

        $widgetSchema['detalle']->setAttribute('disabled', 'disabled');

        $widgetSchema['neto']->setAttribute('disabled', 'disabled');

    }

    
    if($this->form->isValid())

    {
    $hayCostosInd=$request->getParameter('existe_costo_indirecto');
    if($hayCostosInd==1)
    {
      $this->formCosto->bind($request->getParameter('costos_indirectos'), $request->getFiles('costos_indirectos'));
      if ($this->formCosto->isValid())
      {
        $costos_indirectos = $this->formCosto->save();
      }
    }

    $orden_compra->setAccion('Pagada');

    $orden_compra->save();

    $this->redirect('ordencompra/index');
    
    }


    $this->setTemplate('pagar');

  }

  

  public function executeCargarOrdenProducto(sfWebRequest $request)

  {

    $proveedor_id = $request->getParameter('proveedor_id');

    $this->orden_compra_insumos = array();

    $this->proveedor = Doctrine_Core::getTable('Proveedor')->findOneById($proveedor_id);

    if($this->proveedor!=NULL)

        $this->insumos = $this->proveedor->getInsumos();

    

    foreach($this->insumos as $insumo){

        $this->orden_compra_insumos[$insumo->getId()] = new OrdenCompraInsumoForm();

        $widgetSchema = $this->orden_compra_insumos[$insumo->getId()]->getWidgetSchema();

        $validatorSchema = $this->orden_compra_insumos[$insumo->getId()]->getValidatorSchema();

        $widgetSchema->setNameFormat('orden_compra_insumo_'.$insumo->getId().'[%s]');

        $widgetSchema['insumo_id']->setAttribute('value', $insumo->getId());

        $widgetSchema['insumo_id']->setLabel($insumo->getNombreCompleto());

        $widgetSchema['neto']->setAttribute('value', $insumo->getProveedorInsumoByProveedor($proveedor_id));

        $widgetSchema['conversion']->setAttribute('value', $insumo->getPresentacionByInsumo($insumo->getId()));
    }



    return $this->renderPartial('listaInsumos', array('orden_compra_insumos' => $this->orden_compra_insumos));

  }



  public function executeCotizar(sfWebRequest $request)

  {

    $this->insumo = $request->getParameter('insumo');



    $this->todos = Doctrine_Core::getTable('ProveedorInsumo')

      ->createQuery('a')

      ->select('a.*')

      ->groupBy('a.insumo_id')

      ->orderBy('a.insumo_id')

      ->execute();



    $this->proveedor_insumos = Doctrine_Core::getTable('ProveedorInsumo')

      ->createQuery('a')

      ->where('a.insumo_id = ?', $request->getParameter('insumo'))

      ->execute();

    

    $this->formato = new sfNumberFormat('es_CL');
  }

  public function executeCotiza(sfWebRequest $request)

  {

    $this->forward404Unless($proveedor_insumo = Doctrine_Core::getTable('ProveedorInsumo')->find(array($request->getParameter('id'))), sprintf('Object proveedor_insumo does not exist (%s).', $request->getParameter('id')));

   // $proveedor = $this->getRoute()->getObject();

  //  $proveedor->activate();



    // send an email to the affiliate

    if($proveedor_insumo->getProveedor()->getVentasEmail()){

        $message = $this->getMailer()->compose( array($this->getUser()->getGuardUser()->getEmail_Address() => $this->getUser()->getGuardUser()->getName()),

        $proveedor_insumo->getProveedor()->getVentasEmail(),

      'Quesos Artisan: Cotización Insumos',

      <<<EOF

Estimado {$proveedor_insumo->getProveedor()->getVentasNombre()},



Me dirijo a Ud. con motivo de cotizar el insumo {$proveedor_insumo->getInsumo()->getNombre()}.



De antemano muchísimas gracias por su pronta respuesta.



Atentamente,

{$this->getUser()->getGuardUser()->getName()}

Quesos Artisan



EOF

    );



        $this->getMailer()->send($message);

    }

    else{



    }

    $this->redirect('ordencompra/cotizar');

  }



  protected function processForm(sfWebRequest $request, sfForm $form)

  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    //$array = $request->getParameter($form->getName());
    //$array['numero'] = $request->getParameter("orden_compra_numero");
    //$form->bind($array, $request->getFiles($form->getName()));

    if ($form->isValid())
    {
      $orden_compra = $form->save();
    }
  }

  protected function processFormInsumo(sfWebRequest $request, sfForm $form, $orden_compra)

  {

    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

    if($form->isValid()){

        $p = $form->updateObject();

        $p->setOrdenCompraId($orden_compra->getId());


        // Si la cantidad es distinta de cero, guardamos
        if($p->getCantidad() != 0){
            $p->save();
        }
        else{
            // Puede que no esté seteado o se quier borrar un registro
            //Si es que existe el elemento revisamos
            if($p->getId()){

                // Buscamos si está almacenado
                $orden_insumo = Doctrine_Core::getTable('OrdenCompraInsumo')->findOneById($p->getId());
                
                // Si existe, lo borramos
                if($orden_insumo){
                    $orden_insumo->delete();
                }
            }
        }
    }
    else{
        echo 'fail Form Insumo';
        $this->setTemplate('edit');
    }
  }

  protected function getPDF($orden_compra)
  {
      $config = sfTCPDFPluginConfigHandler::loadConfig();
      sfTCPDFPluginConfigHandler::includeLangFile($this->getUser()->getCulture());

      $doc_title    = "Orden de compra";
      $doc_subject  = "Detalles del orden de compra";
      $doc_keywords = "Artisan";
      
      //create new PDF document (document units are set by default to millimeters)
      $pdf = new sfTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

      // set document information
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor(PDF_AUTHOR);
      $pdf->SetTitle($doc_title);
      $pdf->SetSubject($doc_subject);
      $pdf->SetKeywords($doc_keywords);

      $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, ALGO);
      
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

      
      sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');

      $htmlcontent  = get_partial('ordencompra/pdf',array('orden_compra'=>$orden_compra));
      $pdf->writeHTML($htmlcontent , true, 0);
      
      // add page header/footer
      $pdf->setPrintHeader(true);
      $pdf->setPrintFooter(true);

      // Close and output PDF document
      return $pdf->Output('artisan_oc_'.$orden_compra->getId().'.pdf', 'S');
      // Stop symfony process
      //throw new sfStopException();
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

      $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, ALGO);
      
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
      //$pdf->SetBarcode("FIRMA");

      $orden_compra_id = $request->getParameter('orde_compra_id');
      $orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($request->getParameter('orden_compra_id')));

      sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');


      $htmlcontent  = get_partial('ordencompra/pdf',array('orden_compra'=>$orden_compra));
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

  
 public function executeGet_data(sfWebRequest $request)
    {
      $formato = new sfNumberFormat('es_CL');

      if ($request->isXmlHttpRequest())
      {
        $q = Doctrine_Query::create()
             ->from('OrdenCompra');

        $asc_desc = $request->getParameter('sSortDir_0');
        $col = $request->getParameter('iSortCol_0');

        switch($col)
        {
          case 0:
            $q->orderBy('numero '.$asc_desc);
            break;
          case 1:
            $q->orderBy('fecha '.$asc_desc);
            break;
          case 2:
            $q->orderBy('proveedor_id '.$asc_desc);
            break;
          case 3:
            $q->from('OrdenCompra o, o.Insumo i')
              ->orderBy('i.nombre '.$asc_desc);
            break;
          case 4:
            //$q->select('o, sum(oc.neto) as neto')
            //  ->from('OrdenCompra o, o.OrdenCompraInsumo oc')
            //  ->orderBy('neto '.$asc_desc);
            break;
        }

        $pager = new sfDoctrinePager('OrdenCompra', $request->getParameter('iDisplayLength'));
        $pager->setQuery($q);
        $req_page = ((int)$request->getParameter('iDisplayStart') / (int)$request->getParameter('iDisplayLength')) + 1;
        $pager->setPage($req_page);
        $pager->init();

        $aaData = array();
        $list = $pager->getResults();
        foreach ($list as $v)
        {
          $ver = $this->getController()->genUrl('ordencompra/show?id='.$v->getId());
          $mod = $this->getController()->genUrl('ordencompra/edit?id='.$v->getId());


          // Elaboramos los insumos
          $insumos = $v->getInsumos();
          $productos = '';
          if($insumos){
              $productos = '<ul>';

              foreach($insumos as $insumo){
                  $nombre_insumo = $insumo->getNombreCompleto();
                  if (substr($nombre_insumo, -2)==" 0")
                  {
                     @$nombre_insumo=substr($nombre_insumo, 0, strlen($nombre)-2);
                  }

                  $ver_producto = $this->getController()->genUrl('insumo/show?id='.$insumo->getId());
                  $productos = $productos.'<li><a href="'.$ver_producto.'" target="_blank">'.$nombre_insumo.'</a> (' . $insumo->getCantidad() .

')</li>';
              }
              $productos = $productos.'</ul>';
          }

          // Vemos el deshacer
          $deshacer = '';
          if($v->getAccion()!='Validar'){
            $des = $this->getController()->genUrl('ordencompra/deshacer?id='.$v->getId());
            $deshacer = '<a href="'.$des.'" class="deshacer_button"><img src="images/tools/icons/event_icons/ico-undo.png" border="0"></a>';
          }

          $estado_accion = $v->getAccion();
          if($v->getAccion() == 'Recepcionar') {
            $link = 'ordencompra/recepcionar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Recepcionar</a>";
          }
          elseif ($v->getAccion()=='Validar') {
            $link = 'ordencompra/validar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Validar</a>";
          }
          elseif ($v->getAccion()=='Pagar') {
            $link = 'ordencompra/pagar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Pagar</a>";
          }

          $aaData[] = array(
            "0" => $v->getNumero(),
            "1" => $v->getDateTimeObject('fecha')->format('d-m-Y'),
            "2" => $v->getProveedor()->getEmpresaNombre(),
            "3" => $productos,
            "4" => '$'.$formato->format($v->getNeto(),'d','CLP'),
            "5" => $estado_accion,
            "6" => $deshacer,
            "7" => '<a href="'.$ver.'"><img src="images/tools/icons/event_icons/ico-story.png" border="0"></a>',
            "8" => '<a href="'.$mod.'"><img src="images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
            //"9" => '<a href="'.$mod.'"><img src="/images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
            "9" => '<input type="checkbox" class="checkbox1" value="'.$v->getId().'" accion="'.$v->getAccion().'"> <br>',
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

    public function executeGrupo(sfWebRequest $request)
    {
        $grupo = $request->getParameter('grupo');
        $ordenes = array();
        //guardo la lista de ordenes q se pueden accionar
        foreach($grupo as $id)
        {
            $ordenes[] =Doctrine::getTable('OrdenCompra')->find($id);
        }
        $this->ordenes = $ordenes;
        $this->ordenes2=array();
        $i=0;
        $estado=$ordenes[0]->getAccion();
        
        //parte de costos indirectos!!
        $this->formsCostos=array();
        $this->area_de_costos_id = 0;
        $this->centro_de_costos_id = 0;

        foreach ($ordenes as $orden) {
            $this->ordenes2[$i] = new OrdenCompraForm($orden);
            $widgetSchema = $this->ordenes2[$i]->getWidgetSchema();
            $widgetSchema->setNameFormat('ordenes_'.$i.'[%s]');
            $validatorSchema = $this->ordenes2[$i]->getValidatorSchema();

            $widgetSchema['archivo_adjunto']->setAttribute('hidden', 'hidden');
            $widgetSchema['archivo_adjunto2']->setAttribute('hidden', 'hidden');
            $widgetSchema['archivo_adjunto3']->setAttribute('hidden', 'hidden');
            $widgetSchema['condiciones']->setAttribute('hidden', 'hidden');
            $widgetSchema['archivo_adjunto']->setHidden(true);
            $widgetSchema['archivo_adjunto2']->setHidden(true);
            $widgetSchema['archivo_adjunto3']->setHidden(true);
            
            //parte de costos indirectos!
            $this->formsCostos[$i] = new CostosIndirectosForm();
            
            $this->widgetSchema2 = $this->formsCostos[$i]->getWidgetSchema();
            $this->widgetSchema2->setNameFormat('costo_'.$i.'[%s]');
            unset($this->widgetSchema2['descripcion']);
            unset($this->widgetSchema2['detalle']);
            unset($this->widgetSchema2['archivo_adjunto']);


            if($estado=="Validar")
            {
              $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
              $widgetSchema['proveedor_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['lugar_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['numero']->setAttribute('disabled', 'disabled');

            }
            elseif($estado=="Recepcionar")
             {
              $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
              $widgetSchema['proveedor_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['lugar_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['numero']->setAttribute('disabled', 'disabled');

              $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción*");
              $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
              $widgetSchema['encargado_recepcion']->setHidden(false);
              $widgetSchema['encargado_recepcion']->setLabel("Recepcionista Artisan*");
              $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
              $widgetSchema['encargado_despacho']->setHidden(false);
              $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho*");
              $validatorSchema['encargado_despacho'] = new sfValidatorString();
              $validatorSchema['encargado_recepcion'] = new sfValidatorString();
              $validatorSchema['fecha_recepcion'] = new sfValidatorDate();
            }
            elseif($estado=="Pagar")
            { 
              $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
              $widgetSchema['proveedor_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['lugar_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['numero']->setAttribute('disabled', 'disabled');

              $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción*");
              $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['fecha_factura'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_factura']->setLabel("Fecha de Facturación*");
              $widgetSchema['fecha_factura']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['n_factura']->setAttribute('type', 'text');
              $widgetSchema['n_factura']->setHidden(false);
              $widgetSchema['n_factura']->setLabel("N° de Factura*");
              $widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_pago']->setLabel("Fecha de Pago*");
              $widgetSchema['fecha_pago']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['forma_pago'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenCompra')->getFormasPago(),'multiple' => false, 'expanded' => false));
              $widgetSchema['forma_pago']->setHidden(false);
              $widgetSchema['forma_pago']->setLabel("Forma de Pago*");
            }

            $i++;
        }
    }

    public function executeValidategrupal(sfWebRequest $request)
    {
        $conCostos=true;
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
        $this->datosordenes=array();
        $this->datosordenes[]="Datos:";
        // Entramos a validar los formularios
        for($i=0 ; $i < count($ordenes_id) ;$i++)
        {
            $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
            $this->forward404Unless($orden_compra = Doctrine_Core::getTable('OrdenCompra')->find(array($ordenes_id[$i])), sprintf('Object orden_compra does not exist (%s).', $ordenes_id[$i]));
            $this->forward404Unless($orden_compra_insumos = Doctrine_Core::getTable('OrdenCompraInsumo')->findByOrdenCompraId($ordenes_id[$i]));

            $this->form= new OrdenCompraForm($orden_compra);
            $widgetSchema = $this->form->getWidgetSchema();
            $widgetSchema->setNameFormat('ordenes_'.$i.'[%s]');
            
            $widgetSchema['archivo_adjunto']->setAttribute('hidden', 'hidden');
            $widgetSchema['archivo_adjunto2']->setAttribute('hidden', 'hidden');
            $widgetSchema['archivo_adjunto3']->setAttribute('hidden', 'hidden');
            $widgetSchema['condiciones']->setAttribute('hidden', 'hidden');
            $widgetSchema['archivo_adjunto']->setHidden(true);
            $widgetSchema['archivo_adjunto2']->setHidden(true);
            $widgetSchema['archivo_adjunto3']->setHidden(true);
            
            if($estado=="Validar")
            {
              $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
              $widgetSchema['proveedor_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['lugar_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['numero']->setAttribute('disabled', 'disabled');
            }
            elseif($estado=="Recepcionar")
            {
              $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
              $widgetSchema['proveedor_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['lugar_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['numero']->setAttribute('disabled', 'disabled');
              $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción*");
              $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['encargado_recepcion']->setAttribute('type', 'text');
              $widgetSchema['encargado_recepcion']->setHidden(false);
              $widgetSchema['encargado_recepcion']->setLabel("Recepcionista Artisan*");
              $widgetSchema['encargado_despacho']->setAttribute('type', 'text');
              $widgetSchema['encargado_despacho']->setHidden(false);
              $widgetSchema['encargado_despacho']->setLabel("Encargado de Despacho*");
              $validatorSchema['encargado_despacho'] = new sfValidatorString();
              $validatorSchema['encargado_recepcion'] = new sfValidatorString();
              $validatorSchema['fecha_recepcion'] = new sfValidatorDate();
            }
            elseif($estado=="Pagar")
            {  
              $widgetSchema['fecha']->setAttribute('disabled', 'disabled');
              $widgetSchema['proveedor_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['lugar_id']->setAttribute('disabled', 'disabled');
              $widgetSchema['numero']->setAttribute('disabled', 'disabled');
              $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción*");
              $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['fecha_factura'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_factura']->setLabel("Fecha de Facturación*");
              $widgetSchema['fecha_factura']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['n_factura']->setAttribute('type', 'text');
              $widgetSchema['n_factura']->setHidden(false);
              $widgetSchema['n_factura']->setLabel("N° de Factura*");
              $widgetSchema['fecha_pago'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
              $widgetSchema['fecha_pago']->setLabel("Fecha de Pago*");
              $widgetSchema['fecha_pago']->setDefault(date('Y/m/d/H:m'));
              $widgetSchema['forma_pago'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('OrdenCompra')->getFormasPago(),'multiple' => false, 'expanded' => false));
              $widgetSchema['forma_pago']->setHidden(false);
              $widgetSchema['forma_pago']->setLabel("Forma de Pago*");
            }
            
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            $this->est="nada";
            $this->ms="";

            if ($this->form->isValid())
            {
                
                $orden_compra = $this->form->updateObject();
                //Se agrega la siguiente acción
                if($orden_compra->getAccion()=="Validar") $orden_compra->setAccion('Recepcionar');
                elseif($orden_compra->getAccion()=="Recepcionar") 
                  {
                    $orden_compra->setAccion('Pagar');
                    $this->orden_compra_insumos = array();
                        // Guardamos las órdenes
                    foreach($orden_compra_insumos as $orden_compra_insumo)
                    {
                        $this->orden_compra_insumos[$orden_compra_insumo->getInsumoId()] = new OrdenCompraInsumoForm($orden_compra_insumo);
                    }                    
                    foreach($orden_compra_insumos as $orden_compra_insumo){
                      if($orden_compra_insumo->getConversion()!=0)
                          $orden_compra_insumo->cambiarInventario($orden_compra_insumo->getInsumoId(), $orden_compra_insumo->getCantidad()*$orden_compra_insumo->getConversion(), $orden_compra->getFecha_Recepcion(), 'aumentar', $this->getUser()->getGuardUser()->getName());
                      else
                          $orden_compra_insumo->cambiarInventario($orden_compra_insumo->getInsumoId(), $orden_compra_insumo->getCantidad(), $orden_compra->getFecha_Recepcion(), 'aumentar', $this->getUser()->getGuardUser()->getName());
                    }
                  }
                elseif($orden_compra->getAccion()=="Pagar"){
                      $orden_compra->setAccion('Pagada');
                      $this->est="Pagar";
                      $this->datosordenes[]="Orden número ".$orden_compra->getNumero();
                      $this->datosordenes[]="Id del Lugar ".$orden_compra->getLugar_id();
                      $this->datosordenes[]="Monto: ".$orden_compra->getNeto();
                      $this->ms="Por favor llenar los costos indirectos asociados al pago en el formulario de más abajo.";
                } 
                $orden_compra->save();
                $this->mensajes[$i]="Se actualizó la orden de compra número ".$orden_compra->getNumero().". ";
            } 
            else
            {
                $this->mensajes[$i]="Hubo un error al actualizar la orden de compra número ".$orden_compra->getNumero().". ";
            } 
               
        }
            //Parte de costos indirectos
            $this->formsCostos=array();
            $this->area_de_costos_id = 0;
            $this->centro_de_costos_id = 0;

            for($i=0 ; $i < count($ordenes_id) ;$i++)
            {
              $this->formsCostos[$i] = new CostosIndirectosForm();

              $this->widgetSchema2 = $this->formsCostos[$i]->getWidgetSchema();
              $this->widgetSchema2->setNameFormat('costo_'.$i.'[%s]');
              unset($this->widgetSchema2['descripcion']);
              unset($this->widgetSchema2['detalle']);
              unset($this->widgetSchema2['archivo_adjunto']);

            }

    }

    public function executeValidatecostoind(sfWebRequest $request)
    {
          
          $keys = array_keys($request->getPostParameters());
          //Contamos cuantas órdenes hay
          $cont=0;
          $consulta = $request->getPostParameters();
          foreach($keys as $key=>$val){
              if(substr_count($val, 'costo_')){
                  $cont++;
              }
          }
          for($i=0 ; $i < $cont ;$i++)
          {
            $hayCostosInd=$request->getParameter('existe_costo_indirecto_'.$i);
            $this->formCosto = new CostosIndirectosForm();
            $this->widgetSchema2 = $this->formCosto->getWidgetSchema();
            unset($this->widgetSchema2['descripcion']);
            $this->widgetSchema2->setNameFormat('costo_'.$i.'[%s]');
            $this->area_de_costos_id = 0;
            $this->centro_de_costos_id = 0;
            if($hayCostosInd==1)
            {
              $this->formCosto->bind($request->getParameter($this->formCosto->getName()), $request->getFiles($this->formCosto->getName()));
              if ($this->formCosto->isValid())
              {
                $costos_indirectos = $this->formCosto->save();
              }  
            }
          }
          $this->redirect('ordencompra/index');
    }


}

