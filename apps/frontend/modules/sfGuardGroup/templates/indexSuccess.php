<h1>Lista de Grupos</h1>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
                        <th>Nº</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Ver</th>
                        <?php if($sf_user->hasPermission("Ver_Administracion_Usuarios_EditarGrupos")):?>
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
			<?php if($sf_user->hasPermission("Ver_Administracion_Usuarios_EditarGrupos")):?>
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
                "sAjaxSource": "<?php echo url_for('sfGuardGroup/index') ?>",
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
} );
</script>
