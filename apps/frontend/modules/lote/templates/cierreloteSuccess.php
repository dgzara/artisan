        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                    <tr>
                        <th>N° Lote</th>
                        <th>Fecha Elaboración</th>
                        <th>Fecha Vencimiento</th>
                        <th width="20%">Producto</th>
                        <th>Cantidad Inicial</th>
                        <th>Cantidad Actual</th>
                        <th>Cantidad Vendida</th>
                        <th>Rendimiento</th>
                        <th>Estado</th>
                        <th>Cerrar</th>
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
                        <th></th>
                    </tr>
            </tfoot>
        </table>

<script type="text/javascript">
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
                "sAjaxSource": "<?php echo url_for('lote/cierrelote') ?>",
                "aoColumnDefs": [
                    { "aTargets": [ 1 ], "sType": "uk_date" }
                ]
    });
});
</script>
