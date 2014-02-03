<?php use_stylesheets_for_form($ordenes2[0]) ?>
<?php use_javascripts_for_form($ordenes2[0]) ?>
<?php $ordenes2[0]->renderGlobalErrors();?>

<form id="form" action="<?php echo url_for('ordenventa/validategrupal') ?>" method="POST" onSubmit= "return isValid();">
<?php $estado = $ordenes[0]->getAccion();
    if($estado=="Cobrada" || $estado =="Devuelta") echo "No hay más acciones disponibles para la selección.";
    else{
?>
<table>
    <thead>
            <tr>
                <th></th>
                <th>Fecha de Creación</th>
                <th>Cliente</th>
                <th>Local</th>
                <th>N° Orden</th>
                <th>Nº Orden Compra Clientes</th>
                <?php 
                //Diferentes heads según la acción que se haga.
                if($estado=="Validar")
                {
                ?>
                <th>Fecha de Recepción en Local Cliente</th>
                <th>Encargado de Recepción en Local (Cliente)</th>
                <th>Factura/Boleta</th>
                <th>Nº Factura/Boleta</th>
                <?php
                }
                elseif($estado=="Despachar")
                {
                ?>
                <th>Fecha de Despacho</th>
                <th>Encargado de Despacho</th>
                <th>Fecha de Recepción en Local Cliente</th>
                <th>Encargado de Recepción en Local (Cliente)</th>
                <th>Factura/Boleta</th>
                <th>Nº Factura/Boleta</th>
                
                <?php                    
                }
                elseif($estado=="Registrar Recepción")
                {
                ?>
                <th>Fecha de Recepción en Local Cliente</th>
                <th>Encargado de Recepción en Local (Cliente)</th>
                <th>Factura/Boleta</th>
                <th>Nº Factura/Boleta</th>
                
                <?php                    
                }
                elseif($estado=="Registrar Devolución")
                {
                ?>
                <th>Fecha de Recepción en Local Cliente</th>
                <th>Encargado de Recepción en Local (Cliente)</th>
                <th>Factura/Boleta</th>
                <th>Nº Factura/Boleta</th>
                <?php                    
                }
                elseif($estado=="Cobrar")
                {
                ?>
                <th>Fecha de Recepción en Local Cliente</th>
                <th>Encargado de Recepción en Local (Cliente)</th>
                <th>Fecha de Factura/Boleta</th>
                <th>Factura/Boleta</th>
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
<?php } ?>