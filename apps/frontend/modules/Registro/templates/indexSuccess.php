<h1>Registro</h1>
<table id="register" class="one-table">
  <thead>
    <tr>
      <th width="15%">Acción</th>
      <th width="25%">Nombre</th>
      <th>Bodega</th>
      <th>Cantidad</th>
      <th>Cantidad Anterior</th>
      <th>Cantidad Nueva</th>
      <th width="15%">Fecha</th>
      <th width="15%">Usuario</th>
    </tr>
  </thead>
</table>
<script type="text/javascript">
jQuery(document).ready(function() {

  var oTable = jQuery('#register').dataTable( {
    "oLanguage": {
      "sSearch": "Buscar en todas las columnas:"
    },

    "bStateSave": true,
    "sPaginationType": "full_numbers",

    'bProcessing': true,

   // 'bServerSide': true,

    'sAjaxSource': "<?php echo url_for('Registro/get_data') ?>",

    "aoColumnDefs": [
                ],

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


 });

})

</script>
