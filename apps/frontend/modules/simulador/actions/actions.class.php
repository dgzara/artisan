<?php

/**
 * simulador actions.
 *
 * @package    quesos
 * @subpackage simulador
 * @author     Hernan "la maquina" Vigil
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class simuladorActions extends sfActions
{
 
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->ramas = Doctrine_Core::getTable("Rama")->findAll();

    $this->productos_rama = array();

    foreach($this->ramas as $rama){
        $this->productos_rama[] = Doctrine_Core::getTable("Producto")->findByRama($rama->getId());
    }  
  }
  
  public function executeGetDatos(sfWebRequest $request)
  {
      $from = $request->getParameter("from");
      $to = $request->getParameter("to");
      
      $from=$from." 00:00:00";
      $to=$to." 23:59:59";
      $from_1 = strtotime($from);
      $to_1 = strtotime($to);
  
      $from_2 = date('Y-m-d H:i:s', $from_1); 
      $to_2 = date('Y-m-d H:i:s', $to_1);

      //extraemos tabla con rama, producto, cantidad producida y el costo directo
     
      $this->tabla1 = Doctrine_Core::getTable('Lote')->getCostosDirectos($from_2, $to_2);
      
      $p = Doctrine_Query::create()
        ->select('p.id as ident, p.nombre as nombre, p.presentacion as gramaje, r.nombre as nombrerama, r.id as ramaid')
        ->from('Producto p')
        ->leftJoin('p.Rama r');
       $this->productos = $p->execute();
        
      $this->productocantidades = array();
      foreach($this->productos as $producto) 
      {
          $prodcantidad = array('Nombre'=>$producto->getNombre().' '.$producto->getGramaje(),
                                'Rama'=>$producto->getNombrerama(),
                                'Ramaid'=>$producto->getRamaid(),
                                'Cantidad'=>0,
                                'Valor'=>0);
          
          $contador1 = 0;
          $contador2 = 0;
          foreach($this->tabla1 as $tupla)
          {
              if($producto->getIdent() == $tupla->getIdent())
              {
                  $contador1 = $contador1 + $tupla->getCantidad();
                  $contador2 = $contador2 + $tupla->getValor();
              }
          }
          
          $prodcantidad['Cantidad'] = $contador1;
          $prodcantidad['Valor'] = $contador2;
                                
          $this->productocantidades[] = $prodcantidad;
          
      }
      
      
      $q = Doctrine_Query::create()
        ->select('DISTINCT r.nombre, r.id as ident')
        ->from('Rama r');
       $this->ramas = $q->execute();
        
      $this->ramacantidades = array();
      foreach($this->ramas as $rama) 
      {
          
          $contador = 0;
          foreach($this->productocantidades as $producto)
          {
              if($producto['Ramaid'] == $rama->getIdent())
              {
                  $contador = $contador + $producto['Cantidad'];
              }
          }
          
          $ramacantidad = array('Nombre'=>$rama->getNombre(),
                                'Suma'=>$contador);
          $this->ramacantidades[] = $ramacantidad;
          
      }
       
      //Generamos un array de arrays muy especial que pasa los datos al simulador 
      $areasnegocio=array();
          
      foreach($this->ramacantidades as $fila2){
          
          //En cada iteración generamos un array de productos...
          $productos=array();
          
          foreach($this->productocantidades as $fila1){
              
              
              
              $nombre1 = $fila1['Rama'];
              $nombre2 = $fila2['Nombre'];

              if($nombre1 == $nombre2){
              $producto = array('nombre'=>$fila1['Nombre'],
                         'cd'=>0,
                         'Ni'=>0);
              if($fila1['Valor']!=0)
              {$producto['cd'] = $fila1['Valor'];}
              if($fila1['Cantidad']!=0)
              { $producto['Ni']= $fila1['Cantidad'];}
              
              $productos[] = $producto ;     }
              
              }     
                 
          $area = array('nombre'=>$fila2['Nombre'],
                     'produccion'=>$fila2['Suma'],
                     'productos'=>$productos);
          
          $areasnegocio[] = $area;
      }
      //extraemos el costo indirecto
      
      $from_1 = strtotime($from);
      $to_1 = strtotime($to);

      $from_2 = date('Y-m-d H:i:s', $from_1); 
      $to_2 = date('Y-m-d H:i:s', $to_1);

      $q = Doctrine_Query::create();
      $q->select('SUM(monto) as suma');
      $q->from('CostosIndirectos');
      $q->where('fecha between ? and ?',array($from_2, $to_2));
      //$q->andWhere('fecha<=?',$to);
      $suma = $q->fetchOne();    
      $datos = array('inicio'=>$from,
                     'termino'=>$to,
                     'costoIndirecto'=>0,
                     'areasNegocio'=>$areasnegocio,
                    );
      
      if($suma->getSuma()!=0)
              {$datos[costoIndirecto]=$suma->getSuma();}

      print json_encode($datos);
      return sfView::NONE;
  }

  
}


      
      
      
      
      
      
      
      
      
      
      
