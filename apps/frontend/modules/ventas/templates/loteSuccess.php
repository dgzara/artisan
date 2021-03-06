<h1>Recepcionar Lote</h1>
<button class='accionar' style="padding: 5px; float: right; margin-bottom: 15px;">Recepcionar Varios</button>
<table id="calendario" class="one-table">
    <td>Desde</td><td><input type="text" name="filtrar_desde" id="filtrar_desde" readonly="true"/></td>
    <td>Hasta</td><td><input type="text" name="filtrar_hasta" id="filtrar_hasta" readonly="true"/></td>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
                        <th>Nº</th>
                        <th>Fecha Elaboración</th>
                        <th>Producto</th>
                        <th>N°Lote</th>
                        <th>Comentarios</th>
                        <th>Cantidad Inicial</th>
                        <th>Cantidad Actual</th>
                        <?php if($sf_user->hasPermission("Ver_Ventas_RecepcionProductos_Recepcionar")):?>
                            <th>Acción</th>
                        <?php endif;?>
                        <th>Recepcionar</th>
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
			<?php if($sf_user->hasPermission("Ver_Ventas_RecepcionProductos_Recepcionar")):?>
                            <th></th>
                        <th></th>
                        <?php endif;?>
		</tr>
	</tfoot>
</table>
<form action="grupo" method="POST" class="form_grupo" style="display:none"></form>
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
        "bServerSide": true,
        "sAjaxSource": "<?php echo url_for('ventas/get_data') ?>",
        "aoColumnDefs": [
            { "aTargets": [ 2 ], "sType": "uk_date" }
        ],
        "fnServerParams": function ( aoData ) {
          aoData.push( { "iSortCol_0": 1, "iSortingCols": 1} );
        },
        "fnInitComplete": function() {
	    /* Add a select menu for each TH element in the table footer */
            $("tfoot th").each( function ( i ) {
                    if(i != 0 && i != 7){
                        this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(i) );
                        $('select', this).change( function () {
                                oTable.fnFilter( $(this).val(), i );
                        } );
                    }
            } );
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
        var haycheckeados=false;
        var arregloCheckbox = [];
        $(".checkbox1").each(function(){
            haycheckeados =true;
            if($(this).is(':checked')) {
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
