<?php

class submenuComponents extends sfComponents {

// default backend submenu
    public function executeDefault() {
        $opts = array("Login" => "@sf_guard_signin"
        );
        echo get_partial("../submenu", array("opts" => $opts));
        return true;
    }

// sección del submenu de administracion
    public function executeUsuarios() {
        $opts = array(
            "Usuarios" => array(
                "Agregar Usuario" => "sfGuardUser/new",
                "Lista de Usuarios" => "guard/users"),
            "Grupos" => array(
                "Agregar Grupo" => "sfGuardGroup/new",
                "Lista de Grupos" => "sfGuardGroup/index"),
            "Permisos" => array(
                "Lista de Permisos" => "sfGuardPermission/index")
        );
        $permisos = array(
            "Agregar Usuario" => "Ver_Administracion_Usuarios_AgregarUsuario",
            "Agregar Grupo" => "Ver_Administracion_Usuarios_AgregarGrupo",
            "Lista de Usuarios"=> "Ver_Administracion_Usuarios_ManejoUsuario",
            "Lista de Grupos" => "Ver_Administracion_Usuarios_ManejoGrupos",
            "Lista de Permisos" => "Ver_Administracion_Usuarios_ListadoPermisos"
        );
        if (sfContext::getInstance()->getModuleName()=="sfGuardUser"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Usuario";
        }
        if (sfContext::getInstance()->getModuleName()=="sfGuardGroup"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Grupo";
        }
        if (sfContext::getInstance()->getModuleName()=="sfGuardUser"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Usuarios";
        }
        if (sfContext::getInstance()->getModuleName()=="sfGuardGroup"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Grupos";
        }
        if (sfContext::getInstance()->getModuleName()=="sfGuardPermission"){
            $seleccionar = "Lista de Permisos";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeProductos() {
        $opts = array(
            "Productos" => array(
                "Lista de Productos" => "producto/index",
                "Agregar Producto" => "producto/new"),
            "Areas de Negocio" => array(
                "Lista de Áreas de Negocio" => "rama/index",
                "Agregar Área de Negocio" => "rama/new")
        );
        $permisos = array(
            "Lista de Productos" => "Ver_Administracion_ProductosEnVenta_VerListaProductos",
            "Agregar Producto" => "Ver_Administracion_ProductosEnVenta_AgregarProducto",
            "Lista de Áreas de Negocio" => "Ver_Administracion_ProductosEnVenta_VerAreasdeNegocio",
            "Agregar Área de Negocio" => "Ver_Administracion_ProductosEnVenta_AgregarAreaDeNegocio"
        );
        if (sfContext::getInstance()->getModuleName()=="producto"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Productos";
        }
        if (sfContext::getInstance()->getModuleName()=="producto"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Producto";
        }
        if (sfContext::getInstance()->getModuleName()=="rama"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Áreas de Negocio";
        }
        if (sfContext::getInstance()->getModuleName()=="rama"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Área de Negocio";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }
    
    public function executeResumen() {
        $opts = array(
            "Vistas Resumen" => array(
                "Resumen de Costos Totales" => "resumen/index",
                "Costos Indirectos" => "resumen/CostosIndirectos",
                "Costos por Producto" => "resumen/costosproductos",
                "Costos Insumos por O.Compra" => "resumen/insumos"
                
                )
            
        );
        
        $permisos = array(
            "Resumen de Costos Totales" => "Ver_Costos_Resumen",
            "Costos Indirectos" => "Ver_Costos_Resumen",
            "Costos por Producto" => "Ver_Costos_Resumen",
            "Costos por Insumo" => "Ver_Costos_Resumen",
            "Costos Insumos por O.Compra" => "Ver_Costos_Resumen"
        );
        
        if (sfContext::getInstance()->getModuleName()=="resumen"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Resumen de Costos Totales";
        }
        if (sfContext::getInstance()->getModuleName()=="resumen"&&sfContext::getInstance()->getActionName()=="CostosIndirectos"){
            $seleccionar = "Costos Indirectos";
        }
        if (sfContext::getInstance()->getModuleName()=="resumen"&&sfContext::getInstance()->getActionName()=="Costosproductos"){
            $seleccionar = "Costos por Producto";
        }
        if (sfContext::getInstance()->getModuleName()=="resumen"&&sfContext::getInstance()->getActionName()=="Costosinsumo"){
            $seleccionar = "Costos por Insumo";
        }
        if (sfContext::getInstance()->getModuleName()=="resumen"&&sfContext::getInstance()->getActionName()=="Insumos"){
            $seleccionar = "Costos Insumos por O.Compra";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeCostos(){
        $opts = array(
            "Áreas de Costo" => array(
                "Ver Áreas de Costo" => "area_de_costos/index",
                "Agregar Área de Costo" => "area_de_costos/new"),
            "Centros de Costo" => array(
                "Ver Centros de Costo" => "centro_de_costos/index",
                "Agregar Centro de Costo" => "centro_de_costos/new")
        );
        $permisos = array(
            "Ver Áreas de Costo" => "Ver_Administracion_Costos_VerAreasDeCosto",
            "Agregar Área de Costo" => "Ver_Administracion_Costos_AgregarAreaDeCosto",
            "Ver Centros de Costo" => "Ver_Administracion_Costos_VerCentrosDeCosto",
            "Agregar Centro de Costo" => "Ver_Administracion_Costos_AgregarCentroDeCosto"
        );

        if (sfContext::getInstance()->getModuleName()=="area_de_costos"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Ver Áreas de Costo";
        }
        if (sfContext::getInstance()->getModuleName()=="area_de_costos"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Área de Costo";
        }
        if (sfContext::getInstance()->getModuleName()=="centro_de_costos"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Ver Centros de Costo";
        }
        if (sfContext::getInstance()->getModuleName()=="centro_de_costos"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Centro de Costo";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeLugar(){
        $opts = array(
            "Lugares" => array(
                "Ver Lugar" => "lugar/index",
                "Agregar Lugar" => "lugar/new")
        );
        $permisos = array(
            "Ver Lugar" => "Ver_Administracion_Lugares_Ver",
            "Agregar Lugar" => "Ver_Administracion_Lugares_Agregar"
        );

        if (sfContext::getInstance()->getModuleName()=="lugar"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Ver Lugar";
        }
        if (sfContext::getInstance()->getModuleName()=="lugar"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Lugar";
        }

        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }


    public function executeInsumosCompra() {
        $opts = array(
            "Insumos" => array(
                "Lista de Insumos" => "insumo/index",
                "Agregar Insumo" => "insumo/new"),
            "Proveedores" => array(
                "Lista de Proveedores" => "proveedor/index",
                "Agregar Proveedor" => "proveedor/new"),
            "Relación Insumo / Proveedor" => array(
                "Lista de Asociaciones" => "proveedor_insumo/index",
                "Asociar Insumo/Proveedor" => "proveedor_insumo/new")
        );
        $permisos = array(
            "Lista de Insumos" => "Ver_Administracion_Insumos_VerInsumosParaComprar",
            "Agregar Insumo" => "Ver_Administracion_Insumos_AgregarInsumoParaComprar",
            "Lista de Proveedores" => "Ver_Administracion_Insumos_VerProveedores",
            "Agregar Proveedor" => "Ver_Administracion_Insumos_AgregarProveedor",
            "Lista de Asociaciones" => "Ver_Administracion_Insumos_VerAsociaciones",
            "Asociar Insumo/Proveedor" => "Ver_Administracion_Insumos_AsociarAProveedor"
        );
        if (sfContext::getInstance()->getModuleName()=="insumo"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Insumos";
        }
        if (sfContext::getInstance()->getModuleName()=="insumo"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Insumo";
        }
        if (sfContext::getInstance()->getModuleName()=="proveedor"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Proveedores";
        }
        if (sfContext::getInstance()->getModuleName()=="proveedor"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Proveedor";
        }
        if (sfContext::getInstance()->getModuleName()=="proveedor_insumo"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Asociaciones";
        }
        if (sfContext::getInstance()->getModuleName()=="proveedor_insumo"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Asociar Insumo/Proveedor";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeClientes() {
        $opts = array(
            "Clientes" => array(
                "Lista de Clientes" => "cliente/index",
                "Agregar Cliente" => "cliente/new"),
            "Locales" => array(
                "Lista de Locales" => "local/index",
                "Agregar Local" => "local/new"),
            "Relación Cliente/Producto" => array(
                "Lista de Asociaciones" => "cliente_producto/index",
                "Asociar Producto" => "cliente_producto/new")
        );
        $permisos = array(
            "Lista de Clientes" => "Ver_Administracion_Clientes_VerCliente",
            "Agregar Cliente" => "Ver_Administracion_Clientes_AgregarCliente",
            "Lista de Locales" => "Ver_Administracion_Clientes_VerLocales",
            "Agregar Local" => "Ver_Administracion_Clientes_AgregarLocal",
            "Lista de Asociaciones" => "Ver_Administracion_ProductosEnVenta_VerAsociaciones",
            "Asociar Producto" => "Ver_Administracion_ProductosEnVenta_AsociarACliente"
        );
        if (sfContext::getInstance()->getModuleName()=="cliente"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Clientes";
        }
        if (sfContext::getInstance()->getModuleName()=="cliente"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Cliente";
        }
        if (sfContext::getInstance()->getModuleName()=="local"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Locales";
        }
        if (sfContext::getInstance()->getModuleName()=="local"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Local";
        }
        if (sfContext::getInstance()->getModuleName()=="cliente_producto"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Asociaciones";
        }
        if (sfContext::getInstance()->getModuleName()=="cliente_producto"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Asociar Producto";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }

    public function executePlantillasPautas() {
        $opts = array(
            "Pautas" => array(
                "Lista de Pautas de Elaboración" => "plantilla_pauta/index",
                "Agregar Pauta" => "plantilla_pauta/new"),
            "Columnas de las Pautas" => array(
                "Lista de Columnas" => "plantilla_columnas/index",
                "Agregar Columnas" => "plantilla_columnas/new")
        );
        $permisos = array(
            "Lista de Pautas de Elaboración" => "Ver_Administracion_Pautas_VerPlantillas",
            "Agregar Pauta" => "Ver_Administracion_Pautas_AgregarPlantilla",
            "Lista de Columnas" => "Ver_Administracion_Pautas_VerColumnas",
            "Agregar Columnas" => "Ver_Administracion_Pautas_AgregarColumnas",
        );
        if (sfContext::getInstance()->getModuleName()=="plantilla_pauta"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Pautas de Elaboración";
        }
        if (sfContext::getInstance()->getModuleName()=="plantilla_pauta"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Pauta";
        }
        if (sfContext::getInstance()->getModuleName()=="plantilla_columnas"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Columnas";
        }
        if (sfContext::getInstance()->getModuleName()=="plantilla_columnas"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Columnas";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }

    public function executePlanProduccion() {
        $opts = array(
            "Planes de Producción" => array(
                "Lista de Planes de Producción" => "planproduccion/index",
                "Nuevo Plan de Producción" => "planproduccion/new")
        );
        $permisos = array(
            "Lista de Planes de Producción" => "Ver_Produccion_PlanDeProduccion_Lista",
            "Nuevo Plan de Producción" => "Ver_Produccion_PlanDeProduccion_NuevoPlan",
        );
        if (sfContext::getInstance()->getModuleName()=="planproduccion"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Planes de Producción";
        }
        if (sfContext::getInstance()->getModuleName()=="planproduccion"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Nuevo Plan de Producción";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeLotes(){
        $opts = array(
            "Lotes" => array(
                "Lista" => "lote/index",
                "Nuevo Lote" => "lote/new")
        );
        $permisos = array(
            "Lista" => "Lotes_Lista",
            "Nuevo Lote" => "Lotes_NuevoLote"
        );
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null));
        return true;
    }

    public function executeOrdenCompra() {
        $opts = array(
            "Órdenes de Compra" => array(
                "Lista de Órdenes de Compra" => "ordencompra/index",
                "Crear Orden de Compra" => "ordencompra/new"),
            "Cotizaciones" => array(
                "Cotizar" => "ordencompra/cotizar")
        );
        $permisos = array(
            "Lista de Órdenes de Compra" => "Ver_Adquisiciones_OrdenesDeCompra_VerLista",
            "Crear Orden de Compra" => "Ver_Adquisiciones_OrdenesDeCompra_NuevaOrden",
            "Cotizar" => "Ver_Adquisiciones_OrdenesDeCompra_Cotizar"
        );
        if (sfContext::getInstance()->getModuleName()=="ordencompra"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Órdenes de Compra";
        }
        if (sfContext::getInstance()->getModuleName()=="ordencompra"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Crear Orden de Compra";
        }
        if (sfContext::getInstance()->getModuleName()=="ordencompra"&&sfContext::getInstance()->getActionName()=="cotizar"){
            $seleccionar = "Cotizar";
        }
        $accion_actual = sfContext::getInstance()->getModuleName()."/". sfContext::getInstance()->getActionName();
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "seleccionar" => $seleccionar, "isuser" => null, "accion_actual" => $accion_actual));
        return true;
    }

    public function executeOrdenVenta() {
        $opts = array(
            "Orden de Venta" => array(
                "Lista Orden de Venta" => "ordenventa/index",
                "Crear Orden de Venta" => "ordenventa/new",
                "Importar" => "ordenventa/upload",
                "Lista por Factura" => "ordenventa/factura ")
        );
        $permisos = array(
            "Lista Orden de Venta" => "Ver_Ventas_OrdenVenta_Lista",
            "Crear Orden de Venta" => "Ver_Ventas_OrdenVenta_NuevaOrden",
            "Importar" => "Ver_Ventas_OrdenVenta_NuevaOrden",
            "Lista por Factura" => "Ver_Ventas_OrdenVenta_Facturas"
        );
        if (sfContext::getInstance()->getModuleName()=="ordenventa"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista Orden de Venta";
        }
        if (sfContext::getInstance()->getModuleName()=="ordenventa"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Crear Orden de Venta";
        }
        if (sfContext::getInstance()->getModuleName()=="ordenventa"&&sfContext::getInstance()->getActionName()=="upload"){
            $seleccionar = "Importar";
        }
        if (sfContext::getInstance()->getModuleName()=="ordenventa"&&sfContext::getInstance()->getActionName()=="factura"){
            $seleccionar = "Lista por Factura";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeProduccionReal() {
        $opts = array(
            "Elaboraciones" => array(
            "Lista de Elaboraciones" => "pauta/index",
            "Ingresar Elaboración" => "pauta/new"),
            "Lotes" => array(
                "Lista Estado de Maduración" => "lote/index",
                "Nivel de Producción" => "lote/productosElaborados",
                "Stock y Estado de Maduración" => "lote/trazabilidad")
        );
        $permisos = array(
            "Lista de Elaboraciones" => "Ver_Produccion_ProduccionReal_ListaElaboraciones",
            "Ingresar Elaboración" => "Ver_Produccion_ProduccionReal_IngresarElaboracion",
            "Lista Estado de Maduración" => "Ver_Produccion_ProduccionReal_ListaLotesElaborados",
            "Nivel de Producción" => "Ver_Produccion_ProduccionReal_ListaProductosElaborados",
            "Stock y Estado de Maduración" => "Ver_Produccion_ProduccionReal_TrazabilidadDeProductosElaborados"
        );

        if (sfContext::getInstance()->getModuleName()=="pauta"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Elaboraciones";
        }
        if (sfContext::getInstance()->getModuleName()=="pauta"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Ingresar Elaboración";
        }
        if (sfContext::getInstance()->getModuleName()=="lote"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista Estado de Maduración";
        }
        if (sfContext::getInstance()->getModuleName()=="lote"&&sfContext::getInstance()->getActionName()=="productosElaborados"){
            $seleccionar = "Nivel de Producción";
        }
        if (sfContext::getInstance()->getModuleName()=="lote"&&sfContext::getInstance()->getActionName()=="trazabilidad"){
            $seleccionar = "Stock y Estado de Maduración";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }

    public function executePautas(){
        $opts = array(
            "Pautas de Elaboración" => array(
                "Lista" => "pauta/index",
                "Ingresar Pauta" => "pauta/new")
        );
        $permisos = array(
            "Lista" => "Pauta_Lista",
            "Ingresar Pauta" => "Pauta_IngresarPauta"
        );
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null));
        return true;
    }

    public function executeCapturas() {
        $opts = array(
            "Capturas" => array(
                "Lista" => "captura/index",
                "Alertas" => "captura/alertas",
                "Estadisticas" => "captura/estadisticas")
        );
        $permisos = array(
            "Lista" => "Ver_Movil_Capturas_Lista",
            "Alertas" => "Ver_Movil_Capturas_Alertas",
            "Estadisticas" => "Ver_Movil_Capturas_Estadisticas"
        );
        if (sfContext::getInstance()->getModuleName()=="captura"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista";
        }
        if (sfContext::getInstance()->getModuleName()=="captura"&&sfContext::getInstance()->getActionName()=="alertas"){
            $seleccionar = "Alertas";
        }
        if (sfContext::getInstance()->getModuleName()=="captura"&&sfContext::getInstance()->getActionName()=="estadisticas"){
            $seleccionar = "Estadisticas";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeCostosIndirectos(){
        $opts = array(
            "Costos Indirectos" => array(
                "Lista" => "costos_indirectos/index",
                "Agregar" => "costos_indirectos/new")
        );
        $permisos = array(
            "Lista" => "Ver_Costos_CostosIndirectos_Lista",
            "Agregar" => "Ver_Costos_CostosIndirectos_Agregar",
        );
        if (sfContext::getInstance()->getModuleName()=="costos_indirectos"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista";
        }
        if (sfContext::getInstance()->getModuleName()=="costos_indirectos"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }

    public function executeProductosCompetencia()
    {
        $opts = array(
            "Marcas" =>array(
                "Lista de Marcas" => "marca/index",
                "Agregar Marca" => "marca/new"),
            "Productos de la Competencia" => array(
                "Lista de Competencias" => "productos_competencia/index",
                "Agregar Competencia" => "productos_competencia/new"),
            "Relación Productos ARTISAN y Competencia" => array(
                "Lista de Asociaciones" => "producto_competencia_producto/index",
                "Agregar Asociación" => "producto_competencia_producto/new")
        );
        $permisos = array(
            "Lista de Marcas" => "Ver_Movil_ProductosCompetencia_ListaMarcas",
            "Agregar Marca" => "Ver_Movil_ProductosCompetencia_AgregarMarca",
            "Lista de Competencias" => "Ver_Movil_ProductosCompetencia_ListaCompetencias",
            "Agregar Competencia" => "Ver_Movil_ProductosCompetencia_AgregarCompetencia",
            "Lista de Asociaciones" => "Ver_Movil_ProductosCompetencia_ListaAsociaciones",
            "Agregar Asociación" => "Ver_Movil_ProductosCompetencia_AgregarAsociacion"
        );
        if (sfContext::getInstance()->getModuleName()=="marca"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Marcas";
        }
        if (sfContext::getInstance()->getModuleName()=="marca"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Marca";
        }
        if (sfContext::getInstance()->getModuleName()=="productos_competencia"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Competencias";
        }
        if (sfContext::getInstance()->getModuleName()=="productos_competencia"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Competencia";
        }
        if (sfContext::getInstance()->getModuleName()=="producto_competencia_producto"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Asociaciones";
        }
        if (sfContext::getInstance()->getModuleName()=="producto_competencia_producto"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Asociación";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }
    public function executeAspectosCalidad()
    {
        $opts = array(
            "Aspectos de Calidad de los Productos" => array(
                "Lista de Aspectos de Calidad" => "aspectos_calidad/index",
                "Agregar Aspecto de Calidad" => "aspectos_calidad/new")
        );
        $permisos = array(
            "Lista de Aspectos de Calidad" => "Ver_Movil_AspectosCalidad_Lista",
            "Agregar Aspecto de Calidad" => "Ver_Movil_AspectosCalidad_Agregar"
        );
        if (sfContext::getInstance()->getModuleName()=="aspectos_calidad"&&sfContext::getInstance()->getActionName()=="index"){
            $seleccionar = "Lista de Aspectos de Calidad";
        }
        if (sfContext::getInstance()->getModuleName()=="aspectos_calidad"&&sfContext::getInstance()->getActionName()=="new"){
            $seleccionar = "Agregar Aspecto de Calidad";
        }
        echo get_partial("../submenu", array("opts" => $opts, "permisos" => $permisos, "isuser" => null, "seleccionar" => $seleccionar));
        return true;
    }

}

?>
