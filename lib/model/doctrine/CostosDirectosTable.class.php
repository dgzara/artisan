<?php

/**
 * CostosDirectosTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CostosDirectosTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object CostosDirectosTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CostosDirectos');
    }
    
    public static function buscarCostoElaboracion($productoid,$fecha1,$fecha2)
    {
        $q = Doctrine_Query::create()
        ->select('Sum(valor) as suma')
        ->from('CostosDirectos')
        ->where('producto_id = ?', $productoid)
        ->andWhere('tipo_costo= ?', 'elab')
        ->andWhere('fecha between ? and ?',array($fecha1, $fecha2));
        
        $suma = $q->fetchOne();

        if($suma->getSuma()){return $suma->getSuma();}
        else{return 0;}
        
        
        }

        public static function buscarCostoElaboracionNumerico($productoid,$fecha1,$fecha2)
    {
        $q = Doctrine_Query::create()
        ->select('Sum(valor) as suma')
        ->from('CostosDirectos')
        ->where('producto_id = ?', $productoid)
        ->andWhere('tipo_costo= ?', 'elab')
        ->andWhere('fecha between ? and ?',array($fecha1, $fecha2));
        
        $suma = $q->fetchOne();
        
        if($suma->getSuma()){return $suma->getSuma();}
        else{return 0;}      
        }
    
    public static function buscarCant($productoid,$fecha1,$fecha2)
    {
        $q = Doctrine_Query::create()
        ->select('cantidad')
        ->from('Lote')
        ->where('producto_id = ?', $productoid)
        ->andWhere('created_at between ? and ?',array($fecha1, $fecha2));
        
        $prod_cant = $q->execute();
        $cant = 0;
        foreach($prod_cant as $tupla)
        {
            $cant = $cant + $tupla->getCantidad();
        }
         return $cant;
        
        
        
        }
        
      
         public static function buscarCostoRamaEmpa($ramaid,$fecha1,$fecha2)
    {
         $q = Doctrine_Query::create()
        
        ->select('Sum(cd.valor) as suma')
        ->from('CostosDirectos cd')
        ->leftjoin('cd.Producto p')
        ->where('p.rama_id = ?', $ramaid)
        ->andWhere('cd.tipo_costo= ?', 'empa')
        ->andWhere('fecha between ? and ?',array($fecha1, $fecha2));

        $suma = $q->fetchOne();
        
        if($suma->getSuma()){return $suma->getSuma();}
        else{return 0;}
    }
     
    public static function buscarCostoSeco($productoid,$fecha1,$fecha2)
    {
         $q = Doctrine_Query::create()
        ->select('Sum(valor) as suma')
        ->from('CostosDirectos')
        ->where('producto_id = ?', $productoid)
        ->andWhere('tipo_costo= ?', 'empa')
        ->andWhere('fecha between ? and ?',array($fecha1, $fecha2));
        
        $suma = $q->fetchOne();
        
        if($suma->getSuma()){return $suma->getSuma();}
        else{return 0;}  
    }
    
    public static function buscarCostoSecoNumerico($productoid,$fecha1,$fecha2)
    {
         $q = Doctrine_Query::create()
        ->select('Sum(valor) as suma')
        ->from('CostosDirectos')
        ->where('producto_id = ?', $productoid)
        ->andWhere('tipo_costo= ?', 'empa')
        ->andWhere('fecha between ? and ?',array($fecha1, $fecha2));
        
        $suma = $q->fetchOne();
        
        if($suma->getSuma()){return $suma->getSuma();}
        else{return 0;}
         
    }
    

     public static function buscarCostoRamaElab($ramaid,$fecha1,$fecha2)
    {
         $q = Doctrine_Query::create()
        ->select('Sum(cd.valor) as suma')
        ->from('CostosDirectos cd')
        ->leftjoin('cd.Producto p')
        ->where('p.rama_id = ?', $ramaid)
        ->andWhere('cd.tipo_costo= ?', 'elab')
        ->andWhere('fecha between ? and ?',array($fecha1, $fecha2));
        
        $suma = $q->fetchOne();
        
        if($suma->getSuma()){return $suma->getSuma();}
        else{return 0;}
    }
    

    public static function buscarCostoElab($fecha1,$fecha2)
    {
         $q = Doctrine_Query::create()
        ->select('Sum(valor) as suma')
        ->from('CostosDirectos')
        ->where('tipo_costo= ?', 'elab')
        ->andWhere('fecha between ? and ?',array($fecha1, $fecha2));
        
        $suma = $q->fetchOne();
        
        if($suma->getSuma()){return $suma->getSuma();}
        else{return 0;}
    }
    
    public static function buscarCostoEmpa($fecha1,$fecha2)
    {
         $q = Doctrine_Query::create()
        ->select('Sum(valor) as suma')
        ->from('CostosDirectos')
        ->where('tipo_costo= ?', 'empa')
        ->andWhere('fecha between ? and ?',array($fecha1, $fecha2));
        
        $suma = $q->fetchOne();
        
                if($suma->getSuma()){return $suma->getSuma();}
        else{return 0;}

    }
    
    public static function EliminarCostosSecos($idlote)
    {
        $deleted = Doctrine_Query::create()
        ->delete()
        ->from('CostosDirectos')
        ->andWhere('lote_id = ?',$idlote)
        ->andWhere('tipo_costo = ?','empa')        
        ->execute();
    
    }
    
}