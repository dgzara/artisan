<div class="main-col-w">
    <div class="main-col">
        <div align="right">
            <table id="calendario" class="one-table">
                <td>Desde</td><td><input type="text" name="filtrar_desde" id="filtrar_desde" readonly="true"/></td>
                <td>Hasta</td><td><input type="text" name="filtrar_hasta" id="filtrar_hasta" readonly="true"/></td>
            </table>
        </div>
        <h1>Lista Orden de Venta</h1>
        <button class='accionar' style="padding: 5px; float: right; margin-bottom: 15px;">Accion</button>
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                    <tr>
                        <th>N°</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th width="30%">Nº Orden de Compra</th>
			            <th>Local</th>
                        <th>Total Neto</th>
                        <th>Estado</th>
                        <th>Deshacer</th>
                        <th>Ver</th>
                        <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Editar")):?>
                            <th>Modificar</th>
                        <?php endif;?>
                        <th>Acción</th>
                    </tr>
            </thead>
            <tbody>
                    <tr>
                            <td colspan="3" class="dataTables_empty">Cargando datos del servidor</td>
                    </tr>
            </tbody>
            <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Editar")):?>
                            <th></th>
                        <?php endif;?>
                        <th></th>
                    </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="side-col last">
<div class="msidebar rounded">
  <div style="padding: 3px 4px 15px 14px">
    <div id="filter_settings">
      <h2>Herramientas</h2>
      <div id="filter_settings_body">
            <form name="form" method="post" action="ordenventa/filter">
                Acción:
                <p><select name="accion" onchange="this.form.submit()">
                    <option value="">-- Seleccione --</option>
                    <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Lista_Validar")):?>
                        <option value="Validar">Validar</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Lista_Despachar")):?>
                        <option value="Despachar">Despachar</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Lista_Recepcion")):?>
                        <option value="Registrar Recepción">Registrar Recepción</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Lista_Cobrar")):?>
                            <option value="Cobrar">Cobrar</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Lista_Devolucion")):?>
                            <option value="Registrar Devolución">Registrar Devolución</option>
                    <?php endif;?> 
                </select></p>
            </form>
      </div>
    </div>
  </div>

  <div class="cut">&nbsp;</div>
</div>
</div>
<form action="ordenventa/grupo" method="POST" class="form_grupo" style="display:none"></form>
<script type="text/javascript">
/* Custom filtering function which will filter data in column four between two values */
$.fn.dataTableExt.afnFiltering.push(
	function( oSettings, aData, iDataIndex ) {
		// "date-range" is the id for my input
 		var dateMin = $('#filtrar_desde').attr("value");
                var dateMax = $('#filtrar_hasta').attr("value");

		// parse the range from a single field into min and max, remove " - "
		dateMin = dateMin.substring(6,10) + dateMin.substring(3,5) + dateMin.substring(0,2);
		dateMax = dateMax.substring(6,10) + dateMax.substring(3,5) + dateMax.substring(0,2);

		// 4 here is the column where my dates are.
 		var date = aData[1];

 		// remove the "-" characters
 		// 2010-04-11 -> 20100411
 		date = date.substring(6,10) + date.substring(3,5) + date.substring(0,2);

		// run through cases
 		if ( dateMin == "" && date <= dateMax){
 			return true;
 		}
 		else if ( dateMin =="" && date <= dateMax ){
 			return true;
 		}
 		else if ( dateMin <= date && "" == dateMax ){
 			return true;
 		}
 		else if ( dateMin <= date && date <= dateMax ){
 			return true;
 		}
		// all failed
 		return false;
	}
);

$(document).ready(function() {
	   /* Initialise datatables */
        var oTable = $('#example').dataTable( {
        "oLanguage": {
            "sSearch": "Buscar en todas las columnas:"
        },
                "bStateSave": true,
                "sPaginationType": "full_numbers",
                "bProcessing": true,
//      "bServerSide": true,
                "sAjaxSource": "<?php echo url_for('ordenventa/get_data') ?>",
                "aoColumnDefs": [
                    { "aTargets": [ 1 ], "sType": "uk_date" },
                    { "sClass": "right", "aTargets": [ 4 ], "sType": "currency" }
                ],
                "fnInitComplete": function() {
            /* Add a select menu for each TH element in the table footer */
                        $("tfoot th").each( function ( i ) {
                                if(i != 0 && i != 3 && i != 6 && i!= 7 && i != 8){
                                    this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(i) );
                                    $('select', this).change( function () {
                                            oTable.fnFilter( $(this).val(), i );
                                    } );
                                }
                        } );
        },
                "fnDrawCallback": function() {
            $('a.jt').cluetip({
                cluetipClass: 'jtip',
                width: 600,
                arrows: true,
                dropShadow: false,
                hoverIntent: false,
                sticky: true,
                mouseOutClose: true,
                closePosition: 'title',
                closeText: '<img src="../web/images/cross.png" alt="close" />'
            });
        } 
    } );
    

	/* Add event listeners to the two range filtering inputs */
	$('#filtrar_desde').change( function() { oTable.fnDraw(); } );
	$('#filtrar_hasta').change( function() { oTable.fnDraw(); } );
} );
</script>
<script type="text/javascript">
    // para hacer la siguiente accion de varias ordenes de venta al mismo tiempo
    $(document).ready(function(){  
  
    $(".accionar").click(function() {  
        var primero = "algo";
        var bool =false;
        var haycheckeados = false;
        var arregloCheckbox = [];
        $(".checkbox1").each(function(){
            if($(this).is(':checked')) {
                haycheckeados=true;
                if(primero == "algo") {
                    primero = $(this).attr('accion');
                }
                var estado = $(this).attr('accion');
                if(estado == primero){
                    arregloCheckbox.push($(this).attr('value'));
                }
                else{
                    bool=true;
                }

            }
        });
        if(bool){
            alert("Ha chequeado ordenes que corresponden a más de un tipo de acción. Debe elegir acciones que correspondan al mismo tipo.");
        }
        else
        {
                    var i;
        for(i=0; i < arregloCheckbox.length; i++) {
            $('.form_grupo').append("<input type='hidden' name='grupo[]' value='"+ arregloCheckbox[i] +"' />");
        }
        if(haycheckeados)
        {
            $('.form_grupo').submit();
        }
        else{ alert("Debe seleccionar alguna orden para accionar.");}
        }

    });  
  
}); 
</script>