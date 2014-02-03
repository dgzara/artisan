<h1>Lista de Aspectos de Calidad</h1>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
                        <th>Nº</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Ver</th>
                        <?php if($sf_user->hasPermission("Ver_Movil_AspectosCalidad_Editar")):?>
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
			<?php if($sf_user->hasPermission("Ver_Movil_AspectosCalidad_Editar")):?>
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
                "sAjaxSource": "<?php echo url_for('aspectos_calidad/index') ?>"
	} );
} );
</script>