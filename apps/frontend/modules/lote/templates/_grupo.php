<form action="<?php echo url_for('lote/validategrupal') ?>" method="POST" onSubmit= "return isValid();">
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
                <th>Número</th>
                <th>Unidades Producidas</th>
                <th>Unidades Útiles</th>

                <?php 
                //Diferentes heads según la acción que se haga.
                if($estado=="A Madurar")
                {
                ?>
                    <th>Fecha de Ingreso a Cámara de Maduración</th>
                <?php
                }
                elseif($estado=="En Maduración")
                {
                ?>
                <th>Fecha de Retiro de Cámara de Maduración</th>
                <th>Unidades No Consumibles Valdivia</th>
                <th>Unidades Fuera de Formato Valdivia</th>
                
                <?php                    
                }
                elseif($estado=="Empacar")
                {
                ?>
                <th>Fecha de Empaque</th>
                <th>Stock para Control de Calidad Valdivia</th>
                
                <?php                    
                }
                elseif($estado=="Despachar")
                {
                ?>
                <th>Fecha de Despacho a Centro de Distribución</th>
                <th>Empresa de Transporte</th>
                
                <?php                    
                }
                ?>
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