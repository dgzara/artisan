<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th width="80px">Nº Factura</th>
                        <th width="80px">Nº Orden de Venta</th>
                        <th width="80px">Estado</th>
			<th width="100px">Fecha Emisión</th>
			<th>Cliente</th>
			<th>Local</th>
			<th>Valor Neto</th>
                        <th>IVA</th>
			<th>Valor Total</th>
                        <th>Ver</th>
                        <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Editar")):?>
                            <th>Modificar</th>
                        <?php endif; ?>
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
			<th style="text-align: right"></th>
                        <th></th>
                        <th></th>
			<th style="text-align: right"></th>
			<th style="text-align: right"></th>
                        <th style="text-align: right"></th>
			<th ></th>
                        <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Editar")):?>
                            <th></th>
                        <?php endif;?>
		</tr>
	</tfoot>
</table>


<script>
$(document).ready(function() {
	/* Initialise the DataTable */
	var oTable = $('#example').dataTable( {
		"oLanguage": {
			"sSearch": "Buscar en todas las columnas:"
		},
        "bStateSave": true,
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo url_for('ordenventa/factura') ?>",
        "aoColumnDefs": [
            { "sClass": "right", "aTargets": [ 3 ], "sType": "uk_date" },
            { "sClass": "right", "aTargets": [ 6 ], "sType": "currency" },
            { "sClass": "right", "aTargets": [ 7 ], "sType": "currency" },
            { "sClass": "right", "aTargets": [ 8 ], "sType": "currency" },
        ],
        "fnServerParams": function ( aoData ) {
        	aoData.push( { "iSortCol_0": 3, "iSortingCols": 1} );
        },
        "fnInitComplete": function() {
	/* Add a select menu for each TH element in the table footer */
            $("tfoot th").each( function ( i ) {
                    if(i != 0 && i != 8 && i != 9){
                        this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(i) );
                        $('select', this).change( function () {
                                oTable.fnFilter( $(this).val(), i );
                        } );
                    }
            } );
		}
	} );
} );
</script>

