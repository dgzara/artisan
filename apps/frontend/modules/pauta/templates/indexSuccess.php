<h1>Historial de Elaboración por Tipo de Pauta </h1>

<table id="calendario" class="one-table">
    <td>Desde</td><td><input type="text" name="filtrar_desde" id="filtrar_desde" readonly="true"/></td>
    <td>Hasta</td><td><input type="text" name="filtrar_hasta" id="filtrar_hasta" readonly="true"/></td>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
                        <th>Nº</th>
                        <th>Plantilla de Pauta</th>
                        <th>Fecha de Elaboración</th>
                        <th>Ver</th>
                        <?php if($sf_user->hasPermission("Ver_Produccion_ProduccionReal_EditarElaboraciones")):?>
                            <th>Modificar</th>
                        <?php endif;?>
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
			<?php if($sf_user->hasPermission("Ver_Produccion_ProduccionReal_EditarElaboraciones")):?>
                            <th></th>
                        <?php endif;?>
		</tr>
	</tfoot>
</table>


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
 		var date = aData[2];

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
        "sAjaxSource": "<?php echo url_for('pauta/get_data') ?>",
        "aoColumnDefs": [
            { "aTargets": [ 2 ], "sType": "uk_date" }
        ],
        "fnServerParams": function ( aoData ) {
          aoData.push( { "iSortCol_0": 2, "iSortingCols": 1} );
        },
        "fnDrawCallback": function() {
            $('a.jt').cluetip({
                cluetipClass: 'jtip',
                arrows: true,
                dropShadow: false,
                hoverIntent: false,
                sticky: true,
                mouseOutClose: true,
                closePosition: 'title',
                closeText: '<img src="..../images/cross.png" alt="close" />'
            });
        }, 
        "fnInitComplete": function() {
    			/* Add a select menu for each TH element in the table footer */
                        $("tfoot th").each( function ( i ) {
                                if(i != 0 && i != 3 && i != 4){
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
