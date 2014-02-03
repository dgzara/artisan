<?php

class menuComponents extends sfComponents {

    static public $menu_items = array(
        "Adquisiciones" => 'ordencompra/index',
        "Producción" => 'lote/index',
        "Ventas" => 'ordenventa/index',
        "Costos" => 'resumen/index',
        "Móvil" => "captura/index",
        "Administración" => 'cliente/index'

    );

// default backend menu
    public function executeDefault() {
        $opts = null;
        $current = null;
        echo get_partial("../menu", array("opts" => $opts, "menu" => self::$menu_items, "current" => $current));
        return true;
    }

// sección del menu de administracion
    public function executeAdministrador() {
        $current = "Administración";
        $opts = array(
            "Clientes" => "cliente/index",
            "Productos" => "producto/index",
            "Costos" => "area_de_costos/index",
            "Proveedores e Insumos" => "insumo/index",
            "Lugares" => "lugar/index",
            "Pautas" => "plantilla_pauta/index",
            "Usuarios" => "sfGuardUser/index",
            "Registro" => 'Registro/index',
            "QR Formato" => 'formato_qr/edit'
        );
        $permisos = array(
            "Clientes" => "Ver_Administración_Clientes",
            "Productos" => "Ver_Administración_ProductosEnVenta",
            "Costos" => "Ver_Administración_Costos",
            "Proveedores e Insumos" => "Ver_Administración_Insumos",
            "Lugares" => "Ver_Administración_Lugares",
            "Pautas" => "Ver_Administración_Pautas",
            "Usuarios" => "Ver_Administración_Usuarios",
            "Registro" => "Ver_Administración_Usuarios",
            "QR Formato" => "Ver_Administración_Usuarios" //falta el permiso de QR Formato....
        );
        
        if (sfContext::getInstance()->getModuleName()=="cliente"||sfContext::getInstance()->getModuleName()=="local"||sfContext::getInstance()->getModuleName()=="cliente_producto"){
            $seleccionar = "Clientes";
        }
        if (sfContext::getInstance()->getModuleName()=="producto"||sfContext::getInstance()->getModuleName()=="rama"){
            $seleccionar = "Productos";
        }
        if (sfContext::getInstance()->getModuleName()=="centro_de_costos"||sfContext::getInstance()->getModuleName()=="area_de_costos"){
            $seleccionar = "Costos";
        }
        if (sfContext::getInstance()->getModuleName()=="insumo"||sfContext::getInstance()->getModuleName()=="proveedor"||sfContext::getInstance()->getModuleName()=="proveedor_insumo"){
            $seleccionar = "Proveedores e Insumos";
        }
        if (sfContext::getInstance()->getModuleName()=="lugar"){
            $seleccionar = "Lugares";
        }
        if (sfContext::getInstance()->getModuleName()=="plantilla_pauta"||sfContext::getInstance()->getModuleName()=="plantilla_columnas"){
            $seleccionar = "Pautas";
        }
        if (sfContext::getInstance()->getModuleName()=="sfGuardUser"||sfContext::getInstance()->getModuleName()=="sfGuardGroup"||sfContext::getInstance()->getModuleName()=="sfGuardPermission"){
            $seleccionar = "Usuarios";
        }
         if (sfContext::getInstance()->getModuleName()=="Registro"||sfContext::getInstance()->getModuleName()=="Registro"||sfContext::getInstance()->getModuleName()=="Registro"){
          $seleccionar = "Registro";
        }
        if (sfContext::getInstance()->getModuleName()=="formato_qr"){
          $seleccionar = "QR Formato";
       }
        
        echo get_partial("../menu", array("opts" => $opts, "permisos" => $permisos, "menu" => self::$menu_items, "current" => $current, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeInsumos() {
        $current = "Adquisiciones";
        $opts = array(
            "Orden de Compra" => "ordencompra/index",
            "Inventario Insumos" => "inventario_materia_prima/index"
        );
        $permisos = array(
            "Orden de Compra" => "Ver_Adquisiciones_OrdenesDeCompra",
            "Inventario Insumos" => "Ver_Adquisiciones_InventarioMateriaPrima"
        );

        if (sfContext::getInstance()->getModuleName()=="ordencompra"){
            $seleccionar = "Orden de Compra";
        }
        if (sfContext::getInstance()->getModuleName()=="inventario_materia_prima"){
            $seleccionar = "Inventario Insumos";
        }
        

        echo get_partial("../menu", array("opts" => $opts, "permisos" => $permisos, "menu" => self::$menu_items, "current" => $current, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeProductos() {
        $current = "Producción";
        $opts = array(
            "Producción Real" => "lote/index",
            "Plan de Producción" => "planproduccion/index",
            "Inventario Valdivia" => 'inventario_productos/index',
            "Cierre de Lote" => 'lote/cierrelote'
        );
        $permisos = array(
            "Plan de Producción" => "Ver_Produccion_PlanDeProduccion",
            "Producción Real" => "Ver_Produccion_ProduccionReal",
            "Inventario Valdivia" => 'Ver_Produccion_InventarioValdivia',
            "Cierre de Lote" => "Ver_Produccion_ProduccionReal"
        );

        if (sfContext::getInstance()->getModuleName()=="planproduccion"){
            $seleccionar = "Plan de Producción";
        }
        if (sfContext::getInstance()->getModuleName()=="lote"||sfContext::getInstance()->getModuleName()=="pauta"){
            $seleccionar = "Producción Real";
        }
        if (sfContext::getInstance()->getModuleName()=="lote"&&sfContext::getInstance()->getActionName()=="cierrelote"){
            $seleccionar = "Cierre de Lote";
        }
        if (sfContext::getInstance()->getModuleName()=="inventario_productos"){
            $seleccionar = "Inventario Valdivia";
        }


        echo get_partial("../menu", array("opts" => $opts, "permisos" => $permisos, "menu" => self::$menu_items, "current" => $current, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeVentas() {
        $current = "Ventas";
        $opts = array(
            "Orden de Venta" => "ordenventa/index",
            "Recepción Productos" => "ventas/lote",
            "Inventario Santiago" => 'ventas/inventario'
        );
        $permisos = array(
            "Recepción Productos" => "Ver_Ventas_RecepcionProductos",
            "Orden de Venta" => "Ver_Ventas_OrdenesDeVenta",
            "Inventario Santiago" => 'Ver_Ventas_InventarioSantiago'
        );
        if (sfContext::getInstance()->getModuleName()=="ventas"&&sfContext::getInstance()->getActionName()=="lote"){
            $seleccionar = "Recepción Productos";
        }
        if (sfContext::getInstance()->getModuleName()=="ordenventa"){
            $seleccionar = "Orden de Venta";
        }
        if (sfContext::getInstance()->getModuleName()=="ventas"&&sfContext::getInstance()->getActionName()=="inventario"){
            $seleccionar = "Inventario Santiago";
        }
        echo get_partial("../menu", array("opts" => $opts, "permisos" => $permisos, "menu" => self::$menu_items, "current" => $current, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeMovil() {
        $current = "Móvil";
        $opts = array(
            "Capturas" => "captura/index",
            "Productos Competencia" => "productos_competencia/index",
            "Aspectos Calidad" => "aspectos_calidad/index"
        );
        $permisos = array(
            "Capturas" => "Ver_Movil_Capturas",
            "Productos Competencia" => "Ver_Movil_ProductosCompetencia",
            "Aspectos Calidad" => "Ver_Movil_AspectosCalidad"
        );
        if (sfContext::getInstance()->getModuleName()=="captura"){
            $seleccionar = "Capturas";
        }
        if (sfContext::getInstance()->getModuleName()=="productos_competencia"||sfContext::getInstance()->getModuleName()=="producto_competencia_producto"||sfContext::getInstance()->getModuleName()=="marca"){
            $seleccionar = "Productos Competencia";
        }
        if (sfContext::getInstance()->getModuleName()=="aspectos_calidad"){
            $seleccionar = "Aspectos Calidad";
        }
        echo get_partial("../menu", array("opts" => $opts, "permisos" => $permisos, "menu" => self::$menu_items, "current" => $current, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeSimulacion() {
        $current = "Costos";
        $opts = array(
            "Resumen Ventas/Costos" => "resumen/index",
            "Costos Indirectos" => "costos_indirectos/index",
            "Simulación" => "simulador/index",
            
        );
        $permisos = array(
            "Resumen Ventas/Costos" => "Ver_Costos_Resumen",
            "Simulación" => "Ver_Costos_Simulacion",
            "Costos Indirectos" => "Ver_Costos_CostosIndirectos"
        );
        if (sfContext::getInstance()->getModuleName()=="resumen"){
            $seleccionar = "Resumen Ventas/Costos";
        }
        if (sfContext::getInstance()->getModuleName()=="simulador"){
            $seleccionar = "Simulación";
        }
        if (sfContext::getInstance()->getModuleName()=="costos_indirectos"){
            $seleccionar = "Costos Indirectos";
        }
        echo get_partial("../menu", array("opts" => $opts, "permisos" => $permisos, "menu" => self::$menu_items, "current" => $current, "seleccionar" => $seleccionar));
        return true;
    }

}

?>
