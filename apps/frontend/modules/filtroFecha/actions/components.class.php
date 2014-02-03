<?php

class filtroFechaComponents extends sfComponents {
    

// default backend filtroFecha
    public function executeDefault() {
        $to = date('d-m-Y');
        $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
        
        echo get_partial("../filtroFecha", array("from" => $from,"to" => $to));
        return true;
    }

// sección del filtroFecha de administracion
    public function executeSimulador(sfWebRequest $request) {
        
        $desde = $request->getPostParameter("desde");
        $hasta = $request->getPostParameter("hasta");

        if($desde != null && $hasta != null)
        {
            $to = $hasta;
            $from = $desde;
        }
        else
        {
            $to = date('d-m-Y');
           // $from = date("d-m-Y", strtotime('01-'.date('m').'-'.date('Y')));
                 $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
   
        }
        
        //$request->setParameter("to", $to);
        //$request->setParameter("from", $from);

        $action = url_for("simulador/index");
        
        echo get_partial("../filtroFecha", array("from" => $from,"to" => $to, "action" => $action));
        return true;
    }

    public function executeResumen(sfWebRequest $request) {
        $desde = $request->getPostParameter("desde");
        $hasta = $request->getPostParameter("hasta");

        if($desde != null && $hasta != null)
        {
            $to = $hasta;
            $from = $desde;
        }
        else
        {
            $to = date('d-m-Y');
           // $from = date("d-m-Y", strtotime('01-'.date('m').'-'.date('Y')));
                 $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
   
        }
        
        $action = url_for("resumen/index");
                

        echo get_partial("../filtroFecha", array("from" => $from,"to" => $to, "action" => $action));
        return true;
    }
    
    public function executeCostosIndirectos(sfWebRequest $request) {
        $desde = $request->getPostParameter("desde");
        $hasta = $request->getPostParameter("hasta");

        if($desde != null && $hasta != null)
        {
            $to = $hasta;
            $from = $desde;
        }
        else
        {
            $to = date('d-m-Y');
            //$from = date("d-m-Y", strtotime('01-'.date('m').'-'.date('Y')));
                 $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
   
        }
        
        $action = url_for("resumen/CostosIndirectos");
                

        echo get_partial("../filtroFecha", array("from" => $from,"to" => $to, "action" => $action));
        return true;
    }
    
    public function executeCostosInsumos(sfWebRequest $request) {
        $desde = $request->getPostParameter("desde");
        $hasta = $request->getPostParameter("hasta");

        if($desde != null && $hasta != null)
        {
            $to = $hasta;
            $from = $desde;
        }
        else
        {
            $to = date('d-m-Y');
           // $from = date("d-m-Y", strtotime('01-'.date('m').'-'.date('Y')));
                 $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
   
        }
        
        $action = url_for("resumen/costosinsumos");
                

        echo get_partial("../filtroFecha", array("from" => $from,"to" => $to, "action" => $action));
        return true;
    }
    
    public function executeCostosProductos(sfWebRequest $request) {
        $desde = $request->getPostParameter("desde");
        $hasta = $request->getPostParameter("hasta");

        if($desde != null && $hasta != null)
        {
            $to = $hasta;
            $from = $desde;
        }
        else
        {
            $to = date('d-m-Y');
          //  $from = date("d-m-Y", strtotime('01-'.date('m').'-'.date('Y')));
                 $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
   
        }
        
        $action = url_for("resumen/costosproductos");
                

        echo get_partial("../filtroFecha", array("from" => $from,"to" => $to, "action" => $action));
        return true;
    }
    
    public function executeInsumos(sfWebRequest $request) {
        $desde = $request->getPostParameter("desde");
        $hasta = $request->getPostParameter("hasta");

        if($desde != null && $hasta != null)
        {
            $to = $hasta;
            $from = $desde;
        }
        else
        {
            $to = date('d-m-Y');
        //    $from = date("d-m-Y", strtotime('01-'.date('m').'-'.date('Y')));
                 $from = date("d-m-Y", strtotime(date('Y').'-'.date('m').'-01'));
   
        }
        
        $action = url_for("resumen/insumos");
                

        echo get_partial("../filtroFecha", array("from" => $from,"to" => $to, "action" => $action));
        return true;
    }
    
    
}

