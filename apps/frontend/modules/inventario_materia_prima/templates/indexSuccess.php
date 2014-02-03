

<tr>
<th><h1>Inventario Actual de Insumos</h1>

     <h3> <a href="<?php echo url_for('inventario_materia_prima/new') ?>">editar</a> </h3></th>
  </tr>
<table class="one-table">
  <thead>
    <tr>
      <th>Insumo</th>
      <th>Cantidad en Stock</th>
      <th>Último Movimiento</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($inventario_materia_primas as $inventario_materia_prima): ?>
    <tr>
          <?php
               $valor_presentacion=$inventario_materia_prima->getInsumo()->getPresentacion(); 
               if ($valor_presentacion=='0') 
               {
                   $valor_presentacion='';
               }
               $valor_unidad=$inventario_materia_prima->getInsumo()->getUnidad();
               if ($valor_unidad=='0')
               {
                   $valor_unidad='';
               }

          ?>
      <td><?php echo $inventario_materia_prima->getInsumo()->getNombre().' '.$valor_presentacion.$valor_unidad ?></td>
      <td bgcolor="<?php echo $inventario_materia_prima->getColor() ?>"><?php echo $inventario_materia_prima->getCantidad() ?></td>
      <td><?php echo $inventario_materia_prima->getDateTimeObject('fecha')->format('d M Y') ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script type="text/javascript">
jQuery(document).ready(function() {

  var oTable = jQuery('#inventarioMateriaPrima').dataTable( {
    "oLanguage": {
      "sSearch": "Buscar en todas las columnas:"
    },

    "bStateSave": true,
    "sPaginationType": "full_numbers",

    'bProcessing': true,

   // 'bServerSide': true,

    'sAjaxSource': "<?php echo url_for('inventario_materia_prima/get_data') ?>",

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