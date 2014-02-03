<h1>Lista de Locales</h1>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
                        <th>Nº</th>
			<th>Cliente</th>
			<th width="20%">Local</th>
			<th>Direccion</th>
			<th>Contacto 1</th>
                        <th>E-Mail 1</th>
                        <th>Celular 1</th>
                        <th>Ver</th>
                        <?php if($sf_user->hasPermission("Ver_Administracion_Clientes_EditarLocales")):?>
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
                        <th></th>
			<th></th>
			<th></th>
                        <?php if($sf_user->hasPermission("Ver_Administracion_Clientes_EditarLocales")):?>
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
                "sAjaxSource": "<?php echo url_for('local/index') ?>",
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

