<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h2>Lotes</h2>
<table class="tickets" id="lotes">
    <thead>
         <tr>
            <th style="width:20%">Producto</th>
            <th style="width:40%">Cantidad Producida</th>
            <th style="width:40%">Comentarios</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($form_lotes as $form_lote):?>
            <tr>
                <td>
                    <?php echo $form_lote['producto_id']->renderLabel(); ?>
                </td>
                <?php foreach($form_lote as $field):?>
                <?php if($field->getName() == 'fecha_elaboracion'):?>

             <?php else:?>
                    <?php if(!$field->isHidden()):?>
                        <td>
                            <?php echo $field->render(); ?>
                            <label id="<?php echo $field->renderId();?>_error_label" style="color:red;" ></label>
                        </td>
                    <?php else:?>
                        <?php echo $field->render(); ?>
                    <?php endif;?>
                        <?php endif;?>
                <?php endforeach; ?>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>

<script type="text/javascript">
    $(':input', '#lotes').clearDefault();
</script>
