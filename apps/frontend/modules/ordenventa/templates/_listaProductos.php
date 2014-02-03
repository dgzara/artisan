<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h3>Lista de Productos</h3>
<table class="tickets" id="productos">
    <thead>
         <tr>
            <th style="width: 16%;">Producto</th>
            <th style="width: 16%;">Cantidad</th>
            <th style="width: 16%;">Stock Tránsito + Bodega</th>
            <th style="width: 16%;">Detalle</th>
            <th style="width: 16%;">Valor Neto</th>
            <th style="width: 16%;">Descuento</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = 0;
        $i = 0;

        foreach($orden_venta_productos as $orden_venta_producto): 
            $columna = 0;
            ?>
            <tr>
                <td>
                    <?php
                        echo $orden_venta_producto->getWidget('producto_id')->getLabel();

                    ?>
                </td>
            <?php foreach($orden_venta_producto as $field_p): ?>
                <?php if($columna == 2): ?>
                    <td id="<?php echo "stock_disponible_".$i; ?>"><?php echo($orden_venta_producto->getStockDisponible());?></td>
                <?php endif;?>
                <?php if($field_p->getName() == 'cantidad' || $field_p->getName() == 'detalle'): ?>
                    <td >
                        <?php echo $field_p->render();?>
                    </td>
                <?php elseif($field_p->getName() == 'descuento'): ?>
                    <td >
                        <?php echo $field_p->render();?>
                    </td>
               <?php elseif($field_p->getName() == 'neto'):?>
                    <td >
                        <?php echo $field_p->render();?>
                        <label id="<?php echo $field_p->renderId().'_default';?>" style="color:red;"></label>
                    </td>
               <?php else:?>
                    <?php echo $field_p->render();?>
               <?php endif;
                    if($field_p->getName() == 'cantidad')
                        $cantidad = $field_p->getValue();
                    if($field_p->getName() == 'neto')
                    {
                        $total+=$field_p->getValue()*$cantidad;
                        $neto=$field_p->getValue()*$cantidad;
                    }
                     if($field_p->getName() == 'descuento')
                        $descuentoFinal += ($field_p->getValue()/100)*$neto;


                        
                    $columna++;
                ?>
            <?php endforeach;?>
                <?php $i++; ?>
            </tr>
        <?php endforeach;
        if($total!=0):
        echo '<thead><tr><th></th><th></th><th></th><th></th></tr><tr><th></th><th></th><th>Neto</th><th style="text-align: right"> $'.number_format($total,'0',',','.').'</th></tr>';
        echo '<tr><th></th><th></th><th></th><th></th></tr><tr><th></th><th></th><th>Descuento</th><th style="text-align: right"> $'.number_format($descuentoFinal,'0',',','.').'</th></tr>';
        echo '<tr><th></th><th></th><th>IVA</th><th style="text-align: right"> $'.number_format(($total-$descuentoFinal)*0.19,'0',',','.').'</th></tr>';
        echo '<tr><th></th><th></th><th>Total</th><th style="text-align: right"> $'.number_format(($total-$descuentoFinal)*1.19,'0',',','.').'</th></tr></thead>';
        endif;
        ?>
    </tbody>
</table>
<script type="text/javascript">
    $(':input', '#productos').clearDefault();
</script>