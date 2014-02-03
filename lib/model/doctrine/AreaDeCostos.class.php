<?php

/**
 * AreaDeCostos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    quesos
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class AreaDeCostos extends BaseAreaDeCostos
{
    public function getTotal($fec1, $fec2){


        $fecha1 = $fec1;
        $fecha2 = $fec2;

        if(!$fecha1){
            $fecha1 = date("Y-m-d", strtotime(date('Y').'-'.date('m').'-01'));

        }

        if(!$fecha2){
            $fecha2 = date('Y-m-d');
        }     
        $fecha1 = date('Y-m-d',strtotime($fecha1));
        $fecha2 = date('Y-m-d',strtotime($fecha2));
        

        $q = Doctrine_Query::create();
        $q->select('SUM(monto) as suma');
        $q->from('CostosIndirectos');    
        $q->where("fecha >= '".$fecha1."'");
        $q->andWhere("fecha <= '".$fecha2."'");
        $q->andWhere('area_de_costos_id = ?', $this->getId());

        //$q->where('area_de_costos_id = ?', $this->getId());

        $suma = $q->fetchOne();

        if($suma->getSuma()){
            $n = new sfNumberFormat('es_CL');
            return '$'.$n->format($suma->getSuma(), 'd', 'CLP');
            echo 'algo';
        }
        else{
            return '$0';
        }
    }
}