<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h3>Insumos Asociados al Proveedor</h3> 
<table class="tickets" id="insumos">
    <thead>
         <tr>
            <th  style="width: 20%;">Insumo</th>
            <th  style="width: 20%;">Cantidad a Comprar</th>
            <th  style="width: 20%;">Detalle</th>
            <th  style="width: 20%;">Precio Unitario</th>
            <th  style="width: 20%;">Conversión</th>
            <th  style="width: 20%;">Cantidad de Uso</th>

        </tr>
    </thead>
    <tbody>
        <?php if(count($orden_compra_insumos) > 0):?>
            <?php
            $total = 0;
            foreach($orden_compra_insumos as $orden_compra_insumo): ?>
                <tr>
                    <td>
                        <?php
                            $nombre_insumo=$orden_compra_insumo->getWidget('insumo_id')->getLabel();
                        if (substr($nombre_insumo, -2)==" 0")
                        {
                           $nombre_insumo=substr($nombre_insumo, 0, strlen($nombre)-2);
                        }

                            echo $nombre_insumo;
                        ?>
                    </td>
                <?php foreach($orden_compra_insumo as $field_p): ?>
                    <?php if($field_p->getName() == 'cantidad' || $field_p->getName() == 'detalle' || $field_p->getName() == 'conversion'): ?>
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

            //        if($field_p->getName() == 'transform')
            //                $cantidad = $field_p->getValue();

                    if($field_p->getName() == 'neto')
                        $total+=$field_p->getValue()*$cantidad; 

                    if($field_p->getName() == 'conversion')
                        $total2=$field_p->getValue()*$cantidad;
                        $conversion=$field_p->getValue();
                        



                    ?>

                <?php endforeach;?>

                <td><?php echo $total2?></td> 
                </tr>
            <?php endforeach;

            if($total!=0):
            echo '<thead><tr><th></th><th></th><th></th><th></th></tr><tr><th></th><th></th><th>Neto</th><th style="text-align: right">$'.number_format($total,'0',',','.').'</th></tr>';
            echo '<tr><th></th><th></th><th>IVA</th><th style="text-align: right">$'.number_format($total*0.19,'0',',','.').'</th></tr>';
            echo '<tr><th></th><th></th><th>Total</th><th style="text-align: right">$'.number_format($total*1.19,'0',',','.').'</th></tr></thead>';
            endif;

           
            ?>
        <?php else:?>
                <tr>
                    <td colspan="4" style="text-align: center;">
                        No hay insumos para el proveedor seleccionado.
                    </td>
                </tr>
        <?php endif;?>
        

    </tbody>
    
</table>

<script type="text/javascript">
    $(':input', '#insumos').clearDefault();
</script>

