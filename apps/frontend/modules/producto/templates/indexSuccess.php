<h1>Lista de Productos</h1>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Código</th>
			<th>Área De Negocio</th>
			<th width="20%">Nombre Producto</th>
			<th>Peso O Volumen De Producto</th>
                        <th>Ciclo De Vida Comercial</th>
			<th>Ciclo De Vida Producción</th>
			<th>Stock Crítico De Inventario</th>
                        <th>Ver</th>
                        <?php if($sf_user->hasPermission("Ver_Administracion_ProductosEnVenta_EditarListaProductos")):?>
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
			<th style="text-align: right"></th>
                        <th style="text-align: right"></th>
			<th style="text-align: right"></th>
			<th style="text-align: right"></th>
                        <th></th>
			<?php if($sf_user->hasPermission("Ver_Administracion_ProductosEnVenta_EditarListaProductos")):?>
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
                "sAjaxSource": "<?php echo url_for('producto/index') ?>",
                "aoColumnDefs": [
                    { "sClass": "right", "aTargets": [ 3 ] },
                    { "sClass": "right", "aTargets": [ 4 ] },
                    { "sClass": "right", "aTargets": [ 5 ] },
                    { "sClass": "right", "aTargets": [ 6 ] }
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

