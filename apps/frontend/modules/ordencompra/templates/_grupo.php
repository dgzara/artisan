<form id="form" action="<?php echo url_for('ordencompra/validategrupal') ?>" method="POST" onSubmit= "return isValid();">
<?php $estado = $ordenes[0]->getAccion();
    if($estado=="Pagada") echo "No hay más acciones disponibles para la selección.";
    else{
?>
<table margin="100%">
    <thead>
            <tr>
                <th></th>
                <th>Fecha de Emisión</th>
                <th>Proveedor</th>
                <th>Lugar</th>
                <th>N° Orden</th>
                <?php 
                //Diferentes heads según la acción que se haga.
                if($estado=="Validar")
                {
                ?>
                
                <?php
                }
                elseif($estado=="Recepcionar")
                {
                ?>
                <th></th>
                <th>Fecha de Recepción</th>
                <th>Recepcionista Artisan</th>
                <th>Encargado de Despacho</th>
                
                <?php                    
                }
                elseif($estado=="Pagar")
                {
                ?>
                <th></th>
                <th>Fecha de Recepción</th>
                <th>Fecha de Factura/Boleta</th>
                <th>Nº Factura/Boleta</th>
                <th>Fecha de Pago</th>
                <th>Forma de Pago</th>
                
                <?php                  
                }
                ?>
            </tr>
    </thead>
        <?php for($i=0; $i < count($ordenes2); $i++): ?>
          <tr class="orden" id="orden_<?php echo $i?>">
              <td><?php echo $i+1;?></td>
              <?php foreach($ordenes2[$i] as $field_e): ?>
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
<?php } 

if($estado=="Pagar"){
    ?>
    <br><br>
    <h3>Recuerde ingresar los correspondientes costos indirectos para estas órdenes de compra!</h3>
    <?php
}
?>
