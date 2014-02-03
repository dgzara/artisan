<?php

/**
 * ordenventa actions.
 *
 * @package    quesos
 * @subpackage ordenventa
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ventasActions extends sfActions
{

  public function executeLote(sfWebRequest $request)
  {
    $this->lotes = Doctrine_Core::getTable('Lote')
      ->createQuery('a')
      ->andWhere('a.accion = ?', 'Recepcionar')
      ->execute();

    $filtrando = $request->getParameter('value');
    $desde = $this->dateadd($request->getParameter('desde'),0,0,0,0,0,0);
    $hasta = $this->dateadd($request->getParameter('hasta'),1,0,0,0,0,0);

    if($desde == NULL)
        $desde = '2000/01/01';

    if($request->getParameter('hasta') == NULL)
        $hasta = '2100/01/01';

    if($filtrando != NULL){
    $this->lotes = Doctrine_Core::getTable('Lote')
      ->createQuery('a')
      ->from('Lote a, a.Pauta pp, a.Producto p')
      ->where('pp.id like "%"?"%"', $filtrando)
      ->orWhere('p.nombre like "%"?"%"', $filtrando)
      ->orWhere('p.presentacion like "%"?"%"', $filtrando)
      ->orWhere('p.unidad like "%"?"%"', $filtrando)
      ->orWhere('comentarios like "%"?"%"', $filtrando)
      ->orWhere('numero like "%"?"%"', $filtrando)
      ->andWhere('a.accion = ?', 'Recepcionar')
      ->andWhere('fecha_elaboracion >= ?', $desde)
      ->andWhere('fecha_elaboracion <= ?', $hasta)
      ->orderBy('a.fecha_elaboracion DESC')
      ->execute();
    }

    else{
    $this->lotes = Doctrine_Core::getTable('Lote')
      ->createQuery('a')
            ->where('fecha_elaboracion >= ?', $desde)
      ->andWhere('fecha_elaboracion <= ?', $hasta)
            ->andWhere('a.accion = ?', 'Recepcionar')
            ->orderBy('a.fecha_elaboracion DESC')
      ->execute();
    }

  }

  public function dateadd($date, $dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0) {
        $date_r = getdate(strtotime($date));
        $date_result = date("Y/m/d", mktime(($date_r["hours"] + $hh), ($date_r["minutes"] + $mn), ($date_r["seconds"] + $ss), ($date_r["mon"] + $mm), ($date_r["mday"] + $dd), ($date_r["year"] + $yy)));
        return $date_result;
    }

    public function executeRecepcionar(sfWebRequest $request)
  {
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));
    $this->form = new LoteForm($lote);

    $widgetSchema = $this->form->getWidgetSchema();
    $widgetSchema['cantidad_actual']->setAttribute('type', 'text');
    $widgetSchema['cantidad_actual']->setHidden(false);
    $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
    $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Centro de Distribución*");
    $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
    $widgetSchema['cantidad_recibida']->setAttribute('type', 'text');
    $widgetSchema['cantidad_recibida']->setHidden(false);
    $widgetSchema['cantidad_danada_stgo']->setAttribute('type', 'text');
    $widgetSchema['cantidad_danada_stgo']->setHidden(false);
    $widgetSchema['cantidad_danada_stgo']->setDefault(0);
    $widgetSchema['cantidad_ff_stgo']->setAttribute('type', 'text');
    $widgetSchema['cantidad_ff_stgo']->setHidden(false);
    $widgetSchema['cantidad_ff_stgo']->setDefault(0);
    $widgetSchema['cc_santiago']->setAttribute('type', 'text');
    $widgetSchema['cc_santiago']->setHidden(false);
    $widgetSchema['cc_santiago']->setDefault(1);
    $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
    $widgetSchema['cantidad_actual']->setAttribute('disabled', 'disabled');
    $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
    $widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
    $widgetSchema['fecha_elaboracion']->setLabel("Fecha de Elaboración");
    $widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['numero']->setAttribute('disabled', 'disabled');
  }

  public function executeRecieve(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($lote = Doctrine_Core::getTable('Lote')->find(array($request->getParameter('id'))), sprintf('Object lote does not exist (%s).', $request->getParameter('id')));
    $this->form = new LoteForm($lote);

    $widgetSchema = $this->form->getWidgetSchema();
    $widgetSchema['cantidad_actual']->setAttribute('type', 'text');
    $widgetSchema['cantidad_actual']->setHidden(false);
    $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
    $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Centro de Distribución*");
    $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
    $widgetSchema['cantidad_recibida']->setAttribute('type', 'text');
    $widgetSchema['cantidad_recibida']->setHidden(false);
    $widgetSchema['cantidad_danada_stgo']->setAttribute('type', 'text');
    $widgetSchema['cantidad_danada_stgo']->setHidden(false);
    $widgetSchema['cantidad_ff_stgo']->setAttribute('type', 'text');
    $widgetSchema['cantidad_ff_stgo']->setHidden(false);
    $widgetSchema['cc_santiago']->setAttribute('type', 'text');
    $widgetSchema['cc_santiago']->setHidden(false);
    $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
    $widgetSchema['cantidad_actual']->setAttribute('disabled', 'disabled');
    $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
    $widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
    $widgetSchema['fecha_elaboracion']->setLabel("Fecha de Elaboración");
    $widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
    $widgetSchema['numero']->setAttribute('disabled', 'disabled');

    $this->validatorSchema=$this->form->getValidatorSchema();
    $this->validatorSchema['cantidad_recibida'] = new sfValidatorInteger();
    $this->validatorSchema['cantidad_danada_stgo'] = new sfValidatorInteger();
    $this->validatorSchema['cantidad_ff_stgo'] = new sfValidatorInteger();
    $this->validatorSchema['cc_santiago'] = new sfValidatorInteger();
    $this->validatorSchema['fecha_recepcion'] = new sfValidatorDate();


    $this->processForm($request, $this->form);

    if ($this->form->isValid()){
        $lote->setCantidad_Actual($lote->getCantidad_Recibida() - $lote->getCantidad_Danada_Stgo() - $lote->getCantidad_Ff_Stgo() - $lote->getCc_Santiago());
        $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), $lote->getFecha_Recepcion(), '2', 'aumentar', $this->getUser()->getGuardUser()->getName());
        $lote->setAccion('Recepcionado');
        $lote->save();
        $this->redirect('ventas/lote');
    }
    else{
    
        
    }
    
    $this->setTemplate('recepcionar');
  }

  public function executeInventario(sfWebRequest $request)
  {
    //$this->inventario_productoss = Doctrine_Core::getTable('InventarioProductos')->getLastItems('2');
  
    $this->ramas = Doctrine_Core::getTable('Rama')->findAll();
    $this->bodega = Doctrine_Core::getTable('Bodega')->findOneById(2);
    
    $this->inventario_productoss = array();
    
    foreach($this->ramas as $rama){
        $this->inventario_productoss[] = Doctrine_Core::getTable('InventarioProductos')->getTodasPorRama('2', $rama->getId());
    }
  }

  public function executeGet_data(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
           ->from('Lote')
           ->Where('accion = ?', 'Recepcionar');
      
      $pager = new sfDoctrinePager('Lote', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('ventas/recepcionar?id='.$v->getId());

        $aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getDateTimeObject('fecha_elaboracion')->format('d-m-Y'),
          "2" => $v->getProducto()->getNombreCompleto(),
          "3" => $v->getNumero(),
          "4" => $v->getComentarios(),
          "5" => $v->getCantidad(),
          "6" => $v->getCantidad() - $v->getCantidad_Danada() - $v->getCantidad_Ff() - $v->getCc_Valdivia() - $v->getCc_Santiago(),
          "7" => '<a href="'.$ver.'">Recepcionar</a></a>',
          "8" => '<input type="checkbox" class="checkbox1" value="'.$v->getId().'" accion="'.$v->getAccion().'"> <br>',
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
      unset($widgetSchema['comentarios']);
      unset($widgetSchema['padre']);
      $widgetSchema['cantidad_actual']->setAttribute('type', 'text');
      $widgetSchema['cantidad_actual']->setHidden(false);
      $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
      $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Centro de Distribución*");
      $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
      $widgetSchema['cantidad_recibida']->setAttribute('type', 'text');
      $widgetSchema['cantidad_recibida']->setHidden(false);
      $widgetSchema['cantidad_danada_stgo']->setAttribute('type', 'text');
      $widgetSchema['cantidad_danada_stgo']->setHidden(false);
      $widgetSchema['cantidad_danada_stgo']->setDefault(0);
      $widgetSchema['cantidad_ff_stgo']->setAttribute('type', 'text');
      $widgetSchema['cantidad_ff_stgo']->setHidden(false);
      $widgetSchema['cantidad_ff_stgo']->setDefault(0);
      $widgetSchema['cc_santiago']->setAttribute('type', 'text');
      $widgetSchema['cc_santiago']->setHidden(false);
      $widgetSchema['cc_santiago']->setDefault(1);
      $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
      $widgetSchema['cantidad_actual']->setAttribute('disabled', 'disabled');
      $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
      $widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
      $widgetSchema['fecha_elaboracion']->setLabel("Fecha de Elaboración");
      $widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
      $widgetSchema['numero']->setAttribute('disabled', 'disabled');

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
          $widgetSchema['cantidad_actual']->setAttribute('type', 'text');
          $widgetSchema['cantidad_actual']->setHidden(false);
          $widgetSchema['fecha_recepcion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
          $widgetSchema['fecha_recepcion']->setLabel("Fecha de Recepción en Centro de Distribución*");
          $widgetSchema['fecha_recepcion']->setDefault(date('Y/m/d/H:m'));
          $widgetSchema['cantidad_recibida']->setAttribute('type', 'text');
          $widgetSchema['cantidad_recibida']->setHidden(false);
          $widgetSchema['cantidad_danada_stgo']->setAttribute('type', 'text');
          $widgetSchema['cantidad_danada_stgo']->setHidden(false);
          $widgetSchema['cantidad_danada_stgo']->setDefault(0);
          $widgetSchema['cantidad_ff_stgo']->setAttribute('type', 'text');
          $widgetSchema['cantidad_ff_stgo']->setHidden(false);
          $widgetSchema['cantidad_ff_stgo']->setDefault(0);
          $widgetSchema['cc_santiago']->setAttribute('type', 'text');
          $widgetSchema['cc_santiago']->setHidden(false);
          $widgetSchema['cc_santiago']->setDefault(1);
          $widgetSchema['cantidad']->setAttribute('disabled', 'disabled');
          $widgetSchema['cantidad_actual']->setAttribute('disabled', 'disabled');
          $widgetSchema['fecha_elaboracion'] = new sfWidgetFormI18nDate(array('culture' => 'es'));
          $widgetSchema['fecha_elaboracion']->setAttribute('disabled', 'disabled');
          $widgetSchema['fecha_elaboracion']->setLabel("Fecha de Elaboración");
          $widgetSchema['producto_id']->setAttribute('disabled', 'disabled');
          $widgetSchema['numero']->setAttribute('disabled', 'disabled');   

          $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

          if ($this->form->isValid())
          {
              $lote = $this->form->updateObject();
              //Se agrega la siguiente acción
              $lote->setAccion('Recepcionado');
              $lote->setCantidad_Actual($lote->getCantidad_Recibida() - $lote->getCantidad_Danada_Stgo() - $lote->getCantidad_Ff_Stgo() - $lote->getCc_Santiago());
              $lote->cambiarInventario($lote->getProductoId(), $lote->getCantidad_Actual(), $lote->getFecha_Recepcion(), '2', 'aumentar', $this->getUser()->getGuardUser()->getName());
              $lote->save();
              $this->mensajes[$i]="Se actualizó el lote número ".$lote->getNumero().". ";
          } 
          else
          {
              $this->mensajes[$i]="Hubo un error al actualizar el lote número ".$lote->getNumero().". ";
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

    else
        //echo $form
        ;

  }

}
