<h1>Lista de Costos Indirectos</h1>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Nº</th>
                        <th>Nombre</th>
                        <th>Área de Costos</th>
                        <th>Centro de Costos</th>
                        <th>Lugar</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Ver</th>
                        <?php if($sf_user->hasPermission("Ver_Administracion_Costos_EditarCostosIndirectos")):?>
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
			<th></th>
                        <th style="text-align: right"></th>
                        <th></th>
                        <th></th>
                        <?php if($sf_user->hasPermission("Ver_Administracion_Costos_EditarCostosIndirectos")):?>
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
//      "bServerSide": true,
                "sAjaxSource": "<?php echo url_for('costos_indirectos/index') ?>",
                "aoColumnDefs": [
                    { "sClass": "right", "aTargets": [ 5 ], "sType": "currency" }
                ],
                "fnInitComplete": function() {
			/* Add a select menu for each TH element in the table footer */
                        $("tfoot th").each( function ( i ) {
                                if(i != 0 && i != 7 && i != 8){
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


