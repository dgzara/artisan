<?php

/**
 * resumen actions.
 *
 * @package    quesos
 * @subpackage resumen
 * @author     MA
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class resumenActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeCostosproductos(sfWebRequest $request)
  {
    //Se debería filtrar o algo.

    $from = $request->getPostParameter("desde");
    $to = $request->getPostParameter("hasta");
      if($from=='')
    {
      $to = date('d-m-Y');
      $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
    }

      $from=$from." 00:00:00";
      $to=$to." 23:59:59";
      $from_1 = strtotime($from);
      $to_1 = strtotime($to);
  
      $fecha1 = date('Y-m-d H:i:s', $from_1);
      $fecha2 = date('Y-m-d H:i:s', $to_1);


    //Extraemos los costos directos
    $this->costos_elab = array();
    $this->costos_secos = array();
    $this->ramas = Doctrine_Core::getTable("Rama")->findAll();
    $this->productos_rama=array();
    $this->sumaramaelab = array();
    $this->sumaramaempa = array();
    $this->costos_uni = array();
    $this->suma = array();
    $this->totalrama = array();
    $this->totalproducto = array();

    
    $this->sumaelab = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElab($fecha1,$fecha2);
    $this->sumaempa = Doctrine_Core::getTable("CostosDirectos")->buscarCostoEmpa($fecha1,$fecha2);

    
    foreach($this->ramas as $rama){
        $this->productos_rama[$rama->getId()] = Doctrine_Core::getTable("Producto")->findByRama($rama->getId());
    }
    $this->sumarama = array();
    foreach($this->ramas as $rama){
        
 
        $totalelab = Doctrine_Core::getTable("CostosDirectos")->buscarCostoRamaElab($rama->getId(),$fecha1,$fecha2);
        if($totalelab == 0){$this->sumaramaelab[$rama->getId()] = '-';}
        else 
          {
              $tot3 = $totalelab;
              $tot4 = number_format($tot3, 0, ",", ".");
              $this->sumaramaelab[$rama->getId()] = '$'.$tot4;
          }
        
        
        $totalempa = Doctrine_Core::getTable("CostosDirectos")->buscarCostoRamaEmpa($rama->getId(),$fecha1,$fecha2);
        if($totalempa == 0){$this->sumaramaempa[$rama->getId()] = '-';}
        else 
          {
                $tot3 = $totalempa;
                $tot4 = number_format($tot3, 0, ",", ".");
            $this->sumaramaempa[$rama->getId()] = '$'.$tot4;
          }
        
        $totalsumarama = $totalelab + $totalempa;
        if($totalsumarama == 0){$this->totalrama[$rama->getId()]= '-';}
            else
              {
                $tot3 = $totalsumarama;
                $tot4 = number_format($tot3, 0, ",", ".");
                $this->totalrama[$rama->getId()]='$'.$tot4;
              }
        
        foreach($this->productos_rama[$rama->getId()] as $producto)
        {      
            $cepid = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElaboracion($producto->getId(),$fecha1,$fecha2);           
            if($cepid == 0){$this->costos_elab[$producto->getId()] = '-';}
            else 
              {
              $tot3 = $cepid;
              $tot4 = number_format($tot3, 0, ",", ".");
              $this->costos_elab[$producto->getId()] = '$'.$tot4;
              }
       
            $cempid = Doctrine_Core::getTable("CostosDirectos")->buscarCostoSeco($producto->getId(),$fecha1,$fecha2);   
            if($cempid == 0){$this->costos_secos[$producto->getId()] = '-';}
            else 
              {

              $tot3 = $cempid;
              $tot4 = number_format($tot3, 0, ",", ".");
                $this->costos_secos[$producto->getId()] = '$'.$tot4;
              }
            
            
            
            
            $cant = Doctrine_Core::getTable("CostosDirectos")->buscarCant($producto->getId(),$fecha1,$fecha2);
            $this->suma[$producto->getId()] = ($cepid+$cempid);
            
            if($this->suma[$producto->getId()] == 0){$this->totalproducto[$producto->getId()] = '-';}
            else
            {
              $tot = $this->suma[$producto->getId()];
              $tot2 = number_format($tot, 0, ",", ".");
              $this->totalproducto[$producto->getId()] = '$'.$tot2;
            }
            
            
            $unitario = $this->suma[$producto->getId()]/$cant;
            
            if($cant == 0){$this->costos_uni[$producto->getId()] = "-";}
            else 
            {
              $tot3 = intval($unitario);
              $tot4 = number_format($tot3, 0, ",", ".");
              $this->costos_uni[$producto->getId()] = $tot4;
            }
      
            
        }
    }
    
    
    
    
  }
  public function calcularCostosIndirectos($fecha1, $fecha2){
    
     $fech1 = date('Y-m-d',strtotime($fecha1));
     $fech2 = date('Y-m-d',strtotime($fecha2));

    $q = Doctrine_Query::create();
    $q->select('SUM(monto) as suma');
    $q->from('CostosIndirectos');

    $q->where("fecha >= '".$fech1."'");
    $q->andWhere("fecha <= '".$fech2."'");

    //$q->andWhere('fecha between ? and ?', array($fech1, $fech2));

    $suma = $q->fetchOne();
    if($suma->getSuma()){
        $n = new sfNumberFormat('es_CL');
        return '$'.$n->format($suma->getSuma(), 'd', 'CLP');
    }
    else{
        return '$0';//$n->format(0, 'c', 'CLP');
    }
  }


  public function calcularCostosIndirectosNumerico($fecha1, $fecha2)
{
    $q = Doctrine_Query::create();
    $q->select('SUM(monto) as suma');
    $q->from('CostosIndirectos');
    $q->andWhere('fecha between ? and ?', array($fecha1, $fecha2));

    $suma = $q->fetchOne();

    if($suma->getSuma()){
        $n = new sfNumberFormat('es_CL');
        return $suma->getSuma();
    }
    else{
        return 0;//$n->format(0, 'c', 'CLP');
    }
}

  public function calcularCostosDirectosNumerico($fecha = null){

    if(!$fecha){
        $fecha = date('Y/m/d');
    }

    $q = Doctrine_Query::create();
    $q->select('SUM(oi.neto * oi.cantidad) as neto');
    $q->from('OrdenCompra o');
    $q->leftJoin('o.OrdenCompraInsumo oi');
    $q->where('MONTH(o.fecha_pago) = MONTH(?)', $fecha);
    $q->andWhere('YEAR(o.fecha_pago) = YEAR(?)', $fecha);

    $suma = $q->fetchOne();

    if($suma->getNeto()){
        $n = new sfNumberFormat('es_CL');

        $neto = $suma->getNeto();
        $iva = intval($neto * 0.19);
        $total = $neto + $iva;

        return $total;
    }
    else{
        return 0;
    }
  }
  public function calcularCostosDirectos($fecha1 = null, $fecha2 = null){

        if(!$fecha1){
            $fecha1 = date("Y-m-d", strtotime(date('Y').'-'.date('m').'-01'));

        }

        if(!$fecha2){
            $fecha2 = date('Y-m-d');
        }     
        $fecha1 = date('Y-m-d',strtotime($fecha1));
        $fecha2 = date('Y-m-d',strtotime($fecha2));

    $q = Doctrine_Query::create();
 //   $q->select('SUM(oi.neto * oi.cantidad) as neto');
         $q->select('SUM(oi.neto * oi.cantidad) as neto');
         $q->from('OrdenCompraInsumo oi');
         $q->innerJoin('oi.OrdenCompra o');
//    $q->from('OrdenCompra o');
//    $q->leftJoin('o.OrdenCompraInsumo oi');
//    $q->where('MONTH(o.fecha_pago) = MONTH(?)', $fecha);
//    $q->andWhere('YEAR(o.fecha_pago) = YEAR(?)', $fecha);
    $q->where("o.fecha_pago >= '".$fecha1."'");
    $q->andWhere("o.fecha_pago <= '".$fecha2."'");


    $suma = $q->fetchOne();

    if($suma->getNeto()){
        $n = new sfNumberFormat('es_CL');

        $neto = $suma->getNeto();
        $iva = intval($neto * 0.19);
        $total = $neto + $iva;

        return '$'.$n->format($total, 'd', 'CLP');
    }
    else{
        return '$0';
    }
  }
  
  public function executeExcel2(sfWebRequest $request)
  {
    $from = $request->getPostParameter("desde");
    $to = $request->getPostParameter("hasta");
    if($from=='')
    {
      $to = date('d-m-Y');
      $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
    }
    

      $from=$from." 00:00:00";
      $to=$to." 23:59:59";
      $from_1 = strtotime($from);
      $to_1 = strtotime($to);
  
      $fecha1 = date('Y-m-d H:i:s', $from_1);
      $fecha2 = date('Y-m-d H:i:s', $to_1);
    $sumaelab=0;
    $sumaempa=0;
    $costodirecto=0;
    $costos_indirectos_total=0;
    $costotal=0;

    //Extraemos los costos directos
    $costos_elab = array();
    $costos_secos = array();
    $ramas = Doctrine_Core::getTable("Rama")->findAll();
    $productos_rama=array();
    $sumaramaelab = array();
    $sumaramaempa = array();

    $sumaelab=0;
    $sumaempa=0;
    $costodirecto=0;
    $costos_indirectos_total=0;
    $costotal=0;
    $sumaelab = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElab($fecha1,$fecha2);
    $sumaempa = Doctrine_Core::getTable("CostosDirectos")->buscarCostoEmpa($fecha1,$fecha2);
    $costodirecto = $sumaempa + $sumaelab;
    // Extraemos los costos indirectos
    
    $costos_indirectos_total = $this->calcularCostosIndirectosNumerico($fecha1,$fecha2);

    $costotal = $costos_indirectos_total + $costodirecto;
  
    $n = new sfNumberFormat('es_CL');
    $costotal=     '$'.$n->format($costotal, 'd', 'CLP');
    $costos_indirectos_total=     '$'.$n->format($costos_indirectos_total, 'd', 'CLP');
    $sumaelab=     '$'.$n->format($sumaelab, 'd', 'CLP');
    $sumaempa=     '$'.$n->format($sumaempa, 'd', 'CLP');
    $costodirecto=     '$'.$n->format($costodirecto, 'd', 'CLP');
    
    $header = "Tipo Costo\tMonto";
    $datos = "";
    $datos .= "Costos Elaboración\t";
            $datos .= $sumaelab."\n";
            $datos .= "Costos Secos\t";
            $datos .= $sumaempa."\n";
            $datos .= "Costos Directos Totales\t";
            $datos .= $costodirecto."\n";
            $datos .= "Costos Indirectos\t";
            $datos .= $costos_indirectos_total."\n";
            $datos .= "Costos Totales\t";
            $datos .= $costotal."\n";
            
    
    $datos = str_replace('Ã¡', 'á', $datos);
    $datos = str_replace('Ã©', 'é', $datos);
    $datos = str_replace('Ã*', 'í', $datos);
    $datos = str_replace('Ã³', 'ó', $datos);
    $datos = str_replace('ú', '&uacute;', $datos);
    $datos = str_replace('Ú', '&uacute', $datos);


    header("Content-type: application/vnd.ms-excel; charset=UTF-32");
    header("Content-Disposition: attachment; filename=ResumenCostos ".date("Y-m-d  H.i.s").".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    $header = utf8_decode($header);
    $datos = utf8_decode($datos);
    print $header."\n".$datos;
    return sfView::NONE;
  }
  
  public function executeExcel3(sfWebRequest $request)
  {
      $from = $request->getPostParameter("desde");
      $to = $request->getPostParameter("hasta");
        if($from=='')
          {
            $to = date('d-m-Y');
            $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
          }
      $from=$from." 00:00:00";
      $to=$to." 23:59:59";
      $from_1 = strtotime($from);
      $to_1 = strtotime($to);
  
      $fecha1 = date('Y-m-d H:i:s', $from_1);
      $fecha2 = date('Y-m-d H:i:s', $to_1);

      $fec1=$fecha1;
      $fec2=$fecha2;
      
      //Extraemos los costos directos
      $costos_elab = array();
      $costos_secos = array();
      $ramas = Doctrine_Core::getTable("Rama")->findAll();
      $productos_rama=array();
      $sumaramaelab = array();
      $sumaramaempa = array();

    
      $sumaelab = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElab($fecha1,$fecha2);
      $sumaempa = Doctrine_Core::getTable("CostosDirectos")->buscarCostoEmpa($fecha1,$fecha2);

    
        foreach($ramas as $rama)
        {
          $productos_rama[$rama->getId()] = Doctrine_Core::getTable("Producto")->findByRama($rama->getId());
        } 
      $sumarama = array();
      foreach($ramas as $rama)
      {
        $sumaramaelab[$rama->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoRamaElab($rama->getId(),$fecha1,$fecha2);
        $sumaramaempa[$rama->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoRamaEmpa($rama->getId(),$fecha1,$fecha2);
        foreach($productos_rama[$rama->getId()] as $producto)
        {      
            $costos_elab[$producto->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElaboracion($producto->getId(),$fecha1,$fecha2);
            $costos_secos[$producto->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoSeco($producto->getId(),$fecha1,$fecha2);            
        }
      }
    
      // Extraemos los costos indirectos
      $areas_de_costos = Doctrine_Core::getTable("AreaDeCostos")->findAll();
      $centros_de_costos =array();
      $costos_indirectos_total = $this->calcularCostosIndirectos($fecha1,$fecha2);

      foreach($areas_de_costos as $area_de_costo)
      {
        $centros_de_costos[$area_de_costo->getId()] = Doctrine_Core::getTable("CentroDeCostos")->findByAreaDeCostosId($area_de_costo->getId());
      }

      // Extraemos los costos de los insumos
      $insumos = Doctrine_Core::getTable('Insumo')->findAll();
      $ordenes_compra = array();
      $costos_directos_total = $this->calcularCostosDirectos($fecha1,$fecha2);


      foreach($insumos as $insumo)
      {  
        $ordenes_compra[$insumo->getId()] = $insumo->getOrdenCompraMes($fecha1, $fecha2);
      }
    
      $header = "Área Costo\tTipo Costo\tMonto";
      $datos = "";
      foreach ($areas_de_costos as $area_de_costo)
      {
        $datos.=$area_de_costo->getNombre()."\tTotal\t".$area_de_costo->getTotal($fec1, $fec2)."\n";
        foreach ($centros_de_costos[$area_de_costo->getId()] as $centro_de_costo)
        {
          $datos.=$area_de_costo->getNombre()."\t".$centro_de_costo->getNombre()."\t".$centro_de_costo->getTotal($fec1, $fec2)."\n";
        }
      }
      $datos.="Total\t\t".$costos_indirectos_total;
    
      $datos = str_replace('Ã¡', 'á', $datos);
      $datos = str_replace('Ã©', 'é', $datos);
      $datos = str_replace('Ã*', 'í', $datos);
      $datos = str_replace('Ã³', 'ó', $datos);
      $datos = str_replace('ú', '&uacute;', $datos);
      $datos = str_replace('Ú', '&uacute', $datos);

      header("Content-type: application/vnd.ms-excel; charset=UTF-32");
      header("Content-Disposition: attachment; filename=CostosIndirectos ".date("Y-m-d  H.i.s").".xls");
      header("Pragma: no-cache");
      header("Expires: 0");
      $header = utf8_decode($header);
      $datos = utf8_decode($datos);
      print $header."\n".$datos;
      return sfView::NONE;
  }

  public function executeExcel4(sfWebRequest $request)
  {
      $from = $request->getPostParameter("desde");
      $to = $request->getPostParameter("hasta");
      if($from=='')
      {
        $to = date('d-m-Y');
        $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
      }

      $from=$from." 00:00:00";
      $to=$to." 23:59:59";
      $from_1 = strtotime($from);
      $to_1 = strtotime($to);
  
      $fecha1 = date('Y-m-d H:i:s', $from_1);
      $fecha2 = date('Y-m-d H:i:s', $to_1);


      //Extraemos los costos directos
      $costos_elab = array();
      $costos_secos = array();
      $ramas = Doctrine_Core::getTable("Rama")->findAll();
      $productos_rama=array();
      $sumaramaelab = array();
      $sumaramaempa = array();
      $costos_uni = array();
      $suma = array();
      $totalrama = array();
      $totalproducto = array();

    
      $sumaelab = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElab($fecha1,$fecha2);
      $sumaempa = Doctrine_Core::getTable("CostosDirectos")->buscarCostoEmpa($fecha1,$fecha2);

    
      foreach($ramas as $rama)
      {
        $productos_rama[$rama->getId()] = Doctrine_Core::getTable("Producto")->findByRama($rama->getId());
      }
      $sumarama = array();
      foreach($ramas as $rama)
      {
        $totalelab = Doctrine_Core::getTable("CostosDirectos")->buscarCostoRamaElab($rama->getId(),$fecha1,$fecha2);
        if($totalelab == 0){$sumaramaelab[$rama->getId()] = '-';}
        else 
          {
              $tot3 = $totalelab;
              $tot4 = number_format($tot3, 0, ",", ".");
              $sumaramaelab[$rama->getId()] = '$'.$tot4;
          }
        $totalempa = Doctrine_Core::getTable("CostosDirectos")->buscarCostoRamaEmpa($rama->getId(),$fecha1,$fecha2);
        if($totalempa == 0){$sumaramaempa[$rama->getId()] = '-';}
        else 
          {
                $tot3 = $totalempa;
                $tot4 = number_format($tot3, 0, ",", ".");
            $sumaramaempa[$rama->getId()] = '$'.$tot4;
          }
        
        $totalsumarama = $totalelab + $totalempa;
        if($totalsumarama == 0){$totalrama[$rama->getId()]= '-';}
            else
              {
                $tot3 = $totalsumarama;
                $tot4 = number_format($tot3, 0, ",", ".");
                $totalrama[$rama->getId()]='$'.$tot4;
              }
        
        foreach($productos_rama[$rama->getId()] as $producto)
        {      
            $cepid = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElaboracion($producto->getId(),$fecha1,$fecha2);           
            if($cepid == 0){$costos_elab[$producto->getId()] = '-';}
            else 
              {
              $tot3 = $cepid;
              $tot4 = number_format($tot3, 0, ",", ".");
              $costos_elab[$producto->getId()] = '$'.$tot4;
              }
       
            $cempid = Doctrine_Core::getTable("CostosDirectos")->buscarCostoSeco($producto->getId(),$fecha1,$fecha2);   
            if($cempid == 0){$costos_secos[$producto->getId()] = '-';}
            else 
              {

              $tot3 = $cempid;
              $tot4 = number_format($tot3, 0, ",", ".");
                $costos_secos[$producto->getId()] = '$'.$tot4;
              }
            
            $cant = Doctrine_Core::getTable("CostosDirectos")->buscarCant($producto->getId(),$fecha1,$fecha2);
            $suma[$producto->getId()] = ($cepid+$cempid);
            
            if($suma[$producto->getId()] == 0){$totalproducto[$producto->getId()] = '-';}
            else
            {
              $tot = $suma[$producto->getId()];
              $tot2 = number_format($tot, 0, ",", ".");
              $totalproducto[$producto->getId()] = '$'.$tot2;
            }
            
            $unitario = $suma[$producto->getId()]/$cant;
            
            if($cant == 0){$costos_uni[$producto->getId()] = "-";}
            else 
            {
              $tot3 = intval($unitario);
              $tot4 = number_format($tot3, 0, ",", ".");
              $costos_uni[$producto->getId()] = $tot4;
            }  
        }
      }
    
      $header = "Tipo de Producto\tProducto\tCostos Elaboración [$]\tCostos Secos [$]\tCosto Directo Total [$]\tCosto Marginal [$/U]";
      $datos = "";
        foreach ($ramas as $rama)
        {
          $datos.=$rama->getNombre()."\tTotal\t".$sumaramaelab[$rama->getId()]."\t".$sumaramaempa[$rama->getId()]."\t".$totalrama[$rama->getId()]."\n";
          foreach ($productos_rama[$rama->getId()] as $producto)
          {
            $datos.=$rama->getNombre()."\t".$producto->getNombre().' '.$producto->getPresentacion()."\t".$costos_elab[$producto->getId()]."\t".$costos_secos[$producto->getId()]."\t".$totalproducto[$producto->getId()]."\t".$costos_uni[$producto->getId()]."\n";
          }
        }
        $datos.="Total\t\t";
        $tot3 = $sumaelab; $tot4 = number_format($tot3, 0, ",", ".");
        $datos.="$".$tot4."\t";
        $tot3 = $sumaempa; $tot4 = number_format($tot3, 0, ",", ".");
        $datos.="$".$tot4."\t";
        $tot3 = ($sumaempa+$sumaelab); $tot4 = number_format($tot3, 0, ",", ".");
        $datos.="$".$tot4."\t-\t";

            
    
      $datos = str_replace('Ã¡', 'á', $datos);
      $datos = str_replace('Ã©', 'é', $datos);
      $datos = str_replace('Ã*', 'í', $datos);
      $datos = str_replace('Ã³', 'ó', $datos);
      $datos = str_replace('ú', '&uacute;', $datos);
      $datos = str_replace('Ú', '&uacute', $datos);


      header("Content-type: application/vnd.ms-excel; charset=UTF-32");
      header("Content-Disposition: attachment; filename=ResumenCostos ".date("Y-m-d  H.i.s").".xls");
      header("Pragma: no-cache");
      header("Expires: 0");
      $header = utf8_decode($header);
      $datos = utf8_decode($datos);
      print $header."\n".$datos;
      return sfView::NONE;
  }

    public function executeExcel5(sfWebRequest $request)
  {
      $from = $request->getPostParameter("desde");
      $to = $request->getPostParameter("hasta");
        if($from=='')
        {
          $to = date('d-m-Y');
          $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
        }
    
      $from=$from." 00:00:00";
      $to=$to." 23:59:59";
      $from_1 = strtotime($from);
      $to_1 = strtotime($to);
  
      $fecha1 = date('Y-m-d H:i:s', $from_1);
      $fecha2 = date('Y-m-d H:i:s', $to_1);

      // Extraemos los costos de los insumos

      $insumos = Doctrine_Core::getTable('Insumo')->findAll();
      $ordenes_compra = array();
      $costos_directos_total = $this->calcularCostosDirectos($fecha1,$fecha2);
      $fecha1 = $fecha1;
      $fecha2 = $fecha2;
      $cm = array();

      foreach($insumos as $insumo)
      {
        $ordenes_compra[$insumo->getId()] = $insumo->getOrdenCompraMes();
        $cm[$insumo->getId()] = $insumo->getCostoMes($fecha1,$fecha2);
        
      }

    
      $header = "Insumo\tOrden de Compra\tMonto";
      $datos = "";
      foreach ($insumos as $insumo)
      {
        $datos.=substr($insumo->getNombre(), 0,40)."\t\t".$cm[$insumo->getId()]."\n";
        foreach ($ordenes_compra[$insumo->getId()] as $orden_compra)
        {
          $datos.="\tO.C. Nº".number_format($orden_compra->getNumero(), 0, ",", ".")."\t".$orden_compra->getTotalInsumo($insumo->getId())."\n";
        }
      }
      
      $datos = str_replace('Ã¡', 'á', $datos);
      $datos = str_replace('Ã©', 'é', $datos);
      $datos = str_replace('Ã*', 'í', $datos);
      $datos = str_replace('Ã³', 'ó', $datos);
      $datos = str_replace('ú', '&uacute;', $datos);
      $datos = str_replace('Ú', '&uacute', $datos);


      header("Content-type: application/vnd.ms-excel; charset=UTF-32");
      header("Content-Disposition: attachment; filename=ResumenCostos ".date("Y-m-d  H.i.s").".xls");
      header("Pragma: no-cache");
      header("Expires: 0");
      $header = utf8_decode($header);
      $datos = utf8_decode($datos);
      print $header."\n".$datos;
      return sfView::NONE;
  }
  
  

  public function executeExcel(sfWebRequest $request){
  
    $from = $request->getPostParameter("desde");
    $to = $request->getPostParameter("hasta");
       if($from=='')
    {
      $to = date('d-m-Y');
      $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
    }
      $from=$from." 00:00:00";
      $to=$to." 23:59:59";
      $from_1 = strtotime($from);
      $to_1 = strtotime($to);
  
      $fecha1 = date('Y-m-d H:i:s', $from_1);
      $fecha2 = date('Y-m-d H:i:s', $to_1);  
      
      
    $this->costos_elab = array();
    $this->costos_secos = array();
    $this->ramas = Doctrine_Core::getTable("Rama")->findAll();
    $this->productos_rama=array();
    $this->sumaramaelab = array();
    $this->sumaramaempa = array();
      foreach($this->ramas as $rama){
        $this->productos_rama[$rama->getId()] = Doctrine_Core::getTable("Producto")->findByRama($rama->getId());
    }
    $this->sumarama = array();
    foreach($this->ramas as $rama){
        foreach($this->productos_rama[$rama->getId()] as $producto)
        {      
            $this->costos_elab[$producto->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElaboracion($producto->getId(),$fecha1,$fecha2);
            $this->costos_secos[$producto->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoSeco($producto->getId(),$fecha1,$fecha2);
            

            
        }
    }
    

    $header = "Costo\tArea de Negocio\tProducto\tMonto";
    $datos = "";
    if(count($this->costos_elab)!=0){
    foreach($this->ramas as $rama){
        foreach($this->productos_rama[$rama->getId()] as $producto)
        {
            $datos .= "Elaboración\t";
            $datos .= $rama->getNombre()."\t";
            $datos .= $producto->getNombre()." ".$producto->getPresentacion()."\t";
            $datos .= $this->costos_elab[$producto->getId()]."\n";
        }
      }
    }
    
    $datos = str_replace('Ã¡', 'á', $datos);
    $datos = str_replace('Ã©', 'é', $datos);
    $datos = str_replace('Ã*', 'í', $datos);
    $datos = str_replace('Ã³', 'ó', $datos);
    $datos = str_replace('ú', '&uacute;', $datos);
    $datos = str_replace('Ú', '&uacute', $datos);


    header("Content-type: application/vnd.ms-excel; charset=UTF-32");
    header("Content-Disposition: attachment; filename=ResumenCostos-".date("Y-m-d-H-i-s").".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    $header = utf8_decode($header);
    $datos = utf8_decode($datos);
    print $header."\n".$datos;
    return sfView::NONE;
  }
  
  public function executeInsumos(sfWebRequest $request)
  {

      $from = $request->getPostParameter("desde");
      $to = $request->getPostParameter("hasta");
        if($from=='')
        {
      $to = date('d-m-Y');
      $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
      }

    
      $from=$from." 00:00:00";
      $to=$to." 23:59:59";
      $from_1 = strtotime($from);
      $to_1 = strtotime($to);
  
      $fecha1 = date('Y-m-d H:i:s', $from_1);
      $fecha2 = date('Y-m-d H:i:s', $to_1);

      // Extraemos los costos de los insumos
      $this->insumos = Doctrine_Core::getTable('Insumo')->findAll();
      $this->ordenes_compra = array();
      $this->costos_directos_total = $this->calcularCostosDirectos($fecha1,$fecha2);
      $this->fecha1 = $fecha1;
      $this->fecha2 = $fecha2;
      $this->cm = array();

      foreach($this->insumos as $insumo){
        $this->ordenes_compra[$insumo->getId()] = $insumo->getOrdenCompraMes();
        $this->cm[$insumo->getId()] = $insumo->getCostoMes($fecha1,$fecha2);
      }
  }

  public function executeCostosinsumos(sfWebRequest $request)
  {
    $from = $request->getPostParameter("desde");
      $to = $request->getPostParameter("hasta");
           if($from=='')
      {
      $to = date('d-m-Y');
      $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
      }

    
      $from=$from." 00:00:00";
      $to=$to." 23:59:59";
      $from_1 = strtotime($from);
      $to_1 = strtotime($to);
  
      $fecha1 = date('Y-m-d H:i:s', $from_1);
      $fecha2 = date('Y-m-d H:i:s', $to_1);


    //Extraemos los costos directos
    $this->costos_elab = array();
    $this->costos_secos = array();
    $this->ramas = Doctrine_Core::getTable("Rama")->findAll();
    $this->productos_rama=array();
    $this->sumaramaelab = array();
    $this->sumaramaempa = array();

    
    $this->sumaelab = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElab($fecha1,$fecha2);
    $this->sumaempa = Doctrine_Core::getTable("CostosDirectos")->buscarCostoEmpa($fecha1,$fecha2);

    
    foreach($this->ramas as $rama){
        $this->productos_rama[$rama->getId()] = Doctrine_Core::getTable("Producto")->findByRama($rama->getId());
    }
    $this->sumarama = array();
    foreach($this->ramas as $rama){
        $this->sumaramaelab[$rama->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoRamaElab($rama->getId(),$fecha1,$fecha2);
        $this->sumaramaempa[$rama->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoRamaEmpa($rama->getId(),$fecha1,$fecha2);
        foreach($this->productos_rama[$rama->getId()] as $producto)
        {      
            $this->costos_elab[$producto->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElaboracion($producto->getId(),$fecha1,$fecha2);
            $this->costos_secos[$producto->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoSeco($producto->getId(),$fecha1,$fecha2);
        }
    }
    
    // Extraemos los costos indirectos
    $this->areas_de_costos = Doctrine_Core::getTable("AreaDeCostos")->findAll();
    $this->centros_de_costos =array();
    $this->costos_indirectos_total = $this->calcularCostosIndirectos($fecha1,$fecha2);

    foreach($this->areas_de_costos as $area_de_costo){
        $this->centros_de_costos[$area_de_costo->getId()] = Doctrine_Core::getTable("CentroDeCostos")->findByAreaDeCostosId($area_de_costo->getId());
    }

    // Extraemos los costos de los insumos
    $this->insumos = Doctrine_Core::getTable('Insumo')->findAll();
    $this->ordenes_compra = array();
    $this->costos_directos_total = $this->calcularCostosDirectos($fecha1,$fecha2);

    foreach($this->insumos as $insumo){
        $this->ordenes_compra[$insumo->getId()] = $insumo->getOrdenCompraMes();
    }
  }
    
  public function executeIndex(sfWebRequest $request)
  {
    //Se debería filtrar o algo.

    $from = $request->getPostParameter("desde");
    $to = $request->getPostParameter("hasta");
     
    if($from=='')
    {
      $to = date('d-m-Y');
      $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
    }


      $from=$from." 00:00:00";
      $to=$to." 23:59:59";
      $from_1 = strtotime($from);
      $to_1 = strtotime($to);
  
      $fecha1 = date('Y-m-d H:i:s', $from_1);
      $fecha2 = date('Y-m-d H:i:s', $to_1);


    //Extraemos los costos directos
    $this->costos_elab = array();
    $this->costos_secos = array();
    $this->ramas = Doctrine_Core::getTable("Rama")->findAll();
    $this->productos_rama=array();
    $this->sumaramaelab = array();
    $this->sumaramaempa = array();

    
    $this->sumaelab = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElab($fecha1,$fecha2);
    $this->sumaempa = Doctrine_Core::getTable("CostosDirectos")->buscarCostoEmpa($fecha1,$fecha2);
    $this->costodirecto = $this->sumaempa + $this->sumaelab;
    
    // Extraemos los costos indirectos
    
    $this->costos_indirectos_total = $this->calcularCostosIndirectosNumerico($fecha1,$fecha2);

    $this->costotal = $this->costos_indirectos_total + $this->costodirecto;
  
    $n = new sfNumberFormat('es_CL');
    $this->costotal=     '$'.$n->format($this->costotal, 'd', 'CLP');
    $this->costos_indirectos_total=     '$'.$n->format($this->costos_indirectos_total, 'd', 'CLP');
    $this->sumaelab=     '$'.$n->format($this->sumaelab, 'd', 'CLP');
    $this->sumaempa=     '$'.$n->format($this->sumaempa, 'd', 'CLP');
    $this->costodirecto=     '$'.$n->format($this->costodirecto, 'd', 'CLP'); 
  }
  
  public function executeCostosIndirectos(sfWebRequest $request)
  {
    //Se debería filtrar o algo.

    $from = $request->getPostParameter("desde");
    $to = $request->getPostParameter("hasta");
      if($from=='')
    {
      $to = date('d-m-Y');
      $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
    }

    
      $from=$from." 00:00:00";
      $to=$to." 23:59:59";
      $from_1 = strtotime($from);
      $to_1 = strtotime($to);
  
      $fecha1 = date('Y-m-d H:i:s', $from_1);
      $fecha2 = date('Y-m-d H:i:s', $to_1);

      $this->fec1=$fecha1;
      $this->fec2=$fecha2;

    //Extraemos los costos directos
    $this->costos_elab = array();
    $this->costos_secos = array();
    $this->ramas = Doctrine_Core::getTable("Rama")->findAll();
    $this->productos_rama=array();
    $this->sumaramaelab = array();
    $this->sumaramaempa = array();

    
    $this->sumaelab = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElab($fecha1,$fecha2);
    $this->sumaempa = Doctrine_Core::getTable("CostosDirectos")->buscarCostoEmpa($fecha1,$fecha2);

    
    foreach($this->ramas as $rama){
        $this->productos_rama[$rama->getId()] = Doctrine_Core::getTable("Producto")->findByRama($rama->getId());
    }
    $this->sumarama = array();
    foreach($this->ramas as $rama){
        $this->sumaramaelab[$rama->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoRamaElab($rama->getId(),$fecha1,$fecha2);
        $this->sumaramaempa[$rama->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoRamaEmpa($rama->getId(),$fecha1,$fecha2);
        foreach($this->productos_rama[$rama->getId()] as $producto)
        {      
            $this->costos_elab[$producto->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoElaboracion($producto->getId(),$fecha1,$fecha2);
            $this->costos_secos[$producto->getId()] = Doctrine_Core::getTable("CostosDirectos")->buscarCostoSeco($producto->getId(),$fecha1,$fecha2);
            

            
        }
    }
    
    // Extraemos los costos indirectos
    $this->areas_de_costos = Doctrine_Core::getTable("AreaDeCostos")->findAll();
    $this->centros_de_costos =array();
    $this->costos_indirectos_total = $this->calcularCostosIndirectos($fecha1,$fecha2);

    foreach($this->areas_de_costos as $area_de_costo){
        $this->centros_de_costos[$area_de_costo->getId()] = Doctrine_Core::getTable("CentroDeCostos")->findByAreaDeCostosId($area_de_costo->getId());
    }

    // Extraemos los costos de los insumos
    $this->insumos = Doctrine_Core::getTable('Insumo')->findAll();
    $this->ordenes_compra = array();
    $this->costos_directos_total = $this->calcularCostosDirectos($fecha1,$fecha2);


    foreach($this->insumos as $insumo){
      
        $this->ordenes_compra[$insumo->getId()] = $insumo->getOrdenCompraMes($fecha1, $fecha2);
    }
    
  }
}
