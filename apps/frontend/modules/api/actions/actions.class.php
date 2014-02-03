<?php

/**
 * api actions.
 *
 * @package    quesos
 * @subpackage api
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class apiActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

    public function executeList(sfWebRequest $request) {
        $this->objetos = array();
        foreach ($this->getRoute()->getObjects() as $objeto) {
            $this->objetos[] = $objeto->asArray($request->getHost());
        }
    }

    //Aqui llega el pedir la BD
    public function executeListQueries(sfWebRequest $request) {
        if ($request->isMethod('post')) {
            $this->processBasedeDatos($request);
            $this->method = 'post';
        } else if ($request->isMethod('get')) {
            $this->processBasedeDatos($request);
            $this->method = 'get';
        }
    }

    public function executeListProductos(sfWebRequest $request) {
        $this->productos = array();
        foreach ($this->getRoute()->getObjects() as $producto) {
            $this->productos[$this->generateUrl('producto_show', $producto, true)] = $producto->asArray($request->getHost());
        }
    }

    public function executeListClientes(sfWebRequest $request) {
        $this->clientes = array();
        foreach ($this->getRoute()->getObjects() as $cliente) {
            $this->clientes[] = $cliente->asArray($request->getHost());
        }
    }

    public function executeGuardarCaptura(sfWebRequest $request) {
        if ($request->isMethod('post')) {
            $this->processPostRequest($request);
            $this->method = 'post';
        } else if ($request->isMethod('get')) {
            $this->processGetRequest($request);
            $this->method = 'get';
        }
    }

    public function processBasedeDatos(sfWebRequest $request) {
        $postParams = $request->getPostParameters();
        $params = json_decode($postParams[jsondata]);
        foreach ($params as $key => $value) {
            $array[$key] = $value;
        }
        foreach ($array[data] as $key => $value) {
            $capturaArray[$key] = $value;
        }
		$fecha = $capturaArray['fecha'];
		if($capturaArray['fecha'] == null)
			$fecha = '0000-00-00 00:00;00';
        $this->nuevas_instrucciones = Doctrine_Core::getTable('TablaQueries')->getDelta($fecha);
        $this->executeDelta($this->nuevas_instrucciones);
    }

    public function executeDelta($nuevas_instrucciones) {
        $this->objetos = $nuevas_instrucciones;
    }

    public function processPostRequest(sfWebRequest $request) {
        /*         * **COMO ACCEDER A LOS HEADERS EN SYMFONY, NO BORRAR**
          // $raw  = '';
          // $httpContent = fopen('php://input', 'r');
          // while ($kb = fread($httpContent, 1024)) {
          // $raw .= $kb;
          // }
          // fclose($httpContent);
          // $params = json_decode(stripslashes($raw));
         * **************************************************** */

        $postParams = $request->getPostParameters();
        $params = json_decode($postParams[jsondata]);
        foreach ($params as $key => $value) {
            $array[$key] = $value;
        }
        foreach ($array[0] as $key => $value) {
            $array2[$key] = $value;
        }
		foreach ($array2[0] as $key => $value) {
            $array3[$key] = $value;
        }
		foreach ($array3[data] as $key => $value) {
            $capturaArray[$key] = $value;
        }	
		
		for($i = 0;$i<count($array[1]);$i++){
			foreach ($array[1] as $key => $value) {
				$array4[$key] = $value;
			}
			foreach ($array4[$i] as $key => $value) {
				$array5[$key] = $value;
			}
			foreach ($array5[data] as $key => $value) {
				$array6[$key] = $value;
			}
			$calidadesArray[$i] = $array6;
		}
		
		for($i = 0;$i<count($array[2]);$i++){
			foreach ($array[2] as $key => $value) {
				$array4[$key] = $value;
			}
			foreach ($array4[$i] as $key => $value) {
				$array5[$key] = $value;
			}
			foreach ($array5[data] as $key => $value) {
				$array6[$key] = $value;
			}
			$competenciasArray[$i] = $array6;
		}
		
		if($array[3] != null){
		
			for($i = 0;$i<count($array[3]);$i++){
				foreach ($array[3] as $key => $value) {
					$array4[$key] = $value;
				}
				foreach ($array4[$i] as $key => $value) {
					$array5[$key] = $value;
				}
				foreach ($array5[data] as $key => $value) {
					$array6[$key] = $value;
				}
				$fotosArray[$i] = $array6;
			}
		}
        $captura = new Captura();
        

        $producto = Doctrine_Core::getTable('Producto')->findOneByCodigo($capturaArray['codigo']);
        $captura['producto_id'] = $producto->getId();
        $captura['local_id'] = $capturaArray['local'];
        $captura['precio'] = $capturaArray['precio'];
        $captura['facing'] = $capturaArray['facing'];
        $captura['stock'] = $capturaArray['stock'];
        $captura['promocion'] = $capturaArray['promocion'];
        $captura['promotoras'] = $capturaArray['promotoras'];
        $captura['fuera_formato'] = $capturaArray['fueraformato'];
        $captura['mermas'] = $capturaArray['mermas'];
        $captura['fecha'] = $capturaArray['fecha'];
        $captura['photo'] = '/uploads/capturas/'.strtotime($capturaArray['fecha']).'.jpg';
        $captura['modo'] = $capturaArray['modo'];

        $img = imagecreatefromstring(base64_decode($fotosArray[0]['fotobase64']));
        if($img != false){
            imagejpeg($img, sfConfig::get('sf_web_dir').'/uploads/capturas/'.strtotime($capturaArray['fecha']).'.jpg');
            imagedestroy($img);
        }


        $errorMsg = "";
        try {
            $captura->save();
			$numeroDeAspecto = 1;
			for($i = 0;$i<count($calidadesArray);$i++){
				$aspecto = new AspectoCalidadCaptura();
                                $aspectoBase = Doctrine_Core::getTable('AspectoCalidad')->findOneByNombre($calidadesArray[$i]['nombre']);
				$aspecto['captura_id'] = $captura->id;
				$aspecto['aspecto_calidad_id'] = $aspectoBase->id;
				$aspecto['valor'] = $calidadesArray[$i]['isChecked'];
				$aspecto->save();
				$numeroDeAspecto++;
				
			}			
			for($m = 0;$m<count($competenciasArray);$m++){
				$competencia = new ProductoCompetenciaCaptura();
				$competencia['captura_id'] = $captura->id;
				$competencia['producto_competencia_id'] = $competenciasArray[$m]['competencia_id'];
				$competencia['precio_captura'] = $competenciasArray[$m]['precio'];
				$competencia->save();
			}
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }

        if ($captura->id > 0) {
            echo $captura->id . " Se guardó con éxito " . " " . $captura->id;
            echo "mensaje de error es: ".$errorMsg."foto es:".$fotosArray[0]['fotobase64'];
            

            $capturaRevisar = Doctrine_Core::getTable('Captura')->findOneById($captura->id);
            if($capturaRevisar->getStock() <= $capturaRevisar->getProducto()->getStockCritico()){
                $nombreProducto = $capturaRevisar->getProducto()->getNombre();
                $nombreLocal = $capturaRevisar->getLocal()->getCliente()->getName().' '.$capturaRevisar->getLocal()->getNombre();
                $stockCritico = $capturaRevisar->getProducto()->getStockCritico();
                $stockQueHay = $capturaRevisar->getStock();
                $fecha = $capturaRevisar->getFecha();

                $texto = "";
                $texto .= "";
                $texto .= "";

                //TODO
                //mandar mail a administrador!
                $mail = $sf_user->getGuardUser()->getEmailAddress();
                $message = $this->getMailer()->compose( array('alertas@artisan.com' => 'Alertas Artisan'),
       //mail destinatario:
      $mail,
      'Quesos Artisan: Alerta Stock Crítico',
      <<<EOF
    Sr Administrador \n El producto $nombreProducto se encuentra bajo su Stock Critico de $stockCritico.
    A la fecha $fecha hay $stockQueHay productos en el siguiente Local: $nombreLocal \n

    Este mail se envia de forma automatizada para su informacion.


EOF
    );

    $this->getMailer()->send($message);
            }

        } else {
            echo "Error al guardar: " . "<br>" . $errorMsg;
        }
    }

    public function executeDespacho(sfWebRequest $request)
    {
        $qr_codes = $request->getParameter('qr_codes');

        //Separación nombre lote y unidad
        $lotes = array();
        $unidades_lote = array();
        $problemas = array();
        foreach($qr_codes as $qr_code)
        {
            $spl = split('@', $qr_code);
            $numero_lote = $spl[0];
            $unidad = $spl[1];

            $lote = Doctrine_Core::getTable('Lote')->findOneByNumero($numero_lote);
            if(!$lote)
            {
                $problemas[] = array('qr_code' => $qr_code, 'message' => 'El lote '.$qr_code.' no existe sistema');
                continue;
            }

            if($unidad == '')
            {
                $lotes[] = $lote;
            }
            else
            {
                $unidades_lote[$lote->getId()][] = $unidad;
            }
        }

        foreach($lotes as $lote)
        {
            $producto_id = $lote->getProducto()->getId();

            if($lote->getAccion() != 'Despachar')
            {
                $problemas[] = array('qr_code' => $lote->getNumero(), 'message' => 'El lote' .$lote->getNumero().' no está catalogado para despachar');
                continue;
            }

            $lote->setAccion('Recepcionar');

            $stock_actual = $lote->getProducto()->getStockValdivia();

            //Reducimos el inventario en la cantidad necesaria
            $fecha = date('Y/m/d H:m');
            $descuento_inventario = new InventarioProductos();
            $descuento_inventario->setBodegaId(1);
            $descuento_inventario->setProductoId($producto_id);
            $descuento_inventario->setCantidad($stock_actual - $lote->getCantidadActual()); //Revisar!
            $descuento_inventario->setFecha($fecha);

            try
            {
                $lote->save();
                $descuento_inventario->save();
            }
            catch(\Exception $e)
            {
                $problemas[] = array('qr_code' => $lote->getNumero(), 'message' => 'El lote' .$lote->getNumero().' no fue despachado por error en el sistema');
            }
        }

        foreach($unidades_lote as $id => $ul)
        {
            $lote = Doctrine_Core::getTable('Lote')->find($id);

            $producto_id = $lote->getProducto()->getId();

            if($lote->getDescendienteVivo()->getAccion() != 'Despachar')
            {
                //Generamos un problema por cada unidad en conflicto
                foreach($ul as $u)
                {
                    $problemas[] = array('qr_code' => $lote->getNumero().'@'.$u, 'message' => 'La unidad'.$lote->getNumero().'@'.$u.' no está catalogada para despachar');
                }
                continue;
            }

            $stock_actual = $lote->getProducto()->getStockValdivia();

            //Reducimos el inventario en la cantidad necesaria
            $fecha = date('Y/m/d H:m');
            $descuento_inventario = new InventarioProductos();
            $descuento_inventario->setBodegaId(1);
            $descuento_inventario->setProductoId($producto_id);
            $descuento_inventario->setCantidad($stock_actual - count($ul));
            $descuento_inventario->setFecha($fecha);

            $hayNuevo = false;
            if($lote->getDescendienteVivo()->getCantidadActual() != count($ul))
            {
                $hayNuevo = true;
                $lote = $lote->getDescendienteVivo();
                $lote_new = $lote->copy(false);
                $lote_new->save();
                $lote_new->setPadre($lote->getId());
                $lote_new->setCantidadActual($lote->getCantidadActualIndex() - count($ul));
                $lote_new->setCantidad($lote->getCantidadActualIndex() - count($ul));
                $lote_new->setCantidadDanada(0);
                $lote_new->setCantidadFf(0);
                $lote_new->setCcValdivia(0);
                $lote_new->setAccion('Despachar');
                $lote_new->setPadre($lote->getId());
                $spl = split('/', $lote->getNumero());
                $var1 = $spl[0];
                $var2 = $spl[1];
                $lote_new->setNumero($var1."/".++$var2);

                $lote->setCantidad($lote->getCcValdivia() + $lote->getCantidadFf() + $lote->getCantidadDanada() + count($ul));
                $lote->setCantidadActual(count($ul));
                $problemas[] = array('message' => 'Se generó un nuevo lote '.$lote_new->getNumero().' con la diferencia de '.$lote_new->getCantidadActual().' productos.');
                // for($i = 0; $i < $lote->getCantidadActual(); $i++)
                // {
                //     if(!in_array($i, $ul))
                //     {
                //         $problemas[] = array('qr_code' => $lote->getNumero().'@'.$i, 'message' => 'Elemento faltante dentro del lote '.$lote->getNumero());
                //     }
                // }

                $lote->setCantidadDanada($lote->getCantidadDanada() + $lote->getCantidadActual() - count($ul));
                $lote->setCantidadActual(count($ul));
            }

            $lote->setAccion('Recepcionar');

            try
            {
                if($hayNuevo)
                {
                    $lote_new->save();
                }
                $lote->save();
                $descuento_inventario->save();
            }
            catch(\Exception $e)
            {
                foreach($ul as $u)
                {
                    $problemas[] = array('qr_code' => $lote->getNumero().'@'.$u, 'message' => 'La unidad'.$lote->getNumero().'@'.$u.' no ha sido despachada por error en el sistema');
                }
            }
        }

        echo json_encode($problemas);
        exit();

        // echo '</pre>'.print_r($lotes, false).'</pre>';
        // echo '</pre>'.print_r($unidades_lote, false).'</pre>';
    }

    public function executeOrdenesVentas()
    {
        $ordenes = Doctrine_Core::getTable('OrdenVenta')->findByAccion('Despachar');
        $ordenesVenta = array();
        
        foreach($ordenes as $orden)
        {
            $ovProductos = Doctrine_Core::getTable('OrdenVentaProducto')->findByOrdenVentaId($orden->getId());
            $productos=array();
            foreach ($ovProductos as $ovp) {
                $tipo= $ovp->getProducto() -> getNombre().' '.$ovp->getProducto() -> getPresentacion().$ovp->getProducto() -> getUnidad();
                $productos[] = array('tipo' =>$tipo , 'cantidad'=>  $ovp-> getCantidad());
            }
            $ordenesVenta[] = array('id' => $orden->getId() ,'numeroOrdenCompra' => $orden->getNOc(), 'cliente' => $orden->getCliente() -> getNombre() , 'local' => $orden->getLocal() -> getNombre(), 'productos' => $productos );
        }


        echo json_encode($ordenesVenta);
        exit();
    }

    public function executeSesion(sfWebRequest $request)
    {
        $username = $request->getParameter('usuario');
        $password = $request->getParameter('password');

        $usuario= Doctrine_Core::getTable('SfGuardUser')->findOneByUsername($username);
        if(!$usuario)
        {
            echo json_encode("no existe el usuario");             
        }
        else
        {
            $salt=$usuario->getSalt();
            if($usuario->getPassword()==sha1($salt.$password))
            {
                echo json_encode("ok");
            }
            else
            {
                echo json_encode("el usuario no coincide con la contrasena");              
            }
        }

        exit();

    }

    


    public function executeRecepcion(sfWebRequest $request)
    {
        $qr_codes = $request->getParameter('qr_codes');

        //Separación nombre lote y unidad
        $lotes = array();
        $unidades_lote = array();
        foreach($qr_codes as $qr_code)
        {
            $spl = split('@', $qr_code);
            $numero_lote = $spl[0];
            $unidad = $spl[1];

            $lote = Doctrine_Core::getTable('Lote')->findOneByNumero($numero_lote);
            if(!$lote)
            {
                $problemas[] = array('qr_code' => $qr_code, 'message' => 'El lote '.$qr_code.' no existe en el sistema');
                continue;
            }

            if($unidad == '')
            {
                $lotes[] = $lote;
            }
            else
            {
                $unidades_lote[$lote->getId()][] = $unidad;
            }
        }

        $problemas = array();
        foreach($lotes as $lote)
        {
            $producto_id = $lote->getProducto()->getId();

            if($lote->getAccion() != 'Recepcionar')
            {
                $problemas[] = array('qr_code' => $lote->getNumero(), 'message' => 'El lote '.$lote->getNumero().' no está catalogado para recepcionar');
                continue;
            }

            $lote->setAccion('Recepcionado');

            $stock_actual = $lote->getProducto()->getStockSantiago();

            //Aumentamos el inventario en la cantidad necesaria
            $fecha = date('Y/m/d H:m');
            $aumento_inventario = new InventarioProductos();
            $aumento_inventario->setBodegaId(2);
            $aumento_inventario->setProductoId($producto_id);
            $aumento_inventario->setCantidad($stock_actual + $lote->getCantidadActual());
            $aumento_inventario->setFecha($fecha);

            try
            {
                $lote->save();
                $aumento_inventario->save();
            }
            catch(\Exception $e)
            {
                $problemas[] = array('qr_code' => $lote->getNumero(), 'message' => 'El lote '.$lote->getNumero().'no se pudo recepcionar por error en el sistema');
            }
        }

        foreach($unidades_lote as $id => $ul)
        {
            $lote = Doctrine_Core::getTable('Lote')->find($id);

            $producto_id = $lote->getProducto()->getId();

            if($lote->getAccion() != 'Recepcionar')
            {
                //Generamos un problema por cada unidad en conflicto
                foreach($ul as $u)
                {
                    $problemas[] = array('qr_code' => $lote->getNumero().'@'.$u, 'message' => 'La unidad '.$lote->getNumero().'@'.$u.' no está catalogada para recepcionar');
                }
                continue;
            }

            $lote->setAccion('Recepcionado');

            $stock_actual = $lote->getProducto()->getStockSantiago();

            //Aumentamos el inventario en la cantidad necesaria
            $fecha = date('Y/m/d H:m');
            $aumento_inventario = new InventarioProductos();
            $aumento_inventario->setBodegaId(2);
            $aumento_inventario->setProductoId($producto_id);
            $aumento_inventario->setCantidad($stock_actual + count($ul));
            $aumento_inventario->setFecha($fecha);

            if($lote->getCantidadActual() != count($ul))
            {
                for($i = 0; $i < $lote->getCantidadActual(); $i++)
                {
                    if(!in_array($i, $ul))
                    {
                        $problemas[] = array('qr_code' => $lote->getNumero().'@'.$i, 'message' => 'Elemento faltante dentro del lote '.$lote->getNumero());
                    }
                }

                $lote->setCantidadDanada($lote->getCantidadDanada() + $lote->getCantidadActual() - count($ul));
                $lote->setCantidadRecibida(count($ul));
                $lote->setCantidadActual(count($ul));
            }

            try
            {
                $lote->save();
                $aumento_inventario->save();
            }
            catch(\Exception $e)
            {
                foreach($ul as $u)
                {
                    $problemas[] = array('qr_code' => $lote->getNumero().'@'.$u, 'message' => 'La unidad '.$lote->getNumero().'@'.$u.' no se pudo recepcionar por error en el sistema');
                }
            }
        }

        echo json_encode($problemas);
        exit();

        // echo '</pre>'.print_r($lotes, false).'</pre>';
        // echo '</pre>'.print_r($unidades_lote, false).'</pre>';
    }

    public function executeVenta(sfWebRequest $request)
    {
        $qr_codes = $request->getParameter('qr_codes', array());
        //Necesitamos el id de la orden de venta, no el n_oc
        $orden_venta = $request->getParameter('orden_venta'); //si es cero es boleta
        // $orden_venta = 0; 
        // $qr_codes = array('110805-YOYO05-1@0' ,'110805-YOYO05-1@1','110805-YOYO05-1@2' , '110808-QUBR01-1@0', '110808-QUBR01-1@1');
        $problemas = array();              
        if ($orden_venta!=0) {
            $ov = Doctrine_Core::getTable('OrdenVenta')->find($orden_venta);
        }

        if(!$ov && $orden_venta!=0)
        {
            echo json_encode(array('message' => 'Orden de venta no encontrada'));
            exit();
        }

        // echo '<pre>'.print_r($ov->getProductos(), false).'</pre>';
        $productos_solicitados = array();
        if($orden_venta!=0)
        {
            foreach($ov->getProductos() as $producto)
            {
                $productos_solicitados[$producto->getId()] = array('cantidad' => $producto->getCantidad(),'tipo' => $producto->getNombreCompleto() );
                // echo $producto->getId().' / '.$producto->getNombre().' '.$producto->getCantidad().' '.$producto->getPrecio().'<br>';
            }
        }

        $qr_validos = array();
        foreach($qr_codes as $qr_code)
        {
            $spl = split('@', $qr_code);
            $numero_lote = $spl[0];
            $unidad = $spl[1];
            
            $lote = Doctrine_Core::getTable('Lote')->findOneByNumero($numero_lote);

            if(!$lote)
            {
                //Lote no válido, seguimos con el siguiente
                $problemas[] = array('qr_code' => $qr_code, 'message' => 'QR ' .$qr_code.' inválido.');
                continue;
            }
            else if($lote->getAccion() != 'Recepcionado')
            {
                //El lote no está catalogado para despachar
                $problemas[] = array('qr_code' => $lote->getNumero(), 'message' => 'El lote '.$lote->getNumero().'no está catalogado como recepcionado.');
                continue;
            }

            $producto_id = $lote->getProducto()->getId();
            if($orden_venta!=0)
            {
                 
                 if(in_array($producto_id, array_keys($productos_solicitados)))
                {
                    //Descontamos la cantidad de producto faltante
                    $productos_solicitados[$producto_id]['cantidad']--;
                    $qr_validos[] = array('lote_id' => $lote->getId(), 'lote_numero' => $lote->getNumero(), 'unidad' => $unidad);
                }
                else
                {
                    //El producto no fue solicitado en esa orden de venta
                    $problemas[] = array('qr_code' => $qr_code, 'message' => 'Producto' .$productos_solicitados[$producto_id]['tipo'].' no solicitado en orden de venta.');
                }

            }
            else
            {
                $productos_solicitados[$producto_id]++;
                $qr_validos[] = array('lote_id' => $lote->getId(), 'lote_numero' => $lote->getNumero(), 'unidad' => $unidad);
            }
           
        }

        if($orden_venta!=0)
        {
            foreach($productos_solicitados as $pid => $var)
            {
                if($var['cantidad'] > 0)
                {   //No se cumple la cantidad solicitada en la orden de venta
                    $problemas[] = array('pid' => $pid, 'message' => 'Cantidad insuficiente de producto '.$var['tipo']);
                }
                elseif($var['cantidad'] < 0)
                {
                    //No se cumple la cantidad solicitada en la orden de venta
                    $problemas[] = array('pid' => $pid, 'message' => 'Cantidad sobrante de producto '.$var['tipo']);
                }
            }
        }
    

        if(count($problemas) != 0)
        {
            //Abortamos la transacción
            echo json_encode($problemas);
            exit();
        }

        if($orden_venta==0)
        {
            $ov= new OrdenVenta();
            $ov->setNumero(Doctrine_Core::getTable('OrdenVenta')->getLastNumero());
            $ov->setFecha(date('Y/m/d/H:m'));
            $ov->setLocalId(74); //local default
            $ov->setClienteId(27); // cliente default
        
            foreach($productos_solicitados as $pid => $cant)
            {
                $ordenVentaProducto = new OrdenVentaProducto();
                $ordenVentaProducto->setOrdenVenta($ov);
                $ordenVentaProducto->setProductoId($pid);
                $ordenVentaProducto->setCantidad($cant);
                $ordenVentaProducto->setNeto(0);
                $ordenVentaProducto->save();
            }
            //$ov->save();

        }
        
        //Generamos tracking para la unidad
        foreach ($qr_validos as $qr)
        {
            $unidad_lote = new UnidadLote();
            $unidad_lote->setLoteId($qr['lote_id']);
            $unidad_lote->setOrdenVentaId($ov->getId());
            $unidad_lote->setUnidadId($qr['unidad']);

            //Grabamos
            try
            {
                $unidad_lote->save();
            }
            catch(\Exception $e)
            {
                $problemas[] = array('qr_code' => $qr['lote_numero'].'@'.$qr['unidad'], 'message' => 'La unidad '.$qr['lote_numero'].'@'.$qr['unidad'].' no se ha vendido por error en el sistema');
            }
        }

        //Descontamos el inventario de Santiago
        foreach($ov->getProductos() as $producto)
        {
            $stock_actual = $producto->getStockSantiago();
            $fecha = date('Y/m/d H:m');
            $descuento_inventario = new InventarioProductos();
            $descuento_inventario->setBodegaId(2);
            $descuento_inventario->setProductoId($producto->getId());
            $descuento_inventario->setCantidad($stock_actual - $producto->getCantidad());
            $descuento_inventario->setFecha($fecha);

            //Grabamos
            try
            {
                $descuento_inventario->save();
            }
            catch(\Exception $e)
            {
                $problemas[] = array('pid' => $producto->getId(), 'message' => 'Error en el sistema');
            }
        }

        try
        {
            if ($orden_venta!=0) {
                $ov->setAccion('Registrar Recepción');
            }
            else
            {
                 $ov->setAccion('Validar');
            }
            $ov->save();
        }
        catch(\Exception $e)
        {
                $problemas[] = array('message' => 'Error en el sistema');
        }

        echo json_encode($problemas);
        exit();
    }


    public function executeDespachoClientes(sfWebRequest $request)
    {
#        $bodega_origen_id = $request->getParameter('bodega_origen_id');
#        $bodega_destino_id = $request->getParameter('bodega_destino_id');
        $qr_code = $request->getParameter('qr_code', '110726-QUCO02-1');
#        $cantidad = $request->getParameter('cantidad');
#        $fecha = date('Y/m/d/H:m');

        $lote = Doctrine_Core::getTable('Lote')->findOneByNumero($qr_code);
        //$lote->setAccion('');
        echo $lote->getId();

        $unidadLote = Doctrine_Core::getTable('UnidadLote')->findOneById($lote);
        $unidadLoteOrdenVenta = $unidadLote->getOrdenVentaId();

        $OrdenVenta = Doctrine_Core::getTable('OrdenVenta')->findOneById($unidadLoteOrdenVenta);

        $cliente = Doctrine_Core::getTable('Cliente')->findOneByNumero($qr_code);
//       exit();

#        $bodega = Doctrine_Core::getTable('Bodega')->find($bodega_id);
#        $producto = Doctrine_Core::getTable('Producto')->find($producto_id);

        //Deben haber suficientes productos en la bodega de origen.
#        if(getStock($bodega_origen_id, $producto) >= $cantidad)
#        {
#            $descuento_inventario = new InventarioProductos();
#            $descuento_inventario->setBodegaId($bodega_origen_id);
#            $descuento_inventario->setProductoId($producto_id);
#            $descuento_inventario->setCantidad(-$cantidad);
#            $descuento_inventario->setFecha($fecha);

#            $suma_inventario = new InventarioProductos();
#            $suma_inventario->setBodegaId($bodega_destino_id);
#            $suma_inventario->setProductoId($producto_id);
#            $suma_inventario->setCantidad($cantidad);
#            $suma_inventario->setFecha($fecha);

#        }
#        else //Generamos el error
        {
            echo json_encode(array('status' => 'nok', 'message' => 'No hay suficientes unidades en la bodega de origen'));
            exit();
        }

#        try
#        {
#            $descuento_inventario->save();
#            $suma_inventario->save();
#            echo json_encode(array('status' => 'ok'));
#        }
#        catch()
#        {
#            
#        }
    }

}
