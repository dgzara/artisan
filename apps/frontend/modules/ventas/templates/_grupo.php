<form id="form" action="<?php echo url_for('ventas/validategrupal') ?>" method="POST" onSubmit= "return isValid();">
<?php $estado = $lotes[0]->getAccion();
    if($estado=="Recepcionado") echo "No hay más acciones disponibles para la selección.";
    else{
?>
<table>
    <thead>
            <tr>
                <th></th>
                <th>Fecha de Creación</th>
                <th>Producto</th>
                <th>Nº Lote</th>
                <th>Unidades Producidas</th>
                <th>Cantidad Actual</th>
                <th>Fecha Recepción Centro de Distribución</th>              
                <th>Cantidad Recibida Santiago</th>    
                <th>Unidades No Consumibles Santiago</th>              
                <th>Unidades Fuera de Formato Santiago</th>
                <th>Stock Control de Calidad Santiago</th>                                
            </tr>
    </thead>
        <?php for($i=0; $i < count($lotes2); $i++): ?>
          <tr class="orden" id="lote_<?php echo $i?>">
              <td><?php echo $i+1;?></td>
              <?php foreach($lotes2[$i] as $field_e): ?>
                    <?php if(!$field_e->isHidden()): ?>
                        <td>
                            <?php echo $field_e->render();?>
                        </td>
                   <?php else:?>
                        <?php echo $field_e->render();?>
                    <?php endif;?>
                <?php endforeach;?>
            </tr>
        <?php endfor; ?>
</table>
<input type="submit" value="<?php echo $estado.' todos';?>">
</form>
<?php } ?>